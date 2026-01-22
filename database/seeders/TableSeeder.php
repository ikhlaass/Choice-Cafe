<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 tables
        for ($i = 1; $i <= 20; $i++) {
            Table::create([
                'table_number' => str_pad($i, 2, '0', STR_PAD_LEFT),
                'is_occupied' => false,
            ]);
        }
    }
}
