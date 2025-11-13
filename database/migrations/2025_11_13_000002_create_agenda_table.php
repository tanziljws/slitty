<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agenda', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('date_label'); // e.g., "Senin", "Periode Akhir Semester"
            $table->string('time'); // e.g., "08:00 - 10:00 WIB"
            $table->date('event_date')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda');
    }
};
