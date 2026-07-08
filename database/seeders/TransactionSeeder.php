<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transaction::insert([

            [
                'card_id' => 1,
                'transaction_code' => '#12548796',
                'description' => 'Restock',
                'type' => 'Shopping',
                'amount' => 250000,
                'transaction_date' => now()->subDays(1),
                'category' => 'Expense',
                'receipt_path' => 'receipt.pdf',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'card_id' => 1,
                'transaction_code' => '#12548797',
                'description' => 'Selling',
                'type' => 'Transfer',
                'amount' => 35000,
                'transaction_date' => now()->subDays(2),
                'category' => 'Income',
                'receipt_path' => 'receipt.pdf',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'card_id' => 2,
                'transaction_code' => '#12548798',
                'description' => 'Selling',
                'type' => 'Service',
                'amount' => 15000,
                'transaction_date' => now()->subDays(3),
                'category' => 'Income',
                'receipt_path' => 'receipt.pdf',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'card_id' => 2,
                'transaction_code' => '#12548799',
                'description' => 'Salary Wilson',
                'type' => 'Transfer',
                'amount' => 150000,
                'transaction_date' => now()->subDays(4),
                'category' => 'Expense',
                'receipt_path' => 'receipt.pdf',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'card_id' => 1,
                'transaction_code' => '#12548800',
                'description' => 'Salary Emily',
                'type' => 'Transfer',
                'amount' => 150000,
                'transaction_date' => now()->subDays(5),
                'category' => 'Expense',
                'receipt_path' => 'receipt.pdf',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'card_id' => 1,
                'transaction_code' => '#12548801',
                'description' => 'Customer Payment',
                'type' => 'Transfer',
                'amount' => 420000,
                'transaction_date' => now()->subMonths(2)->addDays(5),
                'category' => 'Income',
                'receipt_path' => 'receipt.pdf',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'card_id' => 2,
                'transaction_code' => '#12548802',
                'description' => 'Electricity Bill',
                'type' => 'Service',
                'amount' => 180000,
                'transaction_date' => now()->subMonths(2)->addDays(12),
                'category' => 'Expense',
                'receipt_path' => 'receipt.pdf',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'card_id' => 1,
                'transaction_code' => '#12548803',
                'description' => 'Online Order',
                'type' => 'Shopping',
                'amount' => 510000,
                'transaction_date' => now()->subMonth()->addDays(8),
                'category' => 'Income',
                'receipt_path' => 'receipt.pdf',
                'status' => 'Pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'card_id' => 2,
                'transaction_code' => '#12548804',
                'description' => 'Supplier Payment',
                'type' => 'Transfer',
                'amount' => 275000,
                'transaction_date' => now()->subMonths(3)->addDays(10),
                'category' => 'Expense',
                'receipt_path' => 'receipt.pdf',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'card_id' => 1,
                'transaction_code' => '#12548805',
                'description' => 'Cash Deposit',
                'type' => 'Transfer',
                'amount' => 650000,
                'transaction_date' => now()->subMonths(4)->addDays(7),
                'category' => 'Income',
                'receipt_path' => 'receipt.pdf',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}