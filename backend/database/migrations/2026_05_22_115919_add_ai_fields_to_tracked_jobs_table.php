<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tracked_jobs', function (Blueprint $table) {

            $table->string('seniority')
                ->nullable();

            $table->json('stack')
                ->nullable();

            $table->json('keywords')
                ->nullable();

            $table->integer('match_score')
                ->nullable();

            $table->boolean('english_required')
                ->default(false);

            $table->boolean('remote')
                ->default(false);

            $table->text('ai_summary')
                ->nullable();

            $table->boolean('ai_processed')
                ->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('tracked_jobs', function (Blueprint $table) {

            $table->dropColumn([
                'seniority',
                'stack',
                'keywords',
                'match_score',
                'english_required',
                'remote',
                'ai_summary',
                'ai_processed'
            ]);
        });
    }
};