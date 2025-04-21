<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE VIEW room_types_with_main_image AS
            SELECT 
                rt.room_type_id,
                rt.type_name,
                rt.description,
                rt.guest_num,
                rt.status,
                ri.file_path AS main_image,

                -- First feature name (if any)
                (
                    SELECT f.feature_name
                    FROM room_features rf
                    JOIN features f ON f.feature_id = rf.feature_id
                    WHERE rf.room_type_id = rt.room_type_id
                    LIMIT 1
                ) AS sample_feature,

                -- Check-in 12-hour rate
                (
                    SELECT rr.amount
                    FROM room_rates rr
                    WHERE rr.room_type_id = rt.room_type_id 
                    AND rr.hours = 12 
                    AND rr.rate_type = 'check-in'
                    LIMIT 1
                ) AS checkin_rate_12_hours,

                -- Check-in 24-hour rate
                (
                    SELECT rr.amount
                    FROM room_rates rr
                    WHERE rr.room_type_id = rt.room_type_id 
                    AND rr.hours = 24 
                    AND rr.rate_type = 'check-in'
                    LIMIT 1
                ) AS checkin_rate_24_hours,

                -- Reservation 12-hour rate
                (
                    SELECT rr.amount
                    FROM room_rates rr
                    WHERE rr.room_type_id = rt.room_type_id 
                    AND rr.hours = 12 
                    AND rr.rate_type = 'reservation'
                    LIMIT 1
                ) AS reservation_rate_12_hours,

                -- Reservation 24-hour rate
                (
                    SELECT rr.amount
                    FROM room_rates rr
                    WHERE rr.room_type_id = rt.room_type_id 
                    AND rr.hours = 24 
                    AND rr.rate_type = 'reservation'
                    LIMIT 1
                ) AS reservation_rate_24_hours

            FROM 
                room_types rt
            JOIN 
                room_images ri ON rt.room_type_id = ri.room_type_id
            WHERE 
                ri.main_indicator = 1;
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS room_types_with_main_image;");
    }
};
