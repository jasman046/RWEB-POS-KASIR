<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ASgor - Nasi Goreng Ordering System')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Barlow:wght@400;500;600&family=Inter:wght@400;500;600&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('extra_css')
</head>
<body>
    <!-- HEADER -->
    <header class="header">
        <div class="header-left">
            <a href="/" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-wallet" style="color: #1814F3; font-size: 18px;"></i>
                </div>
                <span class="logo-text">ASgor</span>
            </a>
           <h1 class="page-title">@yield('page-title', 'PRODUCT')</h1>
        </div>

        <div class="header-center">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Search for something" onkeyup="searchProduct(this.value)">
            </div>
        </div>

        <div class="header-right">
            <button class="icon-button">
                <i class="fas fa-cog" style="color: #718EBF; font-size: 20px;"></i>
            </button>
            <button class="icon-button">
                <i class="fas fa-bell" style="color: #FE5C73; font-size: 20px;"></i>
            </button>
            <div class="user-avatar">
                <img src="https://via.placeholder.com/60" alt="User">
            </div>
        </div>
    </header>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <a href="{{ route('dashboard') }}"
            class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
       <a href="{{ route('transactions.index') }}"
            class="sidebar-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
            <i class="fas fa-exchange-alt"></i>
            <span>Transactions</span>
        </a>
        <a href="#" class="sidebar-item">
            <i class="fas fa-cog"></i>
            <span>Setting</span>
        </a>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-container">
        @yield('content')
    </main>

    <!-- CART SECTION (Fixed) -->
     @if(request()->routeIs('dashboard'))
    <div class="cart-container" id="cartContainer">
        <div class="cart-header">
            <h3 style="margin: 0;">Orders #002</h3>
            <div class="cart-tabs">
                <button class="tab-btn active" onclick="setOrderType('dine-in')">Dine In</button>
                <button class="tab-btn" onclick="setOrderType('delivery')">Delivery</button>
            </div>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 0 20px 10px 20px; font-weight: bold; color: var(--text-dark); border-bottom: 1px solid var(--border);">
            <span style="flex: 2;">Item</span>
            <span style="flex: 1; text-align: center;">Qty</span>
            <span style="flex: 1; text-align: right;">Price</span>
        </div>
        <div class="cart-items" id="cartItems">
            <p style="text-align: center; color: #B1B1B1; padding: 20px;">Keranjang kosong</p>
        </div>

        <div class="cart-footer">
            <div class="cart-total">
                <span class="cart-total-label">Sub total</span>
                <span class="cart-total-amount" id="cartTotal">Rp. 0</span>
            </div>
            <button class="checkout-btn" onclick="goToCheckout()">Continue to Payment</button>
        </div>
    </div>
@endif
    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Set order type
        function setOrderType(type) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
        }

        // Add to cart
        function addToCart(productId) {
            $.ajax({
                url: '/api/add-to-cart',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                data: {
                    product_id: productId
                },
                success: function(response) {
                    updateCart(response.cart);
                    showNotification('Item ditambahkan ke keranjang');
                }
            });
        }

        // Update quantity
        function updateQuantity(productId, quantity) {
            if (quantity <= 0) {
                removeFromCart(productId);
                return;
            }

            $.ajax({
                url: '/api/update-cart',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function(response) {
                    updateCart(response.cart);
                }
            });
        }

        // Update cart display
        function updateCart(cart) {
            const cartItemsDiv = document.getElementById('cartItems');
            const cartTotalSpan = document.getElementById('cartTotal');

            if (Object.keys(cart).length === 0) {
                cartItemsDiv.innerHTML = '<p style="text-align: center; color: #B1B1B1; padding: 20px;">Keranjang kosong</p>';
                cartTotalSpan.textContent = 'Rp. 0';
                return;
            }

            let html = '';
            let total = 0;

            Object.values(cart).forEach(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;

                html += `
                    <div class="cart-item">
                        <div class="cart-item-image">
                            <img src="${item.image ? '/storage/' + item.image : 'https://via.placeholder.com/49x50'}" alt="${item.name}">
                        </div>
                        <div class="cart-item-details">
                            <div class="cart-item-name">${item.name}</div>
                            <div class="cart-item-price">Rp.${item.price.toLocaleString('id-ID')}</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <button class="qty-btn" onclick="updateQuantity(${item.product_id}, ${item.quantity - 1})">-</button>
                            <span style="width: 20px; text-align: center;">${item.quantity}</span>
                            <button class="qty-btn" onclick="updateQuantity(${item.product_id}, ${item.quantity + 1})">+</button>
                            
                            <button onclick="updateQuantity(${item.product_id}, 0)" style="border: none; background: transparent; color: #FF4B4A; cursor: pointer; padding: 5px; margin-left: 10px;" title="Hapus Produk">
                                <i class="fas fa-trash-alt" style="font-size: 16px;"></i>
                            </button>
                        </div>
                    </div>
                `;
            });

            cartItemsDiv.innerHTML = html;
            cartTotalSpan.textContent = 'Rp. ' + total.toLocaleString('id-ID');
        }

        // Go to checkout
        function goToCheckout() {
            const cartItems = document.querySelectorAll('#cartItems .cart-item');
            if (cartItems.length === 0) {
                alert('Keranjang kosong');
                return;
            }
            window.location.href = '/checkout';
        }

        // Get cart from session
        function getCart() {
            return @json(session()->get('cart', []));
        }

        // Show notification
        function showNotification(message) {
            const div = document.createElement('div');
            notification.classList.add('custom-toast');
            div.style.cssText = 'position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%); background: #1814F3; color: white; padding: 15px 30px; border-radius: 8px; z-index: 9999; box-shadow: 0px 4px 10px rgba(0,0,0,0.2); font-weight: 500; font-size: 15px;';
            div.textContent = message;
            document.body.appendChild(div);
            setTimeout(() => div.remove(), 3000);
        }

        // Initialize cart on page load
        document.addEventListener('DOMContentLoaded', function() {
            const cart = getCart();
            if (Object.keys(cart).length > 0) {
                updateCart(cart);
            }
        });

        function searchProduct(keyword) {
            keyword = keyword.toLowerCase();
            const cards = document.querySelectorAll('.product-card');
            
            cards.forEach(card => {
                const productName = card.querySelector('.product-name').innerText.toLowerCase();
                // Cek apakah nama produk mengandung huruf yang diketik
                if (productName.includes(keyword)) {
                    card.style.display = 'flex'; // Munculkan
                    card.style.flexDirection = 'column';
                } else {
                    card.style.display = 'none'; // Sembunyikan
                }
            });
        }

        function removeFromCart(productId) {
        // Tampilkan peringatan sebelum menghapus
        if (confirm('Yakin ingin menghapus menu ini dari keranjang?')) {
            $.ajax({
                url: '/api/update-cart', // Kita pakai endpoint yang sama
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                data: {
                    product_id: productId,
                    quantity: 0 // Set quantity ke 0 agar dihapus oleh Controller
                },
                success: function(response) {
                    // Reload halaman agar keranjang ter-update
                    location.reload(); 
                },
                error: function() {
                    alert('Gagal menghapus produk, silakan coba lagi.');
                }
            });
        }
        }
    </script>

    @yield('extra_js')
</body>
</html>