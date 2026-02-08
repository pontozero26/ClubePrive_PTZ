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
        Schema::create('midias', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('caminho');
            $table->string('nome_arquivo');
            $table->boolean('principal')->default(false);
            $table->unsignedBigInteger('modelo_id');
            $table->boolean('ativo')->default(true);
            $table->string('tipo',6);
            $table->string('thumbnail')->nullable();
            $table->foreign('modelo_id')->references('id')->on('modelos')->onDelete('cascade');            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('midias');
    }
};
