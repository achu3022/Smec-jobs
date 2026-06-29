<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {

            $table->id();
            $table->uuid("uuid")->unique();
            $table->foreignId("job_id")->constrained()->cascadeOnDelete();
            $table->foreignId("user_id")->constrained()->cascadeOnDelete();
            $table->foreignId("resume_id")->nullable(); // constrained later or manually
            $table->text("cover_letter")->nullable();
            $table->enum("status", ["pending", "reviewed", "rejected", "accepted"])->default("pending");
            $table->timestamps();
            $table->softDeletes();
                });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
