@extends('layouts.app')

@section('title', 'Laporan Penjualan - Admin')
@section('page-title', 'LAPORAN')

@section('content')
<div class="admin-page-container">

    {{-- ======================== --}}
    {{-- Filter Tanggal --}}
    {{-- ======================== --}}
    <form method="GET" action="{{ route('admin.reports') }}" class="report-filter-form">
        <div class="report-filter-group">
            <label class="admin-form-label">Dari Tanggal</label>
            <input type="date" name="start_date" class="setting-input report-date-input" value="{{ $startDate }}">
        </div>
        <div class="report-filter-group">
            <label class="admin-form-label">Sampai Tanggal</label>
            <input type="date" name="end_date" class="setting-input report-date-input" value="{{ $endDate }}">
        </div>
        <button type="submit" class="admin-btn-primary report-filter-btn">
            <i class="fas fa-filter"></i> Filter
        </button>
        <a href="{{ route('admin.reports') }}" class="admin-btn-secondary report-filter-btn">
            <i class="fas fa-undo"></i> Reset
        </a>
    </form>

    {{-- ======================== --}}
    {{-- Summary Cards --}}
    {{-- ======================== --}}
    <div class="report-summary-grid">
        <div class="report-summary-card report-card-revenue">
            <div class="report-card-icon">
                <i class="fas fa-coins"></i>
            </div>
            <div class="report-card-info">
                <p class="report-card-label">Total Revenue</p>
                <h3 class="report-card-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="report-summary-card report-card-orders">
            <div class="report-card-icon">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="report-card-info">
                <p class="report-card-label">Total Transaksi</p>
                <h3 class="report-card-value">{{ number_format($totalOrders) }}</h3>
            </div>
        </div>
        <div class="report-summary-card report-card-avg">
            <div class="report-card-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="report-card-info">
                <p class="report-card-label">Rata-rata per Order</p>
                <h3 class="report-card-value">Rp {{ number_format($avgOrder, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    {{-- ======================== --}}
    {{-- Layout: Chart + Top Produk --}}
    {{-- ======================== --}}
    <div class="report-main-grid">

        {{-- Chart Pendapatan 30 Hari --}}
        <div class="admin-table-card report-chart-card">
            <div class="report-chart-header">
                <h3 class="admin-table-title">Pendapatan 30 Hari Terakhir</h3>
            </div>
            <div class="report-chart-wrapper">
                <div class="report-bar-chart" id="barChart">
                    @php
                        $maxVal = max(array_merge($chartTotals, [1]));
                    @endphp
                    @foreach($chartTotals as $i => $val)
                    <div class="report-bar-col" title="{{ $chartDays[$i] }}: Rp {{ number_format($val, 0, ',', '.') }}">
                        <div class="report-bar-fill" style="height: {{ $maxVal > 0 ? round(($val / $maxVal) * 100) : 0 }}%">
                        </div>
                        @if($i % 5 === 0 || $i === count($chartTotals) - 1)
                        <span class="report-bar-label">{{ $chartDays[$i] }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Top 5 Produk Terlaris --}}
        <div class="admin-table-card report-top-card">
            <div class="report-chart-header">
                <h3 class="admin-table-title">🏆 Produk Terlaris</h3>
            </div>
            <div class="report-top-list">
                @forelse($topProducts as $rank => $item)
                <div class="report-top-item">
                    <div class="report-top-rank {{ $rank === 0 ? 'rank-gold' : ($rank === 1 ? 'rank-silver' : ($rank === 2 ? 'rank-bronze' : '')) }}">
                        {{ $rank + 1 }}
                    </div>
                    <div class="report-top-thumb">
                        @if($item->product && $item->product->image)
                            <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}">
                        @else
                            <div class="admin-product-no-image"><i class="fas fa-box"></i></div>
                        @endif
                    </div>
                    <div class="report-top-info">
                        <p class="report-top-name">{{ $item->product ? $item->product->name : 'Produk Dihapus' }}</p>
                        <p class="report-top-sub">{{ $item->total_qty }} terjual</p>
                    </div>
                    <div class="report-top-revenue">
                        Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                    </div>
                </div>
                @empty
                <div class="admin-empty-row">
                    <i class="fas fa-chart-bar"></i>
                    <p>Belum ada data penjualan di periode ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ======================== --}}
    {{-- Tabel Riwayat Order --}}
    {{-- ======================== --}}
    <div class="admin-table-card" style="margin-top: 25px;">
        <div class="report-chart-header">
            <h3 class="admin-table-title">Riwayat Transaksi</h3>
            <p class="admin-table-subtitle">{{ $orders->total() }} transaksi ditemukan</p>
        </div>

        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>No. Order</th>
                        <th>Customer</th>
                        <th>Tipe</th>
                        <th>Pembayaran</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <span class="report-order-code">{{ $order->order_number }}</span>
                        </td>
                        <td>{{ $order->customer_name ?? '-' }}</td>
                        <td>
                            <span class="admin-badge {{ $order->type === 'Dine In' ? 'admin-badge-food' : 'admin-badge-drink' }}">
                                {{ $order->type }}
                            </span>
                        </td>
                        <td>{{ $order->payment_method }}</td>
                        <td>{{ $order->orderItems->count() }} item</td>
                        <td class="admin-price-col report-amount">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="admin-empty-row">
                            <i class="fas fa-receipt"></i>
                            <p>Tidak ada transaksi di periode ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="admin-pagination-wrapper">
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
