@extends('layouts.app')

@section('title', 'Tambah Kasir - Admin')
@section('page-title', 'TAMBAH KASIR')

@section('content')
<div class="admin-page-container">
    <div class="admin-form-card admin-form-card-narrow">
        <div class="admin-form-header">
            <a href="{{ route('admin.kasir.index') }}" class="admin-back-link">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kasir
            </a>
            <h2 class="admin-form-title">Buat Akun Kasir Baru</h2>
            <p class="admin-form-subtitle">Akun ini akan memiliki role "Kasir" dan hanya bisa mengakses menu utama POS.</p>
        </div>

        <form method="POST" action="{{ route('admin.kasir.store') }}" class="setting-form">
            @csrf

            <div class="form-group">
                <label class="admin-form-label">Nama Lengkap <span class="admin-required">*</span></label>
                <input type="text" name="name" class="setting-input"
                    value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" required>
                @error('name')<p class="setting-field-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="admin-form-label">Email <span class="admin-required">*</span></label>
                <input type="email" name="email" class="setting-input"
                    value="{{ old('email') }}" placeholder="email@kasir.com" required>
                @error('email')<p class="setting-field-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="admin-form-label">Password <span class="admin-required">*</span></label>
                <input type="password" name="password" class="setting-input"
                    placeholder="Minimal 8 karakter" required>
                @error('password')<p class="setting-field-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="admin-form-label">Konfirmasi Password <span class="admin-required">*</span></label>
                <input type="password" name="password_confirmation" class="setting-input"
                    placeholder="Ulangi password" required>
            </div>

            <div class="admin-form-footer">
                <a href="{{ route('admin.kasir.index') }}" class="admin-btn-secondary">Batal</a>
                <button type="submit" class="admin-btn-primary">
                    <i class="fas fa-user-plus"></i> Buat Akun Kasir
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
