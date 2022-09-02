<?php

namespace App\Controllers;

use App\Entities\User;
use App\Entities\UserGroup;

class UserGroupController extends BaseController
{
    public function index()
    {
        $items = $this->getUserGroupModel()->getAll();

        return view('user-group/index', [
            'items' => $items,
        ]);
    }

    public function edit($id)
    {
        $model = $this->getUserGroupModel();
        if ($id == 0) {
            $item = new UserGroup();
        }
        else {
            $item = $model->find($id);
            if (!$item) {
                return redirect()->to(base_url('user-groups'))->with('warning', 'Grup Pengguna tidak ditemukan.');
            }
        }

        $errors = [];

        if ($this->request->getMethod() === 'post') {
            $item->name = trim($this->request->getPost('name'));
            $item->description = $this->request->getPost('description');

            if ($item->name == '') {
                $errors['name'] = 'Nama harus diisi.';
            }
            else if ($model->exists($item->name, $item->id)) {
                $errors['name'] = 'Nama sudah digunakan, harap gunakan nama lain.';
            }

            if (empty($errors)) {
                $model->save($item);
                return redirect()->to(base_url('user-groups'))->with('info', 'Grup pengguna telah disimpan.');
            }
        }
        
        return view('user-group/edit', [
            'data' => $item,
            'errors' => $errors,
        ]);
    }

    public function delete($id)
    {
        $model = $this->getUserGroupModel();
        $userGroup = $model->find($id);

        // TODO: cek jumlah pengguna, jika ada tidak boleh dihapus

        $message = 'Grup pengguna tidak dapat dihapus.';

        if ($model->delete($userGroup->id)) {
            $message = 'Grup pengguna telah dihapus.';
        }

        return redirect()->to(base_url('user-groups'))->with('info', $message);
    }
}
