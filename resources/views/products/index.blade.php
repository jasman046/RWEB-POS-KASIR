@extends('layouts.app')

@section('title', 'Products - ASgor')

@section('content')
<div style="margin-right: 440px;">
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
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
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
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
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
    <div class="pagination" style="margin-right: -440px; padding-right: 440px; display: flex; justify-content: center; margin-top: 20px;">
        {{ $makanan->links('pagination::bootstrap-4') }}
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
</script>
@endsection