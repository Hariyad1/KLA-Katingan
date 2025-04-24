<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kategoris = [
            [
                'name' => 'Klaster 1 - Hak Sipil dan Kebebasan'
            ],
            [
                'name' => 'Klaster 2 - Lingkungan Keluarga dan Pengasuhan Alternatif'
            ],
            [
                'name' => 'Klaster 3 - Kesehatan Dasar dan Kesejahteraan'
            ],
            [
                'name' => 'Klaster 4 - Pendidikan, Pemanfaatan Waktu Luang, dan Kegiatan Budaya'
            ],
            [
                'name' => 'Klaster 5 - Perlindungan Khusus'
            ],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
} 