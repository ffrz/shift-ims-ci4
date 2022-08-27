<?php

namespace App\Controllers;

use App\Entities\StockUpdate;

class StockUpdateController extends BaseController
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
            $where[] = "(date(datetime) between :d1: and :d2:)";
            $params = [
                'd1' => $daterange[0],
                'd2' => $daterange[1],
            ];
        }

        $sql = 'select * from stock_updates';
        $where = implode(' and ', $where);
        
        if ($where) {
            $sql .= ' where ' . $where;
        }

        $sql .= ' order by datetime desc';
        $items = $this->db->query($sql, $params)->getResultObject();

        return view('stock-update/index', [
            'filter' => $filter,
            'items' => $items,
        ]);
    }

    public function delete($id) {
        $url = $this->request->getPost('goto');
        $action = $this->request->getPost('action');

        $model = $this->getStockUpdateModel();
        $stockUpdate = $model->find($id);

        $this->db->transBegin();
        if ($action == 'delete_revert') {
            $model->revertStockUpdate($stockUpdate);
        }
        $model->delete($id);
        $this->db->transCommit();

        return redirect()->to($url)->with('warning', 'Rekaman telah dihapus');
    }

    public function view($id)
    {
        $data = $this->getStockUpdateModel()->find($id);
        if (!$data) {
            return redirect()->to(base_url('stock-adjustments'))
                ->with('warning', 'Tidak ditemukan.');            
        }

        $items = $this->getStockUpdateDetailModel()->getAllByUpdateId($id);

        return view('stock-update/view', [
            'data' => $data,
            'items' => $items,
        ]);
    }
}
