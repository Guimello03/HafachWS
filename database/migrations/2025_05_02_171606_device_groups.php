<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('device_groups', function (Blueprint $table) {
            $table->uuid('uuid')->primary(); // ✅ padrão unificado
            $table->uuid('school_id');
            $table->string('name');
            
            $table->timestamps();

            $table->foreign('school_id')->references('uuid')->on('schools')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('device_groups');
    }
};
