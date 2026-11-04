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
            $table->integer('disputed_hours')->default(0)->after('minutes');
            $table->integer('disputed_minutes')->default(0)->after('disputed_hours');
            $table->decimal('disputed_amount', 10, 2)->default(0.00)->after('disputed_minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_work_delivery_houly_bid', function (Blueprint $table) {
            $table->dropColumn('disputed_hours');
            $table->dropColumn('disputed_minutes');
            $table->dropColumn('disputed_amount');
        });
    }
};
