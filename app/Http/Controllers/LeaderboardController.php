<?php

namespace App\Http\Controllers;

use App\Models\Contestant;

/*
|--------------------------------------------------------------------------
| LEADERBOARD CONTROLLER
|--------------------------------------------------------------------------
| Route: GET /leaderboard
| Gets all contestants sorted by their average score descending.
|--------------------------------------------------------------------------
*/
class LeaderboardController extends Controller
{
    public function index()
    {
        // Get contestants with their scores, then sort by average
        $contestants = Contestant::where('is_active', true)
                                 ->with('scores')
                                 ->get()
                                 ->sortByDesc('average_score')
                                 ->values(); // re-index after sort (0,1,2... for rank)

        $topScore = $contestants->first()?->average_score ?? 0;

        return view('leaderboard.index', compact('contestants', 'topScore'));
    }
}
