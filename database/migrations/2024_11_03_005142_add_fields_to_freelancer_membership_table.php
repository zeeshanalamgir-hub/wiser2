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
            $table->text('annual_services_rollover')->nullable(); // For Annual Services Rollover
            $table->tinyInteger('purchase_service')->default(0); // For Purchase Additional Service
            $table->text('annual_bids_rollover')->nullable(); // For Annual Bids Rollover
            $table->tinyInteger('purchase_additional_bids')->default(0); // For Purchase Additional Bids
            $table->integer('portfolio_size')->default(0)->change(); // For Portfolio Slots Limit
            $table->boolean('verifiedaccount')->default(0)->change(); // For Verified Account
            $table->boolean('support')->default(0)->change(); // For Priority Support
            $table->boolean('safe_payment')->default(0)->change();
            $table->double('service_cost', 8, 2)->nullable()->after('fee'); // Add service_cost field

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('freelancer_membership', function (Blueprint $table) {
            $table->dropColumn('annual_services_rollover');
            $table->dropColumn('purchase_service');
            $table->dropColumn('annual_bids_rollover');
            $table->dropColumn('purchase_additional_bids');
            $table->dropColumn('verified_account');
            $table->dropColumn('support');
            $table->dropColumn('safe_payment');
            $table->dropColumn('service_cost');
        });
    }
};
