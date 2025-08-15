<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('others', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // lands / shops
            $table->string('title');
            $table->string('location')->nullable();
            $table->string('address');
            $table->text('img_url')->nullable(); // stores JSON for images & video
            $table->text('description')->nullable();
            $table->integer('price');

            $table->json('main_features')->nullable();
            $table->integer('area_distance')->nullable();
            $table->integer('arealength')->nullable();
            $table->integer('areawidth')->nullable();
            $table->integer('floors_number')->nullable();

            $table->boolean('is_rent')->default(false);
            $table->boolean('is_sell')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('others');
    }
};
