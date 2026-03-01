<?php

namespace App\Http\Controllers;

use App\Models\Contestant;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| SCORE CONTROLLER
|--------------------------------------------------------------------------
| GET  /scoring → show the scoring panel
| POST /scoring → save a judge's submitted scores
|--------------------------------------------------------------------------
*/
class ScoreController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW SCORING PANEL
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $contestants = Contestant::where('is_active', true)
                                 ->orderBy('number')
                                 ->get();

        // Which contestant is currently selected?
        // Defaults to the first one if none selected
        $selectedId  = $request->query('contestant', $contestants->first()?->id);
        $selected    = Contestant::find($selectedId);

        // Which segment are we scoring?
        $segment     = $request->query('segment', 'Q&A Round');

        $segments    = ['Pre-Pageant', 'Swimwear', 'Formal Wear', 'Q&A Round', 'Talent Night'];

        // Has this judge already scored this contestant in this segment?
        $existing = Score::where('contestant_id', $selectedId)
                         ->where('user_id', Auth::id())
                         ->where('segment', $segment)
                         ->first();

        return view('scoring.index', compact(
            'contestants',
            'selected',
            'segment',
            'segments',
            'existing'      // pre-fills the form if score already submitted
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE / UPDATE SCORES
    |--------------------------------------------------------------------------
    | Called when the judge clicks "Submit Scores"
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        // 1. Validate all score inputs
        $data = $request->validate([
            'contestant_id' => ['required', 'exists:contestants,id'],
            'segment'       => ['required', 'string'],
            'beauty'        => ['required', 'numeric', 'min:0', 'max:100'],
            'intelligence'  => ['required', 'numeric', 'min:0', 'max:100'],
            'talent'        => ['required', 'numeric', 'min:0', 'max:100'],
            'qa'            => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        // 2. updateOrCreate = INSERT if not exists, UPDATE if already exists
        //    First array = find by these columns
        //    Second array = set these values
        Score::updateOrCreate(
            [
                'contestant_id' => $data['contestant_id'],
                'user_id'       => Auth::id(),
                'segment'       => $data['segment'],
            ],
            [
                'beauty'        => $data['beauty'],
                'intelligence'  => $data['intelligence'],
                'talent'        => $data['talent'],
                'qa'            => $data['qa'],
                // 'average' is auto-calculated by Score model's booted() method
            ]
        );

        // 3. Redirect back with a success message
        //    ->with() flashes a message to the session (one-time message)
        return redirect()
            ->route('scoring.index', [
                'contestant' => $data['contestant_id'],
                'segment'    => $data['segment'],
            ])
            ->with('success', 'Scores submitted successfully!');
    }
}
