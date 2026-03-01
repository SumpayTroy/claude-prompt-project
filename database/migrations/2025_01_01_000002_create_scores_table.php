<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| CREATE SCORES TABLE
|--------------------------------------------------------------------------
| Each row = one judge's score for one contestant in one segment.
| A contestant can have many scores (one per judge per segment).
|--------------------------------------------------------------------------
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();

            // Foreign keys — like a relationship in Android Room @ForeignKey
            $table->foreignId('contestant_id')      // which contestant
                  ->constrained()                   // references contestants.id
                  ->onDelete('cascade');            // if contestant deleted, scores deleted too

            $table->foreignId('user_id')            // which judge submitted this score
                  ->constrained()
                  ->onDelete('cascade');

            // The scoring criteria
            $table->string('segment');              // e.g. "Q&A Round", "Talent Night"
            $table->decimal('beauty',    5, 2)->default(0);  // score out of 100
            $table->decimal('intelligence', 5, 2)->default(0);
            $table->decimal('talent',    5, 2)->default(0);
            $table->decimal('qa',        5, 2)->default(0);

            // Computed average (stored so leaderboard queries are fast)
            $table->decimal('average',   5, 2)->default(0);

            $table->timestamps();

            // A judge can only submit ONE score per contestant per segment
            $table->unique(['contestant_id', 'user_id', 'segment']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
