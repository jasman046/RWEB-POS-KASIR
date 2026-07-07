<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Einhar',
            'email' => 'einhar@example.com',
        ]);

        $this->call([
            CardSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}