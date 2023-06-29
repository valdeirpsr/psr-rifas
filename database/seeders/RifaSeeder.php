<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Rifa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RifaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rifa::factory(5)
            ->hasOrders(100)
            ->create();
    }
}
