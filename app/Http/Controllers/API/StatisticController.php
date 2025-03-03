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
        
        $statistic = Statistic::create([
            'ip' => $request->ip(),
            'os' => $agent->platform(),
            'browser' => $agent->browser()
        ]);

        return response()->json([
            'success' => true,
            'data' => $statistic
        ], 201);
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
     * @OA\Put(
     *     path="/api/statistic/{id}",
     *     tags={"Statistics"},
     *     summary="Mengupdate statistik",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID statistik",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="ip", type="string"),
     *             @OA\Property(property="os", type="string"),
     *             @OA\Property(property="browser", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statistik berhasil diupdate",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Statistic")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Statistik tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $statistic = Statistic::find($id);
        if (!$statistic) {
            return response()->json([
                'success' => false,
                'message' => 'Statistic not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'ip' => 'nullable|string|max:20',
            'os' => 'nullable|string|max:30',
            'browser' => 'nullable|string|max:120'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $statistic->update($request->all());
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

    /**
     * @OA\Get(
     *     path="/api/statistic/visitor-stats",
     *     tags={"Statistics"},
     *     summary="Mendapatkan ringkasan statistik pengunjung",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="total_visitors", type="integer", example=100),
     *                 @OA\Property(
     *                     property="browser_stats",
     *                     type="object",
     *                     @OA\Property(property="Chrome", type="integer", example=60),
     *                     @OA\Property(property="Firefox", type="integer", example=30),
     *                     @OA\Property(property="Safari", type="integer", example=10)
     *                 ),
     *                 @OA\Property(
     *                     property="os_stats",
     *                     type="object",
     *                     @OA\Property(property="Windows", type="integer", example=50),
     *                     @OA\Property(property="MacOS", type="integer", example=30),
     *                     @OA\Property(property="Linux", type="integer", example=20)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getVisitorStats(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $stats = [
            'total_visitors' => Statistic::getVisitorCount($startDate, $endDate),
            'browser_stats' => Statistic::getBrowserStats(),
            'os_stats' => Statistic::getOSStats()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
} 