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
        Schema::create('freelancer_payment_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_type')->unique(); // To store 'Monthly' or 'Annually'
            $table->timestamps();
        });
        DB::table('freelancer_payment_plans')->insert([
            ['plan_type' => 'Monthly'],
            ['plan_type' => 'Annually']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freelancer_payment_plans');
    }
};
