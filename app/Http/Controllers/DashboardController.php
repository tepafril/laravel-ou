<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function dashboard($start_date = null, $end_date = null){
        $total = null;
        if($start_date == null && $end_date == null){
            $date = date('Y-m-d');
            $total = DB::select("SELECT COUNT(*) as total FROM games WHERE gd <= '$date'");
            $matched = DB::select("SELECT COUNT(*) as matched FROM games WHERE gd <= '$date' AND game7m_id IS NOT NULL");
            $unmatched = DB::select("SELECT COUNT(*) as unmatched FROM games WHERE gd <= '$date' AND game7m_id IS NULL");
        }
        else if($start_date == null)
        {
            $start_date = date('Y-m-d');
        }
        else if($end_date == null){
            $end_date = date('Y-m-d');
        }
        
        $total = $total 
            ? $total 
            : DB::select('SELECT COUNT(*) as total FROM games WHERE gd >= '. $start_date .' AND gd <= ' . $end_date);

        return response()->json([
            'total' => $total[0]->total,
            'matched' => $matched[0]->matched,
            'unmatched' => $unmatched[0]->unmatched
        ], 200);
    }
}
