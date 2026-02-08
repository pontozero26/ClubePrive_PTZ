@extends('layouts.cp') <!-- Estende o layout base -->

@section('title', 'Clube Privê')

@section('content')
    <div class="screen-content">
        <div class="box">
            <div class="box-head">
                <h2>
                    <small>Faça seu</small>
                    Pré-cadastro
                </h2>
                <p>Preencha corretamente os campos e siga os passos para o sucesso de seu cadastro.</p>
            </div>
    
            <div class="box-content">
                <form id="registration-form" action="{{ route('register') }}" method="post">
                    @csrf              
                    <input type="hidden" name="role" value="user">          
                    <ul>
                        <li>
                            <label>Nome Completo</label>
                            <input type="text" id="name" name="name" value="{{old('name')}}" required autofocus data-alert-type="error"
                            data-alert-text="{{ $errors->first('name') }}">
                        </li>
                        <li>
                            <label>CPF (Somente Números)</label>
                            <input type="text" id="username" name="username" value="{{old('username')}}" required data-alert-type="error"
                            data-alert-text="{{ $errors->first('username') }}">
                        </li>
                        <li>
                            <label>Data de Nascimento</label>
                            <input type="text" placeholder="dd/mm/aaaa" id="data_nascimento" name="data_nascimento" value="{{old('data_nascimento')}}" required data-alert-type="error"
                            data-alert-text="{{ $errors->first('data_nascimento') }}">
                        </li>
                        <li>
                            <label>E-mail</label>
                            <input type="email" id="email" name="email" value="{{old('email')}}" required data-alert-type="error"
                            data-alert-text="{{ $errors->first('email') }}">
                        </li>
                        <li>
                            <label>Definir senha (mínimo 8 caracteres)</label>
                            <input type="password" id="password" name="password" value="{{old('password')}}" required data-alert-type="error"
                            data-alert-text="{{ $errors->first('password') }}">
                        </li>
                        <li>
                            <label>Confirmar sua senha</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" value="{{old('password_confirmation')}}" required data-alert-type="error"
                            data-alert-text="{{ $errors->first('password_confirmation') }}">
                        </li>
                        <li class="center">
                            <button id="submit-button" class="btn" type="submit">cadastrar</button>
                            <!-- Ícone de carregamento -->
                            <div id="loading-icon" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i> Validando seus dados...
                            </div>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('steps')
    <div class="steps">
        <div class="steps-item active" data-steps-number="1">
            <div class="steps-item-text">
                <small>Faça seu</small>
                Pré-cadastro
            </div>
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
        <div class="steps-item" data-steps-number="5">
            <div class="steps-item-text">
                <small>Tudo</small>
                Pronto!
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Adicionando máscaras para telefone e CPF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="{{ asset('js/global.js') }}"></script>
    <script>
        // Seleciona o campo de CPF
        const cpfInput = document.getElementById('username');

        // Aplica a máscara de CPF
        cpfInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não for número
            if (value.length > 11) {
                value = value.slice(0, 11); // Limita a 11 caracteres
            }
            value = value.replace(/(\d{3})(\d)/, '$1.$2'); // Adiciona o primeiro ponto
            value = value.replace(/(\d{3})(\d)/, '$1.$2'); // Adiciona o segundo ponto
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2'); // Adiciona o traço
            e.target.value = value; // Atualiza o valor do campo
        });

        document.addEventListener("DOMContentLoaded", function () {
            const dataNascimentoInput = document.getElementById('data_nascimento');

            dataNascimentoInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não for número

                if (value.length > 8) {
                    value = value.slice(0, 8); // Limita a 8 caracteres
                }

                // Aplica a máscara no formato dd/mm/yyyy
                if (value.length >= 2) {
                    value = value.replace(/(\d{2})(\d)/, '$1/$2');
                }
                if (value.length >= 5) {
                    value = value.replace(/(\d{2})\/(\d{2})(\d)/, '$1/$2/$3');
                }

                e.target.value = value; // Atualiza o valor do campo
            });

            // Evita que o usuário digite letras ou símbolos
            dataNascimentoInput.addEventListener('keypress', function (e) {
                if (!/[0-9]/.test(e.key)) {
                    e.preventDefault();
                }
            });
        });

        // Manipula o envio do formulário
        document.getElementById('registration-form').addEventListener('submit', function () {
            const submitButton = document.getElementById('submit-button');
            const loadingIcon = document.getElementById('loading-icon');

            // Desabilita o botão e exibe o ícone de carregamento
            submitButton.style.display = 'none';
            loadingIcon.style.display = 'block';
        });
    </script>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
@endsection