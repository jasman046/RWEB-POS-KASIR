@extends('layouts.app')

@section('title', 'Kelola Produk - Admin')
@section('page-title', 'PRODUK')

@section('content')
<div class="admin-page-container">

    {{-- Alert --}}
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

    {{-- Header --}}
    <div class="admin-table-topbar">
        <div>
            <h2 class="admin-table-title">Daftar Produk</h2>
            <p class="admin-table-subtitle">Total: {{ $products->total() }} produk</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="admin-btn-primary">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
    </div>

    {{-- Tabel Produk --}}
    <div class="admin-table-card">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $index => $product)
                    <tr>
                        <td>{{ $products->firstItem() + $index }}</td>
                        <td>
                            <div class="admin-product-thumb">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                @else
                                    <div class="admin-product-no-image">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="admin-product-name">{{ $product->name }}</span>
                            @if($product->description)
                                <p class="admin-product-desc">{{ Str::limit($product->description, 40) }}</p>
                            @endif
                        </td>
                        <td>
                            <span class="admin-badge {{ $product->category === 'Makanan' ? 'admin-badge-food' : 'admin-badge-drink' }}">
                                {{ $product->category }}
                            </span>
                        </td>
                        <td class="admin-price-col">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            <span class="admin-stock-badge {{ $product->stock <= 5 ? 'admin-stock-low' : 'admin-stock-ok' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td>
                            <div class="admin-action-btns">
                                <a href="{{ route('admin.products.edit', $product) }}" class="admin-btn-edit" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn-delete" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="admin-empty-row">
                            <i class="fas fa-box-open"></i>
                            <p>Belum ada produk. <a href="{{ route('admin.products.create') }}">Tambah produk pertama</a></p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
        <div class="admin-pagination-wrapper">
            {{ $products->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>
</div>
@endsection
