<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Servico;

class ServicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itens = ['Acompanhante','Sexo oral com preservativo','Sexo anal com preservativo','Stripteaser','Utiliza acessórios eróticos','Masturbação','Viagem','Sexo vaginal com preservativo','Beijo na boca','Massagem tradicional','Massagem tântrica'];

        $slugs = [
            'acompanhante',
            'beijo-na-boca',
            'massagem-tantrica',
            'massagem-tradicional',
            'masturbacao',
            'sexo-anal-com-preservativo',
            'sexo-oral-com-preservativo',
            'sexo-vaginal-com-preservativo',
            'stripteaser',
            'utiliza-acessorios',
            'viagem',];
        
        $servicos = [];

        foreach ($itens as $index => $item) {
            $servicos[] = [
                'nome' => $item,
                'slug' => $slugs[$index] ?? null,
                'created_at' => now()
            ];
        }

        Servico::insert($servicos);
    }
}
