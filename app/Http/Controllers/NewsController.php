<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Agenda;

class NewsController extends Controller
{
    public function latest()
    {
        $news = News::with(['kategori', 'creator'])
                    ->latest()
                    ->take(5)  // Ambil 5 berita terbaru
                    ->get();

        $agenda = Agenda::latest()->get(); // Fetch agenda items

        return view('news.latest', compact('news', 'agenda'));
    }

    public function show($id)
    {
        $news = News::with(['kategori', 'creator'])
                    ->findOrFail($id);
                    
        // Increment counter
        $news->increment('counter');

        return view('news.show', compact('news'));
    }
}