<?php

namespace Database\Seeders;

use App\Models\Opd;
use App\Models\ProgramKerja;
use Illuminate\Database\Seeder;

class ProgramKerjaSeeder extends Seeder
{
    public function run(): void
    {
        $opds = Opd::all();
        
        if ($opds->isEmpty()) {
            return; // Jika tidak ada OPD, keluar dari seeder
        }
        
        $currentYear = date('Y');
        
        // Program kerja untuk tahun saat ini
        $programKerjasTahunIni = [
            [
                'description' => 'Pembentukan Gugus Tugas KLA tingkat kabupaten/kota dan kecamatan',
                'tahun' => $currentYear,
            ],
            [
                'description' => 'Pelatihan peningkatan kapasitas SDM pengelola KLA',
                'tahun' => $currentYear,
            ],
            [
                'description' => 'Sosialisasi KLA kepada masyarakat dan pemangku kepentingan',
                'tahun' => $currentYear,
            ],
            [
                'description' => 'Pembentukan Forum Anak di tingkat desa/kelurahan',
                'tahun' => $currentYear,
            ],
        ];

        // Program kerja untuk tahun sebelumnya
        $programKerjasTahunLalu = [
            [
                'description' => 'Pengembangan Sekolah Ramah Anak di seluruh wilayah',
                'tahun' => $currentYear - 1,
            ],
            [
                'description' => 'Pembangunan Ruang Publik Ramah Anak di setiap kecamatan',
                'tahun' => $currentYear - 1,
            ],
            [
                'description' => 'Pendataan anak yang memerlukan perlindungan khusus',
                'tahun' => $currentYear - 1,
            ],
        ];
        
        // Program kerja untuk 2 tahun sebelumnya
        $programKerjasDuaTahunLalu = [
            [
                'description' => 'Pengembangan Puskesmas Ramah Anak dan pelayanan kesehatan komprehensif',
                'tahun' => $currentYear - 2,
            ],
            [
                'description' => 'Pengembangan sistem perlindungan anak terintegrasi',
                'tahun' => $currentYear - 2,
            ],
            [
                'description' => 'Pembentukan kawasan ramah anak di seluruh wilayah desa/kelurahan',
                'tahun' => $currentYear - 2,
            ],
        ];
        
        // Gabungkan semua program kerja
        $allProgramKerjas = array_merge(
            $programKerjasTahunIni,
            $programKerjasTahunLalu,
            $programKerjasDuaTahunLalu
        );

        // Distribusikan program kerja ke semua OPD yang ada
        foreach ($allProgramKerjas as $index => $program) {
            $opdIndex = $index % count($opds);
            
            ProgramKerja::create([
                'opd_id' => $opds[$opdIndex]->id,
                'description' => $program['description'],
                'tahun' => $program['tahun']
            ]);
        }
    }
} 