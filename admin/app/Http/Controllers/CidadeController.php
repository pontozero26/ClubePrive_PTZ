<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cidade;
use Illuminate\Support\Facades\Http;

class CidadeController extends Controller
{
    public function cidadesPorUf($uf)
    {
        $response = Http::get("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$uf}/municipios");

        // Verifique se a resposta foi bem-sucedida
        if ($response->successful()) {
            $cidades = $response->json(); // Converte a resposta para array

            // Mapeia os dados para retornar apenas id e nome
            $cidadesFormatadas = array_map(function ($cidade) {
                return [
                    'id' => $cidade['id'],
                    'nome' => $cidade['nome']
                ];
            }, $cidades);

            // Retorna as cidades formatadas em formato JSON
            return response()->json($cidadesFormatadas);
        }

        return response()->json([
            'error' => 'Não foi possível obter as cidades para essa UF.'
        ], 500);
    }
}
