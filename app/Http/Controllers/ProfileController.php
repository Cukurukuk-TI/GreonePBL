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
     * Proses update profil untuk user biasa.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $this->updateUserProfile($request);
        return Redirect::route('profile.index')->with('status', 'profile-updated');
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

        if ($user->foto) {
            Storage::delete('public/' . $user->foto);
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
