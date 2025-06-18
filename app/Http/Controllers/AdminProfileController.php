<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminProfileController extends Controller
{
    //
    public function profile()
    {
        // Logika untuk menampilkan halaman profil admin
        return view('admin.admin-profile.index');
    }
}
