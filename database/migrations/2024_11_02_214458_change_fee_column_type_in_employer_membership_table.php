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
            $table->decimal('fee', 10, 2)->change(); // Changing 'fee' to decimal with precision of 10 and scale of 2

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employer_membership', function (Blueprint $table) {
            $table->integer('fee')->change(); // Reverting 'fee' back to integer if rolled back

        });
    }
};
