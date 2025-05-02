<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Klaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
            
            if ($klaster->indikators()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Klaster tidak dapat dihapus karena masih memiliki indikator terkait.'
                ], 422);
            }
            
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

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:klasters',
                'description' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();
            
            $klaster = Klaster::create([
                'name' => $request->name,
                'description' => $request->description
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Klaster berhasil ditambahkan',
                'data' => $klaster
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan klaster: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $klaster = Klaster::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:klasters,name,' . $id,
                'description' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();
            
            $klaster->update([
                'name' => $request->name,
                'description' => $request->description
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Klaster berhasil diperbarui',
                'data' => $klaster
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui klaster: ' . $e->getMessage()
            ], 500);
        }
    }
} 