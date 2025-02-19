<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgendaController extends Controller
{
    public function index()
    {
        $agenda = Agenda::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $agenda
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $agenda = Agenda::create($request->all());
        return response()->json([
            'success' => true,
            'data' => $agenda
        ], 201);
    }

    public function show($id)
    {
        $agenda = Agenda::find($id);
        if (!$agenda) {
            return response()->json([
                'success' => false,
                'message' => 'Agenda not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $agenda
        ]);
    }

    public function update(Request $request, $id)
    {
        $agenda = Agenda::find($id);
        if (!$agenda) {
            return response()->json([
                'success' => false,
                'message' => 'Agenda not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $agenda->update($request->all());
        return response()->json([
            'success' => true,
            'data' => $agenda
        ]);
    }

    public function destroy($id)
    {
        $agenda = Agenda::find($id);
        if (!$agenda) {
            return response()->json([
                'success' => false,
                'message' => 'Agenda not found'
            ], 404);
        }

        $agenda->delete();
        return response()->json([
            'success' => true,
            'message' => 'Agenda deleted successfully'
        ]);
    }
} 