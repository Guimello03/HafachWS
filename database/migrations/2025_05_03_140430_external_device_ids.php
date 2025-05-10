<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_device_ids', function (Blueprint $table) {
            $table->uuid('uuid')->primary();

            $table->uuid('person_id');
            $table->string('person_type'); // morph: App\Models\Student, etc.
            $table->uuid('device_id'); // seu UUID do equipamento
            $table->unsignedBigInteger('external_id'); // ID usado fisicamente no dispositivo

            $table->timestamps();

            $table->unique(['person_id', 'person_type', 'device_id']);

            $table->foreign('device_id')
                ->references('uuid')
                ->on('devices')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_device_ids');
    }
};
