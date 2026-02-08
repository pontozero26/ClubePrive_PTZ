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
        Schema::table('modelos', function (Blueprint $table) {
            $table->boolean('mostra_idade')->default(false);    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modelos', function (Blueprint $table) {
            //
            $table->dropColumn('mostra_idade');
        });
    }
};
