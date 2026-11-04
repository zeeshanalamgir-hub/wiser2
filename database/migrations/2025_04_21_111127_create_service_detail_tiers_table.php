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
        Schema::create('service_detail_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('service_value_tier', 50)->nullable();
            $table->unsignedInteger('service_tier_id')->nullable();
            $table->unsignedInteger('service_detail_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_detail_tiers');
    }
};
