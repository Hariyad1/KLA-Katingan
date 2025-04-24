<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Indikator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IndikatorController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Indikator::with('klaster');

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhereHas('klaster', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            }

            $sort = $request->get('sort', 'created_at');
            $order = $request->get('order', 'desc');
            $query->orderBy($sort, $order);

            $indikator = $query->paginate($request->per_page ?? 10);

            return response()->json($indikator);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data indikator: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $indikator = Indikator::findOrFail($id);
            
            if ($indikator->dataDukungs()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Indikator tidak dapat dihapus karena masih memiliki data dukung terkait.'
                ], 422);
            }
            
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

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'klaster_id' => 'required|exists:klasters,id',
                'target' => 'required|string',
                'satuan' => 'required|string',
                'tahun' => 'required|integer|min:2000|max:2099',
                'sumber_data' => 'required|string',
                'status' => 'required|in:0,1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();
            
            $indikator = Indikator::create([
                'name' => $request->name,
                'klaster_id' => $request->klaster_id,
                'target' => $request->target,
                'satuan' => $request->satuan,
                'tahun' => $request->tahun,
                'sumber_data' => $request->sumber_data,
                'status' => $request->status
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Indikator berhasil ditambahkan',
                'data' => $indikator
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan indikator: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $indikator = Indikator::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'klaster_id' => 'required|exists:klasters,id',
                'target' => 'required|string',
                'satuan' => 'required|string',
                'tahun' => 'required|integer|min:2000|max:2099',
                'sumber_data' => 'required|string',
                'status' => 'required|in:0,1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();
            
            $indikator->update([
                'name' => $request->name,
                'klaster_id' => $request->klaster_id,
                'target' => $request->target,
                'satuan' => $request->satuan,
                'tahun' => $request->tahun,
                'sumber_data' => $request->sumber_data,
                'status' => $request->status
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Indikator berhasil diperbarui',
                'data' => $indikator
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui indikator: ' . $e->getMessage()
            ], 500);
        }
    }
} 