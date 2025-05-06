<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Game7m;
use FuzzyWuzzy\Fuzz;
use FuzzyWuzzy\Process;

class CompareController extends Controller
{
    public function confirmMatch($game_id, $game7m_id){
        $game = Game::where('id', $game_id)->with(['home_team', 'away_team', 'league'])->first();
        $game7m = Game7m::where('id', $game7m_id)->with(['home_team', 'away_team', 'league'])->first();

        if(is_null($game) == false && is_null($game7m) == false){
            $game->game7m_id = $game7m_id;
            $game->save();
            
            $game->home_team->team7m_id = $game7m->home_team->id;
            $game->home_team->save();

            $game->away_team->team7m_id = $game7m->home_team->id;
            $game->away_team->save();

            $game->league->league7m_id = $game7m->league->id;
            $game->league->save();

            $game7m->game_id = $game_id;
            $game7m->save();
            
            $game7m->home_team->team_id = $game->home_team->id;
            $game7m->home_team->save();

            $game7m->away_team->team_id = $game->home_team->id;
            $game7m->away_team->save();

            $game7m->league->league_id = $game->league->id;
            $game7m->league->save();

            return response()->json([$game, $game7m]);
        }
    }
    
    public function matchSimilarTeams($start_date = null)
    {
        $date = date('Y-m-d');
        if($start_date != null){
            $date = $start_date;
        }
        // $perc = 0;
        // similar_text('JPN3', 'JPN D3', $perc);
        // echo $perc;
        // echo '<br/>';
        $games = Game::where('gd', $date)->with(['league', 'away_team', 'home_team', 'game7m', 'game7m.league', 'game7m.away_team', 'game7m.home_team'])->get();
        $game7ms = Game7m::where('gd', $date)->with(['league', 'away_team', 'home_team', 'game', 'game.league', 'game.away_team', 'game.home_team'])->get();
        
        $matches = [];
        foreach ($games as $game) {
            $match = $this->_findBestGameMatch($game, $game7ms);
            $matches[] = [
                'game' => $game,
                'game7m' => $match
            ];
        }

        $na = 0;
        $av = 0;



        $str = '';
        foreach ($matches as $match) {
            if(is_null($match['game7m'])){
                $na++;
            }
            else{
                $av++;

                if (is_null($match['game7m']->game_id)){
                    $match['game7m']->game_id = $match['game']->id;
                    $match['game7m']->save();
                }

                if (is_null($match['game7m']->league->league_id)){
                    $match['game7m']->league->league_id = $match['game']->league->id;
                    $match['game7m']->league->save();
                }

                if (is_null($match['game7m']->home_team->team_id)){
                    $match['game7m']->home_team->team_id = $match['game']->home_team->id;
                    $match['game7m']->home_team->save();
                }

                if (is_null($match['game7m']->away_team->team_id)){
                    $match['game7m']->away_team->team_id = $match['game']->away_team->id;
                    $match['game7m']->away_team->save();
                }

                if (is_null($match['game']->game7m_id)){
                    $match['game']->game7m_id = $match['game7m']->id;
                    $match['game']->save();
                }

                if (is_null($match['game']->league->league7m_id)){
                    $match['game']->league->league7m_id = $match['game7m']->league->id;
                    $match['game']->league->save();
                }

                if (is_null($match['game']->home_team->team7m_id)){
                    $match['game']->home_team->team7m_id = $match['game7m']->home_team->id;
                    $match['game']->home_team->save();
                }

                if (is_null($match['game']->away_team->team7m_id)){
                    $match['game']->away_team->team7m_id = $match['game7m']->away_team->id;
                    $match['game']->away_team->save();
                }

                $str .= ($match['game']->league->english_short);
                $str .=  ' - ';
                $str .= ($match['game7m']->league->name ?? 'N/A');
                $str .=  '<br/>';

                $str .= ($match['game']->home_team->english);
                $str .=  ' vs ';
                $str .= ($match['game']->away_team->english);
                $str .=  '<br/>';


                $str .= ($match['game']->game7m->home_team->name ?? 'N/A');
                $str .=  ' vs ';
                $str .= ($match['game']->game7m->away_team->name  ?? 'N/A');
                $str .=  '<br/><br/><br/>';
            }
        }

        $total = count($matches);

        $accurate_rate = ($av / $total) * 100;

        $text = 'Ok! [6] similar(): ' . $total . ' records found. ';
        file_get_contents('https://api.telegram.org/bot6362404234:AAHMyXJBYa4mAGwNEx_3b334wKX1k-DfHOc/sendMessage?chat_id=-1002010818149/&text=' . $text);
        
        echo($total);
        echo '(Total)';
        echo ' - ';
        echo($na) . '(N/A)';
        echo ' = ';
        echo $av . '(Matched) or '. round( $accurate_rate , 2) .'%';
        echo '<br/><br/><br/>';

        

        echo $str;
    }

    private function _findBestGameMatch($game, $gameList, $threshold = 80) {
        $bestMatch = null;
        $bestScore = 0;
    
        foreach ($gameList as $candidate) {
            $similarity = $this->_gameSimilarity($game, $candidate);
            if ($similarity > $bestScore && $similarity >= $threshold) {
                $bestScore = $similarity;
                $bestMatch = $candidate;
            }
        }

        if($bestMatch == null){
            foreach ($gameList as $candidate) {
            $similarity = $this->_gameFuzzSimilarity($game, $candidate);
                if ($similarity > $bestScore && $similarity >= $threshold) {
                    $bestScore = $similarity;
                    $bestMatch = $candidate;
                }
            }
        }
    
        return $bestMatch;
    }

    private function _gameSimilarity($game, $game7m) {
        $score = 0;
    
        similar_text($this->_normalize($game->home_team->english), $this->_normalize($game7m->home_team->name), $aSim);
        similar_text($this->_normalize($game->away_team->english), $this->_normalize($game7m->away_team->name), $bSim);
        similar_text($this->_normalize($game->league->english_short), $this->_normalize($game7m->league->name), $leagueSim);
    
        // Weighted scoring
        $score = ($aSim * 0.4) + ($bSim * 0.4) + ($leagueSim * 0.2);
    
        return $score;
    }

    private function _gameFuzzSimilarity($game, $game7m) {
        $score = 0;
        $fuzz = new Fuzz();
        $aSim = $fuzz->tokenSetRatio($this->_normalize($game->home_team->english), $this->_normalize($game7m->home_team->name));
        $bSim = $fuzz->tokenSetRatio($this->_normalize($game->away_team->english), $this->_normalize($game7m->away_team->name));
        $leagueSim = $fuzz->tokenSetRatio($this->_normalize($game->league->english_short), $this->_normalize($game7m->league->name));
        
        similar_text($this->_normalize($game->gt), $this->_normalize($game7m->gt), $gtSim);

        // if($gtSim == 100){
        //     // Weighted scoring
        //     $score = ($aSim * 0.25) + ($bSim * 0.25) + ($leagueSim * 0.25) + ($gtSim * 0.25);
        // }else{
        $score = ($aSim * 0.4) + ($bSim * 0.4) + ($leagueSim * 0.2);
        // }
    
        return $score;
    }

    private function _normalize($string) {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9\s]/', '', $string);
        return trim(preg_replace('/\s+/', ' ', $string));
    }
    

    // public function matchGamesWithGPT(Request $request)
    // {
    //     $gamesA = Game::where('gd', date('y-m-d'))->where('game7m_id', null)->with(['league', 'home_team', 'away_team'])->get();
    //     $gamesB = Game7m::where('gd', date('y-m-d'))->where('game_id', null)->with(['league', 'home_team', 'away_team'])->get();

    //     $prompt = $this->buildMatchingPrompt($gamesA, $gamesB);

    //     // echo 'gamesA '. count($gamesA);
    //     // echo '<br/>';
    //     // echo'gamesB '. count($gamesB);
    //     // echo '<br/>';
    //     // return $prompt;

    //     $response = $this->callChatGPT($prompt);

    //     return $response;

    //     // Step 1: Decode the full response JSON
    //     $parsed = json_decode($response, true);

    //     // Step 2: Extract the content field that contains the ```json code block
    //     $content = $parsed['choices'][0]['message']['content'] ?? '';

    //     // Step 3: Extract actual JSON using regex or string cleanup
    //     $cleanJson = preg_replace('/^```json|```$/m', '', $content); // remove the ```json and ``` markers
    //     $cleanJson = trim($cleanJson);

    //     // Step 4: Decode to array
    //     $mapping = json_decode($cleanJson, true);

    //     $inc = 0;
    //     foreach($mapping as $key => $value ){
    //         $game = Game::where('id',$key)->first();
    //         $game7m = Game7m::find($value);

    //         if(is_null($game) == false && is_null($game7m) == false ){

    //             if(is_null($game->game7m_id)){
    //                 $game->game7m_id = $value;
    //                 $game->save();
    //             }

    //             if(is_null($game7m->game_id)){
    //                 $game7m->game_id = $key;
    //                 $game7m->save();
    //             }
    //         }
    //         // var_dump($game);
    //         // echo "game7m_id $value";
    //         // echo '<br/>';

    //         // var_dump($game7m);
    //         // $game7m->game_id = $key;
            
    //         echo "$inc: ";
    //         echo $key;
    //         echo '<br/>';
    //         echo $value;
    //         echo '<br/>';echo '<br/>';echo '<br/>';
    //         $inc++;
    //     }
    //     // Output result
    //     // print_r($mapping);
    // }

    // private function callChatGPT(string $prompt)
    // {
    //     $apiKey = env('OPENAI_API_KEY');

    //     $postData = [
    //         'model' => 'gpt-3.5-turbo', // or 'gpt-3.5-turbo'
    //         'messages' => [
    //             ['role' => 'system', 'content' => 'You are a helpful assistant.'],
    //             ['role' => 'user', 'content' => $prompt],
    //         ],
    //         'temperature' => 0.2,
    //     ];

    //     $ch = curl_init();

    //     curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, [
    //         'Authorization: Bearer ' . $apiKey,
    //         'Content-Type: application/json',
    //     ]);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

    //     $response = curl_exec($ch);

    //     if (curl_errno($ch)) {
    //         throw new \Exception('Curl error: ' . curl_error($ch));
    //     }

    //     $result = json_decode($response, true);
    //     curl_close($ch);

    //     return $result ?? '{}';
    // }

    // private function buildMatchingPrompt($gamesA, $gamesB)
    // {
    //     $lines = [];

    //     $lines[] = "You are an assistant who matches football games between two lists. Match them based on league, home team, and away team.";
    //     $lines[] = "Return the result in JSON format: {\"<gameA_id>\": \"<gameB_id>\", ...}";
    //     $lines[] = "";
    //     $lines[] = "List A:";

    //     // echo "You are an assistant who matches football games between two lists. Match them based on league, home team, and away team.";
    //     // echo '<br/>';
    //     // echo "Return the result in JSON format: {\"<gameA_id>\": \"<gameB_id>\", ...}";
    //     // echo '<br/>';
    //     // echo "List A:";
    //     // echo '<br/>';

    //     foreach ($gamesA as $g) {
    //         $lines[] = "{$g['id']}. League: {$g['league']['english']}, Home: {$g->home_team->english}, Away: {$g->away_team->english}";
    //         // echo "{$g['id']}. League: {$g['league']['english']}, Home: {$g->home_team->english}, Away: {$g->away_team->english}";
    //         // echo '<br/>';
    //     }

    //     $lines[] = "";
    //     $lines[] = "List B:";
    //     // echo "List B:";
    //     // echo '<br/>';
    //     // echo '<br/>';
    //     foreach ($gamesB as $g) {
    //         $lines[] = "{$g['id']}. League: {$g->league->name}, Home: {$g->home_team->name}, Away: {$g->away_team->name}";
    //         // echo "{$g['id']}. League: {$g->league->name}, Home: {$g->home_team->name}, Away: {$g->away_team->name}";
    //         // echo '<br/>';
    //     }

    //     $lines[] = "";
    //     $lines[] = "Now return the best matches.";

    //     return implode("\n", $lines);
    // }

}
