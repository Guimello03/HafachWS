<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_device_ids', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('person_id');
            $table->string('person_type'); // morph: Student, Functionary, etc.
            $table->uuid('device_id');
            $table->unsignedBigInteger('external_id');

            $table->timestamps();

            // Uma pessoa só pode ter um external_id por dispositivo
            $table->unique(['person_id', 'person_type', 'device_id']);

            // Relacionamento com tabela devices
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_device_ids');
    }
};