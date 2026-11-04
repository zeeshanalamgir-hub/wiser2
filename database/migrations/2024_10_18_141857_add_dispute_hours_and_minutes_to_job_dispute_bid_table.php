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
        Schema::table('job_dispute_bid', function (Blueprint $table) {
            $table->integer('dispute_hours')->after('dispute_amount')->nullable();
            $table->integer('dispute_minutes')->after('dispute_hours')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_dispute_bid', function (Blueprint $table) {
            $table->dropColumn(['dispute_hours', 'dispute_minutes']);
        });
    }
};
