<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Igame;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getIgameCountByF20a()
    {
        $counts = Igame::select(DB::raw('ABS(f20a) as f20a'), DB::raw('count(*) as count'))
            ->whereNotNull('f20a')
            ->groupBy(DB::raw('ABS(f20a)'))
            ->orderBy(DB::raw('ABS(f20a)'))
            ->get();

        return response()->json($counts);
    }
}
