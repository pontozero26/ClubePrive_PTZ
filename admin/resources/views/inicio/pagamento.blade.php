@extends('layouts.cp')

@section('content')
    
    <p>Para o pagamento, você será redirecionada para o site do Mercado Pago.<br>
        
    <strong>Leia com atenção</strong>, realize o pagamento e em seguida você receberá as instruções de acesso.<br>
    Caso ocorra algum problema, clique no link Voltar ao site na tela do pagamento.</p>
    {{-- <div id="paymentBrick_container"></div> --}}
    <a href="{{ $link_pagamento }}" class="btn btn-primary btn-lg" style="text-decoration: none;">
        realizar pagamento
    </a>
@stop

@section('steps')
    <div class="steps">
        <div class="steps-item" data-steps-number="1">
        <div class="steps-item-text">
            <small>Faça seu</small>
            Pré-cadastro
        </diva>
        </div>
        <div class="steps-item" data-steps-number="2">
        <diva class="steps-item-text">
            <small>Escolha</small>
            Seu Plano
        </div>
        </div>
        <div class="steps-item" data-steps-number="3">
        <div class="steps-item-text">
            <small>Valide seu</small>
            Contrato
        </div>
        </div>
        <div class="steps-item active" data-steps-number="4">
        <div class="steps-item-text">
            <small>Realize o</small>
            Pagamento
        </div>
        </div>
        <div class="steps-item" data-steps-number="5">
        <div class="steps-item-text">
            <small>Tudo</small>
            Pronto!
        </div>
        </div>
    </div>
@endsection

@section('js')
{{-- <script src="https://sdk.mercadopago.com/js/v2"></script>
<script>
    const mp = new MercadoPago('{{ env('MERCADOPAGO_PUBLIC_KEY') }}',{
        locale: 'pt-BR'
    });

    const bricksBuilder = mp.bricks();
    const renderPaymentBrick = async (bricksBuilder) => {
    const settings = {
        initialization: {
        /*
            "amount" é a quantia total a pagar por todos os meios de pagamento com exceção da Conta Mercado Pago e Parcelas sem cartão de crédito, que têm seus valores de processamento determinados no backend através do "preferenceId"
        */
        amount: 10000,
        preferenceId: "<PREFERENCE_ID>",
        payer: {
            firstName: "",
            lastName: "",
            email: "",
        },
        },
        customization: {
        visual: {
            style: {
            theme: "default",
            },
        },
        paymentMethods: {
            creditCard: "all",
                                        debitCard: "all",
                                        ticket: "all",
                                        bankTransfer: "all",
                                        atm: "all",
                                        onboarding_credits: "all",
                                        wallet_purchase: "all",
            maxInstallments: 1
        },
        },
        callbacks: {
        onReady: () => {
            /*
            Callback chamado quando o Brick está pronto.
            Aqui, você pode ocultar seu site, por exemplo.
            */
        },
        onSubmit: ({ selectedPaymentMethod, formData }) => {
            // callback chamado quando há click no botão de envio de dados
            return new Promise((resolve, reject) => {
            fetch("/process_payment", {
                method: "POST",
                headers: {
                "Content-Type": "application/json",
                },
                body: JSON.stringify(formData),
            })
                .then((response) => response.json())
                .then((response) => {
                // receber o resultado do pagamento
                resolve();
                })
                .catch((error) => {
                // manejar a resposta de erro ao tentar criar um pagamento
                reject();
                });
            });
        },
        onError: (error) => {
            // callback chamado para todos os casos de erro do Brick
            console.error(error);
        },
        },
    };
    window.paymentBrickController = await bricksBuilder.create(
        "payment",
        "paymentBrick_container",
        settings
    );
    };
    renderPaymentBrick(bricksBuilder);

</script> --}}
@stop
