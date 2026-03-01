<?php

namespace App\Http\Controllers;

use App\Models\Contestant;

/*
|--------------------------------------------------------------------------
| CONTESTANT CONTROLLER
|--------------------------------------------------------------------------
| Route: GET /contestants
| Fetches all contestants from the database and passes to the view.
|--------------------------------------------------------------------------
*/
class ContestantController extends Controller
{
    public function index()
    {
        // Get all active contestants, load their scores relationship too
        // with('scores') = eager loading (avoids N+1 query problem)
        $contestants = Contestant::where('is_active', true)
                                 ->with('scores')
                                 ->orderBy('number')
                                 ->get();

        return view('contestants.index', compact('contestants'));
    }
}
