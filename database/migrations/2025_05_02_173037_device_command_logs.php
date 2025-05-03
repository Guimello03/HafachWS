<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('device_command_logs', function (Blueprint $table) {
            $table->uuid('device_id');
            $table->uuid('device_group_command_id');
            $table->enum('status', ['pending', 'success', 'error'])->default('pending');
            $table->timestamp('executed_at')->nullable();
            $table->timestamps();

            $table->primary(['device_id', 'device_group_command_id']);
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->foreign('device_group_command_id')->references('id')->on('device_group_commands')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('device_command_logs');
    }
};
