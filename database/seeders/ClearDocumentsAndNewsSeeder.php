<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClearDocumentsAndNewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Menghapus semua data dokumen dan berita...');
        
        try {
            DB::beginTransaction();
            
            // Hapus semua data berita
            $this->command->info('Menghapus berita...');
            $newsCount = News::count();
            News::truncate();
            $this->command->info("Berhasil menghapus {$newsCount} data berita.");
            
            // Hapus dokumen dari Media (PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX)
            $this->command->info('Menghapus dokumen dari media...');
            $documents = Media::where(function($query) {
                $query->where('file', 'like', '%.pdf')
                    ->orWhere('file', 'like', '%.doc')
                    ->orWhere('file', 'like', '%.docx')
                    ->orWhere('file', 'like', '%.xls')
                    ->orWhere('file', 'like', '%.xlsx')
                    ->orWhere('file', 'like', '%.ppt')
                    ->orWhere('file', 'like', '%.pptx');
            })->get();
            
            $documentsCount = $documents->count();
            
            // Hapus file dari storage jika ada
            foreach ($documents as $document) {
                $filePath = 'public/media/' . $document->file;
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
            }
            
            // Hapus data dari database
            Media::where(function($query) {
                $query->where('file', 'like', '%.pdf')
                    ->orWhere('file', 'like', '%.doc')
                    ->orWhere('file', 'like', '%.docx')
                    ->orWhere('file', 'like', '%.xls')
                    ->orWhere('file', 'like', '%.xlsx')
                    ->orWhere('file', 'like', '%.ppt')
                    ->orWhere('file', 'like', '%.pptx');
            })->delete();
            
            $this->command->info("Berhasil menghapus {$documentsCount} dokumen.");
            
            DB::commit();
            $this->command->info('Proses penghapusan selesai.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error: ' . $e->getMessage());
        }
    }
} 