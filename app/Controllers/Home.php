<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('homepage', [
            'title' => 'Toko Nawir',
        ]);
    }
    public function about()
    {
        return view('about', [
            'title' => 'About Us',
        ]);
    }
}
