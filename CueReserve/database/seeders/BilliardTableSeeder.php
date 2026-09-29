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
            ['table_number' => '01', 'type' => 'Standard Pool', 'price_per_hour' => 35000, 'is_active' => true],
            ['table_number' => '02', 'type' => 'Standard Pool', 'price_per_hour' => 35000, 'is_active' => true],
            ['table_number' => '03', 'type' => 'Standard Pool', 'price_per_hour' => 35000, 'is_active' => true],
            ['table_number' => '04', 'type' => 'VIP Pool', 'price_per_hour' => 75000, 'is_active' => true],
            ['table_number' => '05', 'type' => 'VIP Pool', 'price_per_hour' => 75000, 'is_active' => true],
        ];

        foreach ($tables as $table) {
            BilliardTable::create($table);
        }
    }
}
