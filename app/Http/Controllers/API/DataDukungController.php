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
     *     summary="Mendapatkan daftar data dukung",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="opd_id", type="integer"),
     *                     @OA\Property(property="indikator_id", type="integer"),
     *                     @OA\Property(property="description", type="string", nullable=true),
     *                     @OA\Property(property="files", type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer"),
     *                             @OA\Property(property="file", type="string"),
     *                             @OA\Property(property="original_name", type="string"),
     *                             @OA\Property(property="size", type="integer"),
     *                             @OA\Property(property="url", type="string")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
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
        
        $transformedData = [];
        foreach ($dataDukung->items() as $item) {
            $transformedFiles = [];
            foreach ($item->files as $file) {
                try {
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
                        $file->size = 0;
                    }

                    try {
                        if (Storage::exists($file->file)) {
                            $file->url = Storage::url($file->file);
                        } else {
                            $file->url = url('storage/' . $file->file);
                        }
                    } catch (\Exception $e) {
                        $file->url = url('storage/' . $file->file);
                    }

                } catch (\Exception $e) {
                    $file->size = 0;
                    $file->url = url('storage/' . $file->file);
                }
                $transformedFiles[] = $file;
            }
            $item->files = collect($transformedFiles);
            $transformedData[] = $item;
        }

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $transformedData,
            $dataDukung->total(),
            $dataDukung->perPage(),
            $dataDukung->currentPage(),
            ['path' => request()->url()]
        );
    }

    /**
     * @OA\Post(
     *     path="/api/data-dukung",
     *     tags={"Data Dukung"},
     *     summary="Tambah data dukung baru",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="opd_id", type="integer"),
     *                 @OA\Property(property="indikator_id", type="integer"),
     *                 @OA\Property(property="description", type="string", nullable=true),
     *                 @OA\Property(
     *                     property="files[]",
     *                     type="array",
     *                     @OA\Items(type="string", format="binary")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data dukung berhasil ditambahkan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'opd_id' => 'required|exists:opds,id',
            'indikator_id' => 'required|exists:indikators,id',
            'description' => 'nullable|string',
            'files.*' => 'required|file|max:25600'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $dataDukung = DataDukung::create([
                'opd_id' => $request->opd_id,
                'indikator_id' => $request->indikator_id,
                'description' => $request->description,
                'created_by' => Auth::id()
            ]);

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    try {
                        $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
                        $path = Storage::disk('public')->putFileAs(
                            'data-dukung-files',
                            $file,
                            $fileName
                        );

                        $dataDukung->files()->create([
                            'file' => $path,
                            'original_name' => $file->getClientOriginalName(),
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize(),
                            'created_by' => Auth::id()
                        ]);
                    } catch (\Exception $e) {
                        throw new \Exception('Gagal mengupload file: ' . $file->getClientOriginalName());
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data dukung berhasil ditambahkan',
                'data' => $dataDukung->load(['opd', 'indikator.klaster', 'files'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/data-dukung/{id}/update",
     *     tags={"Data Dukung"},
     *     summary="Update data dukung",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID data dukung",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="opd_id", type="integer"),
     *                 @OA\Property(property="indikator_id", type="integer"),
     *                 @OA\Property(property="description", type="string", nullable=true),
     *                 @OA\Property(
     *                     property="files[]",
     *                     type="array",
     *                     @OA\Items(type="string", format="binary")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data dukung berhasil diperbarui")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Data dukung tidak ditemukan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
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
            'files.*' => 'nullable|file|max:25600'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $dataDukung->update([
                'opd_id' => $request->opd_id,
                'indikator_id' => $request->indikator_id,
                'description' => $request->description,
                'updated_by' => Auth::id()
            ]);

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    try {
                        $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
                        $path = Storage::disk('public')->putFileAs(
                            'data-dukung-files',
                            $file,
                            $fileName
                        );

                        $dataDukung->files()->create([
                            'file' => $path,
                            'original_name' => $file->getClientOriginalName(),
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize(),
                            'created_by' => Auth::id()
                        ]);
                    } catch (\Exception $e) {
                        throw new \Exception('Gagal mengupload file: ' . $file->getClientOriginalName());
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data dukung berhasil diperbarui',
                'data' => $dataDukung->load(['opd', 'indikator.klaster', 'files'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/data-dukung/file/{id}",
     *     tags={"Data Dukung"},
     *     summary="Hapus file data dukung",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID file data dukung",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="File berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="File berhasil dihapus")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="File tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="File tidak ditemukan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Gagal menghapus file")
     *         )
     *     )
     * )
     */
    public function destroyFile($id)
    {
        try {
            $file = DataDukungFile::findOrFail($id);
            
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
            }
            
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

    /**
     * @OA\Delete(
     *     path="/api/data-dukung/{id}",
     *     tags={"Data Dukung"},
     *     summary="Hapus data dukung",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID data dukung",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data dukung berhasil dihapus")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Data dukung tidak ditemukan")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $dataDukung = DataDukung::findOrFail($id);
            
            foreach ($dataDukung->files as $file) {
                $possiblePaths = [
                    $file->file,
                    'public/' . $file->file,
                    'data-dukung-files/' . basename($file->file),
                    'public/data-dukung-files/' . basename($file->file)
                ];

                foreach ($possiblePaths as $path) {
                    try {
                        if (Storage::exists($path)) {
                            Storage::delete($path);
                            break;
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
            
            $dataDukung->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Data dukung berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data dukung: ' . $e->getMessage()
            ], 500);
        }
    }
} 