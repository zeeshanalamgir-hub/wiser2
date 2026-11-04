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
        Schema::table('jobs', function (Blueprint $table) {
            // Drop the existing 'delivery_time' column
            $table->dropColumn('delivery_time');

            // Add the new 'delivery_date' column with datetime type
            $table->dateTime('delivery_date')->nullable();

            // Drop 'minBudget' and 'maxBudget' columns
            $table->dropColumn('minBudget');
            $table->dropColumn('maxBudget');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            // Add the 'delivery_time' column back as an integer
            $table->integer('delivery_time')->nullable();

            // Drop the 'delivery_date' column
            $table->dropColumn('delivery_date');

            // Add 'minBudget' and 'maxBudget' columns back
            $table->integer('minBudget')->nullable();
            $table->integer('maxBudget')->nullable();
        });
    }
};
