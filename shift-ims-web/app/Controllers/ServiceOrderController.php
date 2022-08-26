<?php

namespace App\Controllers;

use DateTime;

class ServiceOrderController extends BaseController
{
    public function index()
    {
        $filter = new \StdClass();
        $filter->order_status = $this->request->getGet('order_status');
        $filter->service_status = $this->request->getGet('service_status');
        $filter->payment_status = $this->request->getGet('payment_status');

        if (!$filter->order_status) $filter->order_status = 1;
        if (!$filter->service_status) $filter->service_status = -1;
        if (!$filter->payment_status) $filter->payment_status = -1;

        $items = $this->getServiceOrderModel()->getAll($filter);

        return view('service-order/index', [
            'filter' => $filter,
            'items' => $items,
        ]);
    }

    public function edit($id, $duplicate = false)
    {
        $model = $this->getServiceOrderModel();

        if ($id == 0) {
            $item = new \App\Entities\ServiceOrder();
            $item->date = date('d/m/Y');
        }
        else {
            $item = $model->find($id);
            if (!$item) {
                return redirect()->to(base_url('service-orders'))->with('warning', 'Order tidak ditemukan.');
            }
        }

        if ($duplicate) {
            unset($item->id);
        }
        
        $errors = [];

        if ($this->request->getMethod() === 'post') {
            $item->date = trim($this->request->getPost('date'));
            $item->date = DateTime::createFromFormat('d/m/Y', $item->date)->format('Y-m-d');
            $item->status = intval($this->request->getPost('status'));
            $item->customer_id = intval($this->request->getPost('customer_id'));
            $item->customer_name = trim($this->request->getPost('customer_name'));
            $item->customer_address = trim($this->request->getPost('customer_address'));
            $item->customer_contacts = trim($this->request->getPost('customer_contacts'));

            $item->device = trim($this->request->getPost('device'));
            $item->accessories = trim($this->request->getPost('accessories'));
            $item->problems = trim($this->request->getPost('problems'));
            $item->damages = trim($this->request->getPost('damages'));
            $item->actions = trim($this->request->getPost('actions'));
            $item->service_status = intval($this->request->getPost('service_status'));

            $item->estimated_cost = intval($this->request->getPost('estimated_cost'));
            $item->down_payment = intval($this->request->getPost('down_payment'));

            $item->parts_cost = intval($this->request->getPost('parts_cost'));
            $item->service_cost = intval($this->request->getPost('service_cost'));
            $item->other_cost = intval($this->request->getPost('other_cost'));
            $item->total_cost = intval($this->request->getPost('total_cost'));
            $item->payment_status = intval($this->request->getPost('payment_status'));

            $item->notes = trim($this->request->getPost('notes'));

            if (!$item->customer_id) {
                $errors['customer_id'] = 'Silahkan pilih pelanggan.';
            }

            if (empty($errors)) {
                $model->save($item);
                $id = $this->db->insertID();
                return redirect()->to(base_url("service-orders/view/$id"))->with('info', 'Order telah disimpan.');
            }
        }
        
        return view('service-order/edit', [
            'duplicate' => $duplicate,
            'data' => $item,
            'errors' => $errors,
            'customers' => $this->getPartyModel()->getAllCustomers(),
        ]);
    }

    public function duplicate($id)
    {
        return $this->edit($id, true);
    }

    public function delete($id)
    {
        $model = $this->getServiceOrderModel(); 
        $order = $model->find($id);
        if (!$order) {
            return redirect()->to(base_url('service-orders'))->with('warning', 'Order tidak ditemukan.');
        }

        $model->delete($id);
        return redirect()->to(base_url('service-orders'))->with('info', 'Orderd telah dihapus.');
    }

    public function view($id) {
        $model = $this->getServiceOrderModel();
        $item = $model->find($id);

        if (!$item) {
            return redirect()->to(base_url('service-orders'))->with('warning', 'Order tidak ditemukan.');
        }

        $print = $this->request->getGet('print');

        return view('service-order/view', [
            'data' => $item,
            'print' => $print
        ]);
    }
}
