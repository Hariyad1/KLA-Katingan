<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'page' => 'required|string|max:100',
            'url' => 'required|string|max:100',
            'image' => 'nullable|image|max:2048', // max 2MB
            'content' => 'nullable|string',
            'type' => 'required|string|max:500'
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

    public function getByType($type)
    {
        $settings = Setting::getByType($type);
        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

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