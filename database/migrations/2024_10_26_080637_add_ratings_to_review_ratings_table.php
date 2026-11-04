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
        Schema::table('review_ratings', function (Blueprint $table) {
            $table->integer('quality')->nullable();
            $table->integer('availability')->nullable();
            $table->integer('communication')->nullable();
            $table->integer('cooperation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_ratings', function (Blueprint $table) {
            $table->dropColumn(['quality', 'availability', 'communication', 'cooperation']);

        });
    }
};
