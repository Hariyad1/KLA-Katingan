<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Opd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OpdController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $perPage = $request->per_page ?? 10;

        $query = Opd::query()
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc');

        $opds = $query->paginate($perPage);

        // Transform data untuk memastikan format yang konsisten
        $opds->through(function ($opd) {
            return [
                'id' => $opd->id,
                'name' => $opd->name,
                'created_at' => $opd->created_at
            ];
        });

        return response()->json($opds);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $opd = Opd::findOrFail($id);
            $opd->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'OPD berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus OPD: ' . $e->getMessage()
            ], 500);
        }
    }
} 