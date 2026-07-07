<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Card::create([
            'user_id' => 1,
            'card_holder' => 'Einhar',
            'card_number' => '3778 **** **** 1234',
            'balance' => 100000000.00,
            'expired_date' => '12/22',
            'theme' => 'gradient',
            'is_active' => true,
        ]);

        Card::create([
            'user_id' => 1,
            'card_holder' => 'Einhar',
            'card_number' => '4889 **** **** 7865',
            'balance' => 100000000.00,
            'expired_date' => '12/22',
            'theme' => 'light',
            'is_active' => true,
        ]);
    }
}