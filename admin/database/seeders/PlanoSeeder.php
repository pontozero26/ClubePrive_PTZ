<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PlanoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('planos')->insert([
            [
                'nome' => 'Plano Básico',
                'valor' => 49.90,
                'descricao' => 'Acesso básico com recursos limitados.',
                'dias_semana' => 'Sex,Sab',
                'slug' => Str::slug('Plano Básico'),
                'ativo' => true,
                'hora_inicio' => '08:00',
                'hora_fim' => '18:00',
                'qtd_videos' => 3,
                'qtd_imagens' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
