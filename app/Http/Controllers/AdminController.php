<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Tampilkan daftar semua akun Kasir.
     */
    public function index()
    {
        $kasirs = User::where('role', 'kasir')->latest()->get();
        return view('admin.kasir.index', compact('kasirs'));
    }

    /**
     * Form buat akun Kasir baru.
     */
    public function create()
    {
        return view('admin.kasir.create');
    }

    /**
     * Simpan akun Kasir baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah digunakan.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'kasir',
        ]);

        return redirect()->route('admin.kasir.index')
            ->with('success', 'Akun Kasir "' . $validated['name'] . '" berhasil dibuat!');
    }

    /**
     * Hapus akun Kasir.
     */
    public function destroy(User $kasir)
    {
        // Pastikan tidak menghapus akun Admin
        if ($kasir->isAdmin()) {
            return redirect()->route('admin.kasir.index')
                ->with('error', 'Tidak bisa menghapus akun Admin.');
        }

        $kasirName = $kasir->name;
        $kasir->delete();

        return redirect()->route('admin.kasir.index')
            ->with('success', 'Akun Kasir "' . $kasirName . '" berhasil dihapus.');
    }
}
