<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;
use App\Models\DiskonModel; // Tambahkan ini

class AuthController extends BaseController
{
    protected $user;
    protected $diskon; // Tambahkan ini

    function __construct()
    {
        helper('form');
        $this->user = new UserModel();
        $this->diskon = new DiskonModel(); // Tambahkan ini
    }

    public function login()
    {
        if ($this->request->getPost()) {
            $rules = [
                'username' => 'required|min_length[6]',
                'password' => 'required|min_length[7]|numeric',
            ];

            if ($this->validate($rules)) {
                $username = $this->request->getVar('username');
                $password = $this->request->getVar('password');

                $dataUser = $this->user->where(['username' => $username])->first();

                if ($dataUser) {
                    if (password_verify($password, $dataUser['password'])) {
                        $sessionData = [
                            'username' => $dataUser['username'],
                            'role' => $dataUser['role'],
                            'isLoggedIn' => TRUE
                        ];
                        
                        // Cek diskon hari ini
                        $today_diskon = $this->diskon->where('tanggal', date('Y-m-d'))->first();
                        if($today_diskon){
                            $sessionData['diskon'] = $today_diskon['nominal'];
                        }

                        session()->set($sessionData);

                        return redirect()->to(base_url('/'));
                    } else {
                        session()->setFlashdata('failed', 'Kombinasi Username & Password Salah');
                        return redirect()->back();
                    }
                } else {
                    session()->setFlashdata('failed', 'Username Tidak Ditemukan');
                    return redirect()->back();
                }
            } else {
                session()->setFlashdata('failed', $this->validator->listErrors());
                return redirect()->back();
            }
        }

        return view('v_login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }
}