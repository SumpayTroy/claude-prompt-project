<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| CREATE CONTESTANTS TABLE
|--------------------------------------------------------------------------
| Think of migrations like a "blueprint" for your database table.
| Running `php artisan migrate` creates the actual table in SQLite.
| Running `php artisan migrate:rollback` deletes it (like undo).
|--------------------------------------------------------------------------
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contestants', function (Blueprint $table) {
            $table->id();                           // auto-increment primary key (like _id in Android Room)
            $table->string('number', 5);            // e.g. "01", "02"
            $table->string('name');                 // full name
            $table->string('region');               // e.g. "Luzon", "NCR"
            $table->string('emoji')->default('👸'); // avatar emoji
            $table->boolean('is_active')->default(true);
            $table->timestamps();                   // created_at and updated_at (Laravel adds these automatically)
        });
    }

    // This runs when you rollback the migration — drops the table
    public function down(): void
    {
        Schema::dropIfExists('contestants');
    }
};
