<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('location')->nullable();
            $table->string('address');
            $table->string('img_url')->nullable();
            $table->text('description')->nullable();
            $table->integer('price');

            $table->integer('rooms_number')->nullable();
            $table->integer('baths_number')->nullable();
            $table->integer('floors_number')->nullable();
            $table->integer('ground_distance')->nullable();
            $table->integer('building_age')->nullable();

            $table->json('main_features')->nullable();
            $table->boolean('is_furnitured')->nullable();
            $table->boolean('is_rent')->default(false);
            $table->boolean('is_sell')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};
