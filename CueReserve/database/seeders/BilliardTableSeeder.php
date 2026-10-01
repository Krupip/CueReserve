<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BilliardTable;

class BilliardTableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            ['table_number' => 'Meja 01', 'type' => 'Pool', 'price_per_hour' => 50000, 'is_active' => 1],
            ['table_number' => 'Meja 02', 'type' => 'Pool', 'price_per_hour' => 50000, 'is_active' => 1],
            ['table_number' => 'Meja 03', 'type' => 'Pool', 'price_per_hour' => 50000, 'is_active' => 1],
            ['table_number' => 'Meja VIP', 'type' => 'Snooker', 'price_per_hour' => 75000, 'is_active' => 1],
            ['table_number' => 'Meja VIP', 'type' => 'Pool', 'price_per_hour' => 50000, 'is_active' => 0], // Contoh maintenance
        ];

        foreach ($tables as $table) {
            BilliardTable::create($table);
        }
    }
}
