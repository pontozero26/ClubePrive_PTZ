<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::create([
            'name' => 'Administrador',
            'username' => 'admin',
            'password' => bcrypt('a1s2d3f4'),
            'email' => 'test@example.com',
            'role' => 'admin',
            'is_active' => true
        ]);

        $this->call([
            ConfigSeeder::class,
            CabeloSeeder::class,
            PeleSeeder::class,
            OlhosSeeder::class,
            ServicoSeeder::class,
            PlanoSeeder::class,
        ]);        


    }
}
