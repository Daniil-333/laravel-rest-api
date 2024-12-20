<?php

namespace Database\Seeders;

use App\Models\EquipmentType;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        EquipmentType::factory()->create([
            'title' => 'TP-Link TL-WR74',
            'mask' => 'XXAAAAAXAA',
        ]);

        EquipmentType::factory()->create([
            'title' => 'D-Link DIR-300',
            'mask' => 'NXXAAXZXaa',
        ]);

        EquipmentType::factory()->create([
            'title' => 'D-Link DIR-300 E',
            'mask' => 'NAAAAXZXXX',
        ]);

        EquipmentType::factory()->create([
            'title' => 'D-Link DIR-300 G',
            'mask' => 'NXAAAXZXXX',
        ]);
    }
}
