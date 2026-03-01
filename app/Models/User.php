<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/*
|--------------------------------------------------------------------------
| USER MODEL
|--------------------------------------------------------------------------
| Laravel's built-in User model handles authentication.
| We just add the 'role' field and a helper method.
|--------------------------------------------------------------------------
*/
class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',    // auto-hashes password on save
    ];

    /*
    |--------------------------------------------------------------------------
    | ROLE HELPERS
    |--------------------------------------------------------------------------
    | Call these anywhere like: auth()->user()->isAdmin()
    |--------------------------------------------------------------------------
    */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isJudge(): bool
    {
        return $this->role === 'judge';
    }

    public function isTabulator(): bool
    {
        return $this->role === 'tabulator';
    }

    // A User (judge) HAS MANY scores they submitted
    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
