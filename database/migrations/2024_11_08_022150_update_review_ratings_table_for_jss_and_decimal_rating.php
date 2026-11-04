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
            // Add jss column for Job Success Score, defaulting to null
            $table->decimal('jss', 5, 2)->nullable()->after('rating');

            // Modify rating column to accept decimal values (range: 0.00 - 5.00)
            $table->decimal('rating', 3, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_ratings', function (Blueprint $table) {
            // Drop the jss column
            $table->dropColumn('jss');

            // Revert rating column back to tinyint
            $table->tinyInteger('rating')->change();
        });
    }
};
