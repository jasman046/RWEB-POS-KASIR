@extends('layouts.app')

@section('title', 'Checkout - ASgor')

@section('extra_css')
<style>
    /* Sembunyikan keranjang bawaan dari layout utama di halaman checkout */
    .cart-container {
        display: none !important;
    }
    /* Cart FAB juga disembunyikan di halaman checkout */
    .cart-fab {
        display: none !important;
    }

    /* Checkout container: responsive di semua ukuran layar */
    .checkout-container {
        margin-right: 0 !important;
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        gap: 30px;
    }

    /* Tombol Lanjut ke Pembayaran (mobile only) */
    .btn-scroll-to-pay {
        display: none;
        width: 100%;
        padding: 14px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 16px;
        text-align: center;
    }

    @media (max-width: 768px) {
        /* Checkout jadi 1 kolom di mobile */
        .checkout-container {
            grid-template-columns: 1fr !important;
            gap: 20px;
            padding: 0;
        }

        /* Tombol scroll to payment tampil di mobile */
        .btn-scroll-to-pay {
            display: block;
        }

        /* Action buttons full width di mobile */
        .action-buttons {
            flex-direction: column;
            gap: 10px;
        }

        .btn-cancel,
        .btn-confirm {
            width: 100%;
            text-align: center;
        }

        /* Payment methods jadi 3 kolom pas di mobile */
        .payment-methods {
            gap: 8px;
        }

        .payment-method {
            padding: 10px 8px;
            font-size: 12px;
        }
    }
</style>
@endsection

@section('content')
<div class="checkout-container">
    <div class="checkout-order-section">
        <h2 class="form-section-title">Orders #002</h2>
        
        <div style="display: flex; gap: 10px; margin-bottom: 20px;">
            <button class="tab-btn active" id="dineInBtn" onclick="selectOrderType('Dine In')">Dine In</button>
            <button class="tab-btn" id="deliveryBtn" onclick="selectOrderType('Delivery')">Delivery</button>
        </div>

        <div id="orderItems" style="margin-bottom: 20px;">
            @foreach($cart as $item)
                <div class="cart-item" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #393C49;">
                    <div style="display: flex; gap: 10px; flex: 1;">
                        <div class="cart-item-image">
                            <img src="{{ isset($item['image']) ? asset('storage/' . $item['image']) : 'https://via.placeholder.com/49x50' }}" alt="{{ $item['name'] }}">
                        </div>
                        <div>
                            <div class="product-name">{{ $item['name'] }}</div>
                        </div>
                    </div>
                    <div style="display: flex; gap: 20px; align-items: center;">
                        <div style="display: flex; align-items: center; justify-content: center; width: 90px; gap: 5px;">
                            <button onclick="updateQty({{ $item['product_id'] }}, -1)" style="width: 20px; height: 20px; border: none; background: white; color: #1814F3; cursor: pointer; font-weight: bold;">-</button>
                            <span style="display: inline-block; margin: 0 5px; width: 20px;">{{ $item['quantity'] }}</span>
                            <button onclick="updateQty({{ $item['product_id'] }}, 1)" style="width: 20px; height: 20px; border: none; background: white; color: #1814F3; cursor: pointer; font-weight: bold;">+</button>
                        </div>
                        <div style="width: 80px; text-align: right;">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="border-top: 1px solid #393C49; border-bottom: 1px solid #393C49; padding: 15px 0; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between;">
                <span>Sub total</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Tombol Lanjut ke Pembayaran (muncul di mobile untuk skip scroll) --}}
        <button class="btn-scroll-to-pay" onclick="document.getElementById('paymentSection').scrollIntoView({behavior:'smooth'})">
            <i class="fas fa-arrow-down"></i> Lanjut ke Pembayaran
        </button>

    </div>

    <div class="checkout-payment-section" id="paymentSection">
        <h2 class="form-section-title">Payment</h2>
        <p class="form-section-subtitle">Methods</p>

        <div class="payment-methods">
            <div class="payment-method active" onclick="selectPayment('Credit Card', this)">
                <div style="width: 24px; height: 24px; margin: 0 auto 5px; background-color: #0075FF; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-credit-card" style="color: white; font-size: 12px;"></i>
                </div>
                <div class="payment-method-label">Credit Card</div>
            </div>
            <div class="payment-method" onclick="selectPayment('QRIS', this)">
                <div style="width: 24px; height: 24px; margin: 0 auto 5px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-qrcode" style="color: #ABBBC2; font-size: 16px;"></i>
                </div>
                <div class="payment-method-label">QRIS</div>
            </div>
            <div class="payment-method" onclick="selectPayment('Cash', this)">
                <div style="width: 24px; height: 24px; margin: 0 auto 5px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-money-bill" style="color: #ABBBC2; font-size: 16px;"></i>
                </div>
                <div class="payment-method-label">Cash</div>
            </div>
        </div>

        <form id="paymentForm" onsubmit="submitPayment(event)">
            <div id="creditCardSection">
                <div class="form-group">
                    <label class="form-label">Cardholder Name</label>
                    <input type="text" class="form-input" id="cardholderName" name="cardholder_name" placeholder="Einhar">
                </div>

                <div class="form-group">
                    <label class="form-label">Card Number</label>
                    <input type="text" class="form-input" id="cardNumber" name="card_number" placeholder="1704 2005 2300 1845">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Expiration Date</label>
                        <input type="text" class="form-input" id="expirationDate" name="expiration_date" placeholder="02/2022">
                    </div>
                    <div class="form-group">
                        <label class="form-label">CVV</label>
                        <input type="password" class="form-input" id="cvv" name="cvv" placeholder="•••">
                    </div>
                </div>
            </div>

            <div id="qrisSection" style="display: none; text-align: center; padding: 20px 0;">
                <div style="background-color: #F5F7FA; padding: 20px; border-radius: 12px; display: inline-block; border: 1px solid var(--light-border);">
                    
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=Pembayaran+ASgor" alt="QR Code QRIS" style="width: 150px; height: 150px; border-radius: 8px; margin-bottom: 15px; border: 4px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">

                    <p style="font-size: 14px; color: var(--text-dark); font-weight: 600; margin-bottom: 5px;">ASgor - Nasi Goreng</p>
                    <p style="font-size: 12px; color: var(--text-gray);">Scan menggunakan m-Banking / e-Wallet</p>
                </div>
            </div>

            <div id="cashSection" style="display: none;">
                <div class="form-group">
                    <label class="form-label">Nominal Uang Diterima</label>
                    <input type="number" class="form-input" id="cashAmount" name="cash_amount" placeholder="Contoh: 50000" oninput="calculateChange()">
                    
                    <div style="margin-top: 15px; padding: 15px; background-color: #F5F7FA; border-radius: 8px; border: 1px solid var(--light-border);">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="color: var(--text-gray); font-size: 14px;">Total Tagihan:</span>
                            <span style="font-weight: 600; color: var(--text-dark);">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-gray); font-size: 14px;">Kembalian:</span>
                            <span id="changeAmount" style="font-weight: bold; color: var(--text-dark); font-size: 18px;">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>

            <div style="border-top: 1px solid #393C49; padding-top: 20px; margin-top: 20px;">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Order Type</label>
                        <select class="form-input" id="orderType" name="order_type" style="background-color: #0075FF; color: white; cursor: pointer;" onchange="handleOrderType(this.value)">
                            <option value="Dine In">Dine In</option>
                            <option value="Delivery">Delivery</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Nama </label>
                        <input type="text" id="customer_name" name="customer_name" class="form-input" placeholder="Masukkan nama..." >
                    </div>
                </div>
            </div>


            <div class="action-buttons">
                <button type="button" class="btn-cancel" onclick="goBack()">Cancel</button>
                <button type="submit" class="btn-confirm">Confirm Payment</button>
            </div>
        </form>
    </div>
</div>

<script>
    let selectedPaymentMethod = 'Credit Card';
    let selectedOrderType = 'Dine In';

    function selectPayment(method, element) {
        selectedPaymentMethod = method;
        
        // Hapus warna biru dari semua tombol metode pembayaran
        document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('active'));
        // Kasih warna biru ke tombol yang lagi diklik
        element.classList.add('active');

        // Ambil ID masing-masing form
        const creditCardSection = document.getElementById('creditCardSection');
        const qrisSection = document.getElementById('qrisSection');
        const cashSection = document.getElementById('cashSection');

        // Sembunyikan semuanya dulu
        creditCardSection.style.display = 'none';
        qrisSection.style.display = 'none';
        cashSection.style.display = 'none';

        // Tampilkan yang sesuai dengan pilihan
        if (method === 'Credit Card') {
            creditCardSection.style.display = 'block';
        } else if (method === 'QRIS') {
            qrisSection.style.display = 'block';
        } else if (method === 'Cash') {
            cashSection.style.display = 'block';
        }
    }

    // Pastikan ini ada di dalam tag <script> di paling bawah
    const orderTotal = {{ $total }};

    function calculateChange() {
        // Ambil nilai input dan paksa ubah jadi angka bulat (integer)
        const cashInput = parseInt(document.getElementById('cashAmount').value) || 0;
        const changeElement = document.getElementById('changeAmount');
        
        // Kalau uangnya kurang dari total tagihan
        if (cashInput < orderTotal) {
            changeElement.innerText = 'Uang Kurang!';
            changeElement.style.color = '#FF4B4A'; // Merah
        } else {
            // Kalau uangnya pas atau lebih, hitung kembalian
            const change = cashInput - orderTotal;
            changeElement.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(change);
            changeElement.style.color = '#00C853'; // Hijau
        }
    }

    function selectOrderType(type) {
        selectedOrderType = type;
        document.getElementById('orderType').value = type;

        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        if (type === 'Dine In') {
            document.getElementById('dineInBtn').classList.add('active');
        } else {
            document.getElementById('deliveryBtn').classList.add('active');
        }

        handleOrderType(type);
    }

    function handleOrderType(type) {
        // Fix: ID di HTML adalah 'customer_name' bukan 'customerName'
        const customerInput = document.getElementById('customer_name');
        if (customerInput) {
            customerInput.parentElement.style.display = 'block';
            customerInput.required = true;
        }
    }

    function continueToPay() {
        document.querySelector('.checkout-payment-section').scrollIntoView({ behavior: 'smooth' });
    }

    function goBack() {
        window.history.back();
    }

    function updateQty(productId, change) {
        $.ajax({
            url: '/api/update-cart',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            data: {
                product_id: productId,
                quantity: change
            },
            success: function(response) {
                location.reload();
            }
        });
    }

    function submitPayment(e) {
        e.preventDefault();

        // ==========================================
        // 1. VALIDASI FRONT-END (SEBELUM DIKIRIM)
        // ==========================================
        
        // Ambil nilai Nama Customer
        const customerName = document.getElementById('customer_name').value.trim();
        
        // Cek Nama (Wajib diisi untuk semua metode)
        if (customerName === '') {
            alert('Mohon isi kolom Nama pemesan terlebih dahulu!');
            document.getElementById('customer_name').focus();
            return; // Hentikan eksekusi
        }

        // Cek Khusus Metode Credit Card
        if (selectedPaymentMethod === 'Credit Card') {
            const cardName = document.getElementById('cardholderName').value.trim();
            const cardNum = document.getElementById('cardNumber').value.trim();
            
            if (cardName === '' || cardNum === '') {
                alert('Mohon lengkapi data Credit Card (Nama dan Nomor Kartu)!');
                return;
            }
        }

        // Cek Khusus Metode Cash
        if (selectedPaymentMethod === 'Cash') {
            const cashInput = parseInt(document.getElementById('cashAmount').value) || 0;
            
            if (cashInput === 0) {
                alert('Mohon masukkan Nominal Uang yang diterima dari pelanggan!');
                document.getElementById('cashAmount').focus();
                return;
            }
            if (cashInput < orderTotal) {
                alert('Pembayaran gagal: Uang yang dibayarkan kurang dari total tagihan (Rp ' + new Intl.NumberFormat('id-ID').format(orderTotal) + ')');
                document.getElementById('cashAmount').focus();
                return;
            }
        }

        // ==========================================
        // 2. PROSES PENGIRIMAN DATA KE BACKEND
        // ==========================================
        
        const formData = new FormData(document.getElementById('paymentForm'));
        formData.append('payment_method', selectedPaymentMethod);
        formData.append('order_type', selectedOrderType);

        // Ubah teks tombol jadi "Processing..." biar keliatan lagi loading
        const btnSubmit = document.querySelector('.btn-confirm');
        const originalText = btnSubmit.innerHTML;
        btnSubmit.innerHTML = 'Processing...';
        btnSubmit.disabled = true;

        $.ajax({
            url: '/api/process-payment',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            data: Object.fromEntries(formData),
            success: function(response) {
                if (response.success) {
                    window.location.href = '/receipt/' + response.order_id;
                } else {
                    // Berjaga-jaga jika backend me-return JSON success:false dengan pesan error
                    alert('Gagal: ' + (response.message || 'Terjadi kesalahan pada sistem pembayaran.'));
                    btnSubmit.innerHTML = originalText;
                    btnSubmit.disabled = false;
                }
            },
            error: function(xhr) {
                // Tangkap error dari backend (misal error validasi Laravel 422 atau 500)
                let errorMessage = 'Terjadi kesalahan sistem, silakan coba lagi.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                alert('Error: ' + errorMessage);
                
                // Kembalikan tombol ke keadaan semula
                btnSubmit.innerHTML = originalText;
                btnSubmit.disabled = false;
            }
        });
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        handleOrderType('Dine In');
    });
</script>
@endsection