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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('job_work_delivery_hourly_bid_id'); // Assuming the foreign key exists
            $table->integer('keyboard_activity');
            $table->integer('mouse_activity');
            $table->timestamp('log_time')->useCurrent(); // Time of the log (UTC)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
