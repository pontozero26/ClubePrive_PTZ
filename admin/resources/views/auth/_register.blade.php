<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Digite seu nome')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
                
        <div class="mt-4">
            <x-input-label for="username" :value="__('Digite seu CPF (Apenas números)')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->first('username')" class="mt-2" />
        </div>

        <!-- Name -->
        <div class="mt-4">
            <x-input-label for="data_nascimento" :value="__('Digite sua data de nascimento')" />
            <x-text-input id="data_nascimento" class="block mt-1 w-full" type="date" name="data_nascimento" :value="old('data_nascimento')" required autofocus autocomplete="data_nascimento" />
            <x-input-error :messages="$errors->get('data_nascimento')" class="mt-2" />
        </div>        

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Senha')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirme a senha')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Já tem cadastro?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Cadastrar') }}
            </x-primary-button>
        </div>
    </form>

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
    </script> 
    
    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
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

            // Valida a idade
            if (idade < idadeMinima) {
                e.preventDefault(); // Impede o envio do formulário
                alert('Você deve ter pelo menos 18 anos para se cadastrar.');
                dataNascimentoInput.focus(); // Foca no campo de data de nascimento
            }
        });        
    </script>    

</x-guest-layout>

