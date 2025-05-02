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
        
        // Query builder dasar
        $query = ProgramKerja::with('opd');
        
        // Jika ada filter tahun, tambahkan ke query
        if ($tahun) {
            $query->where('tahun', $tahun);
        }
        
        // Jika ada filter OPD, tambahkan ke query
        if ($opd_id) {
            $query->where('opd_id', $opd_id);
        }
        
        // Jika ada pencarian, tambahkan ke query dengan optimasi
        if ($search && strlen(trim($search)) >= 2) {
            // Gunakan full text search jika tersedia, atau fallback ke LIKE
            if (config('database.default') === 'mysql') {
                // MySQL/MariaDB: Gunakan FULLTEXT jika column di-index (case-insensitive)
                $query->whereRaw("MATCH(description) AGAINST(? IN BOOLEAN MODE)", [$search . '*']);
            } elseif (config('database.default') === 'pgsql') {
                // PostgreSQL: Gunakan ILIKE untuk case-insensitive
                $query->whereRaw("description ILIKE ?", ['%' . $search . '%']);
            } elseif (config('database.default') === 'sqlite') {
                // SQLite: Gunakan COLLATE NOCASE untuk case-insensitive
                $query->whereRaw("description LIKE ? COLLATE NOCASE", ['%' . $search . '%']);
            } else {
                // Fallback ke basic LIKE search dengan lower pada database lain
                $query->whereRaw("LOWER(description) LIKE ?", ['%' . strtolower($search) . '%']);
            }
        }
        
        // Paginate hasil sesuai kebutuhan (bisa diubah jumlahnya)
        $programKerjas = $query->latest()->paginate(3);
            
        $opds = Opd::all();
        
        // Mendapatkan daftar tahun dari database - hanya tahun yang ada di database
        $tahunList = ProgramKerja::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc') // Urutkan dari tahun terbaru
            ->pluck('tahun')
            ->toArray();
        
        // Jika tidak ada data, tambahkan tahun saat ini
        if (empty($tahunList)) {
            $tahunList = [date('Y')];
        }
        
        // Jika request AJAX, kembalikan hanya sebagian halaman
        if ($request->ajax()) {
            // Gunakan API endpoint untuk konsistensi response
            $apiUrl = route('api.program.index', $request->all());
            return redirect()->to($apiUrl);
        }
        
        return view('profil.program', compact('programKerjas', 'opds', 'tahun', 'tahunList', 'opd_id', 'search'));
    }
    
    public function create()
    {
        $opds = Opd::all();
        
        // Mendapatkan daftar tahun dari database - hanya tahun yang ada di database
        $tahunList = ProgramKerja::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc') // Urutkan dari tahun terbaru
            ->pluck('tahun')
            ->toArray();
        
        // Jika tidak ada data, tambahkan tahun saat ini
        if (empty($tahunList)) {
            $tahunList = [date('Y')];
        }
        
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
        
        // Mendapatkan daftar tahun dari database - hanya tahun yang ada di database
        $tahunList = ProgramKerja::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc') // Urutkan dari tahun terbaru
            ->pluck('tahun')
            ->toArray();
        
        // Jika tidak ada data, tambahkan tahun saat ini
        if (empty($tahunList)) {
            $tahunList = [date('Y')];
        }
        
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