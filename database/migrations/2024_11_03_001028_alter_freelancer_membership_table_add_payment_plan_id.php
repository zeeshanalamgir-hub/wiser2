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
        Schema::table('freelancer_membership', function (Blueprint $table) {
            $table->unsignedBigInteger('freelancer_payment_plan_id')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('freelancer_membership', function (Blueprint $table) {
            $table->dropColumn('freelancer_payment_plan_id');

        });
    }
};
