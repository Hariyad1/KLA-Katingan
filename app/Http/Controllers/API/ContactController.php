<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="Contact",
 *     required={"nama", "email", "subjek", "isi"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nama", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="subjek", type="string", example="Pertanyaan"),
 *     @OA\Property(property="isi", type="string", example="Isi pesan kontak"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Tag(
 *     name="Contact",
 *     description="API Endpoints untuk manajemen kontak"
 * )
 */
class ContactController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/contact",
     *     tags={"Contact"},
     *     summary="Mendapatkan daftar kontak",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Contact")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $contacts = Contact::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $contacts
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/contact",
     *     tags={"Contact"},
     *     summary="Mengirimkan pesan kontak",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama","email","subjek","isi"},
     *             @OA\Property(property="nama", type="string", maxLength=50),
     *             @OA\Property(property="email", type="string", format="email", maxLength=50),
     *             @OA\Property(property="subjek", type="string", maxLength=100),
     *             @OA\Property(property="isi", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Contact created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Contact")
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
     *                 @OA\Property(property="nama", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="subjek", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="isi", type="array", @OA\Items(type="string"))
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:50',
            'email' => 'required|email|max:50',
            'subjek' => 'required|string|max:100',
            'isi' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $contact = Contact::create($request->all());
        return response()->json([
            'success' => true,
            'data' => $contact
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/contact/{id}",
     *     tags={"Contact"},
     *     summary="Mendapatkan detail kontak",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID kontak",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Contact")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Contact not found"
     *     )
     * )
     */
    public function show($id)
    {
        $contact = Contact::find($id);
        if (!$contact) {
            return response()->json([
                'success' => false,
                'message' => 'Contact not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $contact
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/contact/{id}",
     *     tags={"Contact"},
     *     summary="Menghapus kontak",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID kontak",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contact deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Contact deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Contact not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);
        if (!$contact) {
            return response()->json([
                'success' => false,
                'message' => 'Contact not found'
            ], 404);
        }

        $contact->delete();
        return response()->json([
            'success' => true,
            'message' => 'Contact deleted successfully'
        ]);
    }
} 