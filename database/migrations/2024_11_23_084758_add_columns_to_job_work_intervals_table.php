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
        Schema::table('job_work_intervals', function (Blueprint $table) {
            $table->decimal('cost', 10, 2)->nullable()->after('screenshot_path')->comment('Cost of the interval based on activity level');
            $table->tinyInteger('activity_level')->nullable()->after('cost')->comment('Activity level calculated from mouse and keyboard activity');
            $table->boolean('is_counted')->default(false)->after('activity_level')->comment('Indicates if the interval is counted towards job cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_work_intervals', function (Blueprint $table) {
            $table->dropColumn(['cost', 'activity_level', 'is_counted']);
        });
    }
};
