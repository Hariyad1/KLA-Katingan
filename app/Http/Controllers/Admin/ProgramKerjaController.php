<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Opd;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramKerjaController extends Controller
{
    public function index()
    {
        $programKerjas = ProgramKerja::with('opd')->latest()->get();
        return view('admin.program-kerja.index', compact('programKerjas'));
    }

    public function create()
    {
        $opds = Opd::all();
        
        $existingYears = ProgramKerja::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();
            
        $currentYear = (int)date('Y');
        $previousYear = $currentYear - 1;
        $nextYear = $currentYear + 1;
        
        if (!in_array($currentYear, $existingYears)) {
            $existingYears[] = $currentYear;
        }
        
        if (!in_array($previousYear, $existingYears)) {
            $existingYears[] = $previousYear;
        }
        
        if (!in_array($nextYear, $existingYears)) {
            $existingYears[] = $nextYear;
        }
        
        sort($existingYears, SORT_NUMERIC);
        $tahun = $existingYears;
        
        return view('admin.program-kerja.create', compact('opds', 'tahun'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'opd_id' => 'required|exists:opds,id',
            'description' => 'nullable|string',
            'tahun' => 'required|integer|min:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        ProgramKerja::create($request->all());

        return redirect()->route('admin.program-kerja.index')
            ->with('success', 'Program Kerja berhasil ditambahkan');
    }

    public function edit(ProgramKerja $programKerja)
    {
        $opds = Opd::all();
        
        $existingYears = ProgramKerja::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();
            
        if (!in_array($programKerja->tahun, $existingYears)) {
            $existingYears[] = $programKerja->tahun;
        }
        
        $currentYear = (int)date('Y');
        $previousYear = $currentYear - 1;
        $nextYear = $currentYear + 1;
        
        if (!in_array($currentYear, $existingYears)) {
            $existingYears[] = $currentYear;
        }
        
        if (!in_array($previousYear, $existingYears)) {
            $existingYears[] = $previousYear;
        }
        
        if (!in_array($nextYear, $existingYears)) {
            $existingYears[] = $nextYear;
        }
        
        sort($existingYears, SORT_NUMERIC);
        $tahun = $existingYears;
        
        return view('admin.program-kerja.edit', compact('programKerja', 'opds', 'tahun'));
    }

    public function update(Request $request, ProgramKerja $programKerja)
    {
        $validator = Validator::make($request->all(), [
            'opd_id' => 'required|exists:opds,id',
            'description' => 'nullable|string',
            'tahun' => 'required|integer|min:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $programKerja->update($request->all());

        return redirect()->route('admin.program-kerja.index')
            ->with('success', 'Program Kerja berhasil diperbarui');
    }

    public function destroy(ProgramKerja $programKerja)
    {
        $programKerja->delete();
        
        return redirect()->route('admin.program-kerja.index')
            ->with('success', 'Program Kerja berhasil dihapus');
    }
} 