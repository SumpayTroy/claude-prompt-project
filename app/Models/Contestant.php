<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/*
|--------------------------------------------------------------------------
| CONTESTANT MODEL
|--------------------------------------------------------------------------
| A Model represents one table in your database.
| Think of it like an Entity class in Android Room.
|
| Laravel's Eloquent ORM lets you query the database using PHP
| instead of writing raw SQL. Examples:
|
|   Contestant::all()           → SELECT * FROM contestants
|   Contestant::find(1)         → SELECT * FROM contestants WHERE id = 1
|   Contestant::where('is_active', true)->get()  → with a WHERE clause
|--------------------------------------------------------------------------
*/
class Contestant extends Model
{
    use HasFactory;

    // These columns are allowed to be mass-assigned (e.g. Contestant::create([...]))
    // Always list columns here that users can fill in — security best practice
    protected $fillable = [
        'number',
        'name',
        'region',
        'emoji',
        'is_active',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    | Like @Relation in Android Room, this defines how tables connect.
    | A Contestant HAS MANY Scores (one score per judge per segment).
    |--------------------------------------------------------------------------
    */
    public function scores()
    {
        // One contestant → many score rows
        return $this->hasMany(Score::class);
    }

    /*
    |--------------------------------------------------------------------------
    | COMPUTED PROPERTY (Accessor)
    |--------------------------------------------------------------------------
    | This adds an 'average_score' property that doesn't exist in the DB,
    | but is calculated from related scores on the fly.
    | Access it like: $contestant->average_score
    |--------------------------------------------------------------------------
    */
    public function getAverageScoreAttribute(): float
    {
        $avg = $this->scores()->avg('average');
        return round($avg ?? 0, 1);
    }
}
