<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('media')->truncate();

        $prefixes = ['Foto', 'Gambar', 'Dokumentasi', 'Snapshot', 'Potret'];
        $subjects = ['Kegiatan', 'Rapat', 'Sosialisasi', 'Pembangunan', 'Event', 'Kunjungan'];
        $locations = ['Kantor', 'Lapangan', 'Aula', 'Masyarakat', 'Desa', 'Kecamatan'];
        
        $imageExtensions = ['jpg', 'jpeg', 'png'];
        
        $slideShowStatus = [0, 0, 0, 0];
        
        $medias = [];
        
        for ($i = 1; $i <= 30; $i++) {
            $prefix = $prefixes[array_rand($prefixes)];
            $subject = $subjects[array_rand($subjects)];
            $location = $locations[array_rand($locations)];
            
            $extension = $imageExtensions[array_rand($imageExtensions)];
            
            $fileName = time() . $i . '_' . strtolower(str_replace(' ', '-', "$prefix-$subject-$location")) . '.' . $extension;
            
            $path = '/storage/media/' . $fileName;
            
            $slideShow = $slideShowStatus[array_rand($slideShowStatus)];
            
            $date = date('Y-m-d H:i:s', strtotime('-' . rand(0, 730) . ' days'));
            
            $medias[] = [
                'name' => "$prefix $subject $location " . $i,
                'file' => $fileName,
                'path' => $path,
                'slide_show' => $slideShow,
                'hits' => rand(0, 100),
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }

        foreach ($medias as $media) {
            Media::create($media);
        }
    }
} 