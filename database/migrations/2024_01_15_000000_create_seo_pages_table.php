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
        Schema::create('seo_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique()->comment('Unique identifier for the page (e.g., home, about, contact)');
            $table->string('title')->nullable()->comment('Page title for SEO');
            $table->text('description')->nullable()->comment('Meta description');
            $table->text('keywords')->nullable()->comment('Meta keywords');
            $table->string('og_title')->nullable()->comment('Open Graph title');
            $table->text('og_description')->nullable()->comment('Open Graph description');
            $table->string('og_image')->nullable()->comment('Open Graph image URL');
            $table->json('extra')->nullable()->comment('Additional SEO data (structured data, etc.)');
            $table->boolean('is_active')->default(true)->comment('Whether this SEO page is active');
            $table->timestamps();

            $table->index(['page_key', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_pages');
    }
};

