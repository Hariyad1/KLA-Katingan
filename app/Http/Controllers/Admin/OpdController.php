<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Opd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OpdController extends Controller
{
    public function index()
    {
        $opds = Opd::latest()->get();
        return view('admin.opd.index', compact('opds'));
    }

    public function create()
    {
        return view('admin.opd.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:opds'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Opd::create([
            'name' => $request->name
        ]);

        return redirect()->route('admin.opd.index')
            ->with('success', 'OPD berhasil ditambahkan');
    }

    public function edit(Opd $opd)
    {
        return view('admin.opd.edit', compact('opd'));
    }

    public function update(Request $request, Opd $opd)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:opds,name,' . $opd->id
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $opd->update([
            'name' => $request->name
        ]);

        return redirect()->route('admin.opd.index')
            ->with('success', 'OPD berhasil diperbarui');
    }

    public function destroy(Opd $opd)
    {
        if ($opd->dataDukung()->count() > 0) {
            return redirect()->route('admin.opd.index')
                ->with('error', 'OPD tidak dapat dihapus karena masih memiliki data dukung terkait.');
        }
        
        $opd->delete();
        return redirect()->route('admin.opd.index')
            ->with('success', 'OPD berhasil dihapus');
    }
} 