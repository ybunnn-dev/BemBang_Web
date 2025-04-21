<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateGetActiveFeaturesByRoomTypeProcedure extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS GetActiveFeaturesByRoomType;

            CREATE PROCEDURE GetActiveFeaturesByRoomType(IN input_room_type_id BIGINT)
            BEGIN
                SELECT 
                    f.feature_id,
                    f.feature_name,
                    f.feature_icon,
                    rf.feature_status,
                    rf.room_type_id
                FROM 
                    room_features rf
                JOIN 
                    features f ON rf.feature_id = f.feature_id
                WHERE 
                    rf.room_type_id = input_room_type_id
                    AND rf.feature_status = 'active'
                    AND f.status = 'active';
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS GetActiveFeaturesByRoomType;");
    }
}
