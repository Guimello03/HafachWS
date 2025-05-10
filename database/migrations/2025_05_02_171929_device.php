<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('devices', function (Blueprint $table) {
            $table->uuid('uuid')->primary(); // chave primária
            $table->uuid('school_id');       // vínculo direto com escola
            $table->string('serial_number')->unique();
            $table->string('model');
            $table->timestamps();

            $table->foreign('school_id')->references('uuid')->on('schools')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('devices');
    }
};
