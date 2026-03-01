<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| ADD ROLE TO USERS TABLE
|--------------------------------------------------------------------------
| Laravel already creates a users table by default.
| We just need to ADD a 'role' column to it.
| Roles: admin, judge, tabulator, audience
|--------------------------------------------------------------------------
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'judge', 'tabulator', 'audience'])
                  ->default('audience')
                  ->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
