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
        Schema::table('job_work_intervals', function (Blueprint $table) {
            $table->integer('total_minutes')->after('cost')->nullable()->comment('Total minutes worked in this interval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_work_intervals', function (Blueprint $table) {
            $table->dropColumn('total_minutes');
        });
    }
};
