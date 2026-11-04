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
        Schema::table('buyer_counter_offers_data', function (Blueprint $table) {
            $table->boolean('requested_milestone_status')->after('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buyer_counter_offers_data', function (Blueprint $table) {
            $table->dropColumn('requested_milestone_status');
        });
    }
};
