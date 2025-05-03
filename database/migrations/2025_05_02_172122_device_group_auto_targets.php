<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('device_group_auto_targets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('device_group_id');
            $table->string('target_type'); // Exemplo: App\Models\Student
            $table->timestamps();

            $table->foreign('device_group_id')->references('id')->on('device_groups')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('device_group_auto_targets');
    }
};