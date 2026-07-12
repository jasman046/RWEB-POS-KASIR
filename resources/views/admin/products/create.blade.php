@extends('layouts.app')

@section('title', 'Tambah Produk - Admin')
@section('page-title', 'TAMBAH PRODUK')

@section('content')
<div class="admin-page-container">
    <div class="admin-form-card">
        <div class="admin-form-header">
            <a href="{{ route('admin.products.index') }}" class="admin-back-link">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Produk
            </a>
            <h2 class="admin-form-title">Tambah Produk Baru</h2>
        </div>

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="admin-product-form">
            @csrf

            <div class="admin-form-body">
                {{-- Kolom Kiri: Preview Gambar --}}
                <div class="admin-image-upload-col">
                    <div class="admin-image-preview-box" id="imagePreviewBox">
                        <img id="imagePreview" src="" alt="Preview" class="admin-image-preview-img hidden">
                        <div class="admin-image-placeholder" id="imagePlaceholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Klik untuk upload gambar</p>
                            <span>JPEG, PNG, WebP — Max 2MB</span>
                        </div>
                        <label for="imageInput" class="admin-image-label-overlay"></label>
                        <input type="file" id="imageInput" name="image" accept="image/*" class="setting-hidden-input" onchange="previewImage(this)">
                    </div>
                    @error('image')<p class="setting-field-error">{{ $message }}</p>@enderror
                </div>

                {{-- Kolom Kanan: Form Fields --}}
                <div class="admin-form-fields-col">
                    <div class="form-group">
                        <label class="admin-form-label">Nama Produk <span class="admin-required">*</span></label>
                        <input type="text" name="name" class="setting-input {{ $errors->has('name') ? 'input-error' : '' }}"
                            value="{{ old('name') }}" placeholder="Contoh: Nasi Goreng Special" required>
                        @error('name')<p class="setting-field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="admin-form-row">
                        <div class="form-group">
                            <label class="admin-form-label">Kategori <span class="admin-required">*</span></label>
                            <select name="category" class="setting-input" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Makanan" {{ old('category') === 'Makanan' ? 'selected' : '' }}>Makanan</option>
                                <option value="Minuman" {{ old('category') === 'Minuman' ? 'selected' : '' }}>Minuman</option>
                            </select>
                            @error('category')<p class="setting-field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="form-group">
                            <label class="admin-form-label">Stok Awal <span class="admin-required">*</span></label>
                            <input type="number" name="stock" class="setting-input"
                                value="{{ old('stock', 0) }}" min="0" required>
                            @error('stock')<p class="setting-field-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="admin-form-label">Harga (Rp) <span class="admin-required">*</span></label>
                        <input type="number" name="price" class="setting-input"
                            value="{{ old('price') }}" min="0" placeholder="Contoh: 25000" required>
                        @error('price')<p class="setting-field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="admin-form-label">Deskripsi</label>
                        <textarea name="description" class="setting-input admin-textarea"
                            placeholder="Deskripsi singkat produk (opsional)...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="admin-form-footer">
                <a href="{{ route('admin.products.index') }}" class="admin-btn-secondary">Batal</a>
                <button type="submit" class="admin-btn-primary">
                    <i class="fas fa-save"></i> Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>

@section('extra_js')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imagePreview');
                const placeholder = document.getElementById('imagePlaceholder');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
@endsection
