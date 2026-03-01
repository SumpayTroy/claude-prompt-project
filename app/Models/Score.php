<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/*
|--------------------------------------------------------------------------
| SCORE MODEL
|--------------------------------------------------------------------------
| Represents one judge's score for one contestant in one segment.
|--------------------------------------------------------------------------
*/
class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'contestant_id',
        'user_id',
        'segment',
        'beauty',
        'intelligence',
        'talent',
        'qa',
        'average',
    ];

    // Cast these columns to floats automatically when you read them
    protected $casts = [
        'beauty'        => 'float',
        'intelligence'  => 'float',
        'talent'        => 'float',
        'qa'            => 'float',
        'average'       => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // A Score BELONGS TO one Contestant
    public function contestant()
    {
        return $this->belongsTo(Contestant::class);
    }

    // A Score BELONGS TO one User (the judge who submitted it)
    public function judge()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER: Auto-calculate average before saving
    |--------------------------------------------------------------------------
    | This runs automatically every time a Score is saved.
    | Weights: Beauty 25%, Intelligence 25%, Talent 30%, Q&A 20%
    |--------------------------------------------------------------------------
    */
    protected static function booted(): void
    {
        static::saving(function (Score $score) {
            $score->average = round(
                ($score->beauty       * 0.25) +
                ($score->intelligence * 0.25) +
                ($score->talent       * 0.30) +
                ($score->qa           * 0.20),
                2
            );
        });
    }
}
