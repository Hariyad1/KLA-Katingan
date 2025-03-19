<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * @OA\Schema(
 *     schema="Media",
 *     required={"name", "file"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Foto Kegiatan"),
 *     @OA\Property(property="file", type="string", example="1234567890_foto-kegiatan.jpg"),
 *     @OA\Property(property="path", type="string", example="/storage/media/1234567890_foto-kegiatan.jpg"),
 *     @OA\Property(property="slide_show", type="boolean", example=true),
 *     @OA\Property(property="hits", type="integer", example=0),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Tag(
 *     name="Media",
 *     description="API Endpoints untuk manajemen media"
 * )
 */
class MediaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/media",
     *     tags={"Media"},
     *     summary="Mendapatkan daftar media",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Media")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $media = Media::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $media
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/media",
     *     tags={"Media"},
     *     summary="Mengunggah media baru",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "file"},
     *                 @OA\Property(property="name", type="string", example="Foto Kegiatan"),
     *                 @OA\Property(property="file", type="string", format="binary"),
     *                 @OA\Property(property="slide_show", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Media berhasil diunggah",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Media")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'file' => 'required|file|max:10240', // max 10MB
            'slide_show' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $mimeType = $file->getMimeType();
            
            // Check if file is image or document
            $isImage = strpos($mimeType, 'image/') === 0;
            $isDocument = in_array($mimeType, [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ]);

            if (!$isImage && !$isDocument) {
                return response()->json([
                    'success' => false,
                    'message' => 'File type not allowed'
                ], 422);
            }

            $fileName = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/media', $fileName);

            $media = Media::create([
                'name' => $request->name,
                'file' => $fileName,
                'path' => Storage::url($path),
                'slide_show' => $isImage ? ($request->slide_show ?? 0) : 0,
                'hits' => 0
            ]);

            return response()->json([
                'success' => true,
                'data' => $media
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'File upload failed'
        ], 400);
    }

    /**
     * @OA\Get(
     *     path="/api/media/{id}",
     *     tags={"Media"},
     *     summary="Mendapatkan detail media",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID media",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Media")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Media tidak ditemukan"
     *     )
     * )
     */
    public function show($id)
    {
        $media = Media::find($id);
        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => 'Media not found'
            ], 404);
        }

        // Increment hits counter
        $media->increment('hits');

        return response()->json([
            'success' => true,
            'data' => $media
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/media/{id}",
     *     tags={"Media"},
     *     summary="Mengupdate media",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID media",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="file", type="string", format="binary"),
     *                 @OA\Property(property="slide_show", type="boolean")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Media berhasil diupdate",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Media")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Media tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $media = Media::find($id);
        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => 'Media not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'file' => 'nullable|file|max:10240',
            'slide_show' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle file update if new file is uploaded
        if ($request->hasFile('file')) {
            // Delete old file
            Storage::delete('public/media/' . $media->file);

            // Upload new file
            $file = $request->file('file');
            $fileName = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/media', $fileName);

            $media->update([
                'name' => $request->name,
                'file' => $fileName,
                'path' => Storage::url($path),
                'slide_show' => $request->boolean('slide_show')
            ]);
        } else {
            $media->update([
                'name' => $request->name,
                'slide_show' => $request->boolean('slide_show')
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $media->fresh()  // Refresh data dari database
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/media/{id}",
     *     tags={"Media"},
     *     summary="Menghapus media",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID media",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Media berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Media deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Media tidak ditemukan"
     *     )
     * )
     */
    public function destroy($id)
    {
        $media = Media::find($id);
        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => 'Media not found'
            ], 404);
        }

        // Delete file from storage
        Storage::delete('public/media/' . $media->file);

        $media->delete();
        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/media/slideshow",
     *     tags={"Media"},
     *     summary="Mendapatkan daftar media slideshow",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Media")
     *             )
     *         )
     *     )
     * )
     */
    public function getSlideshow()
    {
        $slideshow = Media::where('slide_show', 1)->get();
        return response()->json([
            'success' => true,
            'data' => $slideshow
        ]);
    }
} 