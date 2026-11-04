<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Add new columns for buyer_id and seller_id
            $table->unsignedBigInteger('buyer_id')->after('id');
            $table->integer('seller_id')->after('job_biding_id');

            // Add the new booking_type column
            $table->string('booking_type')->nullable()->after('buyer_service_fee');
        });

        // Copy data from user_id to buyer_id and pro_id to seller_id
        DB::statement('UPDATE bookings SET buyer_id = user_id');
        DB::statement('UPDATE bookings SET seller_id = pro_id');

        // Drop the old columns
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('pro_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Re-add original columns
            $table->unsignedInteger('user_id')->after('id');
            $table->integer('pro_id')->after('job_biding_id');

            // Drop the new booking_type, buyer_id, and seller_id columns
            $table->dropColumn('booking_type');
            $table->dropColumn('buyer_id');
            $table->dropColumn('seller_id');
        });

        // Copy data back from buyer_id to user_id and seller_id to pro_id
        DB::statement('UPDATE bookings SET user_id = buyer_id');
        DB::statement('UPDATE bookings SET pro_id = seller_id');
    }
};
