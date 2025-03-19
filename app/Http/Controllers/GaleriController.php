<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    public function index()
    {
        $media = Media::latest()
                      ->paginate(12);

        return view('beranda.galeri', compact('media'));
    }
} 