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
        Schema::table('bookings', function (Blueprint $table) {
            // Add the new job_id column
            $table->unsignedBigInteger('job_id')->nullable()->after('service_id');

            // Remove the job_biding_id column
            $table->dropColumn('job_biding_id');

            // Optional: If you want to set up a foreign key for job_id
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Add back the job_biding_id column
            $table->unsignedBigInteger('job_biding_id')->nullable();

            // Drop the job_id column
            $table->dropForeign(['job_id']); // Drop the foreign key first if exists
            $table->dropColumn('job_id');
        });
    }
};
