<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Media;
use App\Models\News;
use App\Models\Setting;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // Ambil data slider
        $slides = Media::where('slide_show', true)->get();

        // Ambil berita terbaru
        $latestNews = News::with(['kategori', 'creator'])
                        ->latest()
                        ->take(3)
                        ->get();
        
        // Ambil agenda yang akan datang
        $upcomingAgendas = Agenda::where('tanggal', '>=', now()->subDay())
                        ->orderBy('tanggal', 'asc')
                        ->take(3)
                        ->get();

        // Ambil galeri gambar
        $galleries = Media::where(function($query) {
            $query->where('file', 'like', '%.jpg')
                  ->orWhere('file', 'like', '%.jpeg')
                  ->orWhere('file', 'like', '%.png')
                  ->orWhere('file', 'like', '%.gif');
        })->latest()->take(4)->get();
        
        // Ambil dokumen
        $documents = Media::whereRaw("LOWER(file) LIKE '%.pdf' OR LOWER(file) LIKE '%.doc' OR LOWER(file) LIKE '%.docx' OR LOWER(file) LIKE '%.xls' OR LOWER(file) LIKE '%.xlsx'")
                        ->latest()
                        ->take(3)
                        ->get();
        
        // Tambahkan videoSetting
        $videoSetting = Setting::where('type', 'video')
                                ->latest()
                                ->first();
        
        return view('welcome', compact(
            'slides', 
            'latestNews', 
            'upcomingAgendas',
            'galleries',
            'documents', 
            'videoSetting'
        ));
    }
} 