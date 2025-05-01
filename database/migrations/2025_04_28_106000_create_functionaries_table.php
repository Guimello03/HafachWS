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
        Schema::create('functionaries', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('cpf')->unique();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->date('birth_date');
            $table->string('photo_path')->nullable();
            $table->uuid('uuid')->unique();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('functionaries');
        
    }
};
