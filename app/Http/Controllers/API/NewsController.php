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
            'image' => 'nullable|image|max:25600',
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

        $status = 0;

        $news = News::create([
            'kategori_id' => $request->kategori_id,
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'created_by' => Auth::id(),
            'counter' => 0,
            'flag' => $request->flag ?? 'kegiatan',
            'status' => $status
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

        $news->increment('counter');

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/news/{id}",
     *     tags={"News"},
     *     summary="Mengupdate berita",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID berita",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","kategori_id","title","content"},
     *                 @OA\Property(property="_method", type="string", enum={"PUT"}, default="PUT"),
     *                 @OA\Property(property="kategori_id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Judul Berita"),
     *                 @OA\Property(property="content", type="string", example="Isi berita..."),
     *                 @OA\Property(property="image", type="string", format="binary"),
     *                 @OA\Property(property="flag", type="string", example="kegiatan")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berita berhasil diupdate",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="kategori_id", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="image", type="string", nullable=true),
     *                 @OA\Property(property="flag", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(
     *                     property="kategori",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string")
     *                 ),
     *                 @OA\Property(
     *                     property="creator",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Berita tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="News not found")
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
            'image' => 'nullable|image|max:25600',
            'flag' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::delete(str_replace('/storage', 'public', $news->image));
            }

            $file = $request->file('image');
            $fileName = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/news', $fileName);
            $news->image = Storage::url($path);
        }

        $status = $news->status;
        if ($news->status == 2) {
            $status = 0;
        }

        $news->update([
            'kategori_id' => $request->kategori_id,
            'title' => $request->title,
            'content' => $request->content,
            'flag' => $request->flag ?? $news->flag,
            'status' => $status
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

        if ($news->image) {
            Storage::delete(str_replace('/storage', 'public', $news->image));
        }

        $news->delete();
        return response()->json([
            'success' => true,
            'message' => 'News deleted successfully'
        ]);
    }

    /*
     * @OA\Put(
     *     path="/api/news/{id}/status",
     *     tags={"News"},
     *     summary="Mengupdate status berita",
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
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"approved", "rejected"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status berita berhasil diupdate"
     *     )
     * )
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $news = News::findOrFail($id);
        $news->status = $request->status;
        $news->save();

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    /*
     * @OA\Post(
     *     path="/api/news/{id}/approve",
     *     tags={"News"},
     *     summary="Menyetujui berita",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berita berhasil disetujui"
     *     )
     * )
     */
    public function approve($id)
    {
        $news = News::find($id);
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News not found'
            ], 404);
        }

        $news->status = 1;
        $news->save();

        return response()->json([
            'success' => true,
            'message' => 'News approved successfully'
        ]);
    }

    /*
     * @OA\Post(
     *     path="/api/news/{id}/reject",
     *     tags={"News"},
     *     summary="Menolak berita",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berita berhasil ditolak"
     *     )
     * )
     */
    public function reject($id)
    {
        $news = News::find($id);
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News not found'
            ], 404);
        }

        $news->status = 2;
        $news->save();

        return response()->json([
            'success' => true,
            'message' => 'News rejected successfully'
        ]);
    }

    /*
     * @OA\Get(
     *     path="/api/user/news",
     *     tags={"News"},
     *     summary="Mendapatkan daftar berita user",
     *     security={{"bearerAuth":{}}},
     *     description="Menampilkan daftar berita yang dibuat oleh user yang sedang login",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/News")
     *             )
     *         )
     *     )
     * )
     */
    public function getUserNews()
    {
        return response()->json([
            'success' => true,
            'data' => News::with(['kategori', 'creator'])
                ->where('created_by', Auth::id())
                ->latest()
                ->get()
        ]);
    }
}