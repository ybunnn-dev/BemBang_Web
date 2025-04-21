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
        Schema::table('room_rates', function (Blueprint $table) {
            $table->enum('rate_type', ['check-in', 'reservation'])->default('check-in')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_rates', function (Blueprint $table) {
            $table->dropColumn('rate_type');
        });
    }
};
