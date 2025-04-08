<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Indikator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndikatorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $perPage = $request->per_page ?? 10;

        $query = Indikator::query()
            ->select('indikators.*', 'klasters.name as klaster_name')
            ->join('klasters', 'klasters.id', '=', 'indikators.klaster_id')
            ->when($search, function ($query) use ($search) {
                return $query->where(function($q) use ($search) {
                    $q->where('indikators.name', 'like', '%' . $search . '%')
                      ->orWhere('klasters.name', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('indikators.created_at', 'desc');

        $indikators = $query->paginate($perPage);

        // Transform data untuk memastikan format yang konsisten
        $indikators->through(function ($indikator) {
            return [
                'id' => $indikator->id,
                'name' => $indikator->name,
                'klaster_name' => $indikator->klaster_name
            ];
        });

        return response()->json($indikators);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $indikator = Indikator::findOrFail($id);
            $indikator->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Indikator berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus indikator: ' . $e->getMessage()
            ], 500);
        }
    }
} 