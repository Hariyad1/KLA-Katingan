<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProgramKerjaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/program-kerja",
     *     summary="Menampilkan daftar program kerja",
     *     description="Menampilkan daftar program kerja dengan filter dan pencarian",
     *     operationId="getProgramKerjaList",
     *     tags={"Program Kerja"},
     *     @OA\Parameter(
     *         name="tahun",
     *         in="query",
     *         description="Filter berdasarkan tahun program kerja (opsional)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="opd_id",
     *         in="query",
     *         description="Filter berdasarkan ID Perangkat Daerah (OPD) (opsional)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Kata kunci pencarian pada deskripsi atau nama OPD",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Halaman yang diminta (contoh=1,2,3,dst)",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Jumlah item per halaman (contoh=10)",
     *         required=false,
     *         @OA\Schema(type="integer", default=3)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daftar program kerja berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="html", type="string", description="Rendered HTML untuk program kerja"),
     *             @OA\Property(property="pagination", type="string", description="Rendered HTML untuk pagination"),
     *             @OA\Property(property="total", type="integer", description="Total data program kerja"),
     *             @OA\Property(property="current_page", type="integer", description="Halaman saat ini"),
     *             @OA\Property(property="last_page", type="integer", description="Halaman terakhir"),
     *             @OA\Property(property="per_page", type="integer", description="Jumlah data per halaman"),
     *             @OA\Property(property="from", type="integer", description="Indeks data pertama pada halaman ini"),
     *             @OA\Property(property="to", type="integer", description="Indeks data terakhir pada halaman ini"),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", description="Data program kerja dalam format terstruktur", 
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="opd_id", type="integer"),
     *                     @OA\Property(property="opd_name", type="string"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="tahun", type="integer"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Terjadi kesalahan pada server",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Terjadi kesalahan saat memproses data")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $query = ProgramKerja::with('opd');
            
            $tahun = $request->tahun;
            if ($tahun) {
                $query->where('tahun', $tahun);
            }
            
            $opd_id = $request->opd_id ?? null;
            if ($opd_id) {
                $query->where('opd_id', $opd_id);
            }
            
            $search = $request->search ?? null;
            if ($search && !empty(trim($search))) {
                $searchTerm = '%' . trim($search) . '%';
                $searchLower = strtolower(trim($search));
                
                if (config('database.default') === 'mysql') {
                    $query->where(function($q) use ($searchLower) {
                        $q->whereRaw("LOWER(description) LIKE ?", ['%' . $searchLower . '%'])
                          ->orWhereHas('opd', function($opdQuery) use ($searchLower) {
                              $opdQuery->whereRaw("LOWER(name) LIKE ?", ['%' . $searchLower . '%']);
                          });
                    });
                } elseif (config('database.default') === 'pgsql') {
                    $query->where(function($q) use ($searchTerm) {
                        $q->whereRaw("description ILIKE ?", [$searchTerm])
                          ->orWhereHas('opd', function($opdQuery) use ($searchTerm) {
                              $opdQuery->whereRaw("name ILIKE ?", [$searchTerm]);
                          });
                    });
                } elseif (config('database.default') === 'sqlite') {
                    $query->where(function($q) use ($searchTerm) {
                        $q->whereRaw("description LIKE ? COLLATE NOCASE", [$searchTerm])
                          ->orWhereHas('opd', function($opdQuery) use ($searchTerm) {
                              $opdQuery->whereRaw("name LIKE ? COLLATE NOCASE", [$searchTerm]);
                          });
                    });
                } else {
                    $query->where(function($q) use ($searchLower) {
                        $q->whereRaw("LOWER(description) LIKE ?", ['%' . $searchLower . '%'])
                          ->orWhereHas('opd', function($opdQuery) use ($searchLower) {
                              $opdQuery->whereRaw("LOWER(name) LIKE ?", ['%' . $searchLower . '%']);
                          });
                    });
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
            
            // Transformasi data sesuai kebutuhan
            $programKerjasData = [];
            foreach ($programKerjas as $program) {
                $programKerjasData[] = [
                    'id' => $program->id,
                    'opd_id' => $program->opd_id,
                    'opd_name' => $program->opd->name,
                    'description' => $program->description,
                    'tahun' => $program->tahun,
                    'created_at' => $program->created_at,
                    'updated_at' => $program->updated_at
                ];
            }
            
            // Render HTML untuk tampilan frontend
            $html = view('profil.partials.program-cards', compact('programKerjas'))->render();
            $pagination = view('profil.partials.pagination', compact('programKerjas'))->render();
            
            // Response tetap menggunakan format yang kompatibel dengan frontend
            $response = [
                'html' => $html,
                'pagination' => $pagination,
                'total' => $programKerjas->total(),
                'current_page' => $programKerjas->currentPage(),
                'last_page' => $programKerjas->lastPage(),
                'per_page' => (int) $perPage,
                'from' => $programKerjas->firstItem(),
                'to' => $programKerjas->lastItem(),
                'success' => true
            ];
            
            // Tambahkan struktur data untuk API consumers
            $response['data'] = $programKerjasData;
            
            return response()->json($response, 200, [
                'Content-Type' => 'application/json; charset=utf-8'
            ]);
            
        } catch (\Exception $e) {
            Log::error('API Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/program-kerja/{id}",
     *     summary="Memperbarui program kerja",
     *     description="Memperbarui data program kerja yang sudah ada",
     *     operationId="updateProgramKerja",
     *     tags={"Program Kerja"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID program kerja yang akan diperbarui",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"opd_id", "description", "tahun"},
     *             @OA\Property(property="opd_id", type="integer", description="ID OPD (Perangkat Daerah)"),
     *             @OA\Property(property="description", type="string", description="Deskripsi program kerja"),
     *             @OA\Property(property="tahun", type="integer", description="Tahun program kerja", minimum=2000)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Program kerja berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Program Kerja berhasil diperbarui"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="opd_id", type="integer"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="tahun", type="integer"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Program kerja tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\ProgramKerja].")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="opd_id", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="description", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="tahun", type="array", @OA\Items(type="string"))
     *             )
     *         )
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/program-kerja/{id}",
     *     summary="Menghapus program kerja",
     *     description="Menghapus data program kerja",
     *     operationId="deleteProgramKerja",
     *     tags={"Program Kerja"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID program kerja yang akan dihapus",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Program kerja berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Program Kerja berhasil dihapus")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Program kerja tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\ProgramKerja].")
     *         )
     *     )
     * )
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