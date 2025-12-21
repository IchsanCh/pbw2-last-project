<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Controllers\BaseController;

class LoginController extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('login', [
            'title' => 'Login'
        ]);
    }

    public function validateLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username dan password tidak boleh kosong');
        }

        $userModel = new UserModel();

        $user = $userModel
            ->where('username', $this->request->getPost('username'))
            ->first();
        if (!$user) {
            return redirect()->back()->with('error', 'Username atau password salah');
        }

        if (!password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->with('error', 'Username atau password salah');
        }
        if (isset($user['is_active']) && !$user['is_active']) {
            return redirect()->back()->with('error', 'Akun Anda telah dinonaktifkan');
        }
        session()->set([
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'nama'       => $user['nama'],
            'role'       => $user['role'],
            'isLoggedIn' => true
        ]);
        session()->regenerate();
        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah logout');
    }
}
