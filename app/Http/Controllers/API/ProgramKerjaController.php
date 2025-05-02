<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProgramKerjaController extends Controller
{
    /**
     * Menampilkan daftar program kerja dengan filter dan pencarian
     */
    public function index(Request $request)
    {
        try {
            $tahun = $request->tahun;
            $opd_id = $request->opd_id ?? null;
            $search = $request->search ?? null;
            
            // Query builder dasar
            $query = ProgramKerja::with('opd');
            
            // Jika ada filter tahun, tambahkan ke query
            if ($tahun) {
                $query->where('tahun', $tahun);
            }
            
            // Jika ada filter OPD, tambahkan ke query
            if ($opd_id) {
                $query->where('opd_id', $opd_id);
            }
            
            // Jika ada pencarian, tambahkan ke query dengan optimasi
            if ($search && strlen(trim($search)) >= 2) {
                try {
                    // Gunakan full text search jika tersedia, atau fallback ke LIKE
                    if (config('database.default') === 'mysql') {
                        // MySQL/MariaDB: Gunakan FULLTEXT jika column di-index (case-insensitive)
                        $query->whereRaw("MATCH(description) AGAINST(? IN BOOLEAN MODE)", [$search . '*']);
                    } elseif (config('database.default') === 'pgsql') {
                        // PostgreSQL: Gunakan ILIKE untuk case-insensitive
                        $query->whereRaw("description ILIKE ?", ['%' . $search . '%']);
                    } elseif (config('database.default') === 'sqlite') {
                        // SQLite: Gunakan COLLATE NOCASE untuk case-insensitive
                        $query->whereRaw("description LIKE ? COLLATE NOCASE", ['%' . $search . '%']);
                    } else {
                        // Fallback ke basic LIKE search dengan lower pada database lain
                        $query->whereRaw("LOWER(description) LIKE ?", ['%' . strtolower($search) . '%']);
                    }
                } catch (\Exception $e) {
                    // Fallback jika query search gagal
                    $query->where('description', 'like', '%' . $search . '%');
                    Log::warning('Search query failed, fallback to simple LIKE: ' . $e->getMessage());
                }
            }
            
            // Konsistenkan jumlah item per halaman
            $perPage = $request->input('per_page', 3);
            if (!is_numeric($perPage) || $perPage < 1) {
                $perPage = 3; // Default jika nilai tidak valid
            }
            
            // Paginate hasil 
            $programKerjas = $query->latest()->paginate($perPage);
            
            // Pastikan pagination tidak melampaui batas
            if ($programKerjas->currentPage() > $programKerjas->lastPage() && $programKerjas->lastPage() > 0) {
                // Kembalikan respons JSON dengan informasi halaman terakhir
                $lastPageUrl = route('api.program.index', array_merge(
                    $request->except('page'),
                    ['page' => $programKerjas->lastPage()]
                ));
                
                return response()->json([
                    'success' => false,
                    'message' => 'Halaman yang diminta melebihi jumlah halaman yang tersedia',
                    'last_page' => $programKerjas->lastPage(),
                    'last_page_url' => $lastPageUrl,
                    'redirect_to' => $lastPageUrl
                ]);
            }
                
            // Selalu gunakan format response yang sama
            $html = view('profil.partials.program-cards', compact('programKerjas'))->render();
            $pagination = view('profil.partials.pagination', compact('programKerjas'))->render();
            
            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'total' => $programKerjas->total(),
                'current_page' => $programKerjas->currentPage(),
                'last_page' => $programKerjas->lastPage(),
                'per_page' => $perPage,
                'from' => $programKerjas->firstItem(),
                'to' => $programKerjas->lastItem(),
                'success' => true
            ]);
            
        } catch (\Exception $e) {
            Log::error('API Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses data: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Memperbarui program kerja yang sudah ada
     */
    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'opd_id' => 'required|exists:opds,id',
            'description' => 'required|string',
            'tahun' => 'required|integer|min:2000',
        ]);

        $programKerja = ProgramKerja::findOrFail($id);
        $programKerja->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Program Kerja berhasil diperbarui',
            'data' => $programKerja
        ]);
    }

    /**
     * Menghapus program kerja
     */
    public function destroy($id)
    {
        $programKerja = ProgramKerja::findOrFail($id);
        $programKerja->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Program Kerja berhasil dihapus',
        ]);
    }
} 