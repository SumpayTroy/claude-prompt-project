<?php

namespace App\Http\Controllers;

use App\Models\Contestant;
use App\Models\Score;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| DASHBOARD CONTROLLER
|--------------------------------------------------------------------------
| Route: GET /dashboard
| Gathers summary stats and passes them to the dashboard view.
|--------------------------------------------------------------------------
*/
class DashboardController extends Controller
{
    public function index()
    {
        // Count stats for the stat cards
        $totalContestants = Contestant::count();
        $totalJudges      = User::where('role', 'judge')->count();
        $totalScores      = Score::count();

        // Get judges with their scoring progress
        $judges = User::where('role', 'judge')
                      ->withCount('scores')   // adds scores_count property
                      ->get();

        // Top scoring contestant
        $topContestant = Contestant::with('scores')
                            ->get()
                            ->sortByDesc('average_score')
                            ->first();

        // Recent scores for the activity feed (latest 5)
        $recentScores = Score::with(['contestant', 'judge'])
                            ->latest()
                            ->take(5)
                            ->get();

        // Pass all data to the view
        // In blade you access these as $totalContestants, $judges, etc.
        return view('dashboard.index', compact(
            'totalContestants',
            'totalJudges',
            'totalScores',
            'judges',
            'topContestant',
            'recentScores'
        ));
    }
}
