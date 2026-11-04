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
        Schema::table('employer_membership', function (Blueprint $table) {
            $table->decimal('service_platform_fee',10, 2)->nullable()->after('price')
                ->comment('Service Platform fee');
            $table->boolean('expertise_matching_mechanism')->default(true)->nullable()->after('jobtag')
                ->comment('Mechanism for matching the seller\'s expertise to the job requirements');
            $table->boolean('money_deposit_for_job')->default(true)->nullable()->after('expertise_matching_mechanism')
                ->comment('Requirement to deposit money that matches the cost of the job');
            $table->boolean('communication_tools')->default(true)->after('money_deposit_for_job')
                ->comment('Chat, share files, and place audio and video calls');
            $table->boolean('time_tracking_features')->default(true)->after('communication_tools')
                ->comment('Timesheets, time tracker');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employer_membership', function (Blueprint $table) {
            $table->dropColumn([
                'service_platform_fee',
                'expertise_matching_mechanism',
                'money_deposit_for_job',
                'communication_tools',
                'time_tracking_features'
            ]);
        });
    }
};
