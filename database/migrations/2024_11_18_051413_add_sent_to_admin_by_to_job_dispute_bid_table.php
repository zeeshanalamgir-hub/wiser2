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
        Schema::table('job_dispute_bid', function (Blueprint $table) {
            $table->string('sent_to_admin_by', 50)->nullable()->after('buyer_id')
                ->comment('Indicates who sent the dispute to the admin: Buyer or Seller');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_dispute_bid', function (Blueprint $table) {
            $table->dropColumn('sent_to_admin_by');

        });
    }
};
