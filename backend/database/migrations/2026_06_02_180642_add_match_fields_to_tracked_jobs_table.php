<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tracked_jobs', function (Blueprint $table) {

            $table->json('strengths')->nullable();

            $table->json('weaknesses')->nullable();

            $table->json('match_reasons')->nullable();

            $table->text('recommendation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tracked_jobs', function (Blueprint $table) {

            $table->dropColumn([
                'strengths',
                'weaknesses',
                'match_reasons',
                'recommendation'
            ]);
        });
    }
};
