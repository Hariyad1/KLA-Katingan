<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;

class StatisticController extends Controller
{
    public function index()
    {
        $statistics = Statistic::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    public function store(Request $request)
    {
        $agent = new Agent();
        
        $statistic = Statistic::create([
            'ip' => $request->ip(),
            'os' => $agent->platform(),
            'browser' => $agent->browser()
        ]);

        return response()->json([
            'success' => true,
            'data' => $statistic
        ], 201);
    }

    public function show($id)
    {
        $statistic = Statistic::find($id);
        if (!$statistic) {
            return response()->json([
                'success' => false,
                'message' => 'Statistic not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $statistic
        ]);
    }

    public function update(Request $request, $id)
    {
        $statistic = Statistic::find($id);
        if (!$statistic) {
            return response()->json([
                'success' => false,
                'message' => 'Statistic not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'ip' => 'nullable|string|max:20',
            'os' => 'nullable|string|max:30',
            'browser' => 'nullable|string|max:120'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $statistic->update($request->all());
        return response()->json([
            'success' => true,
            'data' => $statistic
        ]);
    }

    public function destroy($id)
    {
        $statistic = Statistic::find($id);
        if (!$statistic) {
            return response()->json([
                'success' => false,
                'message' => 'Statistic not found'
            ], 404);
        }

        $statistic->delete();
        return response()->json([
            'success' => true,
            'message' => 'Statistic deleted successfully'
        ]);
    }

    public function getVisitorStats(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $stats = [
            'total_visitors' => Statistic::getVisitorCount($startDate, $endDate),
            'browser_stats' => Statistic::getBrowserStats(),
            'os_stats' => Statistic::getOSStats()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
} 