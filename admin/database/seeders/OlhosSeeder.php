<?php

namespace Database\Seeders;

use App\Models\CorOlho;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OlhosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cores = ['Castanho', 'Azul', 'Aerde' ,'Âmbar'];

        foreach ($cores as $cor) {
            CorOlho::create(['descricao' => $cor]);
        }
    }
}
