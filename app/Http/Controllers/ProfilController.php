<?php

namespace App\Http\Controllers;

use App\Models\Opd;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfilController extends Controller
{
    public function program(Request $request)
    {
        $tahun = $request->tahun;
        $opd_id = $request->opd_id ?? null;
        $search = $request->search ?? null;

        $query = ProgramKerja::with('opd');
        
        if ($tahun) {
            $query->where('tahun', $tahun);
        }
        
        if ($opd_id) {
            $query->where('opd_id', $opd_id);
        }
        
        if ($search && strlen(trim($search)) >= 2) {
            if (config('database.default') === 'mysql') {
                $query->whereRaw("LOWER(description) LIKE ?", ['%' . strtolower($search) . '%']);
            } elseif (config('database.default') === 'pgsql') {
                $query->whereRaw("description ILIKE ?", ['%' . $search . '%']);
            } elseif (config('database.default') === 'sqlite') {
                $query->whereRaw("description LIKE ? COLLATE NOCASE", ['%' . $search . '%']);
            } else {
                $query->whereRaw("LOWER(description) LIKE ?", ['%' . strtolower($search) . '%']);
            }
        }
        
        $programKerjas = $query->latest()->paginate(3);
            
        $opds = Opd::all();
        
        $tahunList = ProgramKerja::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();
        
        if (empty($tahunList)) {
            $tahunList = [date('Y')];
        }
        
        if ($request->ajax()) {
            $apiUrl = route('api.program.index', $request->all());
            return redirect()->to($apiUrl);
        }
        
        return view('profil.program', compact('programKerjas', 'opds', 'tahun', 'tahunList', 'opd_id', 'search'));
    }
    
    public function create()
    {
        $opds = Opd::all();
        
        $tahunList = ProgramKerja::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();
        
        $currentYear = (int)date('Y');
        $previousYear = $currentYear - 1;
        $nextYear = $currentYear + 1;
        
        if (!in_array($currentYear, $tahunList)) {
            $tahunList[] = $currentYear;
        }
        
        if (!in_array($previousYear, $tahunList)) {
            $tahunList[] = $previousYear;
        }
        
        if (!in_array($nextYear, $tahunList)) {
            $tahunList[] = $nextYear;
        }
        
        sort($tahunList, SORT_NUMERIC);
        
        return view('profil.program-form', compact('opds', 'tahunList'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'opd_id' => 'required|exists:opds,id',
            'description' => 'required|string',
            'tahun' => 'required|integer|min:2000',
        ], [
            'opd_id.required' => 'Silakan pilih OPD',
            'description.required' => 'Deskripsi program kerja wajib diisi',
            'tahun.required' => 'Tahun wajib dipilih',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        ProgramKerja::create($request->all());

        return redirect()->route('profil.program')
            ->with('success', 'Program Kerja berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        $programKerja = ProgramKerja::findOrFail($id);
        $opds = Opd::all();
        
        $tahunList = ProgramKerja::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();
        
        $currentYear = (int)date('Y');
        $previousYear = $currentYear - 1;
        $nextYear = $currentYear + 1;
        
        if (!in_array($programKerja->tahun, $tahunList)) {
            $tahunList[] = $programKerja->tahun;
        }
        
        if (!in_array($currentYear, $tahunList)) {
            $tahunList[] = $currentYear;
        }
        
        if (!in_array($previousYear, $tahunList)) {
            $tahunList[] = $previousYear;
        }
        
        if (!in_array($nextYear, $tahunList)) {
            $tahunList[] = $nextYear;
        }
        
        sort($tahunList, SORT_NUMERIC);
        
        return view('profil.program-edit', compact('programKerja', 'opds', 'tahunList'));
    }
    
    public function update(Request $request, $id)
    {
        $programKerja = ProgramKerja::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'opd_id' => 'required|exists:opds,id',
            'description' => 'required|string',
            'tahun' => 'required|integer|min:2000',
        ], [
            'opd_id.required' => 'Silakan pilih OPD',
            'description.required' => 'Deskripsi program kerja wajib diisi',
            'tahun.required' => 'Tahun wajib dipilih',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $programKerja->update($request->all());

        return redirect()->route('profil.program')
            ->with('success', 'Program Kerja berhasil diperbarui');
    }
    
    public function destroy($id)
    {
        $programKerja = ProgramKerja::findOrFail($id);
        $programKerja->delete();
        
        return redirect()->route('profil.program')
            ->with('success', 'Program Kerja berhasil dihapus.');
    }
} 