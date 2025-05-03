<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('device_group_person', function (Blueprint $table) {
            $table->uuid('device_group_id');
            $table->uuid('person_id');
            $table->string('person_type'); // ex: App\Models\Student, Guardian, Functionary
            $table->timestamps();

            $table->primary(['device_group_id', 'person_id', 'person_type']);
            $table->foreign('device_group_id')->references('id')->on('device_groups')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('device_group_person');
    }
};