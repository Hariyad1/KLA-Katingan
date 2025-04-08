<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Indikator;
use App\Models\Klaster;
use Illuminate\Http\Request;

class IndikatorController extends Controller
{
    public function index()
    {
        $indikators = Indikator::with('klaster')->get();
        return view('admin.indikator.index', compact('indikators'));
    }

    public function create()
    {
        $klasters = Klaster::all();
        return view('admin.indikator.create', compact('klasters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'klaster_id' => 'required|exists:klasters,id',
            'name' => 'required|string|max:1000',
        ]);

        Indikator::create($request->all());

        return redirect()->route('admin.indikator.index')
            ->with('success', 'Indikator berhasil ditambahkan');
    }

    public function edit(Indikator $indikator)
    {
        $klasters = Klaster::all();
        return view('admin.indikator.edit', compact('indikator', 'klasters'));
    }

    public function update(Request $request, Indikator $indikator)
    {
        $request->validate([
            'klaster_id' => 'required|exists:klasters,id',
            'name' => 'required|string|max:1000',
        ]);

        $indikator->update($request->all());

        return redirect()->route('admin.indikator.index')
            ->with('success', 'Indikator berhasil diperbarui');
    }

    public function destroy(Indikator $indikator)
    {
        $indikator->delete();

        return redirect()->route('admin.indikator.index')
            ->with('success', 'Indikator berhasil dihapus');
    }
} 