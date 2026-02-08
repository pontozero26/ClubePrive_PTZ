@extends('layouts.cp')

@section('content')
<div class="screen-content">
    <div class="box">
        <div class="box-head">
            <h2>
                <small>Ops!</small>
                Falha no pagamento
            </h2>
        </div>
        <div class="box-content">
            <p>Infelizmente ocorreu um erro durante o processo de pagamento. </p>
            <p>Por favor, tente novamente ou use outro método de pagamento.</p>
        </div>
        <div class="center mt-30">
            <a href="{{ route('inicio.novopagamento', ['plano_id' => $planoId, 'user_id' => $user->id]) }}" class="btn" style="text-decoration: none;">Tentar Novamente</a>
        </div>
    </div>
</div>

@endsection