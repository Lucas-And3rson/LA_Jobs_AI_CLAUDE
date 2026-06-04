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
        Schema::create('generated_resumes', function (Blueprint $table) {

            $table->id();

            $table->foreignId('tracked_job_id')
                ->constrained('tracked_jobs')
                ->cascadeOnDelete();

            $table->integer('ats_score')->default(0);

            $table->string('file_path');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_resumes');
    }
};
