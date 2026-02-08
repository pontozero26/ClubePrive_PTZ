<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            ['id' => 10, 'name' => 'Ana Oliveira', 'email' => 'ana.oliveira@example.com', 'role' => 'user', 'username' => '98765432100', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 11, 'name' => 'Bruna Santos', 'email' => 'bruna.santos@example.com', 'role' => 'user', 'username' => '12345678901', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 12, 'name' => 'Carla Mendes', 'email' => 'carla.mendes@example.com', 'role' => 'user', 'username' => '23456789012', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 13, 'name' => 'Daniela Lima', 'email' => 'daniela.lima@example.com', 'role' => 'user', 'username' => '34567890123', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 14, 'name' => 'Elaine Souza', 'email' => 'elaine.souza@example.com', 'role' => 'user', 'username' => '45678901234', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 15, 'name' => 'Fernanda Rocha', 'email' => 'fernanda.rocha@example.com', 'role' => 'user', 'username' => '56789012345', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 16, 'name' => 'Gabriela Martins', 'email' => 'gabriela.martins@example.com', 'role' => 'user', 'username' => '67890123456', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 17, 'name' => 'Helena Ribeiro', 'email' => 'helena.ribeiro@example.com', 'role' => 'user', 'username' => '78901234567', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 18, 'name' => 'Isabela Cardoso', 'email' => 'isabela.cardoso@example.com', 'role' => 'user', 'username' => '89012345678', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 19, 'name' => 'Juliana Freitas', 'email' => 'juliana.freitas@example.com', 'role' => 'user', 'username' => '90123456789', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 20, 'name' => 'Karen Nogueira', 'email' => 'karen.nogueira@example.com', 'role' => 'user', 'username' => '11223344556', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 21, 'name' => 'Larissa Mendes', 'email' => 'larissa.mendes@example.com', 'role' => 'user', 'username' => '22334455667', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 22, 'name' => 'Mariana Teixeira', 'email' => 'mariana.teixeira@example.com', 'role' => 'user', 'username' => '33445566778', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 23, 'name' => 'Natália Xavier', 'email' => 'natalia.xavier@example.com', 'role' => 'user', 'username' => '44556677889', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 24, 'name' => 'Olívia Melo', 'email' => 'olivia.melo@example.com', 'role' => 'user', 'username' => '55667788990', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 25, 'name' => 'Patrícia Gomes', 'email' => 'patricia.gomes@example.com', 'role' => 'user', 'username' => '66778899001', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 26, 'name' => 'Queila Ferreira', 'email' => 'queila.ferreira@example.com', 'role' => 'user', 'username' => '77889900112', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 27, 'name' => 'Renata Barbosa', 'email' => 'renata.barbosa@example.com', 'role' => 'user', 'username' => '88990011223', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 28, 'name' => 'Sabrina Silva', 'email' => 'sabrina.silva@example.com', 'role' => 'user', 'username' => '99001122334', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 29, 'name' => 'Tatiane Costa', 'email' => 'tatiane.costa@example.com', 'role' => 'user', 'username' => '00112233445', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 30, 'name' => 'Ursula Moreira', 'email' => 'ursula.moreira@example.com', 'role' => 'user', 'username' => '10223344557', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 31, 'name' => 'Vanessa Almeida', 'email' => 'vanessa.almeida@example.com', 'role' => 'user', 'username' => '21234455668', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 32, 'name' => 'Wendy Lopes', 'email' => 'wendy.lopes@example.com', 'role' => 'user', 'username' => '32245566779', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 33, 'name' => 'Ximena Batista', 'email' => 'ximena.batista@example.com', 'role' => 'user', 'username' => '43256677880', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 34, 'name' => 'Yasmin Duarte', 'email' => 'yasmin.duarte@example.com', 'role' => 'user', 'username' => '54267788991', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 35, 'name' => 'Zuleica Farias', 'email' => 'zuleica.farias@example.com', 'role' => 'user', 'username' => '65278899002', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 36, 'name' => 'Amanda Rocha', 'email' => 'amanda.rocha@example.com', 'role' => 'user', 'username' => '76289900113', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 37, 'name' => 'Beatriz Lopes', 'email' => 'beatriz.lopes@example.com', 'role' => 'user', 'username' => '87290011224', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 38, 'name' => 'Camila Moreira', 'email' => 'camila.moreira@example.com', 'role' => 'user', 'username' => '98201122335', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
            ['id' => 39, 'name' => 'Débora Ribeiro', 'email' => 'debora.ribeiro@example.com', 'role' => 'user', 'username' => '09212233446', 'is_active' => 1, 'escolheu_plano' => 1, 'aceitou_contrato' => 1, 'fez_pagamento' => 1,  'password' => Hash::make('a1s2d3f4')],
        ]);
    }
}
