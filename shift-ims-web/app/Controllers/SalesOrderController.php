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
        $filter->status = $this->request->getGet('status');

        if (null == $filter->daterange) {
            $filter->dateStart = date('Y-m-01');
            $filter->dateEnd = date('Y-m-t');
        }

        if (null == $filter->status) {
            $filter->status = StockUpdate::STATUS_SAVED;
        }
        
        if (strlen($filter->daterange) == 23) {
            $daterange = explode(' - ', $filter->daterange);
            $filter->dateStart = datetime_from_input($daterange[0]);
            $filter->dateEnd = datetime_from_input($daterange[1]);
        }

        $where[] = "(date(datetime) between :d1: and :d2:)";
        $params = [
            'd1' => $filter->dateStart,
            'd2' => $filter->dateEnd,
        ];

        if ($filter->status !== 'all') {
            $where[] = 'su.status=' . (int)$filter->status;
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

        return view('sales-order/' . ($this->request->getGet('print') ? 'print' : 'view'), [
            'data' => $order,
            'settings' => $this->getSettings()
        ]);
    }

    public function edit($id)
    {
        $model = $this->getStockUpdateModel();
        $data = $model->find($id);
        if ($data->status != StockUpdate::STATUS_SAVED) {
            return redirect()->to(base_url('sales-orders/view/' . $id));
        }
        
        $items = $this->db->query("
            select sud.id num, sud.parent_id, sud.product_id id,
                abs(sud.quantity) quantity, sud.cost, sud.price,
                p.uom, p.name
            from stock_update_details sud
            inner join products p on p.id = sud.product_id
            where sud.parent_id=$data->id
            order by sud.id asc
        ")->getResultObject();

        $products = $this->db->query('
            select *
            from products
            where active=1
            order by name asc')
        ->getResultObject();

        if ($this->request->getMethod() == 'post') {
            $action = $this->request->getPost('action');
            if ($action == 'complete') {
                $data->status = StockUpdate::STATUS_COMPLETED;
            }
            else if ($action == 'cancel') {
                $data->status = StockUpdate::STATUS_CANCELED;
            }

            $data->fill($this->request->getPost());
            if (!$data->party_id) {
                $data->party_id = null;
            }

            $data->datetime = datetime_from_input($data->datetime);

            $products_by_ids = [];
            foreach ($products as $product) {
                $products_by_ids[$product->id] = $product;
            }

            $quantities = (array)$this->request->getPost('quantities');
            $prices = $this->request->getPost('prices');

            if (empty($quantities) && $action == 'complete') {
                $errors['items']='Silahkan tambahkan item terlebih dahulu';
            }

            if (empty($errors)) {
                $items = [];
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

                $data->lastmod_at = date('Y-m-d H:i:s');
                $data->lastmod_by = current_user()->username;
                $model->save($data);

                $this->db->query('delete from stock_update_details where parent_id=' . $data->id);

                foreach ($items as $item) {
                    $this->db->query(
                        "insert into stock_update_details
                        ( parent_id , id , product_id , quantity , cost , price ) values
                        (:parent_id:,:id:,:product_id:,:quantity:,:cost:,:price:)
                        ", [
                            'parent_id' => $data->id,
                            'id' => $item->num,
                            'product_id' => $item->id,
                            'quantity' => $item->quantity,
                            'cost' => $item->cost,
                            'price' => $item->price
                        ]);

                    $product = $products_by_ids[$product_id];
                    if ($data->status == StockUpdate::STATUS_COMPLETED &&
                        $product->type == Product::TYPE_STOCKED) {                    
                        $this->db->query("
                            update products set
                            stock=stock+$item->quantity
                            where id=$item->id
                        ");
                    }
                }

                $this->db->transCommit();

                if (!$data->status == StockUpdate::STATUS_SAVED) {
                    return redirect()->to(base_url("sales-orders/view/$data->id"))
                        ->with('info', "Order telah selesai");
                }
            }

            return redirect()->to(base_url("sales-orders/edit/$data->id"))
                ->with('info', "Order telah disimpan");
        }

        $customers = $this->getPartyModel()->getAllCustomers();

        return view('sales-order/edit', [
            'data' => $data,
            'customers' => $customers,
            'products' => $products,
            'items' => $items
        ]);
    }

    public function add()
    {
        $model = $this->getStockUpdateModel();
        $data = new StockUpdate();
        $data->status = StockUpdate::STATUS_SAVED;
        $data->type = StockUpdate::UPDATE_TYPE_SALES_ORDER;
        $data->supplier_id = 0;
        $data->datetime = date('Y-m-d H:i:s');
        $data->created_at = date('Y-m-d H:i:s');
        $data->created_by = current_user()->username;
        $data->code = $model->generateCode(StockUpdate::UPDATE_TYPE_SALES_ORDER);
        $data->notes = '';

        $model->save($data);
        return redirect()->to(base_url('sales-orders/edit/' . $this->db->insertID()));
    }

    public function delete($id)
    {
        $model = $this->getStockUpdateModel();
        $stockUpdate = $model->find($id);

        $this->db->transBegin();
        if ($stockUpdate->status == StockUpdate::STATUS_COMPLETED) {
            $model->revertStockUpdate($stockUpdate);
        }
        $model->delete($id);
        $this->db->transCommit();

        return redirect()->to(base_url('/sales-orders'))->with('warning', 'Rekaman telah dihapus');
    }
}
