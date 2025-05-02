<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        // Memastikan directory upload ada
        $uploadPath = public_path('images');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = Str::slug($fileName) . '_' . time() . '.' . $extension;
        
            $request->file('upload')->move($uploadPath, $fileName);
   
            $url = asset('images/' . $fileName);
            
            // Log untuk debugging
            Log::info('Upload successful. CKEditor params:', [
                'CKEditor' => $request->input('CKEditor'),
                'CKEditorFuncNum' => $request->input('CKEditorFuncNum'),
                'langCode' => $request->input('langCode')
            ]);
            
            // Jika request berasal dari CKEditor (memiliki parameter CKEditorFuncNum)
            if ($request->has('CKEditorFuncNum')) {
                $funcNum = $request->input('CKEditorFuncNum');
                $response = "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '');</script>";
                return response($response)->header('Content-Type', 'text/html');
            }
            
            // Jika tidak, berikan response JSON standard
            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
        }
        
        return response()->json(['uploaded'=> 0, 'error' => ['message' => 'No file was uploaded']]);
    }
} 