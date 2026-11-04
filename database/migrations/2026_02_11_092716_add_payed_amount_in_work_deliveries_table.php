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
        Schema::table('work_deliveries', function (Blueprint $table) {
            $table->decimal('payed_amount', 10, 2)->default(0)->after('is_payable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_deliveries', function (Blueprint $table) {
            $table->dropColumn('payed_amount');
        });
    }
};
