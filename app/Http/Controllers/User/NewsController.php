<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::with(['kategori', 'creator'])
            ->where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('user.berita.index', compact('news'));
    }

    /**
     * Display a listing of all news for viewing.
     */
    public function allNews()
    {
        return view('user.berita.all');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Kategori::all();
        return view('user.berita.create', compact('categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        if ($news->created_by !== Auth::id()) {
            abort(403);
        }

        $categories = Kategori::all();
        return view('user.berita.edit', compact('news', 'categories'));
    }
} 