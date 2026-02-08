@extends('layouts.cp')

@section('content')
<div class="screen-content">
    <div class="box box-welcome">
        <div class="box-head">
        <h2>
            <small>{{$user->name}},</small>
            Você faz parte do Clube!
        </h2>
        </div>
        <div class="box-content">
        <div>
            <p>Agradecemos sua confiança, seja bem-vinda.</p>
            <p>Acesse agora a plataforma, complete seu perfil e comece a desfrutar do Clube Privê.</p>
        </div>
        <div class="center mt-30">
            <a href="{{ route('dashboard') }}" class="btn" style="text-decoration: none;">Acessar</a>
        </div>
        </div>
    </div>
</div>
@stop

@section('steps')
    <div class="steps">
        <div class="steps-item" data-steps-number="1">
        <diva class="steps-item-text">
            <small>Faça seu</small>
            Pré-cadastro
        </diva>
        </div>
        <div class="steps-item" data-steps-number="2">
        <div class="steps-item-text">
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
        <div class="steps-item" data-steps-number="4">
        <div class="steps-item-text">
            <small>Realize o</small>
            Pagamento
        </div>
        </div>
        <div class="steps-item active" data-steps-number="5">
        <div class="steps-item-text">
            <small>Tudo</small>
            Pronto!
        </div>
        </div>
    </div>
@endsection