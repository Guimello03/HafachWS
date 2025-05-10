<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_user', function (Blueprint $table) {
            $table->uuid('school_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();

            $table->primary(['school_id', 'user_id']);

            $table->foreign('school_id')->references('uuid')->on('schools')->onDelete('cascade');
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_user');
    }
};
