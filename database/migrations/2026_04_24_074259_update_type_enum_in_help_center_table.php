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
        Schema::table('help_centers', function (Blueprint $table) {
            DB::statement("
                ALTER TABLE help_centers 
                MODIFY COLUMN type 
                ENUM('buyer', 'seller', 'policy', 'terms') 
                NOT NULL
            ");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('help_centers', function (Blueprint $table) {
            DB::statement("
                ALTER TABLE help_centers
                MODIFY COLUMN type 
                ENUM('buyer', 'seller', 'policy') 
                NOT NULL
            ");
        });
    }
};
