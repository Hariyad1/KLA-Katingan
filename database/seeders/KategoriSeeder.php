<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['name' => 'Berita Umum'],
            ['name' => 'Pengumuman'],
            ['name' => 'Kegiatan'],
            ['name' => 'Program'],
            ['name' => 'Laporan'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
} 