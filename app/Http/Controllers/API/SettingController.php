<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Schema(
 *     schema="Setting",
 *     required={"name", "page", "url", "type"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Nama Setting"),
 *     @OA\Property(property="page", type="string", example="home"),
 *     @OA\Property(property="url", type="string", example="https://example.com"),
 *     @OA\Property(property="image", type="string", nullable=true, example="/storage/settings/image.jpg"),
 *     @OA\Property(property="content", type="string", nullable=true),
 *     @OA\Property(property="type", type="string", example="link"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Tag(
 *     name="Settings",
 *     description="API Endpoints untuk manajemen pengaturan"
 * )
 */
class SettingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/setting",
     *     tags={"Settings"},
     *     summary="Mendapatkan daftar pengaturan",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Setting")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $settings = Setting::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/setting",
     *     tags={"Settings"},
     *     summary="Membuat pengaturan baru",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name","page","url","type"},
     *                 @OA\Property(property="name", type="string", example="Nama Setting"),
     *                 @OA\Property(property="page", type="string", example="home"),
     *                 @OA\Property(property="url", type="string", example="https://example.com"),
     *                 @OA\Property(property="image", type="string", format="binary"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="type", type="string", example="link")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Setting berhasil dibuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Setting")
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
            'name' => 'required|string|max:100',
            'page' => 'required|string|max:100',
            'url' => 'required|string|max:100',
            'image' => 'nullable|image|max:2048',
            'content' => 'nullable|string',
            'type' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Log data yang diterima
        Log::info('Data setting yang akan disimpan:', $request->all());

        if ($request->type === 'statis') {
            $url = trim($request->url, '/');
            Log::info('URL yang akan disimpan:', ['url' => $url]);
            $request->merge(['url' => $url]);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/settings', $fileName);
            $imagePath = Storage::url($path);
        }

        $setting = Setting::create([
            'name' => $request->name,
            'page' => $request->page,
            'url' => $request->url,
            'image' => $imagePath,
            'content' => $request->content,
            'type' => $request->type
        ]);

        return response()->json([
            'success' => true,
            'data' => $setting
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/setting/{id}",
     *     tags={"Settings"},
     *     summary="Mendapatkan detail pengaturan",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID pengaturan",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Setting")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Setting tidak ditemukan"
     *     )
     * )
     */
    public function show($id)
    {
        $setting = Setting::find($id);
        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $setting
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/setting/{id}",
     *     tags={"Settings"},
     *     summary="Mengupdate pengaturan",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID pengaturan",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name","page","url","type"},
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="page", type="string"),
     *                 @OA\Property(property="url", type="string"),
     *                 @OA\Property(property="image", type="string", format="binary"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="type", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Setting berhasil diupdate",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Setting")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Setting tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $setting = Setting::find($id);
        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'page' => 'required|string|max:100',
            'url' => 'required|string|max:100',
            'image' => 'nullable|image|max:2048',
            'content' => 'nullable|string',
            'type' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('image')) {
            // Delete old image
            if ($setting->image) {
                Storage::delete(str_replace('/storage', 'public', $setting->image));
            }

            // Upload new image
            $file = $request->file('image');
            $fileName = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/settings', $fileName);
            $setting->image = Storage::url($path);
        }

        $setting->update([
            'name' => $request->name,
            'page' => $request->page,
            'url' => $request->url,
            'content' => $request->content,
            'type' => $request->type
        ]);

        return response()->json([
            'success' => true,
            'data' => $setting
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/setting/{id}",
     *     tags={"Settings"},
     *     summary="Menghapus pengaturan",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID pengaturan",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Setting berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Setting deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Setting tidak ditemukan"
     *     )
     * )
     */
    public function destroy($id)
    {
        $setting = Setting::find($id);
        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found'
            ], 404);
        }

        // Delete image if exists
        if ($setting->image) {
            Storage::delete(str_replace('/storage', 'public', $setting->image));
        }

        $setting->delete();
        return response()->json([
            'success' => true,
            'message' => 'Setting deleted successfully'
        ]);
    }

    /*
     * @OA\Get(
     *     path="/api/setting/type/{type}",
     *     tags={"Settings"},
     *     summary="Mendapatkan pengaturan berdasarkan tipe",
     *     @OA\Parameter(
     *         name="type",
     *         in="path",
     *         required=true,
     *         description="Tipe pengaturan",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Setting")
     *             )
     *         )
     *     )
     * )
     */
    public function getByType($type)
    {
        $settings = Setting::getByType($type);
        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /*
     * @OA\Get(
     *     path="/api/setting/page/{page}",
     *     tags={"Settings"},
     *     summary="Mendapatkan pengaturan berdasarkan halaman",
     *     @OA\Parameter(
     *         name="page",
     *         in="path",
     *         required=true,
     *         description="Nama halaman",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Setting")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Setting tidak ditemukan"
     *     )
     * )
     */
    public function getByPage($page)
    {
        $setting = Setting::getByPage($page);
        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $setting
        ]);
    }
} 