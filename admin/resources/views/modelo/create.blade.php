@extends('adminlte::page')

@section('title', 'Novo cadastro')

@section('content')
    <br>
        <div class="card card-primary card-outline">
            <div class="card-header">
                Mantenha seus dados sempre atualizados.
            </div>
            <div class="card-body">
                <form action="{{ route('modelo.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control" required value="{{old('nome')}}">
                    </div>
                    <div class="form-group">
                        <label for="cpf">CPF</label>
                        <input type="text" name="cpf" id="cpf" required class="form-control" value="{{old('cpf')}}">
                    </div>
                    <div class="form-group">
                        <label for="cep">CEP (somente números)</label>
                        <input type="number" name="cep" id="cep" class="form-control" required value="{{old('cep')}}"></label>                        
                    </div>
                    <div class="form-group">
                        <label for="logradouro">Logradouro</label>
                        <input type="text" name="logradouro" id="logradouro" class="form-control" required value="{{old('logradouro')}}">
                    </div>
                    <div class="form-group">
                        <label for="numero">Nº</label>
                        <input type="number" name="numero" id="numero" class="form-control" required value="{{old('numero')}}"></label>
                    </div>
                    <div class="form-group">
                        <label for="complemento">Complemento</label>
                        <input type="text" name="complemento" id="complemento" class="form-control" required value="{{old('complemento')}}"></label>
                    </div>
                    <div class="form-group">
                        <label for="bairro">Bairro</label>
                        <input type="text" name="bairro" id="bairro" class="form-control" required value="{{old('bairro')}}"></label>
                    </div>
                    <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" name="cidade" id="cidade" class="form-control" required value="{{old('cidade')}}"></label>
                    </div>
                    <div class="form-group">
                        <label for="uf">UF</label>
                        <input type="text" name="uf" id="uf" class="form-control" required value="{{old('uf')}}"></label>
                    </div>
                    <div class="form-group">
                        <label for="data_nascimento">Data de Nascimento</label>
                        <input type="date" name="data_nascimento" id="data_nascimento" required class="form-control" value="{{old('data_nascimento')}}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email" required value="{{old('email')}}">
                    </div>
                    <div class="form-group">
                        <label for="nome_fantasia">Com qual nome vai atender?</label>
                        <input type="text" name="nome_fantasia" id="nome_fantasia" class="form-control" required value="{{old('nome_fantasia')}}">
                    </div>
                    <div class="form-group">
                        <label for="genero">Gênero</label>
                        <select name="genero" id="genero" class="form-control">
                            <option value="-1">Escolha</option>
                            <option value="mulher">Mulher</option>
                            <option value="trans">Trans</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="genitalia">Genitália</label>
                        <select name="genitalia" id="genitalia" class="form-control">
                            <option value="-1">Escolha</option>
                            <option value="vagina">Vagina</option>
                            <option value="penis">Pênis</option>
                            <option value="nao_informado">Não informado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="altura">Altura</label>
                        <input type="number" name="altura" id="altura" class="form-control" step="0.01" value="{{old('altura')}}">
                    </div>
                    <div class="form-group">
                        <label for="peso">Peso</label>
                        <input type="number" name="peso" id="peso" class="form-control" step="0.1" value="{{old('peso')}}">
                    </div>
                    <div class="form-group">
                        <label for="cor_pele">Cor da pele</label>
                        <select name="cor_pele" id="cor_pele" class="form-control">
                            <option value="-1">Escolha</option>
                            @foreach ($pele as $p)
                                <option value="{{$p->id}}">{{$p->descricao}}</option>                                
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cor_cabelo">Cor do cabelo</label>
                        <select name="cor_cabelo" id="cor_cabelo" class="form-control">
                            <option value="-1">Escolha</option>
                            @foreach ($cabelo as $c)
                                <option value="{{$c->id}}">{{$c->descricao}}</option>                                
                            @endforeach
                        </select>
                    </div> 
                    <div class="form-group">
                        <label for="cor_olho">Cor dos olhos</label>
                        <select name="cor_olho" id="cor_olho" class="form-control">
                            <option value="-1">Escolha</option>
                            @foreach ($olhos as $o)
                                <option value="{{$o->id}}">{{$o->descricao}}</option>                                
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="piercing">Possui piercing?</label>
                        <select name="piercing" id="piercing" class="form-control">
                            <option value="-1">Escolha</option>
                            <option value="sim_poucos">Sim, poucos</option>
                            <option value="sim_muitos">Sim, muitos</option>
                            <option value="nao">Não</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tatuagem">Possui tatuagem?</label>
                        <select name="tatuagem" id="tatuagem" class="form-control">
                            <option value="-1">Escolha</option>
                            <option value="sim_poucas">Sim, poucas</option>
                            <option value="sim_muitas">Sim, muitas</option>
                            <option value="nao">Não</option>
                        </select>
                    </div>                    
                    <div class="form-group">
                        <label for="silicone">Possui silicone?</label>
                        <select name="silicone" id="silicone" class="form-control">
                            <option value="-1">Escolha</option>
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quem_atende">Atendo</label>
                        <select name="quem_atende" id="silicone" class="form-control">
                            <option value="-1">Escolha</option>
                            <option value="apenas_homens">Apenas homens</option>
                            <option value="apenas_mulheres">Apenas mulheres</option>
                            <option value="homens_mulheres">Homens e mulheres</option>
                            <option value="trans_afins">Trans e afins</option>
                        </select>
                    </div>  
                    <div class="form-group">
                        <label for="atende_grupo">Atende grupo?</label>
                        <select name="atende_grupo" id="atende_grupo" class="form-control">
                            <option value="-1">Escolha</option>
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </div> 
                    <div class="form-group">
                        <label for="servicos">Serviços oferecidos</label>
                        @foreach ($servicos as $s)
                            <div class="form-check">
                                <input type="checkbox" name="servicos[]" value="{{$s->id}}" class="form-check-input">
                                <label for="" class="form-check-label">{{$s->nome}}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <label for="valor_hora">Valor Hora</label>
                        <input type="number" name="valor_hora" id="valor_hora" class="form-control" step="0.01" value="{{old('valor_hora')}}"></label>
                    </div>
                    <div class="form-group">
                        <label for="frase_impacto">Frase de impacto</label>
                        <input type="text" name="frase_impacto" id="frase_impacto" class="form-control" value="{{old('frase_impacto')}}">
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea name="descricao" id="descricao" cols="30" rows="10" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-primary">Salvar</button>
                        <button type="reset" class="btn btn-sm btn-danger">Limpar tudo</button>
                        <a href="{{route('servicos.index')}}" class="btn btn-sm btn-secondary">Voltar</a>
                    </div>
                </form>
            </div>
        </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"> </script>
    @if (session('success'))
        toastr.success('{{ session('success') }}');
    @elseif (session('error'))
        toastr.error('{{ session('error') }}');
    @endif
@stop