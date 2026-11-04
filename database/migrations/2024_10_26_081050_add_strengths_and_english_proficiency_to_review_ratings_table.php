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
            $table->json('strengths')->nullable()->after('likely_rating'); // Store strengths as a JSON array
            $table->string('english_proficiency')->nullable()->after('strengths');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_ratings', function (Blueprint $table) {
            $table->dropColumn('strengths');
            $table->dropColumn('english_proficiency');
        });
    }
};
