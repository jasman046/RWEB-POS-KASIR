@extends('layouts.app')

@section('title', 'Notification')
@section('page-title', 'PRODUCT')

@section('content')
<div class="notification-container">
    <table class="notif-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pesanan</th>
                <th>Waktu Tunggu</th>
                <th>Nama Customer</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            {{-- Mulai looping data dari database --}}
            @forelse($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                
                <td>
                    {{-- Karena relasi ke tabel produk belum kita intip, nampilin ID Produk dulu ya --}}
                    @foreach($order->orderItems as $item)
                        {{ $item->product->name ?? 'Produk' }} (Qty: {{ $item->quantity }}) <br>
                    @endforeach
                </td>
                
                {{-- diffForHumans bakal nampilin format "5 minutes ago" otomatis --}}
                <td>{{ $order->created_at->diffForHumans() }}</td>
                
                {{-- Nampilin nama dari kolom customer_name yang tadi kita buat --}}
                <td>{{ $order->customer_name ?? 'Tanpa Nama' }}</td>
                
                <td>
                    <div class="status-action">
                        {{-- Warna tulisan otomatis berubah kalau statusnya completed --}}
                        <button class="btn-pending" style="{{ $order->status == 'completed' ? 'color: #00A651;' : '' }}">
                            {{ ucfirst($order->status) }}
                        </button>
                        <button class="btn-check"><i class="fas fa-check"></i></button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 30px; font-weight: bold; color: #B1B1B1;">
                    Belum ada pesanan masuk nih, Bro!
                </td>
            </tr>
            @endforelse
            {{-- Selesai looping --}}
        </tbody>
    </table>
</div>
@endsection