<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->text('contact_address')->nullable();
            $table->string('contact_instagram')->nullable();
            $table->string('contact_whatsapp')->nullable();
            $table->string('contact_tiktok')->nullable();
            $table->string('contact_youtube')->nullable();
            $table->text('contact_map_embed')->nullable();
            $table->boolean('is_contact_page')->default(false);
        });
    }

    
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'contact_phone',
                'contact_email',
                'contact_address',
                'contact_instagram',
                'contact_whatsapp',
                'contact_tiktok',
                'contact_youtube',
                'contact_map_embed',
                'is_contact_page'
            ]);
        });
    }
};
