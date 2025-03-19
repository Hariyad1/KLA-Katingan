<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="Agenda",
 *     required={"title", "tanggal", "keterangan"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Rapat Koordinasi"),
 *     @OA\Property(property="tanggal", type="string", format="date", example="2024-03-20"),
 *     @OA\Property(property="keterangan", type="string", example="Rapat koordinasi bulanan"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Tag(
 *     name="Agenda",
 *     description="API Endpoints untuk manajemen agenda"
 * )
 */
class AgendaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/agenda",
     *     tags={"Agenda"},
     *     summary="Mendapatkan daftar semua agenda",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Agenda")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $agenda = Agenda::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $agenda
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/agenda",
     *     tags={"Agenda"},
     *     summary="Membuat agenda baru",
     *     description="Membuat dan menyimpan agenda baru",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Agenda")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Agenda created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/Agenda"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="title", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="tanggal", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="keterangan", type="array", @OA\Items(type="string"))
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $agenda = Agenda::create($request->all());
        return response()->json([
            'success' => true,
            'data' => $agenda
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/agenda/{id}",
     *     tags={"Agenda"},
     *     summary="Mendapatkan detail agenda",
     *     description="Mengembalikan data agenda berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID agenda",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Agenda")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Agenda not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Agenda not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $agenda = Agenda::find($id);
        if (!$agenda) {
            return response()->json([
                'success' => false,
                'message' => 'Agenda not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $agenda
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/agenda/{id}",
     *     tags={"Agenda"},
     *     summary="Memperbarui agenda",
     *     description="Memperbarui data agenda yang sudah ada",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID agenda",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","tanggal","keterangan"},
     *             @OA\Property(property="title", type="string", maxLength=100),
     *             @OA\Property(property="tanggal", type="string", format="date"),
     *             @OA\Property(property="keterangan", type="string", maxLength=100)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Agenda updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Agenda")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(response=404, description="Agenda not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $agenda = Agenda::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:100',
                'tanggal' => 'required|date',
                'keterangan' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $agenda->update($request->all());
            
            return response()->json([
                'success' => true,
                'data' => $agenda,
                'message' => 'Agenda berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui agenda'
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/agenda/{id}",
     *     tags={"Agenda"},
     *     summary="Menghapus agenda",
     *     description="Menghapus agenda berdasarkan ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID agenda",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Agenda deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Agenda deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Agenda not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Agenda not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $agenda = Agenda::findOrFail($id);
            $agenda->delete();

            return response()->json([
                'success' => true,
                'message' => 'Agenda berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus agenda'
            ], 500);
        }
    }
} 