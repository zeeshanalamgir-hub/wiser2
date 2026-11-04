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
            $table->string('due_date', 255)->change(); // Change to VARCHAR with max length of 255

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buyer_counter_offers_data', function (Blueprint $table) {
            $table->date('due_date')->change(); // Revert back to DATE in case of rollback

        });
    }
};
