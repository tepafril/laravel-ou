<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Country;
use App\Models\Team;
use App\Models\Team7m;
use App\Models\League;
use App\Models\League7m;
use App\Models\Game;
use App\Models\Game7m;
use App\Models\Igame;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FetcherController extends Controller
{
    public function fetchResult($game_date = null)
    {
        set_time_limit(480);
        if($game_date == null){
            $game_date = date('Y-m-d', strtotime('-1 day'));
        }
        $url = 'https://px-crowns2.7mdt.com/result/'. $game_date .'/en.js';

        // Fetch the contents from the URL
        $data = file_get_contents($url);

        if ($data === FALSE) {
            // Handle the error if the request fails
            die('Error occurred while fetching the data');
        }

        // Extract the part that contains the actual game data (after 'datas = ')
        preg_match('/var datas = "(.*)";/', $data, $matches);
        if (!isset($matches[1])) {

            preg_match('/var datas="(.*)";/', $data, $matches);
            if (!isset($matches[1])) {
                return [];  // Return an empty array if the match fails
            }
        }
        $gameData = $matches[1];
        $rows = array_filter(explode('$', $gameData));


        // $parts = explode(',', $rows[0]);
        // echo 'status ' . $parts[11];
        // echo '<br/>';
        // echo 'final_score ' . $parts[12];
        // echo '<br/>';
        // echo 'ht_score ' . $parts[13];
        // echo '<br/>';

        // return;
        foreach ($rows as $row) {
            $parts = explode(',', $row);

            $gameId = $parts[0];

            if (count($parts) < 23) continue;

            $league = League7m::find($parts[1]);
            if (!$league) {
                $league = new League7m();
                $league->id = $parts[1]; // set manually
                // $league->code = $parts[2];
                $league->name = $parts[2];
                $league->bg_color = $parts[3];
                $league->save();
            }

            $homeTeam = Team7m::find($parts[5]);
            if (!$homeTeam) {
                $homeTeam = new Team7m();
                $homeTeam->id = $parts[5]; // set manually
                $homeTeam->name = $parts[6];
                $homeTeam->save();
            }
            
            $awayTeam = Team7m::find($parts[7]);
            if (!$awayTeam) {
                $awayTeam = new Team7m();
                $awayTeam->id = $parts[7]; // set manually
                $awayTeam->name = $parts[8];
                $awayTeam->save();
            }

            $datetimeParts = explode('/', $parts[9]);
            $startTime = Carbon::create(
                $datetimeParts[0],
                $datetimeParts[1],
                $datetimeParts[2],
                $datetimeParts[3] ?? 0,
                $datetimeParts[4] ?? 0,
                $datetimeParts[5] ?? 0
            );

            if (!Game7m::where('id', $gameId)->exists()) {
                $game7m = new Game7m();
                $game7m->id = $gameId;
                $game7m->tid = $league->id;
                $game7m->thid = $homeTeam->id;
                $game7m->taid = $awayTeam->id;

                // var_dump($startTime);
                // echo '<br/>';

                $final_score = strlen($parts[12]) >= 3 ? explode('-', $parts[12]) : null;
                $game7m->ft_home_score = $final_score[0] ?? null;
                $game7m->ft_away_score = $final_score[1] ?? null;

                $ht_score = strlen($parts[13]) >= 3 ? explode('-', $parts[13]) : null;
                $game7m->ht_home_score = $ht_score[0] ?? null;
                $game7m->ht_away_score = $ht_score[1] ?? null;

                $game7m->gd   = $startTime;
                $game7m->gt   = $startTime;
                $game7m->status       =  str_replace('@', '', $parts[11]);

                // $game7m->final_score  = $parts[12];
                // $game7m->ht_score     = $parts[13];

                // $parts_17 = explode('/', $parts[17]);
                // $game7m->odds_set_1_1     = $parts_17[0] ?? '';
                // $game7m->odds_set_1_2     = $parts_17[1] ?? '';
                // $game7m->odds_set_1_3     = $parts_17[2] ?? '';
                // $game7m->odds_set_1_4     = $parts_17[3] ?? '';

                // $parts_18 = explode('/', $parts[18]);
                // $game7m->odds_set_2_1     = $parts_18[0] ?? '';
                // $game7m->odds_set_2_2     = $parts_18[1] ?? '';
                // $game7m->odds_set_2_3     = $parts_18[2] ?? '';
                // $game7m->odds_set_2_4     = $parts_18[3] ?? '';

                // $parts_19 = explode('/', $parts[19]);
                // $game7m->odds_set_3_1     = $parts_19[0] ?? '';
                // $game7m->odds_set_3_2     = $parts_19[1] ?? '';
                // $game7m->odds_set_3_3     = $parts_19[2] ?? '';
                // $game7m->odds_set_3_4     = $parts_19[3] ?? '';


                // $parts_20 = explode('/', $parts[20]);
                // $game7m->odds_set_4_1     = $parts_20[0] ?? '';
                // $game7m->odds_set_4_2     = $parts_20[1] ?? '';
                // $game7m->odds_set_4_3     = $parts_20[2] ?? '';
                // $game7m->odds_set_4_4     = $parts_20[3] ?? '';

                // $parts_21 = explode('/', $parts[21]);
                // $game7m->odds_set_5_1     = $parts_21[0] ?? '';
                // $game7m->odds_set_5_2     = $parts_21[1] ?? '';
                // $game7m->odds_set_5_3     = $parts_21[2] ?? '';
                // $game7m->odds_set_5_4     = $parts_21[3] ?? '';

                $game7m->save();
            }else{
                $game7m = Game7m::where('id', $gameId)->first();

                $final_score = strlen($parts[12]) >= 3 ? explode('-', $parts[12]) : null;
                $game7m->ft_home_score = $final_score[0] ?? null;
                $game7m->ft_away_score = $final_score[1] ?? null;

                $ht_score = strlen($parts[13]) >= 3 ? explode('-', $parts[13]) : null;
                $game7m->ht_home_score = $ht_score[0] ?? null;
                $game7m->ht_away_score = $ht_score[1] ?? null;

                $game7m->gd   = $startTime;
                $game7m->gt   = $startTime;
                $game7m->status       =  str_replace('@', '', $parts[11]);

                $game7m->save();
            }
        }

        $text = 'Ok! [5] fetchResult(): ' . count($rows) . ' records found. ';
        file_get_contents('https://api.telegram.org/bot6362404234:AAHMyXJBYa4mAGwNEx_3b334wKX1k-DfHOc/sendMessage?chat_id=-1002010818149/&text=' . $text);
        echo $text;
    }

    public function fetchAll(Request $request)
    {
        set_time_limit(480);
        $this->fetchGame($request);
        $this->fetchOU($request);
        $this->fetch7M($request);
        $this->fetch7mLive($request);
        $this->indexGames();
    }

    public function fetchResultV2($game_date = null)
    {
        try{
            if($game_date == null){
                $game_date = date('Y-m-d');
            }
            // echo $game_date . '<br/>';
            $result = file_get_contents('https://data.7msport.com/result/' . $game_date . '/index_en.js');
            $results = $this->_convertJavaScriptFileToPHPArrays($result);

            for ($i=0; $i< count($results['Team_B_Arr']); $i++){

                $game_id = $results['live_bh_Arr'][$i];
                $home_score = $results['live_a_Arr'][$i];
                $away_score = $results['live_b_Arr'][$i];

                echo $game_id.'<br/>';
                $game = Game7m::where('id', $game_id)->first();
                if($game){
                    $is_home_handicap = $game->ah1_4;
                    $is_under_over = $game->ou1_2;

                    if($is_under_over){
                        if($home_score + $away_score == $is_under_over){
                            $game->ov = 'draw';
                        }
                        else if($home_score + $away_score > $is_under_over){
                            $game->ov = 'over';
                        }
                        else{
                            $game->ov = 'under';
                        }
                    }

                    $game->ft_home_score = $home_score;
                    $game->ft_away_score = $away_score;

                    if($is_home_handicap){
                        if($home_score - $game->ah1_2 == $away_score){
                            $game->wn = 'draw';
                        }
                        else if( ($home_score - $game->ah1_2) - $away_score == -0.25){
                            $game->wn = 'loss_half';
                        }
                        else if( ($home_score - $game->ah1_2) - $away_score <= -0.50){
                            $game->wn = 'loss';
                        }
                        else if( ($home_score - $game->ah1_2) - $away_score == 0.25){
                            $game->wn = 'win_half';
                        }
                        else if( ($home_score - $game->ah1_2) - $away_score > 0.25){
                            $game->wn = 'win';
                        }
                    }else{
                        if($away_score - $game->ah1_2 == $home_score){
                            $game->wn = 'draw';
                        }
                        else if( ($away_score - $game->ah1_2) - $home_score == -0.25){
                            $game->wn = 'loss_half';
                        }
                        else if( ($away_score - $game->ah1_2) - $home_score <= -0.50){
                            $game->wn = 'loss';
                        }
                        else if( ($away_score - $game->ah1_2) - $home_score == 0.25){
                            $game->wn = 'win_half';
                        }
                        else if( ($away_score - $game->ah1_2) - $home_score > 0.25){
                            $game->wn = 'win';
                        }
                    }

                    $game->save();
                    
                    echo $results['live_bh_Arr'][$i] . ':' . $results['Team_A_Arr'][$i] . ' ' . $home_score .' - ' .  $away_score . ' ' . $results['Team_B_Arr'][$i];
                    echo '<br/>';
                }
            }
            $text = 'Ok! [7] : fetchResultV2()' . count($results['Team_B_Arr']) . ' records found. ';
            file_get_contents('https://api.telegram.org/bot6362404234:AAHMyXJBYa4mAGwNEx_3b334wKX1k-DfHOc/sendMessage?chat_id=-1002010818149/&text=' . $text);
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            file_get_contents('https://api.telegram.org/bot6644142525:AAE6JbyeeaYCZ7U7N1NvD8sooleCrvWnpks/sendMessage?chat_id=-4083928800/&text=' . 'fetchResult: ' . $e->getMessage());
        }
    }

    private function _convertJavaScriptFileToPHPArrays($jsCode) {
        // Use regular expressions to find and convert JavaScript arrays to PHP arrays
        $pattern = '/var\s+(\w+)\s*=\s*\[([^\]]+)\];/s';
        preg_match_all($pattern, $jsCode, $matches, PREG_SET_ORDER);
    
        $phpArrays = [];
    
        foreach ($matches as $match) {
            $variableName = $match[1];
            $arrayString = $match[2];
    
            // Split the array string by commas and spaces
            $arrayValues = preg_split('/\s*,\s*/', $arrayString);
    
            // Convert the array values to their appropriate data types
            $phpArray = [];
            foreach ($arrayValues as $value) {
                if (is_numeric($value)) {
                    // Handle numeric values
                    $phpArray[] = (int)$value;
                } elseif (in_array($value, ['true', 'false'])) {
                    // Handle boolean values
                    $phpArray[] = $value === 'true';
                } else {
                        // Handle string values
                        $phpArray[] = trim($value, "'\"");
                }
            }
    
            // echo $variableName;
            // echo '<br/>';
            // var_dump($phpArray);
            // echo '<br/>';echo '<br/>';
            // Store the PHP array with the variable name as the key
            $phpArrays[$variableName] = $phpArray;
        }
        return $phpArrays;
    }

    public function fetchGame(Request $request)
    {
        set_time_limit(480);
        try{
            $odds_config = file_get_contents('https://am.lucksport.com/xml/odds_config.xml');
            $odds_config_arr = json_decode(json_encode((array)simplexml_load_string($odds_config)),true);

            foreach($odds_config_arr["Fixture"] as $elm){
                $attrs = $elm["@attributes"];
                // foreach($attrs as $attr => $val){
                //     echo $val .' -> '. $attr;echo '<br/>';
                // }
                // echo '<br/>';
                // echo '<br/>';
                // echo '<br/>';
                // Country
                if( isset($attrs['cid']) && $attrs['cid'] > 0){
                    $country = Country::where('id', $attrs['cid'])->firstOr(function () use ($attrs){
                        return Country::create([
                            'id' => $attrs['cid'],
                            'tconutry' => $attrs['tconutry'] ?? "",
                            'sconutry' => $attrs['sconutry'] ?? "",
                            'econutry' => $attrs['econutry'] ?? "",
                        ]);
                    });
                }

                // League
                if(isset($attrs['tid']) && $attrs['tid'] > 0){
                    $league = League::where('id', $attrs['tid'])->firstOr(function () use ($attrs){
                        return League::create([
                            'id' => $attrs['tid'],

                            'traditional_chinese' => $attrs['tt'] ?? "",
                            'simplified_chinese' => $attrs['st'] ?? "",
                            'english' => $attrs['et'],

                            'traditional_chinese_short' => $attrs['ts'] ?? "",
                            'simplified_chinese_short' => $attrs['ss'] ?? "",
                            'english_short' => $attrs['es'] ?? "",
                        ]);
                    });
                }

                // echo $attrs['eh'];
                // echo '<br/>';
                // Home Team
                if( isset($attrs['thid'])  && $attrs['thid'] > 0){
                    $home_team = Team::where('id', $attrs['thid'])->firstOr(function () use ($attrs){
                        return Team::create([
                            'id' => $attrs['thid'],
                            'traditional_chinese' => $attrs['th'] ?? "",
                            'simplified_chinese' => $attrs['sh'] ?? "",
                            'english' => $attrs['eh'] ?? "",
                        ]);
                    });
                }

                // Away Team
                if( isset($attrs['taid'])  && $attrs['taid'] > 0){
                    $away_team = Team::where('id', $attrs['taid'])->firstOr(function () use ($attrs){
                        return Team::create([
                            'id' => $attrs['taid'],
                            'traditional_chinese' => $attrs['ta'] ?? "",
                            'simplified_chinese' => $attrs['sa'] ?? "",
                            'english' => $attrs['ea'] ?? "",
                        ]);
                    });
                }

                if (isset($attrs['id']) && $attrs['id'] > 0) {
                    $data = [
                        'gd'    => Carbon::createFromFormat('Ymd H:i', $attrs['gt'])->toDateString('Y-m-d'),
                        'gt'    => Carbon::createFromFormat('Ymd H:i', $attrs['gt'])->toDateTimeString(),
                        
                        'cid'   => isset($attrs['cid']) && $attrs['cid'] > 0 ? $attrs['cid'] : null,
                        'tid'   => isset($attrs['tid']) && $attrs['tid'] > 0 ? $attrs['tid'] : null,
                        'taid'  => isset($attrs['taid']) && $attrs['taid'] > 0 ? $attrs['taid'] : null,
                        'thid'  => isset($attrs['thid']) && $attrs['thid'] > 0 ? $attrs['thid'] : null,

                        'mb'    => isset($attrs['mb']) ? $attrs['mb'] : null,
                        'hr'    => isset($attrs['hr']) ? $attrs['hr'] : null,
                        'ar'    => isset($attrs['ar']) ? $attrs['ar'] : null,
                        'sg'    => isset($attrs['sg']) ? $attrs['sg'] : null,
                        's'     => isset($attrs['s']) ? $attrs['s'] : null,
                        'hc'    => isset($attrs['hc']) ? $attrs['hc'] : null,
                        'ns'    => isset($attrs['ns']) ? $attrs['ns'] : null,
                        'tdid'  => isset($attrs['tdid']) ? $attrs['tdid'] : null,
                        'ml'    => isset($attrs['ml']) ? $attrs['ml'] : null,
                        'bir'   => isset($attrs['bir']) ? $attrs['bir'] : null,
                        'htb'   => isset($attrs['htb']) ? $attrs['htb'] : null,
                        'evid'  => isset($attrs['evid']) ? $attrs['evid'] : null,
                        'mid'   => isset($attrs['mid']) ? $attrs['mid'] : null,
                        'himg'  => isset($attrs['himg']) ? $attrs['himg'] : null,
                        'aimg'  => isset($attrs['aimg']) ? $attrs['aimg'] : null,
                        'ty'    => isset($attrs['ty']) ? $attrs['ty'] : null,
                        'cimg'  => isset($attrs['cimg']) ? $attrs['cimg'] : null,
                        'cimg2' => isset($attrs['cimg2']) ? $attrs['cimg2'] : null,
                        'tseq'  => isset($attrs['tseq']) ? $attrs['tseq'] : null,
                        'dorder' => isset($attrs['dorder']) ? $attrs['dorder'] : null,
                        'ctid'  => isset($attrs['ctid']) ? $attrs['ctid'] : null,
                        'ls_status'         => isset($attrs['ls_status']) ? $attrs['ls_status'] : null,
                        'display_lineup'    => isset($attrs['display_lineup']) ? $attrs['display_lineup'] : null,
                        'ft_show' => isset($attrs['ft_show']) ? $attrs['ft_show'] : null,
                    ]; 
                    $game = Game::find($attrs['id']);
                
                    if ($game) {
                        $game->update($data);
                    } else {
                        $data['id'] = $attrs['id']; // ensure ID is included for creation
                        $game = Game::create($data);
                    }
                }
            }

            $text = 'Ok! [1] fetchGame(): ' . count($odds_config_arr["Fixture"]) . ' records found. ';
            file_get_contents('https://api.telegram.org/bot6362404234:AAHMyXJBYa4mAGwNEx_3b334wKX1k-DfHOc/sendMessage?chat_id=-1002010818149/&text=' . $text);
            echo $text;
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            file_get_contents('https://api.telegram.org/bot6644142525:AAE6JbyeeaYCZ7U7N1NvD8sooleCrvWnpks/sendMessage?chat_id=-4083928800/&text=' . 'fetchCorrectscoreMatch: ' . $e->getMessage());
        }
    }

    public function fetchOU(Request $request)
    {
        set_time_limit(480);
        try{
            $xml_string = file_get_contents("https://am.lucksport.com/xml/overunder.xml");
            $array = json_decode(json_encode((array)simplexml_load_string($xml_string)),true);

            foreach($array["Fixture"] as $elm){
                $attrs = $elm['@attributes'];
                $game = Game::find($attrs["id"]);

                // if($attrs["ha"] == 1){
                    // foreach($attrs as $attr => $val){
                    //     echo $attr .' -> '. $val; echo '<br/>';
                    // }
                    // echo '<br/>';
                    // echo '<br/>';
                    // echo '<br/>';

                    $li_array = ["0.0","0 / 0.5","0.5","0.5 / 1"
                                ,"1.0","1 / 1.5","1.5","1.5 / 2"
                                ,"2.0","2 / 2.5","2.5","2.5 / 3"
                                ,"3.0","3 / 3.5","3.5","3.5 / 4"
                                ,"4.0","4 / 4.5","4.5","4.5 / 5","5.0"
                                ,"5 / 5.5","5.5","5.5 / 6","6.0"
                                ,"6 / 6.5","6.5","6.5 / 7","7.0"
                                ,"7 / 7.5","7.5","7.5 / 8","8.0"
                                ,"8 / 8.5","8.5","8.5 / 9","9.0"
                            ];

                    $decimal_array = [
                        0.0, 0.25, 0.5, 0.75,
                        1.0, 1.25, 1.5, 1.75,
                        2.0, 2.25, 2.5, 2.75,
                        3.0, 3.25, 3.5, 3.75,
                        4.0, 4.25, 4.5, 4.75,
                        5.0, 5.25, 5.5, 5.75,
                        6.0, 6.25, 6.5, 6.75,
                        7.0, 7.25, 7.5, 7.75,
                        8.0, 8.25, 8.5, 8.75,
                        9.0
                    ];

                    if($game){
                        $game->oo = $attrs["oo"];
                        $game->uo = $attrs["uo"];
                        $game->li_decimal = $decimal_array[$attrs["li"]];
                        $game->li = $attrs["li"];
                        $game->sp = $attrs["sp"];
                        $game->save();
                    }
                // }
            }

            $text = 'Ok! [2] fetchOU(): ' . count($array["Fixture"]) . ' records found. ';
            file_get_contents('https://api.telegram.org/bot6362404234:AAHMyXJBYa4mAGwNEx_3b334wKX1k-DfHOc/sendMessage?chat_id=-1002010818149/&text=' . $text);
            echo $text;
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            file_get_contents('https://api.telegram.org/bot6644142525:AAE6JbyeeaYCZ7U7N1NvD8sooleCrvWnpks/sendMessage?chat_id=-4083928800/&text=' . 'fetchCorrectScoreHandicap: ' . $e->getMessage());
        }
    }

    public function fetch7M(Request $request)
    {
        set_time_limit(480);
        try{
            $fen = file_get_contents('https://ctc-live.7m.com.cn/datafile/fen.js');

            // Use regular expression to extract individual array assignments
            $pattern = '/sDt\[(\d+)\]=\[(.*?)\];/';
            preg_match_all($pattern, $fen, $matches, PREG_SET_ORDER);

            $result = [];
            // echo count($matches) . '<br/>';
            foreach ($matches as $match) {
                $game_id = $match[1];
                $jsonString = '[' . $match[2] . ']';
                $jsonString = str_replace("'",'"', $jsonString);
                $arr = json_decode($jsonString, true);
                
                // echo $game_id . ' => '; var_dump($arr);
                // echo "<br/><br/><br/>";

                // League
                if(isset($arr[16])){
                    $league = League7m::where('id', $arr[16])->firstOr(function () use ($arr){
                        return League7m::create([
                            'id' => $arr[16],
                            'name' => $arr[0],
                            'bg_color' => $arr[1],
                        ]);
                    });
                }

                // Home Team
                if(isset($arr[9])){
                    $home_team = Team7m::where('id', $arr[9])->firstOr(function () use ($arr){
                        return Team7m::create([
                            'id' => $arr[9],
                            'name' => $arr[2],
                        ]);
                    });
                }

                // Away Team
                if(isset($arr[10])){
                    $away_team = Team7m::where('id', $arr[10])->firstOr(function () use ($arr){
                        return Team7m::create([
                            'id' => $arr[10],
                            'name' => $arr[3],
                        ]);
                    });
                }

                // Game
                if (isset($game_id)) {
                    $data = [
                        'f1'    => $arr[1] ?? null,
                        'f4'    => $arr[4] ?? null,
                        'f5'    => $arr[5] ?? null,
                        'f6'    => $arr[6] ?? null,
                        'f7'    => $arr[7] ?? null,
                        'f8'    => $arr[8] ?? null,
                        'thid'  => $arr[9] ?? null,
                        'taid'  => $arr[10] ?? null,
                        'f11'   => $arr[11] ?? null,
                        'f12'   => $arr[12] ?? null,
                        'f13'   => $arr[13] ?? null,
                        'f14'   => $arr[14] ?? null,
                        'f15'   => $arr[15] ?? null,
                        'tid'   => $arr[16] ?? null,
                        'f17'   => $arr[17] ?? null,
                        'f18'   => $arr[18] ?? null,
                        'f19'   => $arr[19] ?? null,
                        'f20'   => $arr[20] ?? null,
                    ];
                
                    $game = Game7m::find($game_id);
                    if ($game) {
                        $f20b = json_decode($game->f20b ?? '[]');
                        $f20b[] = $arr[20];
                        $data['f20b'] = json_encode($f20b);
                        $game->update($data);
                    } else {
                        $data['id'] = $game_id;
                        $data['f20a'] = $arr[20] ?? null;

                        $f20b = json_decode('[]');
                        $f20b[] = $arr[20];

                        $data['f20b'] = json_encode($f20b);
                        $game = Game7m::create($data);
                    }
                }
            }

            $text = 'Ok! [3] fetch7M(): ' . count($matches) . ' records found. ';
            file_get_contents('https://api.telegram.org/bot6362404234:AAHMyXJBYa4mAGwNEx_3b334wKX1k-DfHOc/sendMessage?chat_id=-1002010818149/&text=' . $text);
            // array(21) { 
            //     [0]=> string(6) "SPA D1" 
            //     [1]=> string(6) "993300" 
            //     [2]=> string(8) "Mallorca" 
            //     [3]=> string(6) "Getafe" 
            //     [4]=> string(10) "HK NOW 632" 
            //     [5]=> string(0) "" 
            //     [6]=> string(3) " / " 
            //     [7]=> string(2) "17" 
            //     [8]=> string(2) "11" 
            //     [9]=> int(247) Mallorca ID
            //     [10]=> int(333) Getafe ID
            //     [11]=> int(0) 
            //     [12]=> string(2) "85" League ID
            //     [13]=> int(1)  
            //     [14]=> int(6445987)  Odd ID
            //     [15]=> int(1) 
            //     [16]=> int(85) 
            //     [17]=> int(1) League ID
            //     [18]=> string(24) "-0.5,2.2,1.66,0,1.56,2.3" 
            //     [19]=> string(7) "-0.2500" 
            //     [20]=> string(5) "-0.25" 
            // } 

            // print_r($result);

            echo $text;
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            file_get_contents('https://api.telegram.org/bot6644142525:AAE6JbyeeaYCZ7U7N1NvD8sooleCrvWnpks/sendMessage?chat_id=-4083928800/&text=' . 'asianHandicapMatch: ' . $e->getMessage());
        }
    }

    public function fetch7mLive(Request $request)
    {
        set_time_limit(480);
        try{
            $sxl = file_get_contents('https://ctc-live.7m.com.cn/datafile/sxl.js');

            // Use regular expression to extract individual array assignments
            $pattern = '/sDt2\[(\d+)\]=\[(.*?)\];/';
            preg_match_all($pattern, $sxl, $matches, PREG_SET_ORDER);

            $result = [];

            foreach ($matches as $match) {
                $arrayId = $match[1];
                $jsonString = '[' . $match[2] . ']';
                $jsonString = str_replace("'",'"', $jsonString);
                $json = json_decode($jsonString, true);
                
                $game = Game7m::find($arrayId);
                if($game){
                    $game->gd      = $json[8];
                    $game->gt      = $json[8];
                    $game->save();
                }
                // echo $arrayId . ' => '; var_dump($json[8]);
                // echo "<br/><br/><br/>";
            }

            $text = 'OK! [4] fetch7mLive(): ' . count($matches) . ' records found. ';
            file_get_contents('https://api.telegram.org/bot6362404234:AAHMyXJBYa4mAGwNEx_3b334wKX1k-DfHOc/sendMessage?chat_id=-1002010818149/&text=' . $text);
            echo $text;
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            file_get_contents('https://api.telegram.org/bot6644142525:AAE6JbyeeaYCZ7U7N1NvD8sooleCrvWnpks/sendMessage?chat_id=-4083928800/&text=' . 'asianHandicapLive: ' . $e->getMessage());
        }
    }

    public function indexGames()
    {
        try {
            $games = Game::with(['away_team', 'home_team', 'league', 'game7m', 'game7m.home_team', 'game7m.away_team', 'game7m.league'])
                ->whereNotNull('game7m_id')
                // ->whereHas('game7m', function($query) {
                //     $query->where('status', 4);
                // })
                ->get();

            foreach ($games as $game) {
                $wn = null;
                $ov = null;

                // echo "wn: $wn, ov: $ov <br/>";

                if($game->li_decimal){
                    if($game->game7m->ft_home_score + $game->game7m->ft_away_score == $game->li_decimal){
                        $ov = 'draw';
                    }
                    else if($game->game7m->ft_home_score + $game->game7m->ft_away_score > $game->li_decimal){
                        $ov = 'over';
                    }
                    else{
                        $ov = 'under';
                    }
                    echo "ov: $ov";
                }

                if($game->f20a){
                    if($game->game7m->ft_home_score + $game->f20a == $game->game7m->ft_away_score){
                        echo 'draw';
                        $wn = 'draw';
                    }
                    else if( ($game->game7m->ft_home_score + $game->f20a) - $game->game7m->ft_away_score == -0.25){
                        echo 'loss_half';
                        $wn = 'loss_half';
                    }
                    else if( ($game->game7m->ft_home_score + $game->f20a) - $game->game7m->ft_away_score <= -0.50){
                        echo 'loss';
                        $wn = 'loss';
                    }
                    else if( ($game->game7m->ft_home_score + $game->f20a) - $game->game7m->ft_away_score == 0.25){
                        echo 'win_half';
                        $wn = 'win_half';
                    }
                    else if( ($game->game7m->ft_home_score + $game->f20a) - $game->game7m->ft_away_score > 0.25){
                        echo 'win';
                        $wn = 'win';
                    }
                    // echo ", wn: $wn <br/>";
                }

                $igame = Igame::firstOrCreate(
                    ['id' => $game->game7m->id],
                    [
                        'gt' => $game->gt,
                        'f20' => $game->game7m->f20,
                        'f20a' => $game->game7m->f20a,
                        'f20b' => $game->game7m->f20b,
                        'st' => $game->game7m->status,
                        'fths' => $game->game7m->ft_home_score,
                        'ftas' => $game->game7m->ft_away_score,
                        'ln' => $game->league->english,
                        'hn' => $game->home_team->english,
                        'an' => $game->away_team->english,
                        'oo' => $game->oo,
                        'uo' => $game->uo,
                        'li' => $game->li_decimal,
                        'is_wn' => $wn,
                        'is_ov' => $ov
                    ]
                );
            }

            $text = 'Ok! [6] indexGames(): ' . count($games) . ' records indexed. ';
            file_get_contents('https://api.telegram.org/bot6362404234:AAHMyXJBYa4mAGwNEx_3b334wKX1k-DfHOc/sendMessage?chat_id=-1002010818149/&text=' . $text);
            echo $text;
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            file_get_contents('https://api.telegram.org/bot6644142525:AAE6JbyeeaYCZ7U7N1NvD8sooleCrvWnpks/sendMessage?chat_id=-4083928800/&text=' . 'indexGames: ' . $e->getMessage());
        }
    }
}
