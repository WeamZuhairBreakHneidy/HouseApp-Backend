<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 // In the migration file
// database/migrations/xxxx_create_houses_table.php
public function up()
{
    Schema::create('houses', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->integer('price');
        $table->integer('bedrooms');
        $table->integer('bathrooms');
        $table->integer('area');
        $table->string('address');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};
