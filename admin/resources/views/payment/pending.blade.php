@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning">Pagamento em Processamento</div>

                <div class="card-body text-center">
                    <i class="fa fa-clock fa-5x text-warning mb-3"></i>
                    <h3>Seu pagamento está sendo processado</h3>
                    
                    <p>Estamos aguardando a confirmação do seu pagamento. Assim que for aprovado, seu plano será ativado automaticamente.</p>
                    
                    <div class="mt-4">
                        <p>Se você escolheu pagar com boleto, você precisa efetuar o pagamento para que possamos processar seu pedido.</p>
                        <p>Se você escolheu pagar com PIX, aguarde alguns instantes para a confirmação do pagamento.</p>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Voltar ao Dashboard</a>
                    </div>
                    
                    @if(isset($paymentId))
                    <div class="mt-4">
                        <p class="text-muted">
                            <small>ID do pagamento: {{ $paymentId }}</small>
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection