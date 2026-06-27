<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For MySQL, the safest way to change an enum to a string without doctrine/dbal
        // is using raw SQL or by explicitly changing it to a string.
        // We will just recreate it as a string to hold any of the new statuses.
        Schema::table('applications', function (Blueprint $table) {
            $table->string('status')->default('new')->change();
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Note: Reverting back to ENUM might cause data loss if there are values not in the ENUM list.
            // Leaving as string or throwing an exception could be better, but we'll attempt rollback for safety.
            DB::statement("ALTER TABLE applications MODIFY status ENUM('pending', 'reviewed', 'rejected', 'accepted') DEFAULT 'pending'");
        });
    }
};
