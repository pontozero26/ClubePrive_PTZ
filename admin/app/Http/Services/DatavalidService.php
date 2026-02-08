<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DatavalidService
{
    private $token;
    private $consumerKey;
    private $consumerSecret;

    public function __construct()
    {
        // Substitua pelo seu token de acesso ao Data Valid
        $this->consumerKey = env('DATAVALID_CONSUMER_KEY');
        $this->consumerSecret = env('DATAVALID_CONSUMER_SECRET');
    }

    // Método para obter o token de acesso
    public function getBearerToken() {
        $consumerKey = $this->consumerKey;
        $consumerSecret = $this->consumerSecret;

        // Concatenar chave e segredo e codificar em Base64
        $credentials = base64_encode("$consumerKey:$consumerSecret");
        
        $url = "https://gateway.apiserpro.serpro.gov.br/token";
        
        $headers = [
            "Authorization: Basic $credentials",
            "Content-Type: application/x-www-form-urlencoded"
        ];
        
        $postData = "grant_type=client_credentials";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200) {
            $data = json_decode($response, true);
            return $data['access_token'] ?? null;
        } else {
            return "Erro ao obter o token na função getBearerToken. Código HTTP: $httpCode";
        }
    }

    // Método para consultar CPF no Data Valid
    public function consultarCPF($cpf, $nome, $dataNascimento)
    {
        $token =$this->getBearerToken();

        if (!$token) {
            return null; // Retorna null se o token não for obtido
        }

        $url = "https://gateway.apiserpro.serpro.gov.br/datavalid/v4/pf-basica";
        // Dados da requisição
        $dados = [
            'cpf' => $cpf,
            'validacao' => [
                'nome' => $nome,
                'data_nascimento' => $dataNascimento,
                'endereco' => (object)[], // Objeto vazio
                'cnh' => (object)[], // Objeto vazio
            ],
        ];

        try {
            $ch = curl_init();
            
            $headers = [
                'accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ];
            
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($dados),
                CURLOPT_HTTPHEADER => $headers
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode == 200) {
                $responseData = json_decode($response, true);
                $nomeValido = $responseData['rfb']['nome'] ?? null;
                $nomeSimilaridade = $responseData['rfb']['nome_similaridade'] ?? null;
                $dataNascimentoValida = $responseData['rfb']['data_nascimento'] ?? null;

                if ($nomeValido && $dataNascimentoValida && $nomeSimilaridade >= 0.95) {
                    return true; // Retorna null se o nome ou data de nascimento não forem válidos
                }
                else 
                {
                    return false;
                }

            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }
}