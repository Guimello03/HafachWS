<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guardians', function (Blueprint $table) {
            $table->uuid('uuid')->primary(); // ✅ chave primária é uuid
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('cpf')->unique();
            $table->date('birth_date');
            $table->string('photo_path')->nullable();
            $table->uuid('school_id');
            $table->foreign('school_id')->references('uuid')->on('schools')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('guardians', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
        });

        Schema::dropIfExists('guardians');
    }
};
