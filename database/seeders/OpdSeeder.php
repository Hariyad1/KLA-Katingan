<?php

namespace Database\Seeders;

use App\Models\Opd;
use Illuminate\Database\Seeder;

class OpdSeeder extends Seeder
{
    public function run(): void
    {
        $opds = [
            ['name' => 'Dinas Kesehatan'],
            ['name' => 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak'],
            ['name' => 'Dinas Komunikasi Informatika, Statistik dan Persandian'],
            ['name' => 'Dinas Pendidikan'],
            ['name' => 'Dinas Pemberdayaan Masyarakat dan Desa'],
            ['name' => 'Dinas Perhubungan'],
            ['name' => 'Dinas Sosial'],
            ['name' => 'Dinas Kependudukan dan Pencatatan Sipil'],
            ['name' => 'Dinas Lingkungan Hidup'],
            ['name' => 'Dinas Perumahan Rakyat, Kawasan Permukiman serta Pertanahan'],
            ['name' => 'Dinas Koperasi, Usaha Kecil Menengah, dan Perdagangan'],
            ['name' => 'Badan Penanggulangan Bencana Daerah'],
            ['name' => 'Badan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan'],
            ['name' => 'Satuan Polisi Pamong Praja'],
            ['name' => 'RSUD Mas Amsyar'],
        ];

        foreach ($opds as $opd) {
            Opd::create($opd);
        }
    }
} 