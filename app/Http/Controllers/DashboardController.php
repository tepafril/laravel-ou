<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    //
    public function dashboard($start_date = null, $end_date = null){
        $total = null;
        
        $date = date('Y-m-d');

        $result = DB::select("
            SELECT
                COUNT(*) as total,
                SUM(CASE WHEN game7m_id IS NOT NULL THEN 1 ELSE 0 END) as matched,
                SUM(CASE WHEN game7m_id IS NULL THEN 1 ELSE 0 END) as unmatched,
                SUM(CASE WHEN gd = ? THEN 1 ELSE 0 END) as todayTotal,
                SUM(CASE WHEN gd = ? AND game7m_id IS NOT NULL THEN 1 ELSE 0 END) as todayMatched,
                SUM(CASE WHEN gd = ? AND game7m_id IS NULL THEN 1 ELSE 0 END) as todayUnmatched
            FROM games
            WHERE gd <= ?
        ", [$date, $date, $date, $date]);

        $data["count"] = (array) $result[0];


        // $total = DB::select("SELECT COUNT(*) as total FROM games WHERE gd <= '$date'");
        // $matched = DB::select("SELECT COUNT(*) as matched FROM games WHERE gd <= '$date' AND game7m_id IS NOT NULL");
        // $unmatched = DB::select("SELECT COUNT(*) as unmatched FROM games WHERE gd <= '$date' AND game7m_id IS NULL");


        // $today_total = DB::select("SELECT COUNT(*) as today_total FROM games WHERE gd = '$date'");
        // $today_matched = DB::select("SELECT COUNT(*) as today_matched FROM games WHERE gd = '$date' AND game7m_id IS NOT NULL");
        // $today_unmatched = DB::select("SELECT COUNT(*) as today_unmatched FROM games WHERE gd = '$date' AND game7m_id IS NULL");
        
        $results = DB::table('games')
            ->select(DB::raw('DATE(gt) as d'), DB::raw('COUNT(*) as t'))
            ->where('gt', '>=', Carbon::now()->subDays(30))
            ->groupBy(DB::raw('DATE(gt)'))
            ->orderBy('d', 'asc')
            ->get();

        $data["ou_chart"] = $results;
        
        return response()->json($data, 200);
    }
}
