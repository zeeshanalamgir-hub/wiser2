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
            $table->time('start_time')->nullable();  // Optional column
            $table->time('end_time')->nullable();    // Optional column

            // Adding timezone as an integer for storing offset (+/-)
            $table->integer('timezone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_work_delivery_houly_bid', function (Blueprint $table) {
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
            $table->dropColumn('timezone');
        });
    }
};
