<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function index() {
        // Logika untuk menampilkan halaman admin
        return view('admin.dashboard');
    }
}
