<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CekAtmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // cek admin 
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            // Jika user tidak terautentikasi atau bukan admin, redirect ke halaman login
            return redirect()->route('login')->with('error', 'Anda harus login sebagai admin untuk mengakses halaman ini.');
        }
        return $next($request);
    }
}
