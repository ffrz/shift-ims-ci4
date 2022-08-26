<?php

namespace App\Controllers;

use App\Models\ProductCategoryModel;
use App\Entities\ProductCategory;

class ProductCategoryController extends BaseController
{
    public function index()
    {
        $items = $this->getProductCategoryModel()->getAllWithProductCount();

        return view('product-category/index', [
            'items' => $items,
        ]);
    }

    public function edit($id)
    {
        $model = $this->getProductCategoryModel();
        if ($id == 0) {
            $item = new ProductCategory();
        }
        else {
            $item = $model->find($id);
            if (!$item) {
                return redirect()->to(base_url('product-categories'))->with('warning', 'Kategori produk tidak ditemukan.');
            }
        }

        $errors = [];

        if ($this->request->getMethod() === 'post') {
            $item->name = trim($this->request->getPost('name'));

            if ($item->name == '') {
                $errors['name'] = 'Nama kategori harus diisi.';
            }

            if ($model->exists($item->name, $item->id)) {
                $errors['name'] = 'Nama kategori sudah digunakan, harap gunakan nama lain.';
            }

            if (empty($errors)) {
                $model->save($item);
                return redirect()->to(base_url('product-categories'))->with('info', 'Berhasil disimpan.');
            }
        }
        
        return view('product-category/edit', [
            'data' => $item,
            'errors' => $errors,
        ]);
    }

    public function delete($id)
    {
        $this->getProductCategoryModel()->delete($id);
        return redirect()->to(base_url('product-categories'))->with('info', 'Kategori telah dihapus.');
    }
}
