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
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key')->unique()->comment('Unique identifier for the setting');
            $table->text('setting_value')->nullable()->comment('Setting value');
            $table->string('setting_type')->default('text')->comment('Type of setting (text, boolean, json, etc.)');
            $table->text('description')->nullable()->comment('Description of what this setting does');
            $table->boolean('is_active')->default(true)->comment('Whether this setting is active');
            $table->timestamps();

            $table->index(['setting_key', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};

