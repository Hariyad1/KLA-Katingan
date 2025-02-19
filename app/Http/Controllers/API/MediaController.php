<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $media
        ]);
    }

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
            $fileName = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/media', $fileName);

            $media = Media::create([
                'name' => $request->name,
                'file' => $fileName,
                'path' => Storage::url($path),
                'slide_show' => $request->slide_show ?? 0,
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
            'file' => 'nullable|file|max:10240', // max 10MB
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
                'slide_show' => $request->slide_show ?? $media->slide_show
            ]);
        } else {
            $media->update([
                'name' => $request->name,
                'slide_show' => $request->slide_show ?? $media->slide_show
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $media
        ]);
    }

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

    public function getSlideshow()
    {
        $slideshow = Media::where('slide_show', 1)->get();
        return response()->json([
            'success' => true,
            'data' => $slideshow
        ]);
    }
} 