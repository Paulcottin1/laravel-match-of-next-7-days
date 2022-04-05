<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Match;

class MatchApiController extends Controller
{
    public function index()
    {
        $data = [];
        $matches = Match::all();
        $index = 0;
        foreach ($matches as $match) {
            $data[$index] = [
                'season' => $match->season,
                'league' => $match->league,
                'matchDate' => $match->matchDate,
            ];

            foreach ($match->teams as $team) {
                foreach ($team->odds as $odd) {
                    $data[$index][$team->name]['winOdds'] = $odd->odd;
                }
            }

            foreach ($match->odds as $odd) {
                if($odd->side === 3) {
                    $data[$index]['drawOdds'] = $odd->odd;
                }
            }

            $index++;
        }


        return $data;
    }
}
