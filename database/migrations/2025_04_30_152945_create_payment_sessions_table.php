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
        Schema::create('payment_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('gateway_id');
            $table->decimal('amount', 10, 2);
            $table->string('payable_type')->nullable(); // e.g., 'service', 'deposit', 'package'
            $table->unsignedBigInteger('payable_id')->nullable(); // e.g., service_id or package_id
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_sessions');
    }
};
