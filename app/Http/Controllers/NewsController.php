<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Agenda;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function latest()
    {
        $news = News::with(['kategori', 'creator'])
                    ->latest()
                    ->take(3)
                    ->get();

        $agenda = Agenda::latest()->get();

        return view('news.latest', compact('news', 'agenda'));
    }

    public function show($title)
    {
        $news = News::with(['kategori', 'creator'])
                    ->where('status', 1)
                    ->get()
                    ->first(function($item) use ($title) {
                        return Str::slug($item->title) === $title;
                    });

        if (!$news) {
            abort(404);
        }
                
        $news->increment('counter');

        return view('news.show', compact('news'));
    }
}