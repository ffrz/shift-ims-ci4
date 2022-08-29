<?php

namespace App\Controllers;

use App\Entities\Product;
use App\Entities\StockUpdate;
use stdClass;

class PurchaseOrderController extends BaseController
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

        $where[] = 'su.type=' . StockUpdate::UPDATE_TYPE_PURCHASE_ORDER;
        $where = implode(' and ', $where);

        $sql = 'select su.*, p.name party_name from stock_updates su
            left join parties p on p.id=su.party_id';

        if ($where) {
            $sql .= ' where ' . $where;
        }

        $sql .= ' order by su.id desc';
        $items = $this->db->query($sql, $params)->getResultObject();

        return view('purchase-order/index', [
            'filter' => $filter,
            'items' => $items,
        ]);
    }


    public function view($id)
    {
        $model = $this->getStockUpdateModel();
        $order = $model->find($id);
        $order->supplier = null;
        if ($order->party_id) {
            $order->supplier = $this->getPartyModel()->find($order->party_id);
        }
        $order->items = $this->db->query("
            select d.*, p.name, p.uom
            from stock_update_details d
            inner join products p on p.id = d.product_id
            where parent_id=$order->id")
            ->getResultObject();

        return view('purchase-order/view', [
            'data' => $order
        ]);
    }

    public function add()
    {
        $model = $this->getStockUpdateModel();
        $data = new StockUpdate();
        $data->type = StockUpdate::UPDATE_TYPE_PURCHASE_ORDER;
        $data->supplier_id = 0;
        $data->datetime = date('Y-m-d H:i:s');
        $data->code = $model->generateCode(StockUpdate::UPDATE_TYPE_PURCHASE_ORDER);
        $data->notes = '';
        
        $items = [];

        $products = $this->db->query('
            select *
            from products
            where active=1 and type=1
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
            $costs = $this->request->getPost('costs');
            $prices = $this->request->getPost('prices');

            if (empty($quantities)) {
                $errors['items']='Silahkan tambahkan item terlebih dahulu';
            }

            if (empty($errors)) {
                $i = 0;
                $data->total_cost = 0;
                $data->total_price = 0;
                foreach ($quantities as $product_id => $qty) {
                    $item = new stdClass();
                    $item->num = ++$i;
                    $item->name = $products_by_ids[$product_id]->name;
                    $item->uom = $products_by_ids[$product_id]->uom;
                    $item->id = $product_id;
                    $item->cost = floatval($costs[$product_id]);
                    $item->price = floatval($prices[$product_id]);
                    $item->quantity = intval($qty);
                    $item->subtotal_cost = $item->cost * $item->quantity;
                    $item->subtotal_price = $item->price * $item->quantity;

                    $data->total_cost += $item->subtotal_cost;
                    $data->total_price += $item->subtotal_price;
                    $items[] = $item;
                }

                $this->db->transBegin();
                $supplier_id = $data->party_id = $data->party_id ? $data->party_id : null;
                if (!$supplier_id)
                    $supplier_id = 'null';

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

                    // add last supplier id
                    $product = $products_by_ids[$item->id];
                    $costingMethod = $product->costing_method;
                    $cost = $product->cost;
                    if ($costingMethod == Product::COSTING_METHOD_LAST) {
                        $cost = $item->cost;
                    }
                    else if ($costingMethod == Product::COSTING_METHOD_AVERAGE) {
                        $cost = (($product->stock * $product->cost) + ($item->quantity * $item->cost))
                              / ($product->stock + $item->quantity);
                    }

                    
                    $this->db->query("
                        update products set
                        stock=stock+$item->quantity,
                        cost=$cost,
                        price=$item->price,
                        last_supplier_id=$supplier_id
                        where id=$item->id
                    ");
                }
                $this->db->transCommit();
                return redirect()->to(base_url('purchase-orders'))
                    ->with('info', 'Pembelian telah disimpan');
            }
        }

        $suppliers = $this->getPartyModel()->getAllSuppliers();

        return view('purchase-order/add', [
            'data' => $data,
            'suppliers' => $suppliers,
            'products' => $products,
            'items' => $items
        ]);
    }
}
