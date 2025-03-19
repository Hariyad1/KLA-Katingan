<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;

/**
 * @OA\Schema(
 *     schema="Statistic",
 *     required={"ip", "os", "browser"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="ip", type="string", example="192.168.1.1"),
 *     @OA\Property(property="os", type="string", example="Windows 10"),
 *     @OA\Property(property="browser", type="string", example="Chrome"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Tag(
 *     name="Statistics",
 *     description="API Endpoints untuk manajemen statistik pengunjung"
 * )
 */
class StatisticController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/statistic",
     *     tags={"Statistics"},
     *     summary="Mendapatkan daftar statistik",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Statistic")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $statistics = Statistic::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/statistic",
     *     tags={"Statistics"},
     *     summary="Mencatat statistik pengunjung baru",
     *     @OA\Response(
     *         response=201,
     *         description="Statistik berhasil dicatat",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Statistic")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $agent = new Agent();
        
        // Cek apakah IP sudah tercatat hari ini
        $existingVisit = Statistic::where('ip', $request->ip())
            ->whereDate('created_at', today())
            ->first();
            
        if (!$existingVisit) {
            $statistic = Statistic::create([
                'ip' => $request->ip(),
                'os' => $agent->platform(),
                'browser' => $agent->browser()
            ]);

            return response()->json([
                'success' => true,
                'data' => $statistic,
                'message' => 'Pengunjung baru tercatat'
            ], 201);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengunjung sudah tercatat hari ini'
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/statistic/{id}",
     *     tags={"Statistics"},
     *     summary="Mendapatkan detail statistik",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID statistik",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Statistic")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Statistik tidak ditemukan"
     *     )
     * )
     */
    public function show($id)
    {
        $statistic = Statistic::find($id);
        if (!$statistic) {
            return response()->json([
                'success' => false,
                'message' => 'Statistic not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $statistic
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/statistic/{id}",
     *     tags={"Statistics"},
     *     summary="Menghapus statistik",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID statistik",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statistik berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Statistic deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Statistik tidak ditemukan"
     *     )
     * )
     */
    public function destroy($id)
    {
        $statistic = Statistic::find($id);
        if (!$statistic) {
            return response()->json([
                'success' => false,
                'message' => 'Statistic not found'
            ], 404);
        }

        $statistic->delete();
        return response()->json([
            'success' => true,
            'message' => 'Statistic deleted successfully'
        ]);
    }

    public function getStatistics()
    {
        $totalVisitors = Statistic::count();
        $todayVisitors = Statistic::whereDate('created_at', today())->count();
        $browserStats = Statistic::getBrowserStats();
        $osStats = Statistic::getOSStats();

        return response()->json([
            'total_visitors' => $totalVisitors,
            'today_visitors' => $todayVisitors,
            'browser_stats' => $browserStats,
            'os_stats' => $osStats
        ]);
    }
} 