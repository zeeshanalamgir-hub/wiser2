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
            $table->boolean('invite_professional')->default(false)->after('status'); // Add the new column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buyer_counter_offers', function (Blueprint $table) {
            $table->dropColumn('invite_professional');
        });
    }
};
