<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_course_mappings', function (Blueprint $table) {

            $table->foreignId("job_category_id")->constrained()->cascadeOnDelete();
            $table->foreignId("course_id")->constrained()->cascadeOnDelete();
            $table->primary(["job_category_id", "course_id"]);
                });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_course_mappings');
    }
};
