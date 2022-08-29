<?php

namespace App\Controllers;

use App\Entities\StockUpdate;
use App\Entities\StockUpdateDetail;
use DateTime;
use stdClass;

class StockAdjustmentController extends BaseController
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

        $where[] = '(type=' . StockUpdate::UPDATE_TYPE_INITIAL_STOCK
              . ' or type=' . StockUpdate::UPDATE_TYPE_MANUAL_AJDUSTMENT
              . ' or type=' . StockUpdate::UPDATE_TYPE_ADJUSTMENT
              . ')';

        $sql = 'select * from stock_updates';
        $where = implode(' and ', $where);
        
        if ($where) {
            $sql .= ' where ' . $where;
        }

        $sql .= ' order by datetime desc';

        $items = $this->db->query($sql, $params)->getResultObject();

        return view('stock-adjustment/index', [
            'filter' => $filter,
            'items' => $items,
        ]);
    }

    public function add()
    {
        $data = new \stdClass();
        $data->datetime = date('Y-m-d H:i:s');
        $data->notes = '';

        $error = '';

        $items = $this->db->query('
            select id, name, stock, 0 as actual_stock, 0 as balance, uom, cost, price
            from products
            where active=1 and type=1
            order by name asc')
        ->getResultObject();

        if ($this->request->getMethod() == 'post') {
            $productByIds = [];
            foreach ($items as $item) {
                $productByIds[$item->id] = $item;
            }

            $balances = $this->request->getPost('balances');
            $actual_quantities = $this->request->getPost('actual_quantities');

            $adjustment = new StockUpdate();
            $adjustment->datetime = $data->datetime;
            $adjustment->type = StockUpdate::UPDATE_TYPE_ADJUSTMENT;
            $adjustment->notes = $this->request->getPost('notes');

            $this->db->transBegin();
            $this->getStockAdjustmentModel()->save($adjustment);
            $adjustment->id = $this->db->insertID();
            
            $id = 0;
            foreach ($balances as $product_id => $qty) {
                if ($qty == 0) continue;

                $product = $productByIds[$product_id];
                $actual_quantity = intVal($actual_quantities[$product_id]);

                $this->db->query(
                    "insert into stock_update_details
                    ( parent_id , id , product_id , quantity , cost , price ) values
                    (:parent_id:,:id:,:product_id:,:quantity:,:cost:,:price:)
                    ", [
                        'parent_id' => $adjustment->id,
                        'id' => ++$id,
                        'product_id' => $product_id,
                        'quantity' => $qty,
                        'cost' => $product->cost,
                        'price' => $product->price
                    ]);
                $this->db->query("update products set stock=$actual_quantity where id=$product->id");

                $adjustment->total_cost += $product->cost * $qty;
                $adjustment->total_price += $product->price * $qty;
            }

            if ($id == 0) {
                $error ='Tidak ada selisih, tidak dapat menyimpan.';
            }

            if (empty($error)) {
                $this->getStockAdjustmentModel()->save($adjustment);
                $this->db->transCommit();
                return redirect()->to(base_url('stock-adjustments'))
                    ->with('info', 'Berhasil disimpan.');
            }

            
        }

        return view('stock-adjustment/add', [
            'data' => $data,
            'error' => $error,
            'items' => $items,
        ]);
    }
}
