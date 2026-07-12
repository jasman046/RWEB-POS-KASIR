<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ASgor - Nasi Goreng Ordering System')</title>
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Barlow:wght@400;500;600&family=Inter:wght@400;500;600&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('extra_css')
</head>
<body>
    <!-- HEADER -->
    <header class="header">
        <div class="header-left">
            {{-- HAMBURGER BUTTON (mobile only) --}}
            <button class="hamburger-btn" id="hamburgerBtn" onclick="toggleSidebar()" aria-label="Toggle Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <a href="{{ route('dashboard') }}" class="logo">
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
            <a href="{{ route('setting') }}" class="icon-button">
                <i class="fas fa-cog" style="color: #718EBF; font-size: 20px;"></i>
            </a>
            <a href="{{ route('notification') }}" class="icon-button">
                <i class="fas fa-bell" style="color: #FE5C73; font-size: 20px;"></i>
            </a>
            <div class="user-avatar">
                <img src="{{ auth()->user()->avatar
                    ? asset('storage/avatars/' . auth()->user()->avatar)
                    : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=1814F3&color=fff&size=60' }}"
                    alt="{{ auth()->user()->name }}">
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

        @if(auth()->user()->isAdmin())
        {{-- Menu khusus Admin --}}
        <a href="{{ route('transactions.index') }}"
            class="sidebar-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
            <i class="fas fa-exchange-alt"></i>
            <span>Transactions</span>
        </a>
        <a href="{{ route('admin.products.index') }}"
            class="sidebar-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="fas fa-box-open"></i>
            <span>Products</span>
        </a>
        <a href="{{ route('admin.kasir.index') }}"
            class="sidebar-item {{ request()->routeIs('admin.kasir.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Kasir</span>
        </a>
        <a href="{{ route('admin.reports') }}"
            class="sidebar-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i>
            <span>Laporan</span>
        </a>
        @endif

        <a href="{{ route('setting') }}" class="sidebar-item {{ request()->routeIs('setting') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span>Setting</span>
        </a>

        {{-- ===== LOGOUT ===== --}}
        <hr class="sidebar-divider">
        <div class="sidebar-logout-area">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Log Out</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- OVERLAY BACKDROP (mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- MAIN CONTENT -->
    <main class="main-container">
        @yield('content')
    </main>

    <!-- CART SECTION (Fixed) -->
    @if(request()->routeIs('dashboard'))
    <div class="cart-container" id="cartContainer">
        {{-- Tombol tutup cart (mobile) --}}
        <button class="cart-close-btn" id="cartCloseBtn" onclick="toggleCart()">
            <i class="fas fa-times"></i>
        </button>
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

    {{-- FAB Keranjang (mobile only) --}}
    <button class="cart-fab" id="cartFab" onclick="toggleCart()" aria-label="Buka Keranjang">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-fab-badge" id="cartFabBadge" style="display:none;">0</span>
    </button>

    {{-- Overlay backdrop untuk cart drawer (mobile) --}}
    <div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>
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
                    if (response.success) {
                        updateCart(response.cart);
                        showNotification('Item ditambahkan ke keranjang');
                    } else {
                        // success HTTP tapi backend bilang gagal
                        showNotification(response.message || 'Gagal menambahkan item', 'error');
                    }
                },
                error: function(xhr) {
                    // HTTP 400/500 — misalnya stok habis
                    let msg = 'Terjadi kesalahan, silakan coba lagi.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    showNotification(msg, 'error');
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

                // Definisikan path gambar di sini
                let imageSrc = item.image ? '/storage/' + item.image : 'https://via.placeholder.com/50?text=No+Image';

                html += `
                    <div class="cart-item">
                        <div class="cart-item-image">
                            <!-- Panggil variabel imageSrc di dalam tag img -->
                            <img src="${imageSrc}" alt="${item.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
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
        function showNotification(message, type = 'success') {

            const div = document.createElement('div');

            let bgColor = '#1814F3';

            if(type === 'error'){
                bgColor = '#EF4444';
            }

            div.classList.add('custom-toast');

            div.style.cssText = `
                position: fixed;
                background: ${bgColor};
                color: white;
                padding: 14px 22px;
                border-radius: 10px;
                z-index: 99999;
                box-shadow: 0 8px 20px rgba(0,0,0,.2);
                font-weight: 500;
                min-width: 320px;
                max-width: 420px;
                animation: slideIn .3s ease;
            `;

            div.innerHTML = message;

            document.querySelector('body').appendChild(div);

            setTimeout(() => {
                div.remove();
            }, 3000);
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

        // =========================================
        // MOBILE: Toggle Sidebar (Hamburger Menu)
        // =========================================
        function toggleSidebar() {
            const sidebar  = document.getElementById('sidebar') || document.querySelector('.sidebar');
            const overlay  = document.getElementById('sidebarOverlay');
            const hamburger = document.getElementById('hamburgerBtn');

            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
            hamburger.classList.toggle('open');

            // Cegah scroll body saat sidebar terbuka
            document.body.classList.toggle('sidebar-open');
        }

        // =========================================
        // MOBILE: Toggle Cart Drawer
        // =========================================
        function toggleCart() {
            const cart    = document.getElementById('cartContainer');
            const overlay = document.getElementById('cartOverlay');
            if (!cart) return;
            cart.classList.toggle('cart-open');
            if (overlay) overlay.classList.toggle('active');
            document.body.classList.toggle('cart-drawer-open');
        }

        // Update badge FAB saat cart berubah
        const _origUpdateCart = updateCart;
        updateCart = function(cart) { 
            _origUpdateCart(cart);
            
            // Sesuaikan getElementById di bawah dengan ID kodingan lo (misal: 'cartFab' atau 'cartFabBadge')
            const badge = document.getElementById('cartFab'); 
            
            if (!badge) return;
            const count = Object.keys(cart).length;
            
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        };

        // Tutup sidebar saat klik link navigasi (mobile UX)
        document.querySelectorAll('.sidebar-item, .sidebar-logout-btn').forEach(function(link) {
            link.addEventListener('click', function() {
                const sidebar = document.querySelector('.sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                if (sidebar && sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                    if (overlay) overlay.classList.remove('active');
                    document.body.classList.remove('sidebar-open');
                }
            });
        });
    </script>

@if (session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        showNotification(@json(session('error')), 'error');
    });
    if (!document.getElementById('toast-animation')) {
    const style = document.createElement('style');
    style.id = 'toast-animation';
    style.innerHTML = `
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    `;
    document.head.appendChild(style);
}
</script>
@endif

    @yield('extra_js')
</body>
</html>