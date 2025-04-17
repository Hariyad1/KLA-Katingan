<?php

namespace Database\Seeders;

use App\Models\DataDukung;
use App\Models\DataDukungFile;
use Illuminate\Database\Seeder;

class DataDukungSeeder extends Seeder
{
    public function run(): void
    {
        // Create some data dukung
        $dataDukungs = [
            [
                'opd_id' => 1, // Dinas Pendidikan
                'indikator_id' => 3, // Angka Partisipasi Sekolah
                'description' => 'Data partisipasi sekolah tahun 2023',
                'created_by' => 1, // Admin
            ],
            [
                'opd_id' => 2, // Dinas Kesehatan
                'indikator_id' => 1, // Angka Harapan Hidup
                'description' => 'Laporan kesehatan masyarakat 2023',
                'created_by' => 1,
            ],
            [
                'opd_id' => 3, // Dinas PU
                'indikator_id' => 5, // Panjang Jalan Kondisi Baik
                'description' => 'Data infrastruktur jalan 2023',
                'created_by' => 2, // User Demo
            ],
        ];

        foreach ($dataDukungs as $dataDukung) {
            $created = DataDukung::create($dataDukung);
            
            // Create dummy file for each data dukung
            DataDukungFile::create([
                'data_dukung_id' => $created->id,
                'file' => 'dummy.pdf',
                'original_name' => 'laporan.pdf',
                'mime_type' => 'application/pdf',
                'size' => 1024,
            ]);
        }
    }
} 