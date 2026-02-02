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
        Schema::create('page_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->onDelete('cascade');
            $table->integer('version_number');
            $table->string('title');
            $table->longText('content');
            $table->string('meta_description')->nullable();
            $table->json('changes')->nullable(); // Track what changed
            $table->string('created_by')->nullable(); // User who made the change
            $table->timestamps();

            $table->index(['page_id', 'version_number']);
            $table->unique(['page_id', 'version_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_versions');
    }
};
