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
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        
        $query = DataDukung::with(['opd', 'indikator.klaster', 'files'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('opd', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('indikator', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('indikator.klaster', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });

        $dataDukung = $query->latest()->paginate($perPage);

        // Tambahkan informasi ukuran file
        $dataDukung->through(function ($item) {
            $item->files->transform(function ($file) {
                try {
                    // Coba beberapa kemungkinan lokasi file
                    $possiblePaths = [
                        $file->file,
                        'public/' . $file->file,
                        'data-dukung-files/' . basename($file->file),
                        'public/data-dukung-files/' . basename($file->file)
                    ];

                    $fileFound = false;
                    foreach ($possiblePaths as $path) {
                        try {
                            if (Storage::exists($path)) {
                                $file->size = Storage::size($path);
                                $fileFound = true;
                                break;
                            }
                        } catch (\Exception $e) {
                            continue;
                        }
                    }

                    if (!$fileFound) {
                        // Jika file tidak ditemukan, set ukuran 0
                        $file->size = 0;
                        \Log::warning("File not found in any location: " . $file->file);
                    }

                } catch (\Exception $e) {
                    \Log::error('Error getting file size: ' . $e->getMessage());
                    $file->size = 0;
                }

                // Tambahkan URL yang valid untuk file
                try {
                    if (Storage::exists($file->file)) {
                        $file->url = Storage::url($file->file);
                    } else {
                        $file->url = url('storage/' . $file->file);
                    }
                } catch (\Exception $e) {
                    $file->url = url('storage/' . $file->file);
                }

                return $file;
            });
            return $item;
        });

        return response()->json($dataDukung);
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
                    try {
                        $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
                        // Simpan file ke storage publik
                        $path = Storage::disk('public')->putFileAs(
                            'data-dukung-files',
                            $file,
                            $fileName
                        );

                        // Simpan informasi file ke database
                        $dataDukung->files()->create([
                            'file' => $path,
                            'original_name' => $file->getClientOriginalName(),
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize()
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Error uploading file: ' . $e->getMessage());
                        throw new \Exception('Gagal mengupload file: ' . $file->getClientOriginalName());
                    }
                }
            }

            DB::commit();

            // Load ulang data dengan file yang baru
            $dataDukung->load(['opd', 'indikator.klaster', 'files']);

            return response()->json([
                'success' => true,
                'data' => $dataDukung,
                'message' => 'Data dukung berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error updating data dukung: ' . $e->getMessage());
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
            
            // Coba beberapa kemungkinan lokasi file
            $possiblePaths = [
                $file->file,
                'public/' . $file->file,
                'data-dukung-files/' . basename($file->file),
                'public/data-dukung-files/' . basename($file->file)
            ];

            $fileDeleted = false;
            foreach ($possiblePaths as $path) {
                try {
                    if (Storage::exists($path)) {
                        Storage::delete($path);
                        $fileDeleted = true;
                        break;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            if (!$fileDeleted) {
                \Log::warning("File not found for deletion: " . $file->file);
            }
            
            // Hapus record dari database
            $file->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'File berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting file: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus file: ' . $e->getMessage()
            ], 500);
        }
    }
} 