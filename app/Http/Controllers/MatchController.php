<?php

namespace App\Http\Controllers;

use App\Models\Match;
use App\Models\Odd;
use App\Models\Team;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class MatchController extends Controller
{
    /**
     * Display matches of the day
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function index()
    {
        if(!Match::whereBetween('matchDate', [Carbon::today(), Carbon::today()->addDay(7)])->get()->count() > 0) {
            $days = array(
                0 => Carbon::today(),
                1 => Carbon::today()->addDay(1),
                2 => Carbon::today()->addDay(2),
                3 => Carbon::today()->addDay(3),
                4 => Carbon::today()->addDay(4),
                5 => Carbon::today()->addDay(5),
                6 => Carbon::today()->addDay(6),
                7 => Carbon::today()->addDay(7),
            );
            foreach ($days as $day) {
                $response = Http::withHeaders([
                    'x-rapidapi-key' => env('API_KEY')
                ])->get('https://v3.football.api-sports.io/fixtures', [
                    'league' => intval(env('LEAGUE_ID')),
                    'season' => intval(env('SEASON')),
                    'date' => $day->format('Y-m-d')
                ]);

                foreach ($response['response'] as $match) {
                    // Create match
                    $matchData = array(
                        'season' => $match['league']['season'],
                        'league' => $match['league']['name'],
                        'matchDate' => new \DateTime($match['fixture']['date'])
                    );

                    $newMatch = Match::create($matchData);

                    // Create team away
                    $teamAwayCount = Team::where('name', $match['teams']['away']['name']);
                    if(0 != $teamAwayCount->count()) {
                        $teamAway = $teamAwayCount->first();
                    } else {
                        $teamAwayData = array(
                            'name' => $match['teams']['away']['name'],
                        );
                        $teamAway = Team::create($teamAwayData);
                    }

                    // Create team home
                    $teamHomeCount = Team::where('name', $match['teams']['home']['name']);
                    if(0 != $teamHomeCount->count()) {
                        $teamHome = $teamHomeCount->first();
                    } else {
                        $teamAwayData = array(
                            'name' => $match['teams']['home']['name'],
                        );
                        $teamHome = Team::create($teamAwayData);
                    }

                    // Save teams to the match
                    $newMatch->teams()->saveMany([
                        $teamAway,
                        $teamHome
                    ]);

                    $response = Http::withHeaders([
                        'x-rapidapi-key' => '904de67464538f61e02ea8fe0d030616'
                    ])->get('https://v3.football.api-sports.io/odds/', [
                        'fixture' => $match['fixture']['id'],
                        'season' => 2021,
                    ]);

                    if(isset($response['response'][0])) {
                        // Create odds
                        // Save odds to the match and the teams
                        foreach ($response['response'][0]['bookmakers'][0]['bets'][0]['values'] as $value) {
                            $odd = '';

                            if($value['value'] === 'Away') {
                                $awayOddData = array(
                                    'odd' => $value['odd'],
                                    'type' => 1,
                                    'side' => 2
                                );
                                $odd = Odd::create($awayOddData);
                                $teamAway->odds()->save($odd);
                            }
                            if($value['value'] === 'Home') {
                                $homeOddData = array(
                                    'odd' => $value['odd'],
                                    'type' => 1,
                                    'side' => 1
                                );
                                $odd = Odd::create($homeOddData);
                                $teamHome->odds()->save($odd);
                            }
                            if($value['value'] === 'Draw') {
                                $drawOddData = array(
                                    'odd' => $value['odd'],
                                    'type' => 1,
                                    'side' => 3
                                );
                                $odd = Odd::create($drawOddData);
                            }
                            if($odd) {
                                $newMatch->odds()->save($odd);
                            }
                        }
                    }
                }
            }
        }

        return view('matches.index', [
            'matches' => Match::whereBetween('matchDate', [Carbon::today(), Carbon::today()->addDay(7)])->orderBy('matchDate', 'ASC')->get()

        ]);
    }
}
