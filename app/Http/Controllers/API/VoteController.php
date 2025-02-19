<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoteController extends Controller
{
    public function index()
    {
        $votes = Vote::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $votes
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nilai_vote' => 'required|integer|in:1,2,3',
            'data_pengguna' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $vote = Vote::create([
            'nilai_vote' => $request->nilai_vote,
            'data_pengguna' => $request->data_pengguna
        ]);

        return response()->json([
            'success' => true,
            'data' => $vote
        ], 201);
    }

    public function show($id)
    {
        $vote = Vote::find($id);
        if (!$vote) {
            return response()->json([
                'success' => false,
                'message' => 'Vote not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $vote
        ]);
    }

    public function update(Request $request, $id)
    {
        $vote = Vote::find($id);
        if (!$vote) {
            return response()->json([
                'success' => false,
                'message' => 'Vote not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nilai_vote' => 'required|integer|in:1,2,3',
            'data_pengguna' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $vote->update($request->all());
        return response()->json([
            'success' => true,
            'data' => $vote
        ]);
    }

    public function destroy($id)
    {
        $vote = Vote::find($id);
        if (!$vote) {
            return response()->json([
                'success' => false,
                'message' => 'Vote not found'
            ], 404);
        }

        $vote->delete();
        return response()->json([
            'success' => true,
            'message' => 'Vote deleted successfully'
        ]);
    }

    public function getStatistics()
    {
        $stats = Vote::getVoteStats();
        $total = array_sum($stats->toArray());

        $response = [
            'total_votes' => $total,
            'vote_distribution' => $stats,
            'percentages' => $stats->map(function ($count) use ($total) {
                return $total > 0 ? round(($count / $total) * 100, 2) : 0;
            })
        ];

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }
} 