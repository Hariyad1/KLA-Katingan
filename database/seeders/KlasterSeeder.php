<?php

namespace Database\Seeders;

use App\Models\Klaster;
use Illuminate\Database\Seeder;

class KlasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $klasters = [
            [
                'name' => 'Hak Sipil dan Kebebasan'
            ],
            [
                'name' => 'Lingkungan Keluarga dan Pengasuhan Alternatif'
            ],
            [
                'name' => 'Kesehatan Dasar dan Kesejahteraan'
            ],
            [
                'name' => 'Pendidikan, Pemanfaatan Waktu Luang, dan Kegiatan Budaya'
            ],
            [
                'name' => 'Perlindungan Khusus'
            ],
        ];

        foreach ($klasters as $klaster) {
            Klaster::create($klaster);
        }
    }
} 