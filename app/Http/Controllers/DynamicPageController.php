<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DynamicPageController extends Controller
{
    public function show($url)
    {
        // Log URL yang diterima
        Log::info('URL yang diakses:', ['url' => $url]);
        
        // Bersihkan URL dari slash di awal dan akhir
        $cleanUrl = trim($url, '/');
        
        // Cari semua setting dengan URL yang sama
        $settings = Setting::where('type', 'statis')
                         ->where(function($query) use ($cleanUrl) {
                             $query->where('url', $cleanUrl)
                                   ->orWhere('url', str_replace('-', '_', $cleanUrl))
                                   ->orWhere('url', str_replace('_', '-', $cleanUrl));
                         })
                         ->orderBy('created_at', 'asc')
                         ->get();
        
        Log::info('Settings yang ditemukan dengan URL yang sama:', ['settings' => $settings->toArray()]);
        
        // Jika tidak ada setting dengan URL yang sama persis, coba cari parent URL
        if ($settings->isEmpty() && str_contains($cleanUrl, '/')) {
            $parentUrl = substr($cleanUrl, 0, strrpos($cleanUrl, '/'));
            $parentSettings = Setting::where('type', 'statis')
                                   ->where(function($query) use ($parentUrl) {
                                       $query->where('url', $parentUrl)
                                             ->orWhere('url', str_replace('-', '_', $parentUrl))
                                             ->orWhere('url', str_replace('_', '-', $parentUrl));
                                   })
                                   ->orderBy('created_at', 'asc')
                                   ->get();
            
            Log::info('Parent settings yang ditemukan:', ['parent_settings' => $parentSettings->toArray()]);
            
            // Gabungkan parent settings dengan settings yang ada
            $settings = $parentSettings->concat($settings);
        }
        
        if ($settings->isEmpty()) {
            return response()->view('errors.404', [], 404);
        }

        // Gabungkan konten dari semua setting
        $combinedContent = $settings->map(function($setting) {
            return [
                'name' => $setting->name,
                'content' => $setting->content,
                'image' => $setting->image
            ];
        })->toArray();

        // Sebelum return view, tambahkan log untuk debugging
        Log::info('Data yang dikirim ke view:', [
            'setting' => $settings->first(),
            'allSettings' => $combinedContent,
            'url' => $cleanUrl
        ]);

        // Kirim data ke view
        return view('dynamic.index', [
            'setting' => $settings->first(),
            'allSettings' => $combinedContent
        ]);
    }
}
