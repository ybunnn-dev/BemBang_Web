<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeatureStatusToRoomFeaturesTable extends Migration
{
    public function up(): void
    {
        Schema::table('room_features', function (Blueprint $table) {
            $table->string('feature_status')->default('active')->after('feature_id'); // or after any existing column
        });
    }

    public function down(): void
    {
        Schema::table('room_features', function (Blueprint $table) {
            $table->dropColumn('feature_status');
        });
    }
}
