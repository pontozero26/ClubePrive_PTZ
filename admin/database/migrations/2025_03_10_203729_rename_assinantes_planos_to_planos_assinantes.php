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
        Schema::table('assinantes_planos', function (Blueprint $table) {
            Schema::rename('assinantes_planos', 'planos_assinantes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planos_assinantes', function (Blueprint $table) {
            //
        });
    }
};
