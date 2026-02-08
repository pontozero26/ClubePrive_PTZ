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
                <p>No Clube Privê você possui um ID ÚNICO para todos os produtos e serviços oferecidos por nossa plataforma.</p>
                <p>Fique tranquila, aqui seus dados estão protegidos por sistemas avançados de segurança, em conformidade com a Lei Geral de Proteção de Dados.</p>
                <p>Discrição, qualidade e confiança em um só lugar.</p>
            </div>
    
            <div class="box-content">
                <form action="{{ route('inicio.cadastrar') }}" method="post">
                    @csrf              
                    <input type="hidden" name="role" value="user">          
                    <ul>
                        <li>
                            <label>Nome Completo</label>
                            <input type="text" id="name" name="name" value="{{old('name')}}" required autofocus data-alert-text="Lorem">
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror                            
                        </li>
                        <li>
                            <label>ID CLUBE PRIVÊ - CPF (Somente Números)</label>
                            <input type="text" id="username" name="username" value="{{old('username')}}" required>
                            @error('username')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </li>
                        <li>
                            <label>Data de Nascimento</label>
                            <input type="text" placeholder="dd/mm/aaaa" id="data_nascimento" name="data_nascimento" value="{{old('data_nascimento')}}" required >
                            @error('data_nascimento')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </li>
                        <li>
                            <label>Telefone (Digite apenas números)</label>
                            <input type="phone" name="telefone" id="telefone" required value="{{old('telefone')}}">
                            @error('telefone')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </li>                           
                        <li>
                            <label>E-mail</label>
                            <input type="email" id="email" name="email" value="{{old('email')}}" required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </li>                     
                        <li>
                            <label>Definir senha (mínimo 8 caracteres)</label>
                            <div class="password-container" style="position: relative;">
                                <input type="password" id="password" name="password" value="{{old('password')}}" required>
                                <i class="fas fa-eye-slash toggle-password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;" data-target="password"></i>
                            </div>
                            @error('password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </li>
                        <li>
                            <label>Confirmar sua senha</label>
                            <div class="password-container" style="position: relative;">
                                <input type="password" id="password_confirmation" name="password_confirmation" value="{{old('password_confirmation')}}" required>
                                <i class="fas fa-eye-slash toggle-password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;" data-target="password_confirmation"></i>
                            </div>
                            @error('password_confirmation')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </li>
                        <li class="center">
                            <button class="btn" type="submit">cadastrar</button>
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
    <script src="{{ asset('js/global.js')}}""></script>
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

        $(document).ready(function() {
            $('#data_nascimento').mask('00/00/0000'); // Mascara para dd/mm/aaaa
        });     

        function formatarTelefone(input) {
            let valor = input.value.replace(/\D/g, ''); // Remove tudo que não for número

            if (valor.length > 11) {
                valor = valor.substring(0, 11); // Limita a 11 dígitos (DDD + 9 dígitos)
            }

            if (valor.length > 10) {
                // Formato para números de celular com DDD: (XX) 9XXXX-XXXX
                valor = `(${valor.substring(0, 2)}) ${valor.substring(2, 7)}-${valor.substring(7)}`;
            } else if (valor.length > 6) {
                // Formato intermediário: (XX) XXXX-XXXX
                valor = `(${valor.substring(0, 2)}) ${valor.substring(2, 6)}-${valor.substring(6)}`;
            } else if (valor.length > 2) {
                // Formato inicial: (XX) XXXX
                valor = `(${valor.substring(0, 2)}) ${valor.substring(2)}`;
            } else if (valor.length > 0) {
                // Formato inicial: (XX
                valor = `(${valor}`;
            }

            input.value = valor;
        }

        // Adiciona o evento de input ao campo de telefone
        $('#telefone').on('input', function() {
            formatarTelefone(this);
        });            

        document.querySelector('form').addEventListener('submit', function (e) {
            console.log("teste");
            
            const dataNascimentoInput = document.getElementById('data_nascimento');
            const dataNascimento = new Date(dataNascimentoInput.value);
            const hoje = new Date();
            const idadeMinima = 18;

            // Calcula a idade
            const idade = hoje.getFullYear() - dataNascimento.getFullYear();
            const mes = hoje.getMonth() - dataNascimento.getMonth();
            if (mes < 0 || (mes === 0 && hoje.getDate() < dataNascimento.getDate())) {
                idade--;
            }

            // // Valida a idade
            // if (idade < idadeMinima) {
            //     console.log("18 anos");
                
            //     e.preventDefault(); // Impede o envio do formulário
            //     alert('Você deve ter pelo menos 18 anos para se cadastrar.');
            //     dataNascimentoInput.focus(); // Foca no campo de data de nascimento
            // }
        });      

        // Adicione isso ao seu script existente
        document.addEventListener("DOMContentLoaded", function () {
            // Código existente...
            
            // Funcionalidade para mostrar/ocultar senha
            const togglePasswordIcons = document.querySelectorAll('.toggle-password');
            
            togglePasswordIcons.forEach(icon => {
                icon.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    
                    // Alterna o tipo de input entre "password" e "text"
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    } else {
                        passwordInput.type = 'password';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    }
                });
            });
        });

    </script> 
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        .password-container {
            position: relative;
            width: 100%;
        }
        
        .toggle-password {
            color: #888;
            transition: color 0.3s ease;
        }
        
        .toggle-password:hover {
            color: #333;
        }

        .error-message {
            color: red;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }        
    </style>
@endsection
