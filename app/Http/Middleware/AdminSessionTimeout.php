<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class AdminSessionTimeout
{
    // Timeout dalam detik (1 menit = 60 detik)
    protected $timeout = 7200;

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            $lastActivity = Session::get('last_activity_time');

            if ($lastActivity && (time() - $lastActivity > $this->timeout)) {
                Auth::logout();
                Session::flush();
                return redirect()->route('login')->with('message', 'Session telah habis karena tidak ada aktivitas.');
            }

            Session::put('last_activity_time', time());
        }

        return $next($request);
    }
}
