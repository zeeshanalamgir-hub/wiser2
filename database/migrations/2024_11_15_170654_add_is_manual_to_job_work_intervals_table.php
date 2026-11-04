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
            $table->boolean('is_manual')->default(0)->after('status')->comment('1 = Manual Entry, 0 = Auto-tracked');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_work_intervals', function (Blueprint $table) {
            $table->dropColumn('is_manual');

        });
    }
};
