<?php

namespace App\Controllers;

use App\Entities\CashTransaction;
use CodeIgniter\Database\Exceptions\DataException;
use Exception;
use stdClass;

class CashTransactionController extends BaseController
{
    public function index()
    {
        $filter = new stdClass;
        $filter->year = (int)$this->request->getGet('year');
        $filter->month = $this->request->getGet('month');

        if ($filter->year == 0) {
            $filter->year = date('Y');
        }
        if ($filter->month == null) {
            $filter->month = date('m');
        }

        if ($filter->month < 0 || $filter->month > 12) {
            $filter->month = date('m');
        }

        $where = [];
        $where[] = 'year(datetime)=' . $filter->year;
        if ($filter->month != 0) {
            $where[] = 'month(datetime)=' . $filter->month;
        }

        $where = implode(' and ', $where);
        if (!empty($where)) {
            $where = ' where ' . $where;
        }

        $sql = "
            select ct.*, ctc.name category_name, ca.name account_name
            from cash_transactions ct
            left join cash_accounts ca on ca.id = ct.account_id
            left join cash_transaction_categories ctc on ctc.id = ct.category_id
            $where
            order by ct.datetime desc";
        $items = $this->db->query($sql)->getResultObject();

        return view('cash-transaction/index', [
            'items' => $items,
            'filter' => $filter,
        ]);
    }

    public function edit($id)
    {
        $model = $this->getCashTransactionModel();
        if ($id == 0) {
            $item = new CashTransaction();
            $item->datetime = date('Y-m-d H:i:s');
        } else {
            $item = $model->find($id);
            if (!$item) {
                return redirect()->to(base_url('cash-transactions'))->with('warning', 'Transaksi tidak ditemukan.');
            }
        }

        $errors = [];
        $oldAmount = $item->amount;

        if ($this->request->getMethod() === 'post') {
            $item->fill($this->request->getPost());
            $item->datetime = datetime_from_input($item->datetime);
            $item->amount = (float)$item->amount;
            if (!$item->category_id) {
                $item->category_id = null;
            }

            if (empty($errors)) {
                try {
                    $this->db->transBegin();
                    if ($oldAmount != $item->amount) {
                        $accountModel = $this->getCashAccountModel();
                        $account = $accountModel->find($item->account_id);
                        $oldBalance = $account->balance;
                        $account->balance = $oldBalance - ($oldAmount - $item->amount);
                        $accountModel->save($account);
                    }

                    $model->save($item);
                    $this->db->transCommit();
                } catch (DataException $ex) {
                }
                return redirect()->to(base_url("cash-transactions"))->with('info', 'Transaksi telah disimpan.');
            }
        }

        return view('cash-transaction/edit', [
            'data' => $item,
            'categories' => $this->getCashTransactionCategoryModel()->getAll(),
            'accounts' => $this->getCashAccountModel()->getAll(),
            'errors' => $errors,
        ]);
    }

    public function delete($id)
    {
        $model = $this->getCashTransactionModel();
        $item = $model->find($id);
        if (!$item) {
            return redirect()->to(base_url('cash-transaction'))->with('warning', 'Transaksi tidak ditemukan.');
        }

        $accountModel = $this->getCashAccountModel();
        $account = $accountModel->find($item->account_id);

        try {
            $this->db->transBegin();
            $account->balance += -$item->amount;
            $accountModel->save($account);
            $this->db->query("delete from cash_transactions where id=$item->id");
            $this->db->transCommit();
        } catch (Exception $ex) {
            return redirect()->to(base_url('cash-transactions'))->with('error', 'Transaksi tidak dapat dihapus.');
        }

        return redirect()->to(base_url('cash-transactions'))->with('info', 'Transaksi telah dihapus.');
    }
}
