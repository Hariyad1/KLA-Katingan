<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsController extends Controller
{
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

    public function getByKategori($kategoriId)
    {
        $news = News::with(['kategori', 'creator'])
                    ->where('kategori_id', $kategoriId)
                    ->latest()
                    ->get();
        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    public function getByFlag($flag)
    {
        $news = News::with(['kategori', 'creator'])
                    ->where('flag', $flag)
                    ->latest()
                    ->get();
        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }
} 