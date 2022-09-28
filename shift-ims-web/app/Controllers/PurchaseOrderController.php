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
            $filter->dateStart = date('Y-m-01');
            $filter->dateEnd = date('Y-m-t');
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
        $data->status = StockUpdate::STATUS_SAVED;
        $data->type = StockUpdate::UPDATE_TYPE_PURCHASE_ORDER;
        $data->datetime = date('Y-m-d H:i:s');
        $data->created_at = date('Y-m-d H:i:s');
        $data->created_by = current_user()->username;
        $data->code = $model->generateCode(StockUpdate::UPDATE_TYPE_PURCHASE_ORDER);
        $model->save($data);
        return redirect()->to(base_url('purchase-orders/edit/' . $this->db->insertID()));
    }

    public function edit($id)
    {
        $model = $this->getStockUpdateModel();
        $data = $model->find($id);
        if ($data->status != StockUpdate::STATUS_SAVED) {
            return redirect()->to(base_url('purchase-orders/view/' . $id));
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
            $data->fill($this->request->getPost());
            $data->expedition_cost = floatval($data->expedition_cost);
            $data->other_cost = floatval($data->other_cost);
            $data->datetime = datetime_from_input($data->datetime);
            $quantities = (array)$this->request->getPost('quantities');
            $costs = (array)$this->request->getPost('costs');
            $prices = (array)$this->request->getPost('prices');

            if (!$data->party_id) {
                $data->party_id = null;
            }
            
            $products_by_ids = [];
            foreach ($products as $product) {
                $products_by_ids[$product->id] = $product;
            }

            if ($action == 'complete') {
                $data->status = StockUpdate::STATUS_COMPLETED;
            }
            else if ($action == 'cancel') {
                $data->status = StockUpdate::STATUS_CANCELED;
            }
            else if ($action == 'save') {
                $data->status = StockUpdate::STATUS_SAVED;
            }
            else if ($action == 'complete_and_paid') {
                $data->status = StockUpdate::STATUS_COMPLETED;
                $data->payment_status = StockUpdate::PAYMENTSTATUS_FULLYPAID;
            }

            if (empty($quantities) && ($action == 'complete' || $action == 'complete_and_paid')) {
                $errors['items']='Silahkan tambahkan item terlebih dahulu';
            }

            if (empty($errors)) {
                $items = [];
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
                if (!$supplier_id) {
                    $supplier_id = 'null';
                }

                $data->lastmod_at = date('Y-m-d H:i:s');
                $data->lastmod_by = current_user()->username;
                $data->total_bill = abs($data->total_cost) + $data->expedition_cost + $data->other_cost;
                if ($action == 'complete_and_paid') {
                    $data->total_paid = $data->total_bill;
                }
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

                    // add last supplier id
                    $product = $products_by_ids[$item->id];
                    if ($data->status == StockUpdate::STATUS_COMPLETED &&
                            $product->type == Product::TYPE_STOCKED) {       
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
                }
                $this->db->transCommit();
                
                if ($data->status != StockUpdate::STATUS_SAVED) {
                    return redirect()->to(base_url("purchase-orders/view/$data->id"))
                        ->with('info', "Order telah selesai");
                }

                return redirect()->to(base_url("purchase-orders/edit/$data->id"))
                ->with('info', 'Pembelian telah disimpan');
            }
        }

        $suppliers = $this->getPartyModel()->getAllSuppliers();

        return view('purchase-order/edit', [
            'data' => $data,
            'suppliers' => $suppliers,
            'products' => $products,
            'items' => $items,
            'orderOptions' => $this->db->query('select order_via from stock_updates group by order_via order by order_via asc')
                ->getResultObject()
        ]);
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

        return redirect()->to(base_url('/purchase-orders'))->with('warning', 'Rekaman telah dihapus');
    }
}
