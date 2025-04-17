<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $medias = [
            [
                'name' => 'Foto Kegiatan 1',
                'file' => 'kegiatan1.jpg',
                'type' => 'image/jpeg',
                'size' => 1024,
                'created_by' => 1,
            ],
            [
                'name' => 'Dokumen Laporan',
                'file' => 'laporan.pdf',
                'type' => 'application/pdf',
                'size' => 2048,
                'created_by' => 1,
            ],
            [
                'name' => 'Video Sosialisasi',
                'file' => 'sosialisasi.mp4',
                'type' => 'video/mp4',
                'size' => 5120,
                'created_by' => 1,
            ],
        ];

        foreach ($medias as $media) {
            Media::create($media);
        }
    }
} 