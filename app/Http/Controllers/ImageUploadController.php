<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = Str::slug($fileName) . '_' . time() . '.' . $extension;
        
            $path = $request->file('upload')->storeAs('public/ckeditor-images', $fileName);
            
            $url = Storage::url($path);
            
            Log::info('Upload successful. CKEditor params:', [
                'CKEditor' => $request->input('CKEditor'),
                'CKEditorFuncNum' => $request->input('CKEditorFuncNum'),
                'langCode' => $request->input('langCode')
            ]);
            
            if ($request->has('CKEditorFuncNum')) {
                $funcNum = $request->input('CKEditorFuncNum');
                $response = "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '');</script>";
                return response($response)->header('Content-Type', 'text/html');
            }
            
            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
        }
        
        return response()->json(['uploaded'=> 0, 'error' => ['message' => 'No file was uploaded']]);
    }
} 