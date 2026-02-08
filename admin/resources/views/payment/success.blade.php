@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">Pagamento Realizado com Sucesso</div>

                <div class="card-body text-center">
                    <i class="fa fa-check-circle fa-5x text-success mb-3"></i>
                    <h3>Seu pagamento foi aprovado!</h3>
                    
                    <p>Seu plano já está ativo e você pode começar a usufruir de todos os benefícios.</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Ir para o Dashboard</a>
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