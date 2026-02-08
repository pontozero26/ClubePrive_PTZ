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
        Schema::create('modelos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nome_fantasia')->nullable();
            $table->string('telefone')->nullable();
            $table->boolean('possui_local')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf')->nullable();
            $table->string('cep')->nullable();
            $table->string('complemento')->nullable();
            $table->string('numero')->nullable();
            $table->string('frase_impacto')->nullable();
            $table->text('descricao')->nullable();
            $table->string('valor_hora')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('altura')->nullable();
            $table->string('peso')->nullable();
            $table->string('genero')->nullable();
            $table->string('genitalia')->nullable();
            $table->string('quem_atende')->nullable();
            $table->string('tatuagem')->nullable();
            $table->string('piercing')->nullable();
            $table->boolean('silicone')->nullable();  
            $table->boolean('atende_grupo')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('cor_pele_id')->nullable();
            $table->foreign('cor_pele_id')->references('id')->on('cores_pele');
            $table->unsignedBigInteger('cor_cabelo_id')->nullable();
            $table->foreign('cor_cabelo_id')->references('id')->on('cores_cabelo');
            $table->unsignedBigInteger('cor_olho_id')->nullable();
            $table->foreign('cor_olho_id')->references('id')->on('cores_olhos');   
            $table->string('cidade_atendimento')->nullable();     
            $table->string('uf_atendimento')->nullable();        
            $table->date('data_nascimento')->nullable();
            
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modelos');
    }
};
