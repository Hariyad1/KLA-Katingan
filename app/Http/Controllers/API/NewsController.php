<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * @OA\Tag(
 *     name="News",
 *     description="API Endpoints untuk manajemen berita"
 * )
 */
class NewsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/news",
     *     tags={"News"},
     *     summary="Mendapatkan daftar berita",
     *     description="Menampilkan daftar semua berita dengan kategori dan pembuat",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="kategori_id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Judul Berita"),
     *                     @OA\Property(property="content", type="string", example="Isi berita..."),
     *                     @OA\Property(property="image", type="string", nullable=true),
     *                     @OA\Property(property="created_by", type="integer", example=1),
     *                     @OA\Property(property="counter", type="integer", example=0),
     *                     @OA\Property(property="flag", type="string", example="kegiatan"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *                     @OA\Property(
     *                         property="kategori",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string")
     *                     ),
     *                     @OA\Property(
     *                         property="creator",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $news = News::with(['kategori', 'creator'])
                    ->latest()
                    ->get();
        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/news",
     *     tags={"News"},
     *     summary="Membuat berita baru",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"kategori_id","title","content"},
     *             @OA\Property(property="kategori_id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Judul Berita"),
     *             @OA\Property(property="content", type="string", example="Isi berita..."),
     *             @OA\Property(property="image", type="string", format="binary"),
     *             @OA\Property(property="flag", type="string", example="kegiatan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Berita berhasil dibuat"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_id' => 'required|exists:kategori,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048', // max 2MB
            'flag' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/news', $fileName);
            $imagePath = Storage::url($path);
        }

        $news = News::create([
            'kategori_id' => $request->kategori_id,
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'created_by' => Auth::id(),
            'counter' => 0,
            'flag' => $request->flag ?? 'kegiatan'
        ]);

        return response()->json([
            'success' => true,
            'data' => $news->load(['kategori', 'creator'])
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/news/{id}",
     *     tags={"News"},
     *     summary="Mendapatkan detail berita",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID berita",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function show($id)
    {
        $news = News::with(['kategori', 'creator'])->find($id);
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News not found'
            ], 404);
        }

        // Increment counter
        $news->increment('counter');

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/news/{id}",
     *     tags={"News"},
     *     summary="Mengupdate berita",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"kategori_id","title","content"},
     *             @OA\Property(property="kategori_id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="image", type="string", format="binary"),
     *             @OA\Property(property="flag", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berita berhasil diupdate"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $news = News::find($id);
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'kategori_id' => 'required|exists:kategori,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'flag' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('image')) {
            // Delete old image
            if ($news->image) {
                Storage::delete(str_replace('/storage', 'public', $news->image));
            }

            // Upload new image
            $file = $request->file('image');
            $fileName = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/news', $fileName);
            $news->image = Storage::url($path);
        }

        $news->update([
            'kategori_id' => $request->kategori_id,
            'title' => $request->title,
            'content' => $request->content,
            'flag' => $request->flag ?? $news->flag
        ]);

        return response()->json([
            'success' => true,
            'data' => $news->load(['kategori', 'creator'])
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/news/{id}",
     *     tags={"News"},
     *     summary="Menghapus berita",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berita berhasil dihapus"
     *     )
     * )
     */
    public function destroy($id)
    {
        $news = News::find($id);
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News not found'
            ], 404);
        }

        // Delete image if exists
        if ($news->image) {
            Storage::delete(str_replace('/storage', 'public', $news->image));
        }

        $news->delete();
        return response()->json([
            'success' => true,
            'message' => 'News deleted successfully'
        ]);
    }
}