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
        // Menghapus data media yang ada (opsional)
        // DB::table('media')->truncate();

        // Definisi array untuk variasi nama
        $prefixes = ['Foto', 'Gambar', 'Dokumentasi', 'Snapshot', 'Potret'];
        $subjects = ['Kegiatan', 'Rapat', 'Sosialisasi', 'Pembangunan', 'Event', 'Kunjungan'];
        $locations = ['Kantor', 'Lapangan', 'Aula', 'Masyarakat', 'Desa', 'Kecamatan'];
        
        // Definisi array untuk variasi ekstensi gambar
        $imageExtensions = ['jpg', 'jpeg', 'png'];
        
        // Definisi array untuk status slideshow
        $slideShowStatus = [0, 1, 0, 0]; // 25% chance untuk slide_show = true
        
        $medias = [];
        
        // Membuat 30 data gambar dengan variasi
        for ($i = 1; $i <= 30; $i++) {
            // Pilih prefix, subject, dan location secara acak
            $prefix = $prefixes[array_rand($prefixes)];
            $subject = $subjects[array_rand($subjects)];
            $location = $locations[array_rand($locations)];
            
            // Pilih ekstensi secara acak
            $extension = $imageExtensions[array_rand($imageExtensions)];
            
            // Generate nama file
            $fileName = time() . $i . '_' . strtolower(str_replace(' ', '-', "$prefix-$subject-$location")) . '.' . $extension;
            
            // Generate path
            $path = '/storage/media/' . $fileName;
            
            // Pilih status slide_show secara acak
            $slideShow = $slideShowStatus[array_rand($slideShowStatus)];
            
            // Buat tanggal acak dalam 2 tahun terakhir
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