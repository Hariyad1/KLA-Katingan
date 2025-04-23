<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.setting.index');
    }

    public function create()
    {
        return view('admin.setting.create');
    }

    public function edit(Setting $setting)
    {
        return view('admin.setting.edit', compact('setting'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'page' => 'required|string|max:100',
            'url' => 'required|string|max:100',
            'image' => 'nullable|image|max:25600',
            'content' => 'nullable|string',
            'type' => 'required|string|max:500'
        ]);

        // Jika tipe adalah statis, buat slug dari URL
        if ($validated['type'] === 'statis') {
            $validated['url'] = Str::slug($validated['url']);
            
            // Cek apakah URL sudah ada
            if (Setting::where('url', $validated['url'])->exists()) {
                return back()
                    ->withInput()
                    ->withErrors(['url' => 'URL sudah digunakan']);
            }
        }

        // Upload gambar jika ada
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/settings', $fileName);
            $validated['image'] = '/storage/settings/' . $fileName;
        }

        $setting = Setting::create($validated);

        return redirect()
            ->route('admin.setting.index')
            ->with('success', 'Setting berhasil ditambahkan');
    }

    public function indexStatis()
    {
        return view('admin.setting.statis.index');
    }

    public function createStatis()
    {
        return view('admin.setting.statis.create');
    }

    public function editStatis(Setting $setting)
    {
        if ($setting->type !== 'statis') {
            abort(404);
        }
        return view('admin.setting.statis.edit', compact('setting'));
    }

    public function indexVideo()
    {
        return view('admin.setting.video.index');
    }

    public function createVideo()
    {
        return view('admin.setting.video.create');
    }

    public function editVideo(Setting $setting)
    {
        if ($setting->type !== 'video') {
            abort(404);
        }
        return view('admin.setting.video.edit', compact('setting'));
    }
} 