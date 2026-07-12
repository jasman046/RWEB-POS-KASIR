@extends('layouts.app')

@section('title', 'Kelola Kasir - Admin')
@section('page-title', 'KASIR')

@section('content')
<div class="admin-page-container">

    @if(session('success'))
        <div class="admin-alert admin-alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="admin-alert admin-alert-error">
            <i class="fas fa-times-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="admin-table-topbar">
        <div>
            <h2 class="admin-table-title">Daftar Akun Kasir</h2>
            <p class="admin-table-subtitle">Total: {{ $kasirs->count() }} kasir terdaftar</p>
        </div>
        <a href="{{ route('admin.kasir.create') }}" class="admin-btn-primary">
            <i class="fas fa-user-plus"></i> Tambah Kasir
        </a>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kasirs as $index => $kasir)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="admin-user-cell">
                                <div class="admin-user-avatar">
                                    <img src="{{ $kasir->avatar
                                        ? asset('storage/avatars/' . $kasir->avatar)
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($kasir->name) . '&background=1814F3&color=fff&size=40' }}"
                                        alt="{{ $kasir->name }}">
                                </div>
                                <div>
                                    <span class="admin-product-name">{{ $kasir->name }}</span>
                                    @if($kasir->username)
                                        <p class="admin-product-desc">@{{ $kasir->username }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $kasir->email }}</td>
                        <td>{{ $kasir->created_at->format('d M Y') }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.kasir.destroy', $kasir) }}"
                                onsubmit="return confirm('Yakin ingin menghapus akun kasir {{ $kasir->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn-delete" title="Hapus Akun">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="admin-empty-row">
                            <i class="fas fa-users"></i>
                            <p>Belum ada akun kasir. <a href="{{ route('admin.kasir.create') }}">Buat akun kasir pertama</a></p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
