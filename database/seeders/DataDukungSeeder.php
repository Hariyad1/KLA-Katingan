<?php

namespace Database\Seeders;

use App\Models\DataDukung;
use App\Models\DataDukungFile;
use Illuminate\Database\Seeder;

class DataDukungSeeder extends Seeder
{
    public function run(): void
    {
        $dataDukungs = [
            [
                'opd_id' => 1, 
                'indikator_id' => 3, 
                'description' => 'Lorem ipsum dolor sit amet',
                'created_by' => 1, 
            ],
            [
                'opd_id' => 2, 
                'indikator_id' => 1, 
                'description' => 'Lorem ipsum dolor sit amet',
                'created_by' => 1,
            ],
            [
                'opd_id' => 3, 
                'indikator_id' => 5, 
                'description' => 'Lorem ipsum dolor sit amet',
                'created_by' => 2, 
            ],
        ];

        foreach ($dataDukungs as $dataDukung) {
            $created = DataDukung::create($dataDukung);
            
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