<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Opd;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramKerjaController extends Controller
{    public function index()
    {
        $programKerjas = ProgramKerja::with('opd')
            ->latest()
            ->paginate(10);
        
        $totalPrograms = ProgramKerja::count();
        $totalOpds = ProgramKerja::distinct('opd_id')->count();
        $currentYear = date('Y');
        $programsThisYear = ProgramKerja::where('tahun', $currentYear)->count();
        
        return view('admin.program-kerja.index', compact(
            'programKerjas', 
            'totalPrograms', 
            'totalOpds', 
            'programsThisYear'
        ));
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
    }    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'opd_id' => 'required|exists:opds,id',
            'description' => 'required|string|min:10',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
        ], [
            'opd_id.required' => 'Perangkat Daerah wajib dipilih.',
            'opd_id.exists' => 'Perangkat Daerah yang dipilih tidak valid.',
            'description.required' => 'Deskripsi program kerja wajib diisi.',
            'description.min' => 'Deskripsi program kerja minimal 10 karakter.',
            'tahun.required' => 'Tahun wajib dipilih.',
            'tahun.min' => 'Tahun tidak boleh kurang dari 2000.',
            'tahun.max' => 'Tahun tidak boleh lebih dari ' . (date('Y') + 5) . '.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi. Silakan periksa kembali data yang dimasukkan.');
        }

        try {
            // Check for duplicate program kerja
            $existingProgram = ProgramKerja::where('opd_id', $request->opd_id)
                ->where('tahun', $request->tahun)
                ->first();

            if ($existingProgram) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Program kerja untuk OPD ini pada tahun yang sama sudah ada.');
            }

            ProgramKerja::create([
                'opd_id' => $request->opd_id,
                'description' => trim($request->description),
                'tahun' => $request->tahun,
            ]);

            return redirect()->route('admin.program-kerja.index')
                ->with('success', 'Program Kerja berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
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
    }    public function update(Request $request, ProgramKerja $programKerja)
    {
        $validator = Validator::make($request->all(), [
            'opd_id' => 'required|exists:opds,id',
            'description' => 'required|string|min:10',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
        ], [
            'opd_id.required' => 'Perangkat Daerah wajib dipilih.',
            'opd_id.exists' => 'Perangkat Daerah yang dipilih tidak valid.',
            'description.required' => 'Deskripsi program kerja wajib diisi.',
            'description.min' => 'Deskripsi program kerja minimal 10 karakter.',
            'tahun.required' => 'Tahun wajib dipilih.',
            'tahun.min' => 'Tahun tidak boleh kurang dari 2000.',
            'tahun.max' => 'Tahun tidak boleh lebih dari ' . (date('Y') + 5) . '.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi. Silakan periksa kembali data yang dimasukkan.');
        }

        try {
            // Check for duplicate program kerja (excluding current record)
            $existingProgram = ProgramKerja::where('opd_id', $request->opd_id)
                ->where('tahun', $request->tahun)
                ->where('id', '!=', $programKerja->id)
                ->first();

            if ($existingProgram) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Program kerja untuk OPD ini pada tahun yang sama sudah ada.');
            }

            $programKerja->update([
                'opd_id' => $request->opd_id,
                'description' => trim($request->description),
                'tahun' => $request->tahun,
            ]);

            return redirect()->route('admin.program-kerja.index')
                ->with('success', 'Program Kerja berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
        }
    }    public function destroy(ProgramKerja $programKerja)
    {
        try {
            $programKerja->delete();
            
            return redirect()->route('admin.program-kerja.index')
                ->with('success', 'Program Kerja berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.program-kerja.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
        }
    }
} 