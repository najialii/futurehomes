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
        Schema::create('designs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->default('general'); // e.g., 'interior', 'exterior', 'landscape', 'general'
            $table->string('image_path');
            $table->string('alt_text')->nullable();
            $table->integer('display_order')->default(0);
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->boolean('is_featured')->default(false);
            $table->json('tags')->nullable(); // For design tags like 'modern', 'classic', etc.
            $table->timestamps();
            
            $table->index(['status', 'display_order']);
            $table->index(['category', 'status']);
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designs');
    }
};