<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Igame;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getIgameCountByF20a()
    {
        // Define all possible handicap values
        $handicaps = [
            0, 0.25, 0.5, 0.75,
            1, 1.25, 1.5, 1.75,
            2, 2.25, 2.5, 2.75,
            3, 3.25, 3.5, 3.75,
            4, 4.25, 4.5, 4.75,
            5, 5.25, 5.5, 5.75,
            6, 6.25, 6.5, 6.75,
            7, 7.25, 7.5, 7.75,
            8, 8.25, 8.5, 8.75,
            9
        ];

        // Get actual counts from database
        $counts = Igame::select(DB::raw('ABS(f20a) as f20a'), DB::raw('count(*) as count'))
            ->whereNotNull('f20a')
            ->where('f20a', '!=', '')
            ->groupBy(DB::raw('ABS(f20a)'))
            ->get()
            ->keyBy('f20a')
            ->toArray();

        return $counts;

        // Create final result with all handicap values
        $result = [];
        foreach ($handicaps as $handicap) {
            $result[] = [
                'f20a' => $handicap,
                'count' => $counts[$handicap]['count'] ?? 0
            ];
        }

        return response()->json($result);
    }
}
