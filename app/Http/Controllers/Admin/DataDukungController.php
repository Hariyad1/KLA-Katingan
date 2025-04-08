<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataDukung;
use App\Models\DataDukungFile;
use App\Models\Opd;
use App\Models\Klaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DataDukungController extends Controller
{
    public function index()
    {
        $dataDukungs = DataDukung::with(['opd', 'indikator.klaster', 'files'])->get();
        return view('admin.data-dukung.index', compact('dataDukungs'));
    }

    public function create()
    {
        $opds = Opd::all();
        $klasters = Klaster::all();
        return view('admin.data-dukung.create', compact('opds', 'klasters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'opd_id' => 'required|exists:opds,id',
            'indikator_id' => 'required|exists:indikators,id',
            'files' => 'required|array',
            'files.*' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
            'description' => 'nullable|string'
        ]);

        $dataDukung = DataDukung::create([
            'opd_id' => $request->opd_id,
            'indikator_id' => $request->indikator_id,
            'description' => $request->description,
            'created_by' => auth()->id()
        ]);

        $uploadedFiles = [];
        
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                try {
                    $path = $file->store('data-dukung-files', 'public');
                    
                    $dataDukungFile = DataDukungFile::create([
                        'data_dukung_id' => $dataDukung->id,
                        'file' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize()
                    ]);
                    
                    $uploadedFiles[] = $file->getClientOriginalName();
                    
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        $message = 'Data dukung berhasil ditambahkan';
        if (count($uploadedFiles) > 0) {
            $message .= ' dengan ' . count($uploadedFiles) . ' file: ' . implode(', ', $uploadedFiles);
        }

        return redirect()->route('admin.data-dukung.index')
            ->with('success', $message);
    }

    public function edit(DataDukung $dataDukung)
    {
        $opds = Opd::all();
        $klasters = Klaster::all();
        return view('admin.data-dukung.edit', compact('dataDukung', 'opds', 'klasters'));
    }

    public function update(Request $request, DataDukung $dataDukung)
    {
        $request->validate([
            'opd_id' => 'required|exists:opds,id',
            'indikator_id' => 'required|exists:indikators,id',
            'files.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
            'description' => 'nullable|string'
        ]);

        $dataDukung->update([
            'opd_id' => $request->opd_id,
            'indikator_id' => $request->indikator_id,
            'description' => $request->description
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                try {
                    $path = $file->store('data-dukung-files', 'public');
                    
                    DataDukungFile::create([
                        'data_dukung_id' => $dataDukung->id,
                        'file' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize()
                    ]);
                    
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        return redirect()->route('admin.data-dukung.index')
            ->with('success', 'Data dukung berhasil diperbarui');
    }

    public function destroy(DataDukung $dataDukung)
    {
        foreach ($dataDukung->files as $file) {
            Storage::disk('public')->delete($file->file);
            $file->delete();
        }

        $dataDukung->delete();

        return redirect()->route('admin.data-dukung.index')
            ->with('success', 'Data dukung berhasil dihapus');
    }

    public function approve(DataDukung $dataDukung)
    {
        $dataDukung->update(['status' => 1]);
        return response()->json(['success' => true]);
    }

    public function reject(DataDukung $dataDukung)
    {
        $dataDukung->update(['status' => 2]);
        return response()->json(['success' => true]);
    }

    public function destroyFile(DataDukungFile $file)
    {
        Storage::disk('public')->delete($file->file);
        $file->delete();

        return back()->with('success', 'File berhasil dihapus');
    }
} 