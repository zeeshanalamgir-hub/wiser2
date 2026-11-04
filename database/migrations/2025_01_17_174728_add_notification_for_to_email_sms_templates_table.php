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
        Schema::table('email_sms_templates', function (Blueprint $table) {
            $table->unsignedTinyInteger('notification_for')->default(3)->comment('1: Buyer, 2: Seller, 3: Both, 4: Admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_sms_templates', function (Blueprint $table) {
            $table->dropColumn('notification_for');
        });
    }
};
