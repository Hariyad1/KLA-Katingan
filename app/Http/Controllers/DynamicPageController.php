<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class DynamicPageController extends Controller
{
    public function show($url)
    {
        $setting = Setting::where('url', $url)->first();
        if (!$setting) {
            dd("Setting not found for URL: " . $url);
            abort(404);
        }

        return view('dynamic.index', compact('setting'));
    }
}
