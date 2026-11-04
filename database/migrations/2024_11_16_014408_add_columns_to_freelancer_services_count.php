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
        Schema::table('freelancer_services_count', function (Blueprint $table) {
            $table->integer('annual_services_rollover')->default(0);
            $table->integer('annual_bids_rollover')->default(0);
            $table->integer('portfolio_size')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('freelancer_services_count', function (Blueprint $table) {
            $table->dropColumn('annual_services_rollover');
            $table->dropColumn('annual_bids_rollover');
            $table->dropColumn('portfolio_size');
        });
    }
};
