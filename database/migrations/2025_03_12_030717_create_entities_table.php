<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->string('api'); // Nombre de la API
            $table->text('description'); // Descripción
            $table->string('link'); // Enlace
            $table->unsignedBigInteger('category_id'); // ID de categoría
            $table->timestamps();
    
            // Relación con la tabla de categorías
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entities');
    }
};
