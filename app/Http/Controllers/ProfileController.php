<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information (name, email, username, etc.)
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update profil lengkap dari halaman Setting.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'username'          => 'nullable|string|max:100',
            'email'             => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'date_of_birth'     => 'nullable|date',
            'present_address'   => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:100',
            'postal_code'       => 'nullable|string|max:20',
            'country'           => 'nullable|string|max:100',
        ]);

        $user = Auth::user();

        // Jika email berubah, reset verifikasi
        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->fill($validated);
        $user->save();

        return Redirect::route('setting')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Ganti password user dengan aman.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|confirmed',
        ], [
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'new_password.min'       => 'Password baru minimal 8 karakter.',
        ]);

        $user = Auth::user();

        // Cek apakah password lama cocok
        if (!Hash::check($validated['current_password'], $user->password)) {
            return Redirect::route('setting')
                ->withErrors(['current_password' => 'Password saat ini salah.'])
                ->with('open_tab', 'security');
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return Redirect::route('setting')
            ->with('success', 'Password berhasil diubah!')
            ->with('open_tab', 'security');
    }

    /**
     * Upload & update foto profil user.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'avatar.image'    => 'File harus berupa gambar.',
            'avatar.mimes'    => 'Format gambar harus jpeg, png, jpg, gif, atau webp.',
            'avatar.max'      => 'Ukuran gambar maksimal 2MB.',
        ]);

        $user = Auth::user();

        // Hapus avatar lama jika ada
        if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        // Simpan avatar baru
        $file     = $request->file('avatar');
        $fileName = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('avatars', $fileName, 'public');

        $user->avatar = $fileName;
        $user->save();

        return Redirect::route('setting')->with('success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
