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
        Schema::table('applicant_profiles', function (Blueprint $table) {
            $table->text('skills')->nullable();
            $table->text('languages')->nullable();
            $table->text('projects')->nullable();
            $table->text('certifications')->nullable();
            $table->text('social_links')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicant_profiles', function (Blueprint $table) {
            $table->dropColumn(['skills', 'languages', 'projects', 'certifications', 'social_links']);
        });
    }
};
