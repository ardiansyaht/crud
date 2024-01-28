<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        // Cek apakah pengguna sudah login
        if (!session()->has('session_username')) {
            return redirect()->to('login'); // Ganti dengan URL login yang sesuai
        }

        // Cek apakah peran pengguna adalah "admin"
        if (session()->get('session_role') !== 'admin') {
            return redirect()->to('unauthorized'); // Ganti dengan URL halaman unauthorized yang sesuai
        }

        // Load view dan layout
        return view('layouts/main_layout', [
            'content' => view('dashboard_content') // Ganti dengan nama file view dashboard_content.php
        ]);
    }
}
