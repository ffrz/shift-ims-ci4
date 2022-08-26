<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Entities\Product;
use App\Entities\ProductCategory;
use App\Entities\StockUpdate;
use App\Models\ProductCategoryModel;

class ProductController extends BaseController
{
    public function index()
    {
        $filter = new \StdClass();
        $filter->category_id = $this->request->getGet('category_id');
        if ($filter->category_id === null)
            $filter->category_id = 'all';

        $filter->supplier_id = $this->request->getGet('supplier_id');
        if ($filter->supplier_id == null)
            $filter->supplier_id = 'all';

        $filter->type = $this->request->getGet('type');
        if ($filter->type == null)
            $filter->type = 'all';

        $filter->active = $this->request->getGet('active');
        if ($filter->active == null)
            $filter->active = 1;

        return view('product/index', [
            'filter' => $filter,
            'items' => $this->getProductModel()->getAll($filter),
            'categories' => $this->getProductCategoryModel()->getAll(),
            'suppliers' => $this->getPartyModel()->getAllSuppliers(),
        ]);
    }

    public function edit($id, $duplicate = false)
    {
        $model = $this->getProductModel();

        if ($id == 0) {
            $item = new Product();
            $adjustmentType = StockUpdate::UPDATE_TYPE_INITIAL_STOCK;
        }
        else {
            $item = $model->find($id);
            $adjustmentType = StockUpdate::UPDATE_TYPE_MANUAL_AJDUSTMENT;
            if (!$item) {
                return redirect()->to(base_url('products'))->with('warning', 'Produk tidak ditemukan.');
            }
        }

        if ($duplicate) {
            unset($item->id);
        }
        
        $errors = [];
        $oldStock = $item->stock;
        

        if ($this->request->getMethod() === 'post') {
            $item->fill($this->request->getPost());
            $item->stock = str_to_int($this->request->getPost('stock'));
            $item->cost = str_to_double($this->request->getPost('cost'));
            $item->price = str_to_double($this->request->getPost('price'));

            if (!$item->category_id) $item->category_id = null;
            if (!$item->supplier_id) $item->supplier_id = null;

            if ($item->name == '') {
                $errors['name'] = 'Nama produk harus diisi.';
            }

            if ($model->exists($item->name, $item->id)) {
                $errors['name'] = 'Nama produk sudah digunakan, harap gunakan nama lain.';
            }

            if (empty($errors)) {
                $this->db->transBegin();

                $model->save($item);
                if (!$item->id) {
                    $item->id = $this->db->insertID();
                }

                if ($oldStock != $item->stock) {
                    $balance = $item->stock - $oldStock;
                    $this->getStockUpdateModel()->addStockUpdate($item, $adjustmentType, $balance);
                }

                $this->db->transCommit();

                return redirect()->to(base_url('products'))->with('info', 'Berhasil disimpan.');
            }
        }
        
        return view('product/edit', [
            'duplicate' => $duplicate,
            'data' => $item,
            'errors' => $errors,
            'categories' => $this->getProductCategoryModel()->getAll(),
            'suppliers' => $this->getPartyModel()->getAllSuppliers(),
        ]);
    }

    public function duplicate($id)
    {
        return $this->edit($id, true);
    }

    public function delete($id)
    {
        $model = $this->getProductModel(); 
        $product = $model->find($id);
        if (!$product) {
            return redirect()->to(base_url('products'))->with('warning', 'Produk tidak ditemukan.');
        }

        if ($product->active) {
            $product->active = false;
            $model->save($product);
            return redirect()->to(base_url('products'))->with('info', 'Produk telah dinonaktifkan.');
        }

        $model->delete($id);
        return redirect()->to(base_url('products'))->with('info', 'Produk telah dihapus.');
    }
}
