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

        ]);
    }
}