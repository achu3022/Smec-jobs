<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {

            $table->id();
            $table->uuid("uuid")->unique();
            $table->foreignId("blog_category_id")->constrained()->cascadeOnDelete();
            $table->foreignId("author_id")->constrained("users")->cascadeOnDelete();
            $table->string("title");
            $table->string("slug")->unique();
            $table->longText("content");
            $table->string("image")->nullable();
            $table->timestamp("published_at")->nullable();
            $table->timestamps();
            $table->softDeletes();
                });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
