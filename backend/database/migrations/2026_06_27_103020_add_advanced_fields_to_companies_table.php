<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('cover_image')->nullable();
            $table->string('company_size')->nullable();
            $table->string('year_established')->nullable();
            $table->string('headquarters')->nullable();
            $table->json('branch_locations')->nullable();
            $table->string('contact_person')->nullable();
            $table->json('hr_contact_details')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->json('verification_documents')->nullable();
            $table->json('social_links')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'cover_image',
                'company_size',
                'year_established',
                'headquarters',
                'branch_locations',
                'contact_person',
                'hr_contact_details',
                'is_verified',
                'verification_documents',
                'social_links'
            ]);
        });
    }
};
