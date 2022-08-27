<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function login()
    {
        $session = session();
        $username = '';
        $password = '';
        $remember = 1;
        $error = null;

        if ($this->request->getMethod() == 'post') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $remember = (bool)$this->request->getPost('remember');
            $user = $this->getUserModel()->findByUsername($username);

            if ($username == '') {
                $error = 'Username harus diisi.';
            }
            else if (empty($password)) {
                $error = 'Masukkan kata sandi anda.';
            }
            else if (!$user) {
                $error = 'Pengguna tidak ditemukan.';
            }
            else if (!$user->active) {
                $error = 'Akun anda tidak aktif.';
            }
            else if ($user->password != sha1($password)) {
                $error = 'Kata sandi anda salah.';
            }
            else {
                $session->set([
                    'is_logged_in' => TRUE,
                    'current_user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'is_admin' => $user->is_admin
                    ]
                ]);
                return redirect()->to(base_url('/'));
            }
        }

        return view('auth/login', [
            'username' => $username,
            'password' => $password,
            'remember' => $remember,
            'error' => $error,
        ]);
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url('auth/login'));
    }
}
