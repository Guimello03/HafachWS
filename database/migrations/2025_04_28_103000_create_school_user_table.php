<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_user', function (Blueprint $table) {
            $table->id();
        
            // 🧠 school_id usa uuid (porque schools usam uuid)
            $table->uuid('school_id');
            $table->foreign('school_id')->references('uuid')->on('schools')->onDelete('cascade');
        
            // ✅ user_id é inteiro, pois users têm primary key como ID
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_user');
    }
};