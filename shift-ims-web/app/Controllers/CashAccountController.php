<?php

namespace App\Controllers;

use App\Entities\CashAccount;
use App\Entities\CashTransaction;
use CodeIgniter\Database\Exceptions\DataException;
use Exception;

class CashAccountController extends BaseController
{
    public function index()
    {
        $items = $this->getCashAccountModel()->getAll();

        return view('cash-account/index', [
            'items' => $items,
        ]);
    }

    public function edit($id)
    {
        $model = $this->getCashAccountModel();
        if ($id == 0) {
            $item = new CashAccount();
        } else {
            $item = $model->find($id);
            if (!$item) {
                return redirect()->to(base_url('cash-accounts'))->with('warning', 'Akun tidak ditemukan.');
            }
        }

        $errors = [];

        $oldBalance = $item->balance;

        if ($this->request->getMethod() === 'post') {
            $item->fill($this->request->getPost());
            $item->balance = (float)$item->balance;

            $item->name = trim($item->name);
            if (strlen($item->name) < 3) {
                $errors['name'] = 'Nama Akun harus diisi, minimal 3 karakter.';
            } elseif (strlen($item->name) > 100) {
                $errors['name'] = 'Nama Akun terlalu panjang, maksimal 100 karakter.';
            } else if (!preg_match('/^[a-zA-Z\d ]+$/i', $item->name)) {
                $errors['name'] = 'Nama Akun tidak valid, gunakan huruf alfabet, angka dan spasi.';
            } else if ($model->exists($item->name, $item->id)) {
                $errors['name'] = 'Nama Akun sudah digunakan, silahkan gunakan nama lain.';
            }
            if (empty($errors)) {
                try {
                    $model->save($item);
                    if (!$item->id) {
                        $item->id = $this->db->insertID();
                    }

                    if ($item->balance != $oldBalance) {
                        $amount = $item->balance - $oldBalance;
                        $transaction = new CashTransaction();
                        $transaction->account_id = $item->id;
                        $transaction->datetime = date('Y-m-d H:i:s');
                        $transaction->amount = $amount;
                        $transaction->description = 'Penyesuaian Saldo';
                        $transaction->category_id = null;
                        $transaction->created_at = date('Y-m-d H:i:s');
                        $transaction->created_by = current_user()->username;
                        $this->getCashTransactionModel()->save($transaction);
                    }
                } catch (DataException $ex) {
                }
                return redirect()->to(base_url("cash-accounts"))->with('info', 'Akun telah disimpan.');
            }
        }

        return view('cash-account/edit', [
            'data' => $item,
            'errors' => $errors,
        ]);
    }

    public function delete($id)
    {
        $model = $this->getCashAccountModel();
        $item = $model->find($id);
        if (!$item) {
            return redirect()->to(base_url('cash-accounts'))->with('warning', 'Akun tidak ditemukan.');
        }

        try {
            $this->db->query("delete from cash_accounts where id=$item->id");
        } catch (Exception $ex) {
            return redirect()->to(base_url('cash-accounts'))->with('error', 'Akun tidak dapat dihapus.');
        }

        return redirect()->to(base_url('cash-accounts'))->with('info', 'Akun telah dihapus.');
    }
}
