<?php

namespace App\Controllers;

use App\Entities\CashTransactionCategory;
use CodeIgniter\Database\Exceptions\DataException;

class CashTransactionCategoryController extends BaseController
{
    public function index()
    {
        $items = $this->getCashTransactionCategoryModel()->getAll();

        return view('cash-transaction-category/index', [
            'items' => $items,
        ]);
    }

    public function edit($id)
    {
        $model = $this->getCashTransactionCategoryModel();
        if ($id == 0) {
            $item = new CashTransactionCategory();
        } else {
            $item = $model->find($id);
            if (!$item) {
                return redirect()->to(base_url('cash-transaction-categories'))->with('warning', 'Kategori tidak ditemukan.');
            }
        }

        $errors = [];

        if ($this->request->getMethod() === 'post') {
            $item->fill($this->request->getPost());

            $item->name = trim($item->name);
            if (strlen($item->name) < 3) {
                $errors['name'] = 'Nama Kategori harus diisi, minimal 3 karakter.';
            } elseif (strlen($item->name) > 100) {
                $errors['name'] = 'Nama Kategori terlalu panjang, maksimal 100 karakter.';
            } else if (!preg_match('/^[a-zA-Z\d ]+$/i', $item->name)) {
                $errors['name'] = 'Nama Kategori tidak valid, gunakan huruf alfabet, angka dan spasi.';
            } else if ($model->exists($item->name, $item->id)) {
                $errors['name'] = 'Nama Kategori sudah digunakan, silahkan gunakan nama lain.';
            }
            if (empty($errors)) {
                try {
                    $model->save($item);
                } catch (DataException $ex) {
                }
                return redirect()->to(base_url("cash-transaction-categories"))->with('info', 'Kategori telah disimpan.');
            }
        }

        return view('cash-transaction-category/edit', [
            'data' => $item,
            'errors' => $errors,
        ]);
    }

    public function delete($id)
    {
        $model = $this->getCashTransactionCategoryModel();
        $item = $model->find($id);
        if (!$item) {
            return redirect()->to(base_url('cash-transaction-categories'))->with('warning', 'Kategori tidak ditemukan.');
        }

        $this->db->query("delete from cash_transaction_categories where id=$item->id");
        
        return redirect()->to(base_url('cash-transaction-categories'))->with('info', 'Kategori telah dihapus.');
    }
}
