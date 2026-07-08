@extends('layouts.app')

@section('title', 'Receipt - ASgor')

@section('extra_css')
<style>
    /* Kunci layar biar background gak bisa di-scroll pas struk muncul */
    body { 
        overflow: hidden; 
    }
    
    /* Pelapis abu-abu yang menutupi full satu layar */
    .receipt-modal-overlay {
        position: fixed;
        top: 100px;
        left: 250px;
        width: calc(100vw - 250px);
        height: calc(100vh - 100px);
        background-color: rgba(217, 217, 217, 0.85);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10; /* Paksa posisi paling atas, ngalahin header */
    }

    @media (max-width: 768px) {
        .receipt-modal-overlay {
            left: 0;
            width: 100vw;
        }
    }

    /* Kotak putih struknya */
    .receipt-card {
        background-color: #FFFFFF;
        border-radius: 28px;
        padding: 40px;
        width: 90%;
        max-width: 450px;
        box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.2);
        max-height: 90vh;
        overflow-y: auto;
        max-height: calc(100vh - 180px);
    }

    /* Typography khusus struk */
    .receipt-header-text {
        text-align: center;
        font-family: 'Pacifico', cursive;
        font-size: 35px;
        color: var(--primary-dark);
        margin-bottom: 5px;
    }

    .receipt-sub {
        text-align: center;
        font-size: 18px;
        color: var(--primary-dark);
        margin-bottom: 20px;
    }

    .receipt-title {
        text-align: center;
        font-family: 'Baskervville SC', 'Times New Roman', serif;
        font-size: 32px;
        font-weight: bold;
        color: var(--primary-dark);
        margin: 20px 0;
        letter-spacing: 2px;
    }

    .receipt-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        font-size: 15px;
        color: var(--text-dark);
    }

    .receipt-row.receipt-head {
        font-weight: bold;
        border-bottom: 2px solid var(--primary-dark);
        margin-bottom: 10px;
        padding-bottom: 15px;
    }

    .receipt-row.total {
        font-weight: bold;
        border-top: 2px solid var(--primary-dark);
        border-bottom: 2px solid var(--primary-dark);
        margin-top: 15px;
        padding: 15px 0;
        font-size: 16px;
    }
    
    .barcode-container {
        text-align: center;
        margin-top: 30px;
    }
</style>
@endsection

@section('content')
<div class="receipt-modal-overlay">
    <div class="receipt-card">
        <div class="receipt-header-text">ASgor</div>
        <div class="receipt-sub">Nasi Goreng</div>
        
        <div class="receipt-title">RECEIPT</div>
        
        <div class="receipt-row receipt-head">
            <span>Description</span>
            <span>Price</span>
        </div>
        
        @foreach($order->orderItems as $item)
        <div class="receipt-row">
            <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
            
            <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
        </div>
        @endforeach
        
        <div class="receipt-row total">
            <span>Total</span>
            <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
        
        <div class="barcode-container">
            <svg width="200" height="50">
                <rect x="0" y="0" width="4" height="50" fill="black"></rect>
                <rect x="8" y="0" width="8" height="50" fill="black"></rect>
                <rect x="20" y="0" width="4" height="50" fill="black"></rect>
                <rect x="28" y="0" width="12" height="50" fill="black"></rect>
                <rect x="44" y="0" width="4" height="50" fill="black"></rect>
                <rect x="52" y="0" width="8" height="50" fill="black"></rect>
                <rect x="64" y="0" width="12" height="50" fill="black"></rect>
                <rect x="80" y="0" width="4" height="50" fill="black"></rect>
                <rect x="88" y="0" width="8" height="50" fill="black"></rect>
                <rect x="100" y="0" width="16" height="50" fill="black"></rect>
                <rect x="120" y="0" width="4" height="50" fill="black"></rect>
                <rect x="128" y="0" width="8" height="50" fill="black"></rect>
                <rect x="140" y="0" width="12" height="50" fill="black"></rect>
                <rect x="156" y="0" width="4" height="50" fill="black"></rect>
                <rect x="164" y="0" width="8" height="50" fill="black"></rect>
            </svg>
            <div style="margin-top: 10px; font-size: 13px; font-weight: 500; color: var(--text-dark);">
                {{ $order->order_number }}
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <button onclick="window.location.href='/'" style="padding: 14px 40px; background-color: var(--primary); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; width: 100%; transition: 0.3s;">
                Back to Dashboard
            </button>
        </div>
    </div>
</div>
@endsection