<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CorCabelo;

class CabeloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cores = ['Loiro', 'Castanho', 'Preto', 'Ruivo'];

        foreach ($cores as $cor) {
            CorCabelo::create(['descricao' => $cor]);
        }
    }
}
