<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (
            Blueprint $table
        ) {

            $table->id();

            $table->string('name');

            $table->string('email')
                ->nullable();

            $table->string('seniority')
                ->nullable();

            $table->json('stack')
                ->nullable();

            $table->json('keywords')
                ->nullable();

            $table->boolean('english')
                ->default(false);

            $table->boolean('remote_only')
                ->default(false);

            $table->string('location')
                ->nullable();

            $table->integer('salary_expectation')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'user_profiles'
        );
    }
};