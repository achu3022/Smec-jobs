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
        Schema::create('course_category_job_category', function (Blueprint $table) {
            $table->foreignId('job_category_id')->constrained('job_categories')->cascadeOnDelete();
            $table->foreignId('course_category_id')->constrained('course_categories')->cascadeOnDelete();
            $table->primary(['job_category_id', 'course_category_id'], 'job_course_category_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_category_job_category');
    }
};
