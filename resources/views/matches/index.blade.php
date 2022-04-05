<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Match of the next 7 days</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" >
</head>
<body>
    <div>
        <div class="flex">
            <h1> Match of the next 7 day for Ligue 1 </h1>
        </div>
        <div class="flex" id="todayDate">
            <h2> {{ date('Y') }}</h2>
        </div>
    </div>

    <div class="flex">
        <div style="width: 40%">
            @forelse ($matches as $match)
                <div id="match" class="margin-bottom">
                    <div class="flex" id="matchDate">
                        <p>{{ date('l d M H:i', strtotime($match->matchDate)) }} (GMT+2)</p>
                    </div>
                    <h3 class="flex">Teams :</h3>
                    <div class="flex space-between padding-left padding-right">
                        @foreach($match->teams as $team)
                            <div>
                                <span style="font-weight: bold">{{ $team->name }}</span>
                                @foreach($team->odds as $odd)
                                    @if($match->id === $odd->match->id)
                                        <p class="win-odds">Win odds : {{ $odd->odd }}</span></p>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    <div class="flex">
                        <div class="draw-odds">
                            <span>Draw odds : </span>
                            @foreach($match->odds as $odd)
                                @if($odd->side === 3)
                                    <span>{{ $odd->odd }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <h2 class="flex">No matches today</h2>
            @endforelse
        </div>
    </div>
</body>
</html>

