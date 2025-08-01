<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
     Schema::create('architects', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('specialization')->nullable();
        $table->string('university')->nullable();
        $table->string('country')->nullable();
        $table->string('city')->nullable();
        $table->string('experience')->nullable();
        $table->string('phone')->nullable();
        $table->text('languages')->nullable();
        $table->integer('years_experience')->nullable();
        $table->json('img_url')->nullable(); // 👈 for image and video
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('architects');
    }
};
