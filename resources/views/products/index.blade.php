@extends('layouts.app')

@section('title', 'Products - ASgor')

@section('content')
<style>
    .category-dropdown {
        /* Padding kanan (35px) diperbesar agar teks tidak menabrak ikon */
        padding: 8px 35px 8px 15px !important; 
        border-radius: 8px;
        border: 1px solid #E2E8F0;
        background-color: white;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        
        /* Menghilangkan panah default bawaan browser */
        appearance: none; 
        -moz-appearance: none;
        -webkit-appearance: none;
        
        /* Membuat panah custom yang jaraknya bisa kita atur presisi */
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23000000%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
        background-repeat: no-repeat;
        background-position: right 12px top 50%; /* Jarak ikon panah dari kanan adalah 12px */
        background-size: 10px auto;
    }
</style>

<div class="products-wrapper">
    <!-- MENU HEADER -->
    <div class="menu-header">
        <h2 class="menu-title">Menu</h2>
        <div class="category-filter">
            <select class="category-dropdown" id="categorySelect" onchange="changeCategory(this.value)">
                <option value="Makanan" {{ $category === 'Makanan' ? 'selected' : '' }}>Makanan</option>
                <option value="Minuman" {{ $category === 'Minuman' ? 'selected' : '' }}>Minuman</option>
            </select>
        </div>
    </div>

    <!-- PRODUCTS GRID -->
    <div class="product-grid" id="productGrid">
        @forelse($makanan as $product)
            <div class="product-card" data-category="{{ $product->category }}" style="{{ $category !== $product->category ? 'display:none;' : '' }}">
                <div class="product-image">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/180x135?text={{ urlencode($product->name) }}" alt="{{ $product->name }}">
                    @endif
                </div>
                <div class="product-info">
                    <div class="product-name">{{ $product->name }}</div>
                    <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    <button onclick="addToCart({{ $product->id }})" style="margin-top: 10px; width: 100%; padding: 8px; background-color: #1814F3; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 500;">
                        Add
                    </button>
                </div>
            </div>
        @empty
            <p style="grid-column: 1/-1; text-align: center; color: #B1B1B1;">Tidak ada produk</p>
        @endforelse

        @forelse($minuman as $product)
            <div class="product-card" data-category="{{ $product->category }}" style="{{ $category !== $product->category ? 'display:none;' : '' }}">
                <div class="product-image">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/180x135?text={{ urlencode($product->name) }}" alt="{{ $product->name }}">
                    @endif
                </div>
                <div class="product-info">
                    <div class="product-name">{{ $product->name }}</div>
                    <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    <button onclick="addToCart({{ $product->id }})" style="margin-top: 10px; width: 100%; padding: 8px; background-color: #1814F3; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 500;">
                        Add
                    </button>
                </div>
            </div>
        @empty
            <p style="grid-column: 1/-1; text-align: center; color: #B1B1B1;">Tidak ada produk</p>
        @endforelse
    </div>

    <!-- PAGINATION -->
    <div class="pagination products-pagination">
        {{ $makanan->links('vendor.pagination.custom') }}
    </div>
</div>

<script>
    function changeCategory(category) {
        const cards = document.querySelectorAll('.product-card');
        cards.forEach(card => {
            if (card.dataset.category === category) {
                card.style.display = 'flex';
                card.style.flexDirection = 'column';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function nextPage() {
        alert('Next page');
    }

    function prevPage() {
        alert('Previous page');
    }

    function addToCart(productId) {
        $.ajax({
            url: '/api/add-to-cart',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            data: {
                product_id: productId
            },
            success: function(response) {
                // Jika backend membalas success: true
                if (response.success) {
                    location.reload(); 
                } 
                // Jika backend menolak tapi pakai status HTTP 200 OK
                else {
                    alert('Gagal: ' + (response.message || 'Stok tidak mencukupi!'));
                }
            },
            error: function(xhr) {
                // Jika backend menolak pakai status HTTP Error (400, 500, dll)
                let errorMessage = 'Terjadi kesalahan sistem, silakan coba lagi.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                alert('Gagal: ' + errorMessage);
            }
        });
    }
</script>
@endsection