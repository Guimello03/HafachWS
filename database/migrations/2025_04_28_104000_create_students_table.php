<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('uuid')->primary(); // ✅ chave primária
            $table->string('name');
            $table->string('registration_number')->unique();
            $table->date('birth_date');
            $table->string('photo_path')->nullable();

            $table->uuid('guardian_id')->nullable();
            $table->foreign('guardian_id')->references('uuid')->on('guardians')->nullOnDelete();

            $table->uuid('school_id');
            $table->foreign('school_id')->references('uuid')->on('schools')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['guardian_id']);
            $table->dropForeign(['school_id']);
        });

        Schema::dropIfExists('students');
    }
};
