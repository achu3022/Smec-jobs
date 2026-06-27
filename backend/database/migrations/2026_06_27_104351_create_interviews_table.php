<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->dateTime('scheduled_at');
            $table->string('interviewer_name')->nullable();
            $table->string('location_url')->nullable(); // Zoom, Google Meet link or Physical Address
            $table->string('status')->default('scheduled'); // scheduled, completed, cancelled, no_show
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
