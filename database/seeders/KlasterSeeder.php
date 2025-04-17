<?php

namespace Database\Seeders;

use App\Models\Klaster;
use Illuminate\Database\Seeder;

class KlasterSeeder extends Seeder
{
    public function run(): void
    {
        $klasters = [
            ['name' => 'Klaster Kesehatan'],
            ['name' => 'Klaster Pendidikan'],
            ['name' => 'Klaster Infrastruktur'],
            ['name' => 'Klaster Ekonomi'],
            ['name' => 'Klaster Sosial'],
        ];

        foreach ($klasters as $klaster) {
            Klaster::create($klaster);
        }
    }
} 