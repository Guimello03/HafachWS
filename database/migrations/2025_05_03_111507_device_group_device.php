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
        Schema::create('device_device_group', function (Blueprint $table) {
            $table->uuid('device_id');
            $table->uuid('device_group_id');
            $table->timestamps();
        
            $table->primary(['device_id', 'device_group_id']);
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->foreign('device_group_id')->references('id')->on('device_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_device_group');
    }
};
