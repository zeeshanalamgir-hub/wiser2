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
            $table->text('request_description')->nullable()->after('allow_manual_time'); // Replace 'column_name' with the column after which you want to add this field.

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buyer_counter_offers', function (Blueprint $table) {
            $table->dropColumn('request_description');

        });
    }
};
