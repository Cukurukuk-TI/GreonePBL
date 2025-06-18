<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRules;

class PasswordController extends Controller
{
    /**
     * Memperbarui password pengguna.
     */
    public function update(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', PasswordRules::defaults(), 'confirmed'],
        ]);

        // Perbarui password di database
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Redirect kembali dengan pesan sukses
        return back()->with('status', 'password-updated');
    }
}

