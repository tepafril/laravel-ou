<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Igame;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getIgameCountByF20a()
    {
        // Get actual counts from database
        $counts = Igame::select('f20a', DB::raw('count(*) as count'))
            ->whereNotNull('f20a')
            ->where('f20a', '!=', '')
            ->groupBy('f20a')
            ->get();

        return response()->json($counts);
    }

    public function getIgameCountByLi($s2)
    {
        // Get actual counts from database
        $counts = Igame::select('li',  DB::raw('count(*) as count'))
            ->where('f20a', $s2)
            ->groupBy('li')
            ->get();

        return response()->json($counts);
    }


    public function getIgameRecords($s2, $li)
    {
        // Get actual counts from database
        $counts = DB::table('igames')
            ->select([
                'id',
                'an',
                'f20a',
                'ftas',
                'fths',
                'gt',
                'handi',
                'hn',
                'is_ov',
                'is_wn',
                'ln',
                'lns',
                'oo',
                'uo'
            ])
            ->where('f20a', $s2)
            ->where('li', $li)
            ->orderBy('oo', 'asc')
            ->paginate(100);

        return response()->json($counts);
    }
}
