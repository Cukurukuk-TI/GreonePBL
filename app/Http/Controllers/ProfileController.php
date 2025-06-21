<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil.
     */
    public function adminIndex(): View {
    return view('admin.profile.content', [
        'user' => auth()->user()->isAdmin()
    ]);
    }

    public function index(Request $request): View
    {
        return view('profile.content', [
            'user' => $request->user(),
            // Refactor: bisa eager load relasi jika dibutuhkan → $request->user()->load('alamat')
        ]);
    }

    /**
     * Menampilkan form edit profil.
     */

    // edit atmin
    public function editatmin(Request $request) :View {
        return view('admin.profile.edit');
    }
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            // Refactor: jika form menampilkan alamat, gunakan eager loading → $request->user()->load('alamat')
        ]);
    }

    /**
     * Memperbarui informasi profil pengguna.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $path = $request->file('foto')->store('profile-photos', 'public');
            $data['foto'] = $path;
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        if ($user->email_verified_at === null) {
            $user->sendEmailVerificationNotification();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Menghapus akun pengguna.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->foto) {
            Storage::delete('public/' . $user->foto);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // Refactor: Tambahkan method privat untuk mengelola upload file agar tidak duplikatif
    // private function handleProfilePhotoUpload(Request $request, User $user): ?string { ... }
}
