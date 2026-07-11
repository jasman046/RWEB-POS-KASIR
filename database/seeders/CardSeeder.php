<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\User;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        if (! $admin) {
            $this->command->error('Admin tidak ditemukan. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        Card::updateOrCreate(
            [
                'card_number' => '3778 **** **** 1234',
            ],
            [
                'user_id' => $admin->id,
                'card_holder' => $admin->name,
                'balance' => 100000000.00,
                'expired_date' => '12/22',
                'theme' => 'gradient',
                'is_active' => true,
            ]
        );

        Card::updateOrCreate(
            [
                'card_number' => '4889 **** **** 7865',
            ],
            [
                'user_id' => $admin->id,
                'card_holder' => $admin->name,
                'balance' => 100000000.00,
                'expired_date' => '12/22',
                'theme' => 'light',
                'is_active' => true,
            ]
        );
    }
}