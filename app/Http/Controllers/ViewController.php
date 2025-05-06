<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\User;
use App\Models\Country;
use App\Models\Team;
use App\Models\Team7m;
use App\Models\League;
use App\Models\League7m;
use App\Models\Game;
use App\Models\Game7m;
use Carbon\Carbon;


class ViewController extends Controller
{
    
    public function fetch7mGames($start_date = null, $end_date = null){
        if($start_date == null && $end_date == null)
        {
            $games = Game7m::with(['away_team', 'home_team', 'league'])
                            ->where('gd', '>=', now())
                            ->orderBy('gt', 'asc')->get();

            return response()->json($games, 200);
            // return response($games, 200)->header('Content-Type', 'text/plain');
        }
        else if($start_date == null)
        {
            $start_date = date('Y-m-d');
        }
        else if($end_date == null){
            $end_date = date('Y-m-d');
        }

        $games = Game7m::with(['away_team', 'home_team', 'league'])
                        ->where('gd', '>=', $start_date)
                        ->where('gd', '<=', $end_date)
                        ->orderBy('gt', 'asc')->get();

        return response()->json($games, 200);
        // return response($games, 200)->header('Content-Type', 'text/plain');
    }



    public function getCorrectscoreMatch($start_date = null, $end_date = null, $exclude7m = ''){
        if($start_date == null && $end_date == null)
        {
            $games = Game::with(['away_team', 'home_team', 'league'])
                ->where('gd', '>=', date('Y-m-d'))
                ->where('home_o2', '!=', NULL)
                ->where('away_o2', '!=', NULL)
                ->orderBy('gt', 'asc')
                ->get();
                
            return response()->json($games, 200);
        }
        else if($start_date == null)
        {
            $start_date = date('Y-m-d');
        }
        else if($end_date == null){
            $end_date = date('Y-m-d');
        }

        
        $games = Game::with(['away_team', 'home_team', 'league', 'game7m', 'game7m.home_team', 'game7m.away_team', 'game7m.league'])
            ->where('game7m_id', '!=', NULL)
            ->where('gd', '>=', $start_date)
            ->where('gd', '<=', $end_date)
            // ->where('gd', '>=', $start_date)
            // ->where('gd', '<=', $end_date)
            ->orderBy('gt', 'asc')->get();
            

        return response()->json($games, 200);
    }



    public function getUnmatched($start_date = null, $end_date = null){
        if($start_date == null && $end_date == null)
        {
            $games = Game::with(['away_team', 'home_team', 'league'])
                ->where('game7m_id', '=', NULL)
                
                ->orderBy('gt', 'asc')
                ->get();

            
            $game7ms = Game7m::with(['away_team', 'home_team', 'league'])
                ->where('game_id', '=', NULL)
                ->where('gd', '<=', now())
                ->orderBy('gt', 'asc')
                ->get();


            return response()->json(
                    [
                        'games' => $games,
                        'game7ms' => $game7ms,
                        'here we go ' => 'ho we gear'
                    ]
                , 200);
        }
        else if($start_date == null)
        {
            $start_date = date('Y-m-d');
        }
        else if($end_date == null){
            $end_date = date('Y-m-d');
        }

        $game7ms = Game7m::with(['away_team', 'home_team', 'league'])
            ->where('game_id', '=', NULL)
            ->where('gd', $start_date)
            ->orderBy('gt', 'asc')
            ->get();
    
        $games = Game::with(['away_team', 'home_team', 'league'])
            ->where('game7m_id', '=', NULL)
            ->where('gd', $start_date)
            ->orderBy('gt', 'asc')
            ->get();
            
        return response()->json(
            [
                'games' => $games,
                'game7ms' => $game7ms,
            ]
            , 200);
    }
}
