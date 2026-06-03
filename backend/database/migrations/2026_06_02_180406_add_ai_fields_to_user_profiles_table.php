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
        Schema::table('user_profiles', function (Blueprint $table) {

            $table->integer('years_experience')
                ->nullable()
                ->after('seniority');

            $table->json('desired_roles')
                ->nullable()
                ->after('stack');

            $table->json('preferred_locations')
                ->nullable()
                ->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {

            $table->dropColumn([
                'years_experience',
                'desired_roles',
                'preferred_locations'
            ]);
        });
    }
};
