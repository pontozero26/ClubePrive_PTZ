<?php 
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

// Configuração do SDK do Mercado Pago
MercadoPagoConfig::setAccessToken('APP_USR-2886509531990428-020417-476b34387edd5f76a47ab7b44bef6e42-87969335');

// Criação do plano de assinatura
$client = new PreferenceClient();
$preference = $client->create([
    "items" => [
        [
            "title" => "Plano Mensal",
            "quantity" => 1,
            "currency_id" => "BRL",
            "unit_price" => 29.90
        ]
    ],
    "back_urls" => [
        "success" => "https://seusite.com/success",
        "failure" => "https://seusite.com/failure",
        "pending" => "https://seusite.com/pending"
    ],
    "auto_return" => "approved",
    "notification_url" => "https://seusite.com/webhook"
]);

$planId = $preference->id; // ID do plano criado
echo $planId;
?>