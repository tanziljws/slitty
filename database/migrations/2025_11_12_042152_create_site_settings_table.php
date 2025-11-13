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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // unique key identifier
            $table->string('label'); // display label for admin
            $table->string('type')->default('text'); // text, textarea, editor, image, etc.
            $table->text('value')->nullable(); // the actual content
            $table->string('group')->default('general'); // profil, visi_misi, contact, etc.
            $table->text('description')->nullable(); // helper text for admin
            $table->integer('order')->default(0); // sorting order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
