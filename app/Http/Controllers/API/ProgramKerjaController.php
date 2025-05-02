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
            
            $query = ProgramKerja::with('opd');
            
            if ($tahun) {
                $query->where('tahun', $tahun);
            }
            
            if ($opd_id) {
                $query->where('opd_id', $opd_id);
            }
            
            if ($search && strlen(trim($search)) >= 2) {
                try {
                    $searchTerm = '%' . trim($search) . '%';
                    
                    $query->where(function($q) use ($searchTerm, $search) {
                        $q->where('description', 'like', $searchTerm);
                        
                        if (is_numeric($search)) {
                            $q->orWhere('tahun', 'like', $searchTerm);
                        }
                        
                        $q->orWhereHas('opd', function($opdQuery) use ($searchTerm) {
                            $opdQuery->where('name', 'like', $searchTerm);
                        });
                    });
                } catch (\Exception $e) {
                    $query->where('description', 'like', '%' . $search . '%');
                }
            }
            
            $perPage = $request->input('per_page', 3);
            if (!is_numeric($perPage) || $perPage < 1) {
                $perPage = 3;
            }
            
            $programKerjas = $query->latest()->paginate($perPage);
            
            if ($programKerjas->currentPage() > $programKerjas->lastPage() && $programKerjas->lastPage() > 0) {
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
                'message' => 'Terjadi kesalahan saat memproses data'
            ], 500);
        }
    }

    /**
     * Memperbarui program kerja yang sudah ada
     */
    public function update(Request $request, $id)
    {
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