<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomManagementTables extends Migration
{
    public function up()
    {
        Schema::create('room_types', function (Blueprint $table) {
            $table->id('room_type_id');
            $table->string('type_name');
            $table->text('description')->nullable();
            $table->bigInteger('guest_num');
            $table->string('status')->default('active'); // Status column added
            $table->timestamps();
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->bigIncrements('room_id');
            $table->integer('room_no');
            $table->unsignedBigInteger('room_type_id');
            $table->string('status')->default('available'); // Status column added (Available, Reserved, etc.)
            $table->timestamps();
            
            $table->foreign('room_type_id')->references('room_type_id')->on('room_types')->onDelete('cascade');
        });

        Schema::create('features', function (Blueprint $table) {
            $table->id('feature_id');
            $table->string('feature_name');
            $table->string('feature_icon')->nullable();
            $table->string('status')->default('active'); // Status column added
            $table->timestamps();
        });

        Schema::create('room_features', function (Blueprint $table) {
            $table->unsignedBigInteger('room_type_id');
            $table->unsignedBigInteger('feature_id');
            $table->timestamps();
            
            $table->foreign('room_type_id')->references('room_type_id')->on('room_types')->onDelete('cascade');
            $table->foreign('feature_id')->references('feature_id')->on('features')->onDelete('cascade');
            
            $table->primary(['room_type_id', 'feature_id']);
        });

        Schema::create('room_rates', function (Blueprint $table) {
            $table->id('room_rate_id');
            $table->unsignedBigInteger('room_type_id');
            $table->integer('hours');
            $table->decimal('amount', 10, 2); // Added this line for the price
            $table->string('status')->default('active');
            $table->timestamps();
        
            $table->foreign('room_type_id')->references('room_type_id')->on('room_types')->onDelete('cascade');
        });
        
        Schema::create('room_images', function (Blueprint $table) {
            $table->bigIncrements('room_image_id');
            $table->unsignedBigInteger('room_type_id');
            $table->string('file_path');
            $table->boolean('main_indicator')->default(0); // 1 for main, 0 for others
            $table->string('status')->default('active'); // Status column added
            $table->timestamps();
            
            $table->foreign('room_type_id')->references('room_type_id')->on('room_types')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('room_images');
        Schema::dropIfExists('room_rates');
        Schema::dropIfExists('room_features');
        Schema::dropIfExists('features');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('room_types');
    }
}
