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
        Schema::table('job_work_delivery_houly_bid', function (Blueprint $table) {
            // Adding a new column to identify if the time is manual (1) or auto-tracked (0)
            $table->boolean('is_manual')->default(0)->after('end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_work_delivery_houly_bid', function (Blueprint $table) {
            $table->dropColumn('is_manual');
        });
    }
};
