<?php

namespace App\Controllers;

use App\Entities\User;

class UserController extends BaseController
{
    public function index()
    {
        $items = $this->getUserModel()->getAll();

        return view('user/index', [
            'items' => $items,
        ]);
    }

    public function edit($id)
    {
        $model = $this->getUserModel();
        if ($id == 0) {
            $item = new User();
        }
        else {
            $item = $model->find($id);
            if (!$item) {
                return redirect()->to(base_url('users'))->with('warning', 'Pengguna tidak ditemukan.');
            }
        }

        if ($item->username == 'admin') {
            return redirect()->to(base_url('users'))->with('error', 'Akun ini tidak dapat diubah.');
        }

        $errors = [];

        if ($this->request->getMethod() === 'post') {
            $item->username = trim($this->request->getPost('username'));
            $item->fullname = trim($this->request->getPost('fullname'));
            $item->password = $this->request->getPost('password');
            $item->is_admin = (int)$this->request->getPost('is_admin');
            $item->active = (int)$this->request->getPost('active');

            if ($item->username == '') {
                $errors['username'] = 'Username harus diisi.';
            }

            if ($model->exists($item->username, $item->id)) {
                $errors['username'] = 'Username sudah digunakan, harap gunakan nama lain.';
            }
            
            if ($item->fullname == '') {
                $errors['fullname'] = 'Nama lengkap harus diisi.';
            }

            if (!$item->id) {
                if ($item->password == '') {
                    $errors['password'] = 'Kata sandi harus diisi.';
                }
                else {
                    $item->password = sha1($item->password);
                }
            }
            else if ($item->password != '') {
                $item->password = sha1($item->password);
            }

            if (empty($errors)) {
                $model->save($item);
                return redirect()->to(base_url('users'))->with('info', 'Berhasil disimpan.');
            }
        }
        else {
            $item->password = '';
        }
        
        return view('user/edit', [
            'data' => $item,
            'errors' => $errors,
        ]);
    }

    public function profile()
    {
        // TODO: implementasikan auth
        $id = session()->get('current_user')['id'];
        $errors = [];

        $model = $this->getUserModel(); 
        $item = $model->find($id);

        if ($this->request->getMethod() === 'post') {
            $item->fullname = trim($this->request->getPost('fullname'));
            $item->password1 = $this->request->getPost('password1');
            $item->password2 = $this->request->getPost('password2');
            
            if ($item->fullname == '') {
                $errors['fullname'] = 'Nama lengkap harus diisi.';
            }

            if ($item->password1 != '') {
                // user ingin mengganti password
                if ($item->password1 != $item->password2) {
                    $errors['password2'] = 'Kata sandi tidak cocok.';
                }
            }

            if (empty($errors)) {
                $item->password = sha1($item->password1);
                $model->save($item);
                return redirect()->to(base_url('users'))->with('info', 'Berhasil disimpan.');
            }
        }
        else {
            $item->password1 = '';
            $item->password2 = '';
        }

        return view('user/profile', [
            'data' => $item,
            'errors' => $errors,
        ]);
    }

    public function delete($id)
    {
        $model = $this->getUserModel();
        $user = $model->find($id);

        if ($user->username == 'admin') {
            return redirect()->to(base_url('users'))
                ->with('error', 'Akun <b>' . esc($user->username) . '</b> tidak dapat dihapus.');
        }

        $user->active = 0;
        $model->save($user);
        return redirect()->to(base_url('users'))->with('info', 'Pengguna telah dinonaktifkan.');
    }
}
