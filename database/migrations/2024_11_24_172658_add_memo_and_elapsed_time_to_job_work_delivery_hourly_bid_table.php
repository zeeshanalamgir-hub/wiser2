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
            $table->text('memo')->nullable()->after('end_time'); // Add memo column
            $table->integer('elapsed_time_in_seconds')->nullable()->after('memo'); // Add elapsed_time_in_seconds column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_work_delivery_houly_bid', function (Blueprint $table) {
            $table->dropColumn('memo'); // Remove memo column
            $table->dropColumn('elapsed_time_in_seconds'); // Remove elapsed_time_in_seconds column
        });
    }
};
