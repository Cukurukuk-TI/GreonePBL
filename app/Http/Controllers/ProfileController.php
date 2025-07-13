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
     * Menampilkan halaman profil untuk admin.
     */
    public function adminIndex(): View
    {
        return view('admin.profile.content', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Menampilkan halaman profil untuk user.
     */
    public function index(Request $request): View
    {
        return view('profile.content', [
            'user' => $request->user(),
        ]);
    }

    public function show(Request $request): View
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Menampilkan form edit profil untuk admin.
     */
    public function editAdmin(Request $request): View
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Menampilkan form edit profil untuk user.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Menampilkan form untuk mengganti password.
     */
    public function editPassword(): View
    {
        return view('profile.password');
    }

    /**
     * Menampilkan halaman konfirmasi hapus akun.
     */
    public function delete(): View
    {
        return view('profile.delete');
    }

    /**
     * Proses update profil untuk user biasa.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle file upload jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            // Simpan foto baru
            $user->foto = $request->file('foto')->store('profile-photos', 'public');
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Proses update profil untuk admin.
     */
    public function updateAdmin(ProfileUpdateRequest $request): RedirectResponse
    {
        $this->updateUserProfile($request);
        return Redirect::route('admin.profile.index')->with('status', 'profile-updated');
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

        // Hapus file foto profil dari storage
        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }

        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Method privat untuk update profil yang digunakan oleh admin dan user.
     */
    private function updateUserProfile(ProfileUpdateRequest $request): void
    {
        $user = $request->user();
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }

            $data['foto'] = $request->file('foto')->store('profile-photos', 'public');
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }

        $user->save();
    }
}
