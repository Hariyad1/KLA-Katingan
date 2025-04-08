<?php

namespace App\Http\Controllers;

use App\Models\DataDukungFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataDukungController extends Controller
{
    public function destroyFile($id)
    {
        try {
            $file = DataDukungFile::findOrFail($id);
            
            // Hapus file fisik jika ada
            if (Storage::exists($file->file)) {
                Storage::delete($file->file);
            }
            
            // Hapus record dari database
            $file->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'File berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus file: ' . $e->getMessage()
            ], 500);
        }
    }
} 