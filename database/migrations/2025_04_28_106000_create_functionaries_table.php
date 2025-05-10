<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('functionaries', function (Blueprint $table) {
            $table->uuid('uuid')->primary(); // ✅ Chave primária real
            $table->timestamps();
            $table->string('name');
            $table->string('cpf')->unique();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->date('birth_date');
            $table->string('photo_path')->nullable();

            $table->uuid('school_id');
            $table->foreign('school_id')->references('uuid')->on('schools')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('functionaries');
    }
};
