<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/*
 * @OA\Schema(
 *     schema="Vote",
 *     required={"nilai_vote"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nilai_vote", type="integer", enum={1,2,3}, example=1),
 *     @OA\Property(
 *         property="data_pengguna",
 *         type="object",
 *         nullable=true,
 *         @OA\Property(property="browser", type="string", example="Chrome"),
 *         @OA\Property(property="os", type="string", example="Windows"),
 *         @OA\Property(property="ip", type="string", example="192.168.1.1")
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Tag(
 *     name="Votes",
 *     description="API Endpoints untuk manajemen voting"
 * )
 */
class VoteController extends Controller
{
    /*
     * @OA\Get(
     *     path="/api/votes",
     *     tags={"Votes"},
     *     summary="Mendapatkan daftar vote",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Vote")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $votes = Vote::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $votes
        ]);
    }

    /*
     * @OA\Post(
     *     path="/api/votes",
     *     tags={"Votes"},
     *     summary="Membuat vote baru",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nilai_vote"},
     *             @OA\Property(property="nilai_vote", type="integer", enum={1,2,3}, example=1),
     *             @OA\Property(
     *                 property="data_pengguna",
     *                 type="object",
     *                 @OA\Property(property="browser", type="string", example="Chrome"),
     *                 @OA\Property(property="os", type="string", example="Windows"),
     *                 @OA\Property(property="ip", type="string", example="192.168.1.1")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vote berhasil dibuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Vote")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
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
            'nilai_vote' => 'required|integer|in:1,2,3',
            'data_pengguna' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $vote = Vote::create([
            'nilai_vote' => $request->nilai_vote,
            'data_pengguna' => $request->data_pengguna
        ]);

        return response()->json([
            'success' => true,
            'data' => $vote
        ], 201);
    }

    /*
     * @OA\Get(
     *     path="/api/votes/{id}",
     *     tags={"Votes"},
     *     summary="Mendapatkan detail vote",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID vote",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Vote")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vote tidak ditemukan"
     *     )
     * )
     */
    public function show($id)
    {
        $vote = Vote::find($id);
        if (!$vote) {
            return response()->json([
                'success' => false,
                'message' => 'Vote not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $vote
        ]);
    }

    /*
     * @OA\Delete(
     *     path="/api/votes/{id}",
     *     tags={"Votes"},
     *     summary="Menghapus vote",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID vote",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vote berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Vote deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vote tidak ditemukan"
     *     )
     * )
     */
    public function destroy($id)
    {
        $vote = Vote::find($id);
        if (!$vote) {
            return response()->json([
                'success' => false,
                'message' => 'Vote not found'
            ], 404);
        }

        $vote->delete();
        return response()->json([
            'success' => true,
            'message' => 'Vote deleted successfully'
        ]);
    }
} 