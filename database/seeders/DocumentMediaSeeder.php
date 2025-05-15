<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DocumentMediaSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Menambahkan 20 data dokumen baru...');
        
        try {
            $prefixes = ['Dokumen', 'Laporan', 'Data', 'File', 'Arsip'];
            $subjects = ['Anggaran', 'Keuangan', 'Kegiatan', 'Evaluasi', 'Perencanaan', 'Pendataan'];
            $departments = ['OPD', 'Dinas', 'Pemerintahan', 'Kesehatan', 'Pendidikan', 'Sosial'];

            $documentExtensions = ['pdf', 'docx', 'xlsx'];
            
            $medias = [];
            $timestamp = time();

            for ($i = 1; $i <= 20; $i++) {
                $prefix = $prefixes[array_rand($prefixes)];
                $subject = $subjects[array_rand($subjects)];
                $department = $departments[array_rand($departments)];
                
                $extension = $documentExtensions[array_rand($documentExtensions)];
                
                $fileName = $timestamp . $i . '_' . strtolower(str_replace(' ', '-', "$prefix-$subject-$department")) . '.' . $extension;
                
                $path = '/storage/media/' . $fileName;
                
                $date = date('Y-m-d H:i:s', strtotime('-' . rand(0, 365) . ' days'));
                
                $mediaData = [
                    'name' => "$prefix $subject $department " . $i,
                    'file' => $fileName,
                    'path' => $path,
                    'slide_show' => 0,
                    'hits' => rand(0, 50),
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
                
                $medias[] = $mediaData;
            }

            $docExtensions = ['pdf', 'docx', 'doc', 'xlsx', 'xls'];
            $existingCount = 0;
            
            foreach ($docExtensions as $ext) {
                $count = Media::where('file', 'like', '%.' . $ext)->count();
                $existingCount += $count;
            }
            
            $this->command->info("Jumlah dokumen yang sudah ada: $existingCount");

            foreach ($medias as $media) {
                Media::create($media);
            }

            $this->command->info('Berhasil menambahkan 20 dokumen baru.');
            $this->command->info('Catatan: Untuk menampilkan file dokumen, pastikan ada file handler yang sesuai di front-end.');
        } catch (\Exception $e) {
            $this->command->error('Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Mendapatkan MIME type berdasarkan ekstensi
     */
    private function getMimeType($extension)
    {
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'zip' => 'application/zip'
        ];
        
        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
} 