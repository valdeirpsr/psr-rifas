<?php

namespace Database\Seeders;

use App\Models\Rifa;
use Illuminate\Database\Seeder;

class RifaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rifa::factory(20)->generateImage()->create();
    }
}
