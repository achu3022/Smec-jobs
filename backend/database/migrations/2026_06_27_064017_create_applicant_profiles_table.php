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
        Schema::create('applicant_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Personal
            $table->string('photo')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('nationality')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('aadhaar')->nullable();
            $table->string('pan')->nullable();
            $table->string('passport')->nullable();
            $table->string('driving_licence')->nullable();
            $table->string('voter_id')->nullable();

            // Contact
            $table->string('mobile')->nullable();
            $table->string('alt_mobile')->nullable();
            $table->text('address_current')->nullable();
            $table->text('address_permanent')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('pin_code')->nullable();

            // Career
            $table->string('job_title')->nullable();
            $table->string('total_experience')->nullable();
            $table->string('relevant_experience')->nullable();
            $table->string('current_company')->nullable();
            $table->string('current_designation')->nullable();
            $table->decimal('current_salary', 10, 2)->nullable();
            $table->decimal('expected_salary', 10, 2)->nullable();
            $table->string('notice_period')->nullable();
            $table->boolean('immediate_joiner')->default(false);
            $table->string('pref_location')->nullable();
            $table->string('pref_industry')->nullable();
            $table->string('pref_employment_type')->nullable();
            $table->boolean('willing_to_relocate')->default(false);
            $table->boolean('willing_to_travel')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_profiles');
    }
};
