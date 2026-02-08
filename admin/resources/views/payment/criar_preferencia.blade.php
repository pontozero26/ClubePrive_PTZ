<?php
// SDK do MercadoPago
    use MercadoPago\MercadoPagoConfig;
    use MercadoPago\Client\Preference\PreferenceClient;
    //Adicione as credenciais
    MercadoPagoConfig::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));

    $client = new PreferenceClient();
    $preference = $client->create([
        "items"=> array(
            array(
            "title" => "Meu produto",
            "quantity" => 1,
            "unit_price" => 25
            )
        )
    ]);

    dd($preference);
?>