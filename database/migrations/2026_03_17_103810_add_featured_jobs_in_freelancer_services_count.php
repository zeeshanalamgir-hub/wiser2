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
            $table->string('featured_job_bids')->nullable()->after('bids');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('freelancer_services_count', function (Blueprint $table) {
            $table->dropColumn('featured_job_bids');
        });
    }
};
