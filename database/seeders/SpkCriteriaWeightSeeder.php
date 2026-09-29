<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpkCriteriaWeightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $weights = [
            ['code' => 'C1', 'name' => 'IPK', 'type' => 'benefit', 'weight' => 0.35],
            ['code' => 'C2', 'name' => 'Tren IPS', 'type' => 'benefit', 'weight' => 0.25],
            ['code' => 'C3', 'name' => 'Nilai Bermasalah', 'type' => 'cost', 'weight' => 0.20],
            ['code' => 'C4', 'name' => 'Konsultasi', 'type' => 'benefit', 'weight' => 0.10],
            ['code' => 'C5', 'name' => 'Prestasi', 'type' => 'benefit', 'weight' => 0.10],
        ];

        foreach ($weights as $weight) {
            \App\Models\SpkCriteriaWeight::updateOrCreate(
                ['code' => $weight['code']],
                $weight
            );
        }
    }
}
