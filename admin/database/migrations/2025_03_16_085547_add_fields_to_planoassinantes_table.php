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
        Schema::table('planos_assinantes', function (Blueprint $table) {
            $table->string('payment_id')->nullable()->after('ativo');
            $table->string('payment_status')->nullable()->after('payment_id');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->timestamp('expira_em')->nullable()->after('payment_method'); 
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
