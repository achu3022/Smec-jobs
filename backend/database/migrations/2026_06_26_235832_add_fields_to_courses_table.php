<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('course_code')->nullable();
            $table->decimal('fee_1', 10, 2)->nullable();
            $table->decimal('fee_2', 10, 2)->nullable();
            $table->decimal('fee_3', 10, 2)->nullable();
            $table->string('qualifications')->nullable();
            $table->boolean('placement_assistance')->default(false);
            
            // Note: Making descriptions and provider nullable since the raw data doesn't provide them reliably
            $table->text('description')->nullable()->change();
            $table->string('provider')->nullable()->change();
            $table->string('url')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['course_code', 'fee_1', 'fee_2', 'fee_3', 'qualifications', 'placement_assistance']);
            
            $table->text('description')->nullable(false)->change();
            $table->string('provider')->nullable(false)->change();
            $table->string('url')->nullable(false)->change();
        });
    }
};
