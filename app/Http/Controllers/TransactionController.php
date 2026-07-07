<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Card aktif
        $cards = Card::where('is_active', true)->get();

        // Filter kategori
        $category = $request->get('category');

        $transactions = Transaction::with('card')
            ->when($category, function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->latest('transaction_date')
            ->get();

        // Ringkasan
        $totalIncome = Transaction::where('category', 'Income')->sum('amount');

        $totalExpense = Transaction::where('category', 'Expense')->sum('amount');

        // ============================
        // Chart Expense 6 Bulan Terakhir
        // ============================

        $chartData = [];

        for ($i = 5; $i >= 0; $i--) {

            $date = now()->subMonths($i);

            $month = $date->month;
            $year = $date->year;

            $total = Transaction::where('category', 'Expense')
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $month)
                ->sum('amount');

            $chartData[] = [
                'label' => $date->format('M'),
                'total' => $total,
            ];

        }

        return view('transaction.index', compact(
            'cards',
            'transactions',
            'category',
            'totalIncome',
            'totalExpense',
            'chartData'
        ));
    }

    public function downloadReceipt(Transaction $transaction)
    {
        if (!$transaction->receipt_path) {

            abort(404, 'Receipt tidak ditemukan.');

        }

        $path = 'receipts/' . $transaction->receipt_path;

        if (!Storage::disk('public')->exists($path)) {

            abort(404, 'File receipt tidak ditemukan.');

        }

       return response()->download(storage_path('app/public/' . $path));
    }
}