<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $cards = Card::where('is_active', true)->get();

        $transactions = Transaction::latest()->get();

        return view('transaction.index', compact(
            'cards',
            'transactions'
        ));
    }
}