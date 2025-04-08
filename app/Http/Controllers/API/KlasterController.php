<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Klaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KlasterController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $perPage = $request->per_page ?? 10;

        $query = Klaster::query()
            ->select('klasters.*')
            ->withCount('indikators as indikator_count')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc');

        $klasters = $query->paginate($perPage);

        // Transform data untuk memastikan format yang konsisten
        $klasters->through(function ($klaster) {
            return [
                'id' => $klaster->id,
                'name' => $klaster->name,
                'indikator_count' => $klaster->indikator_count
            ];
        });

        return response()->json($klasters);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $klaster = Klaster::findOrFail($id);
            
            // Hapus semua indikator terkait
            $klaster->indikators()->delete();
            
            // Hapus klaster
            $klaster->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Klaster berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus klaster: ' . $e->getMessage()
            ], 500);
        }
    }
} 