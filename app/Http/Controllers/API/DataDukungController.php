<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DataDukung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\DataDukungFile;
use Illuminate\Support\Facades\Storage;

class DataDukungController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/data-dukung",
     *     tags={"Data Dukung"},
     *     summary="Mendapatkan daftar data dukung dengan pagination",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Kata kunci pencarian",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Nomor halaman",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Jumlah item per halaman",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = DataDukung::with(['opd', 'indikator.klaster', 'files'])
            ->where('created_by', Auth::id());

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('opd', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('indikator', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('indikator.klaster', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $dataDukungs = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $dataDukungs
        ]);
    }

    /**
     * Update data dukung.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dataDukung = DataDukung::find($id);
        if (!$dataDukung) {
            return response()->json([
                'success' => false,
                'message' => 'Data dukung tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'opd_id' => 'required|exists:opds,id',
            'indikator_id' => 'required|exists:indikators,id',
            'description' => 'nullable|string',
            'files.*' => 'nullable|file|max:10240' // max 10MB per file
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Update data dukung
            $dataDukung->update([
                'opd_id' => $request->opd_id,
                'indikator_id' => $request->indikator_id,
                'description' => $request->description
            ]);

            // Handle file uploads
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $fileName = time() . '_' . Str::slug($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('public/data-dukung', $fileName);
                    
                    $dataDukung->files()->create([
                        'file' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize()
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $dataDukung->load(['opd', 'indikator.klaster', 'files']),
                'message' => 'Data dukung berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyFile($id)
    {
        try {
            $file = DataDukungFile::findOrFail($id);
            
            // Hapus file dari storage
            if (Storage::exists($file->file)) {
                Storage::delete($file->file);
            }
            
            // Hapus record dari database
            $file->delete();
            
            return response()->json([
                'message' => 'File berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus file',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 