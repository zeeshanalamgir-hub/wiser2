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
        Schema::table('buyer_counter_offers', function (Blueprint $table) {
            $table->boolean('close_job')->default(false)->after('status'); // Replace 'some_existing_column' with the actual column name
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buyer_counter_offers', function (Blueprint $table) {
            $table->dropColumn('close_job');
        });
    }
};
