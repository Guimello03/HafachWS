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
        Schema::create('device_events', function (Blueprint $table) {
            $table->id();
            $table->uuid('person_id');
            $table->string('person_type'); // Ex: App\Models\Student, App\Models\Guardian, App\Models\Functionary
            $table->string('device_id');
            $table->timestamp('date');
            $table->enum('direction', ['in', 'out'])->default('in');
            $table->timestamps();

            $table->index(['person_id', 'person_type']);
            $table->index('device_id');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_events');
    }
};
