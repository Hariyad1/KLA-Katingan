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
use Illuminate\Support\Facades\Auth;

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
        $validated = $request->validate([
            'opd_id' => 'required|exists:opds,id',
            'indikator_id' => 'required|exists:indikators,id',
            'files' => 'required|array',
            'files.*' => 'required|file|max:25600|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
            'description' => 'nullable|string'
        ]);

        $data = array_merge($validated, [
            'created_by' => Auth::id() ?? 1
        ]);

        $dataDukung = DataDukung::create([
            'opd_id' => $data['opd_id'],
            'indikator_id' => $data['indikator_id'],
            'description' => $data['description'] ?? null,
            'created_by' => $data['created_by']
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
                    Log::error('Error uploading file: ' . $e->getMessage());
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
            'files.*' => 'nullable|file|max:25600|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
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

    public function all(Request $request)
    {
        $query = DataDukung::with(['opd', 'indikator.klaster', 'files'])
            ->orderBy('created_at', 'desc');

        if ($request->has('opd') && $request->opd) {
            $query->where('opd_id', $request->opd);
        }

        if ($request->has('klaster') && $request->klaster) {
            $query->whereHas('indikator', function($q) use ($request) {
                $q->where('klaster_id', $request->klaster);
            });
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('opd', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('indikator', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('klaster', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                })
                ->orWhereHas('files', function($q) use ($search) {
                    $q->where('original_name', 'like', "%{$search}%");
                });
            });
        }

        $dataDukungs = $query->paginate(10);
        $opds = Opd::orderBy('name')->get();
        $klasters = Klaster::orderBy('name')->get();

        return view('user.data-dukung.all', compact('dataDukungs', 'opds', 'klasters'));
    }
} 