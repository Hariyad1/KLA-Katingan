<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;

class ProgramKerjaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/program-kerja",
     *     summary="Menampilkan daftar program kerja",
     *     description="Menampilkan daftar program kerja dengan filter dan pencarian",
     *     operationId="getProgramKerjaList",
     *     tags={"Program Kerja"},     *     @OA\Parameter(
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
     *         description="Kata kunci pencarian pada deskripsi program kerja (minimal 2 karakter)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Halaman yang diminta",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Jumlah item per halaman",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daftar program kerja berhasil diambil",
     *         @OA\JsonContent(     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="total", type="integer", description="Total data program kerja"),
     *             @OA\Property(property="current_page", type="integer", description="Halaman saat ini"),
     *             @OA\Property(property="last_page", type="integer", description="Halaman terakhir"),
     *             @OA\Property(property="per_page", type="integer", description="Jumlah data per halaman"),
     *             @OA\Property(property="from", type="integer", description="Indeks data pertama pada halaman ini"),
     *             @OA\Property(property="to", type="integer", description="Indeks data terakhir pada halaman ini"),     *             @OA\Property(property="data", type="array", description="Data program kerja", 
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="opd_id", type="integer"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="tahun", type="integer"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *                     @OA\Property(property="opd", type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string"),
     *                         @OA\Property(property="code", type="string"),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Terjadi kesalahan pada server",     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tahun = $request->get('tahun');
        $opd_id = $request->get('opd_id');
        $search = $request->get('search');
        $per_page = $request->get('per_page', 10);

        $query = ProgramKerja::with('opd');
        
        // Filter by tahun
        if (!empty($tahun)) {
            $query->where('tahun', $tahun);
        }
        
        // Filter by OPD
        if (!empty($opd_id)) {
            $query->where('opd_id', $opd_id);
        }
        
        // Filter by search
        if (!empty($search) && strlen(trim($search)) >= 2) {
            $searchTerm = trim($search);
            $query->whereRaw('LOWER(description) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
        }
        
        $programKerjas = $query->orderBy('created_at', 'desc')->paginate($per_page);
        
        return response()->json([
            'success' => true,
            'data' => $programKerjas->items(),
            'total' => $programKerjas->total(),
            'current_page' => $programKerjas->currentPage(),
            'last_page' => $programKerjas->lastPage(),
            'per_page' => $programKerjas->perPage(),
            'from' => $programKerjas->firstItem(),
            'to' => $programKerjas->lastItem(),
        ]);
    }
}
