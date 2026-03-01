<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Contestant;
use App\Models\Score;

/*
|--------------------------------------------------------------------------
| DATABASE SEEDER
|--------------------------------------------------------------------------
| Seeders fill your database with test/sample data.
| Run with: php artisan db:seed
| Or reset + seed fresh: php artisan migrate:fresh --seed
|--------------------------------------------------------------------------
*/
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── CREATE USERS (one per role) ──────────────────────────────
        $admin = User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@pageant.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $judges = [];
        foreach ([
            ['name' => 'Judge A. Reyes',  'email' => 'judge1@pageant.com'],
            ['name' => 'Judge M. Garcia', 'email' => 'judge2@pageant.com'],
            ['name' => 'Judge S. Tan',    'email' => 'judge3@pageant.com'],
            ['name' => 'Judge R. Santos', 'email' => 'judge4@pageant.com'],
        ] as $j) {
            $judges[] = User::create([
                'name'     => $j['name'],
                'email'    => $j['email'],
                'password' => Hash::make('password'),
                'role'     => 'judge',
            ]);
        }

        User::create([
            'name'     => 'Tabulator',
            'email'    => 'tabulator@pageant.com',
            'password' => Hash::make('password'),
            'role'     => 'tabulator',
        ]);

        User::create([
            'name'     => 'Audience Viewer',
            'email'    => 'audience@pageant.com',
            'password' => Hash::make('password'),
            'role'     => 'audience',
        ]);

        // ── CREATE CONTESTANTS ───────────────────────────────────────
        $contestants = [
            ['number' => '01', 'name' => 'Sofia Reyes',    'region' => 'Luzon',           'emoji' => '👸'],
            ['number' => '02', 'name' => 'Maria Santos',   'region' => 'Visayas',         'emoji' => '🌸'],
            ['number' => '03', 'name' => 'Ana Cruz',       'region' => 'Mindanao',        'emoji' => '💫'],
            ['number' => '04', 'name' => 'Lea Torres',     'region' => 'NCR',             'emoji' => '✨'],
            ['number' => '05', 'name' => 'Rosa Lim',       'region' => 'CAR',             'emoji' => '🌟'],
            ['number' => '06', 'name' => 'Carmen Reyes',   'region' => 'Bicol',           'emoji' => '💎'],
            ['number' => '07', 'name' => 'Isabella Go',    'region' => 'Western Visayas', 'emoji' => '🎀'],
            ['number' => '08', 'name' => 'Gabrielle Cruz', 'region' => 'Eastern Visayas', 'emoji' => '🦋'],
        ];

        foreach ($contestants as $data) {
            $contestant = Contestant::create($data);

            // Give each contestant a score from each judge
            foreach ($judges as $judge) {
                Score::create([
                    'contestant_id' => $contestant->id,
                    'user_id'       => $judge->id,
                    'segment'       => 'Q&A Round',
                    'beauty'        => rand(75, 98),
                    'intelligence'  => rand(75, 98),
                    'talent'        => rand(75, 98),
                    'qa'            => rand(75, 98),
                    // 'average' is auto-calculated by Score model's booted() method
                ]);
            }
        }
    }
}
