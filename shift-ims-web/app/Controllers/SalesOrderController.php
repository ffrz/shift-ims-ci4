<?php

namespace App\Controllers;

use App\Entities\Product;
use App\Entities\StockUpdate;
use stdClass;

class SalesOrderController extends BaseController
{
    public function index()
    {
        $filter = new \StdClass();
        $filter->daterange = (string)$this->request->getGet('daterange');
        if (null == $filter->daterange) {
            $filter->daterange = date('Y-m-d') . ' - ' . date('Y-m-d');
        }
        
        $where = [];
        $params = [];

        if (strlen($filter->daterange) == 23) {
            $daterange = explode(' - ', $filter->daterange);
            $where[] = "(date(su.datetime) between :d1: and :d2:)";
            $params = [
                'd1' => $daterange[0],
                'd2' => $daterange[1],
            ];
        }

        $where[] = 'su.type=' . StockUpdate::UPDATE_TYPE_SALES_ORDER;
        $where = implode(' and ', $where);

        $sql = 'select su.*, p.name party_name from stock_updates su
            left join parties p on p.id=su.party_id';

        if ($where) {
            $sql .= ' where ' . $where;
        }

        $sql .= ' order by su.id desc';
        $items = $this->db->query($sql, $params)->getResultObject();

        return view('sales-order/index', [
            'filter' => $filter,
            'items' => $items,
        ]);
    }

    public function view($id)
    {
        $model = $this->getStockUpdateModel();
        $order = $model->find($id);
        $order->customer = null;
        if ($order->party_id) {
            $order->customer = $this->getPartyModel()->find($order->party_id);
        }
        $order->items = $this->db->query("
            select d.*, p.name, p.uom
            from stock_update_details d
            inner join products p on p.id = d.product_id
            where parent_id=$order->id")
            ->getResultObject();

        return view('sales-order/view', [
            'data' => $order,
            'print' => $this->request->getGet('print'),
        ]);
    }

    public function add()
    {
        $model = $this->getStockUpdateModel();
        $data = new StockUpdate();
        $data->type = StockUpdate::UPDATE_TYPE_SALES_ORDER;
        $data->supplier_id = 0;
        $data->datetime = date('Y-m-d H:i:s');
        $data->code = $model->generateCode(StockUpdate::UPDATE_TYPE_SALES_ORDER);
        $data->notes = '';
        
        $items = [];

        $products = $this->db->query('
            select *
            from products
            where active=1
            order by name asc')
        ->getResultObject();

        if ($this->request->getMethod() == 'post') {
            $data->fill($this->request->getPost());
            $data->datetime = datetime_from_input($data->datetime);

            $products_by_ids = [];
            foreach ($products as $product) {
                $products_by_ids[$product->id] = $product;
            }

            $quantities = $this->request->getPost('quantities');
            $prices = $this->request->getPost('prices');

            if (empty($quantities)) {
                $errors['items']='Silahkan tambahkan item terlebih dahulu';
            }

            if (empty($errors)) {
                $i = 0;
                $data->total_cost = 0;
                $data->total_price = 0;
                foreach ($quantities as $product_id => $qty) {
                    $product = $products_by_ids[$product_id];
                    $item = new stdClass();
                    $item->num = ++$i;
                    $item->name = $product->name;
                    $item->uom = $product->uom;
                    $item->id = $product_id;
                    $item->type = $product->type;
                    $item->cost = floatval($product->cost);
                    $item->price = floatval($prices[$product_id]);
                    $item->quantity = -intval($qty); // penjualan set negatif
                    $item->subtotal_cost = abs($item->cost * $item->quantity);
                    $item->subtotal_price = abs($item->price * $item->quantity);

                    $data->total_cost += $item->subtotal_cost;
                    $data->total_price += $item->subtotal_price;
                    $items[] = $item;
                }

                $this->db->transBegin();
                $customer_id = $data->party_id = $data->party_id ? $data->party_id : null;
                if (!$customer_id)
                    $customer_id = 'null';

                $model->save($data);
                $orderId = $this->db->insertID();
                foreach ($items as $item) {
                    $this->db->query(
                        "insert into stock_update_details
                        ( parent_id , id , product_id , quantity , cost , price ) values
                        (:parent_id:,:id:,:product_id:,:quantity:,:cost:,:price:)
                        ", [
                            'parent_id' => $orderId,
                            'id' => $item->num,
                            'product_id' => $item->id,
                            'quantity' => $item->quantity,
                            'cost' => $item->cost,
                            'price' => $item->price
                        ]);

                    $product = $products_by_ids[$product_id];
                    if ($product->type == Product::TYPE_STOCKED) {                    
                        $this->db->query("
                            update products set
                            stock=stock+$item->quantity
                            where id=$item->id
                        ");
                    }
                }
                $this->db->transCommit();
                $code = format_stock_update_code($data->type, $data->code);
                return redirect()->to(base_url("sales-orders/view/$orderId"))
                    ->with('info', "Penjualan $code telah disimpan");
            }
        }

        $customers = $this->getPartyModel()->getAllCustomers();

        return view('sales-order/add', [
            'data' => $data,
            'customers' => $customers,
            'products' => $products,
            'items' => $items
        ]);
    }
}
