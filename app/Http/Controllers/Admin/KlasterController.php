<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Klaster;
use Illuminate\Http\Request;

class KlasterController extends Controller
{
    public function index()
    {
        $klasters = Klaster::with('indikators')->get();
        return view('admin.klaster.index', compact('klasters'));
    }

    public function create()
    {
        return view('admin.klaster.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:klasters',
        ]);

        Klaster::create($request->all());

        return redirect()->route('admin.klaster.index')
            ->with('success', 'Klaster berhasil ditambahkan');
    }

    public function edit(Klaster $klaster)
    {
        return view('admin.klaster.edit', compact('klaster'));
    }

    public function update(Request $request, Klaster $klaster)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:klasters,name,' . $klaster->id,
        ]);

        $klaster->update($request->all());

        return redirect()->route('admin.klaster.index')
            ->with('success', 'Klaster berhasil diperbarui');
    }

    public function destroy(Klaster $klaster)
    {
        if ($klaster->indikators->count() > 0) {
            return redirect()->route('admin.klaster.index')
                ->with('error', 'Klaster tidak dapat dihapus karena masih memiliki indikator terkait.');
        }

        $klaster->delete();

        return redirect()->route('admin.klaster.index')
            ->with('success', 'Klaster berhasil dihapus');
    }
} 