<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::with('news')->latest()->get();
        return response()->json([
            'success' => true,
            'data' => $kategori
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $kategori = Kategori::create($request->all());
        return response()->json([
            'success' => true,
            'data' => $kategori
        ], 201);
    }

    public function show($id)
    {
        $kategori = Kategori::with('news')->find($id);
        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $kategori
        ]);
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::find($id);
        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $kategori->update($request->all());
        return response()->json([
            'success' => true,
            'data' => $kategori
        ]);
    }

    public function destroy($id)
    {
        $kategori = Kategori::find($id);
        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori not found'
            ], 404);
        }

        // Check if kategori has related news
        if ($kategori->news()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete kategori with related news'
            ], 422);
        }

        $kategori->delete();
        return response()->json([
            'success' => true,
            'message' => 'Kategori deleted successfully'
        ]);
    }
} 