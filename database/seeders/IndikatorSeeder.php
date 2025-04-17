<?php

namespace Database\Seeders;

use App\Models\Indikator;
use Illuminate\Database\Seeder;

class IndikatorSeeder extends Seeder
{
    public function run(): void
    {
        $indikators = [
            [
                'klaster_id' => 1, // Klaster Kesehatan
                'name' => 'Angka Harapan Hidup'
            ],
            [
                'klaster_id' => 1,
                'name' => 'Tingkat Imunisasi'
            ],
            [
                'klaster_id' => 2, // Klaster Pendidikan
                'name' => 'Angka Partisipasi Sekolah'
            ],
            [
                'klaster_id' => 2,
                'name' => 'Rasio Guru-Murid'
            ],
            [
                'klaster_id' => 3, // Klaster Infrastruktur
                'name' => 'Panjang Jalan Kondisi Baik'
            ],
        ];

        foreach ($indikators as $indikator) {
            Indikator::create($indikator);
        }
    }
} 