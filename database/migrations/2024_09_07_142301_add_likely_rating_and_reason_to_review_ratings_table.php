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
            $table->integer('likely_rating')->after('rating')->nullable(); // Likely rating (1-10 scale)
            $table->text('ending_contract_reason')->after('review')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_ratings', function (Blueprint $table) {
            //
        });
    }
};
