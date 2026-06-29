<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {

            $table->id();
            $table->uuid("uuid")->unique();
            $table->foreignId("company_id")->constrained()->cascadeOnDelete();
            $table->foreignId("job_category_id")->constrained()->cascadeOnDelete();
            $table->string("title");
            $table->longText("description");
            $table->string("location");
            $table->decimal("salary_min", 10, 2)->nullable();
            $table->decimal("salary_max", 10, 2)->nullable();
            $table->string("job_type");
            $table->string("experience_level")->nullable();
            $table->string("education_level")->nullable();
            $table->boolean("is_remote")->default(false);
            $table->boolean("is_featured")->default(false);
            $table->boolean("is_active")->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->fullText(["title", "location"]);
                });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
