<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Filter tanggal (opsional)
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', now()->format('Y-m-d'));

        // ================================
        // Ringkasan (Summary Cards)
        // ================================

        // Total revenue dalam rentang tanggal
        $totalRevenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->sum('total_price');

        // Jumlah order
        $totalOrders = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();

        // Rata-rata nilai order
        $avgOrder = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // ================================
        // Produk Terlaris (Top 5)
        // ================================

        $topProducts = OrderItem::select('product_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(quantity * price) as total_revenue')
            )
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->where('status', 'completed')
                  ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // ================================
        // Data Chart: Pendapatan per Hari (30 hari terakhir)
        // ================================

        $chartData = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw("DATE(created_at) as date"), // ✅ UBAH JADI GINI AJA BRO
                DB::raw('SUM(total_price) as total'),
                DB::raw('COUNT(id) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Buat array 30 hari lengkap (isi 0 jika tidak ada transaksi)
        $chartDays   = [];
        $chartTotals = [];

        for ($i = 29; $i >= 0; $i--) {
            $date          = now()->subDays($i)->format('Y-m-d');
            $chartDays[]   = now()->subDays($i)->format('d M');
            $chartTotals[] = isset($chartData[$date]) ? (float) $chartData[$date]->total : 0;
        }

        // ================================
        // Riwayat Order Terbaru
        // ================================

        $orders = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->with('orderItems.product')
            ->latest()
            ->paginate(15);

        return view('admin.reports.index', compact(
            'totalRevenue',
            'totalOrders',
            'avgOrder',
            'topProducts',
            'chartDays',
            'chartTotals',
            'orders',
            'startDate',
            'endDate'
        ));
    }
}
