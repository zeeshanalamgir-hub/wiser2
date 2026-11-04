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
            if (Schema::hasColumn('job_work_delivery_houly_bid', 'is_manual')) {
                $table->dropColumn('is_manual');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_work_delivery_hourly_bid', function (Blueprint $table) {
            $table->tinyInteger('is_manual')->default(0)->comment('1 = Manual Entry, 0 = Auto-tracked');

        });
    }
};
