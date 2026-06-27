<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_candidates', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // applicant
            $table->text('notes')->nullable();
            $table->timestamps();

            // Prevent saving same candidate twice for a company
            $table->unique(['company_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_candidates');
    }
};
