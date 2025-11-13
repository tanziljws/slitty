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
        Schema::table('user_likes', function (Blueprint $table) {
            $table->dropForeign(['foto_id']);
            $table->dropUnique(['user_id', 'foto_id']);
            $table->dropColumn('user_id');
            $table->string('user_id')->after('id');
            $table->unique(['user_id', 'foto_id']);
            $table->foreign('foto_id')->references('id')->on('foto')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_likes', function (Blueprint $table) {
            $table->dropForeign(['foto_id']);
            $table->dropUnique(['user_id', 'foto_id']);
            $table->dropColumn('user_id');
            $table->unsignedBigInteger('user_id')->after('id');
            $table->unique(['user_id', 'foto_id']);
            $table->foreign('foto_id')->references('id')->on('foto')->onDelete('cascade');
        });
    }
};