<?php

namespace Database\Seeders;

use App\Models\CorPele;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cores = ['Branca', 'Morena', 'Negra'];

        foreach ($cores as $cor) {
            CorPele::create(['descricao' => $cor]);
        }
    }
}
