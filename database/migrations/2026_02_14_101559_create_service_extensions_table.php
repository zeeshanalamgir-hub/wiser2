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
        Schema::create('service_extensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('buyer_id')->comment('Buyer who accept the extension request')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->comment('Seller who request to extend the service')->constrained('users')->onDelete('cascade');
            $table->date('from_extension_date');
            $table->date('upto_extension_date');
            $table->date('change_status_date')->nullable();
            $table->longText('details')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_extensions');
    }
};
