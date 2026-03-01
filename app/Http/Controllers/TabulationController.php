<?php

namespace App\Http\Controllers;

use App\Models\Contestant;
use App\Models\Score;
use App\Models\User;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| TABULATION CONTROLLER
|--------------------------------------------------------------------------
| Route: GET /tabulation
| Shows the full score matrix — all judges × all contestants.
|--------------------------------------------------------------------------
*/
class TabulationController extends Controller
{
    public function index(Request $request)
    {
        $segment     = $request->query('segment', 'Q&A Round');
        $segments    = ['Pre-Pageant', 'Swimwear', 'Formal Wear', 'Q&A Round', 'Talent Night'];

        $contestants = Contestant::where('is_active', true)
                                 ->orderBy('number')
                                 ->get();

        $judges      = User::where('role', 'judge')->get();

        // Build a lookup table: scores[contestant_id][judge_id] = Score
        // This avoids querying the DB inside a nested loop
        $scoresRaw   = Score::where('segment', $segment)->get();

        $scoreMatrix = [];
        foreach ($scoresRaw as $score) {
            $scoreMatrix[$score->contestant_id][$score->user_id] = $score;
        }

        return view('tabulation.index', compact(
            'contestants',
            'judges',
            'scoreMatrix',
            'segment',
            'segments'
        ));
    }
}
