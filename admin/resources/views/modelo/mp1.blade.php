@extends('adminlte::page')

@section('title', 'Assinatura')

@section('content_header')
    <h1>Assinatura</h1>
@stop

@section('content')
    <form id="form-checkout">
        @csrf
        <div class="form-group">
            <div id="form-checkout__cardNumber" class="form-control"></div>
        </div>
        
        <div class="form-group">
            <div id="form-checkout__expirationDate" class="form-control"></div>
        </div>
        
        <div class="form-group">
            <div id="form-checkout__securityCode" class="form-control"></div>
        </div>
        <div class="form-group">
            <input type="text" id="form-checkout__cardholderName" class="form-control"/>
        </div>
                
        <div class="form-group">
            <select id="form-checkout__issuer" class="form-control"></select>
        </div>
        <div class="form-group">
            <select id="form-checkout__installments" class="form-control"></select>
        </div>
        <div class="form-group">
            <select id="form-checkout__identificationType" class="form-control"></select>
        </div>
        <div class="form-group">
            <input type="text" id="form-checkout__identificationNumber" class="form-control"/>
        </div>
        <div class="form-group">
            <input type="email" id="form-checkout__cardholderEmail" class="form-control"/>        
        </div>

        <button type="submit" id="form-checkout__submit">Pagar</button>
        <div class="loader" style="display: none;">Processando...</div>
        <div class="error-message" style="display: none;"></div>
        <div class="success-message" style="display: none;"></div>
        <div class="progress-bar"></div>
    </form>
@stop   

@section('js')
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        const mp = new MercadoPago("{{ env('MERCADOPAGO_PUBLIC_KEY') }}");

        
        const cardForm = mp.cardForm({
    amount: "10.00",
    iframe: true,
    form: {
        id: "form-checkout",
        cardNumber: {
            id: "form-checkout__cardNumber",
            placeholder: "Número do cartão",
        },
        expirationDate: {
            id: "form-checkout__expirationDate",
            placeholder: "MM/YY",
        },
        securityCode: {
            id: "form-checkout__securityCode",
            placeholder: "Código de segurança",
        },
        cardholderName: {
            id: "form-checkout__cardholderName",
            placeholder: "Titular do cartão",
        },
        issuer: {
            id: "form-checkout__issuer",
            placeholder: "Banco emissor",
        },
        installments: {
            id: "form-checkout__installments",
            placeholder: "Parcelas",
        },        
        identificationType: {
            id: "form-checkout__identificationType",
            placeholder: "Tipo de documento",
        },
        identificationNumber: {
            id: "form-checkout__identificationNumber",
            placeholder: "Número do documento",
        },
        cardholderEmail: {
            id: "form-checkout__cardholderEmail",
            placeholder: "E-mail",
        },
    },
    callbacks: {
        onFormMounted: error => {
            if (error) {
                console.error("Erro ao montar o formulário:", error);
                // Mostra mensagem de erro para o usuário
                showError("Houve um erro ao carregar o formulário de pagamento");
                return;
            }
            console.log("Formulário montado com sucesso");
        },
        onSubmit: async (event) => {
            event.preventDefault();

            try {
                // Mostra loader
                showLoading(true);

                const {
                    paymentMethodId: payment_method_id,
                    issuerId: issuer_id,
                    cardholderEmail: email,
                    amount,
                    token,
                    installments,
                    identificationNumber,
                    identificationType,
                } = cardForm.getCardFormData();

                // Validações básicas
                if (!token || !email || !identificationNumber) {
                    throw new Error("Por favor, preencha todos os campos obrigatórios");
                }

                const idempotencyKey = generateIdempotencyKey();

                const response = await fetch("/process_payment", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                        "X-Idempotency-Key": idempotencyKey
                    },
                    body: JSON.stringify({
                        token,
                        issuer_id,
                        payment_method_id,
                        transaction_amount: Number(amount),
                        installments: Number(installments),
                        description: "Descrição do produto",
                        payer: {
                            email,
                            identification: {
                                type: identificationType,
                                number: identificationNumber,
                            },
                        },
                    }),
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || "Erro ao processar pagamento");
                }

                // Sucesso
                //showSuccess("Pagamento realizado com sucesso!");
                
                // Redireciona ou atualiza a página após sucesso
                // setTimeout(() => {
                //     window.location.href = "/confirmation";
                // }, 2000);

            } catch (error) {
                console.error("Erro no pagamento:", error);
                showError(error.message || "Ocorreu um erro ao processar o pagamento");
            } finally {
                showLoading(false);
            }
        },
        onFetching: (resource) => {
            console.log("Buscando recurso:", resource);

            // Anima barra de progresso
            const progressBar = document.querySelector(".progress-bar");
            if (progressBar) {
                progressBar.removeAttribute("value");
            }

            return () => {
                if (progressBar) {
                    progressBar.setAttribute("value", "0");
                }
            };
        }
    },
});

// Funções auxiliares
function showLoading(show) {
    const loader = document.querySelector(".loader");
    if (loader) {
        loader.style.display = show ? "block" : "none";
    }
}

function showError(message) {
    const errorDiv = document.querySelector(".error-message");
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.style.display = "block";
    }
}

function showSuccess(message) {
    const successDiv = document.querySelector(".success-message");
    if (successDiv) {
        successDiv.textContent = message;
        successDiv.style.display = "block";
    }
}

function generateIdempotencyKey() {
    // Combina timestamp com um número aleatório
    const timestamp = new Date().getTime();
    const random = Math.random().toString(36).substring(2, 15);
    return `${timestamp}-${random}`;
}
</script>


@stop

@section('css')
<style>
    #form-checkout {
      display: flex;
      flex-direction: column;
      max-width: 600px;
    }

    .container {
      height: 18px;
      display: inline-block;
      border: 1px solid rgb(118, 118, 118);
      border-radius: 2px;
      padding: 1px 2px;
    }
  </style>
@stop
