<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DataDukung;
use App\Models\DataDukungFile;  
use App\Models\Opd;
use App\Models\Indikator;
use App\Models\Klaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DataDukungController extends Controller
{
    public function index()
    {
        $dataDukungs = DataDukung::with(['opd', 'indikator.klaster', 'files'])
            ->where('created_by', Auth::id())
            ->latest()
            ->get();
        return view('user.data-dukung.index', compact('dataDukungs'));
    }

    public function create()
    {
        $opds = Opd::all();
        $klasters = Klaster::all();
        $indikators = Indikator::all();
        return view('user.data-dukung.create', compact('opds', 'klasters', 'indikators'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'opd_id' => 'required|exists:opds,id',
            'klaster_id' => 'required|exists:klasters,id',
            'indikator_id' => 'required|exists:indikators,id',
            'description' => 'nullable|string',
            'files' => 'required|array',
            'files.*' => 'required|file|max:51200|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png'
        ]);

        $dataDukung = DataDukung::create([
            'opd_id' => $request->opd_id,
            'indikator_id' => $request->indikator_id,
            'description' => $request->description,
            'created_by' => Auth::id()
        ]);

        $uploadedFiles = [];
        
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

        return redirect()->route('user.data-dukung.index')
            ->with('success', $message);
    }

    public function edit(DataDukung $dataDukung)
    {
        if ($dataDukung->created_by !== Auth::id()) {
            abort(403);
        }

        $opds = Opd::all();
        $klasters = Klaster::all();
        $indikators = Indikator::with('klaster')->get();
        return view('user.data-dukung.edit', compact('dataDukung', 'opds', 'klasters', 'indikators'));
    }

    public function update(Request $request, DataDukung $dataDukung)
    {
        if ($dataDukung->created_by !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'opd_id' => 'required|exists:opds,id',
            'klaster_id' => 'required|exists:klasters,id',
            'indikator_id' => [
                'required',
                'exists:indikators,id',
                function ($attribute, $value, $fail) use ($request) {
                    $indikator = Indikator::find($value);
                    if ($indikator && $indikator->klaster_id != $request->klaster_id) {
                        $fail('Indikator yang dipilih tidak sesuai dengan klaster.');
                    }
                },
            ],
            'description' => 'nullable|string',
            'files.*' => 'nullable|file|max:51200|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png'
        ]);

        try {
            DB::beginTransaction();

            $dataDukung->update([
                'opd_id' => $validated['opd_id'],
                'indikator_id' => $validated['indikator_id'],
                'description' => $validated['description']
            ]);

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('data-dukung-files', 'public');
                    
                    DataDukungFile::create([
                        'data_dukung_id' => $dataDukung->id,
                        'file' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize()
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('user.data-dukung.index')
                ->with('success', 'Data dukung berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(DataDukung $dataDukung)
    {
        if ($dataDukung->created_by !== Auth::id()) {
            abort(403);
        }

        foreach ($dataDukung->files as $file) {
            Storage::delete($file->file);
            $file->delete();
        }

        $dataDukung->delete();

        return redirect()->route('user.data-dukung.index')
            ->with('success', 'Data dukung berhasil dihapus');
    }

    public function destroyFile(DataDukungFile $file)
    {
        if ($file->dataDukung->created_by !== Auth::id()) {
            abort(403);
        }

        Storage::delete($file->file);
        $file->delete();

        return back()->with('success', 'File berhasil dihapus');
    }

    public function list(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        
        try {
            $query = DataDukung::with(['opd', 'indikator.klaster', 'files'])
                ->where('created_by', Auth::id())
                ->latest();
            
            if ($request->has('search') && !empty(trim($request->input('search')))) {
                $search = trim($request->input('search'));
                $query->where(function($q) use ($search) {
                    $q->whereHas('opd', function($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
                    
                    $q->orWhereHas('indikator', function($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
                    
                    $q->orWhereHas('indikator.klaster', function($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
                    
                    $q->orWhereHas('files', function($q) use ($search) {
                        $q->where('original_name', 'like', '%' . $search . '%');
                    });
                    
                    $q->orWhere('description', 'like', '%' . $search . '%');
                });
            }
            
            $dataDukung = $query->paginate($perPage);
            
            return response()->json([
                'data' => $dataDukung
            ]);
        } catch (\Exception $e) {
            Log::error('Error dalam endpoint list data dukung: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage()
            ], 500);
        }
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
        $opds = OPD::orderBy('name')->get();
        $klasters = Klaster::orderBy('name')->get();

        return view('user.data-dukung.all', compact('dataDukungs', 'opds', 'klasters'));
    }
} 