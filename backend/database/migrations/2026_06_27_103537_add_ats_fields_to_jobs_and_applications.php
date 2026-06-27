<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->boolean('is_urgent')->default(false);
            $table->unsignedInteger('views_count')->default(0);
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->integer('resume_score')->nullable();
            $table->integer('rating')->nullable();
            $table->text('employer_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['is_urgent', 'views_count']);
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['resume_score', 'rating', 'employer_notes']);
        });
    }
};
