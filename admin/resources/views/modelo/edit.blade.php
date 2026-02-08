@extends('adminlte::page')

@section('title', 'Seu cadastro')

@section('content')
    @php
        $url = url('/api/cidades/')
    @endphp
    <br>
        <div class="card card-primary card-outline">
            <div class="card-header">
                @if ($role == 'admin')
                    Dados da acompanhante. Todas as alterações são gravadas.
                @else
                    Mantenha seus dados sempre atualizados.
                @endif
            </div>
            <div class="card-body">
                <form action="{{ route('modelo.update', $modelo->id) }}" method="post">
                    @csrf
                    @method('PUT')  
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" name="nome" id="nome" class="form-control" readonly value="{{$modelo->user->name}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cpf">CPF</label>
                            <input type="text" name="cpf" id="cpf" class="form-control" readonly value="{{$modelo->user->username}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_of_birth">Data de Nascimento</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" required class="form-control" value="{{$modelo->data_nascimento}}" readonly>
                            </div>   
                        </div>
                        <div class="col-md-3">
                            <label for="mostra_idade">Exibir minha idade no site?</label>
                            <select name="mostra_idade" id="mostra_idade" class="form-control" required>
                                <option value="0" {{$modelo->mostra_idade == '0' ? 'selected' : ''}}>Não</option>
                                <option value="1" {{$modelo->mostra_idade == '1' ? 'selected' : ''}}>Sim</option>       
                            </select>                     
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="uf_atendimento">UF (Atendimento)</label>
                            <select name="uf_atendimento" id="uf_atendimento" class="form-control" onchange="carregarCidades()" required>
                                <option value="">Selecione</option>
                                <option value="AC" {{$modelo->uf_atendimento == 'ac' ? 'selected' : ''}}>Acre (AC)</option>
                                <option value="AL" {{$modelo->uf_atendimento == 'al' ? 'selected' : ''}}>Alagoas (AL)</option>
                                <option value="AM" {{$modelo->uf_atendimento == 'am' ? 'selected' : ''}}>Amazonas (AM)</option>
                                <option value="AP" {{$modelo->uf_atendimento == 'ap' ? 'selected' : ''}}>Amapá (AP)</option>
                                <option value="BA" {{$modelo->uf_atendimento == 'ba' ? 'selected' : ''}}>Bahia (BA)</option>
                                <option value="CE" {{$modelo->uf_atendimento == 'ce' ? 'selected' : ''}}>Ceará (CE)</option>
                                <option value="DF" {{$modelo->uf_atendimento == 'df' ? 'selected' : ''}}>Distrito Federal (DF)</option>
                                <option value="ES" {{$modelo->uf_atendimento == 'es' ? 'selected' : ''}}>Espírito Santo (ES)</option>
                                <option value="GO" {{$modelo->uf_atendimento == 'go' ? 'selected' : ''}}>Goiás (GO)</option>
                                <option value="MA" {{$modelo->uf_atendimento == 'ma' ? 'selected' : ''}}>Maranhão (MA)</option>
                                <option value="MT" {{$modelo->uf_atendimento == 'mt' ? 'selected' : ''}}>Mato Grosso (MT)</option>
                                <option value="MS" {{$modelo->uf_atendimento == 'ms' ? 'selected' : ''}}>Mato Grosso do Sul (MS)</option>
                                <option value="MG" {{$modelo->uf_atendimento == 'mg' ? 'selected' : ''}}>Minas Gerais (MG)</option>
                                <option value="PA" {{$modelo->uf_atendimento == 'pa' ? 'selected' : ''}}>Pará (PA)</option>
                                <option value="PB" {{$modelo->uf_atendimento == 'pb' ? 'selected' : ''}}>Paraíba (PB)</option>
                                <option value="PE" {{$modelo->uf_atendimento == 'pe' ? 'selected' : ''}}>Pernambuco (PE)</option>
                                <option value="PI" {{$modelo->uf_atendimento == 'pi' ? 'selected' : ''}}>Piauí (PI)</option>
                                <option value="PR" {{$modelo->uf_atendimento == 'pr' ? 'selected' : ''}}>Paraná (PR)</option>
                                <option value="RJ" {{$modelo->uf_atendimento == 'rj' ? 'selected' : ''}}>Rio de Janeiro (RJ)</option>
                                <option value="RN" {{$modelo->uf_atendimento == 'rn' ? 'selected' : ''}}>Rio Grande do Norte (RN)</option>
                                <option value="RO" {{$modelo->uf_atendimento == 'ro' ? 'selected' : ''}}>Rondônia (RO)</option>
                                <option value="RR" {{$modelo->uf_atendimento == 'rr' ? 'selected' : ''}}>Roraima (RR)</option>
                                <option value="RS" {{$modelo->uf_atendimento == 'rs' ? 'selected' : ''}}>Rio Grande do Sul (RS)</option>
                                <option value="SC" {{$modelo->uf_atendimento == 'sc' ? 'selected' : ''}}>Santa Catarina (SC)</option>
                                <option value="SP" {{$modelo->uf_atendimento == 'sp' ? 'selected' : ''}}>São Paulo (SP)</option>
                                <option value="SE" {{$modelo->uf_atendimento == 'se' ? 'selected' : ''}}>Sergipe (SE)</option>
                                <option value="TO" {{$modelo->uf_atendimento == 'to' ? 'selected' : ''}}>Tocantins (TO)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cidade_atendimento" >Cidade de Atendimento <strong>(Campo obrigatório)</strong></label>
                            <input type="text" id="cidade_atendimento" name="cidade_atendimento" placeholder="Digite 3 letras do nome da cidade e aguarde a lista aparecer" class="form-control" value="{{$modelo->cidade_atendimento}}">
                            {{-- <select name="cidade_atendimento" id="cidade_atendimento" class="form-control" required >
                                <!-- As cidades serão carregadas aqui dinamicamente -->
                            </select> --}}
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="possui_local">Possui local de atendimento? <strong>(Campo obrigatório)</strong></label>
                            <select name="possui_local" id="possui_local" class="form-control" required>
                                <option value="1" {{$modelo->possui_local == 1 ? 'selected' : ''}}>Sim</option>
                                <option value="0" {{$modelo->possui_local == 0 ? 'selected' : ''}}>Não</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cep">CEP (Local de atendimento)</label>
                        <input type="number" name="cep" id="cep" class="form-control"  value="{{$modelo->cep}}" onblur="buscarEndereco()">                       
                    </div>
                    <div class="form-group">
                        <label for="numero">Nº</label>
                        <input type="number" name="numero" id="numero" class="form-control"  value="{{$modelo->numero}}">
                    </div>
                    <div class="form-group">
                        <label for="complemento">Complemento</label>
                        <input type="text" name="complemento" id="complemento" class="form-control" value="{{$modelo->complemento}}">
                    </div>                    
                    <div class="form-group">
                        <label for="logradouro">Logradouro</label>
                        <input type="text" name="logradouro" id="logradouro" class="form-control"  readonly value="{{$modelo->logradouro}}">
                    </div>
                    <div class="form-group">
                        <label for="bairro">Bairro (Preenchimento automático)</label>
                        <input type="text" name="bairro" id="bairro" class="form-control"  readonly value="{{$modelo->bairro}}">
                    </div>
                    <div class="form-group">
                        <label for="cidade">Cidade (Preenchimento automático)</label>
                        <input type="text" name="cidade" id="cidade" class="form-control"  readonly value="{{$modelo->cidade}}">
                    </div>
                    <div class="form-group">
                        <label for="uf">UF (Preenchimento automático)</label>
                        <input type="text" name="uf" id="uf" class="form-control" readonly value="{{$modelo->uf}}">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email" required value="{{$modelo->user->email}}">
                        </div>
                    </div>                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefone">Telefone (Digite apenas números)</label>
                            <input type="phone" name="telefone" id="telefone" class="form-control" required value="{{$modelo->telefone}}">
                        </div>  
                    </div>
                    <div class="form-group">
                        <label for="nome_fantasia">Com qual nome vai atender? <strong>(Campo obrigatório)</strong></label>
                        <input type="text" name="nome_fantasia" id="nome_fantasia" class="form-control" required value="{{$modelo->nome_fantasia}}">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="altura">Altura (Digite apenas números)</label>
                            <input type="text" name="altura" id="altura" class="form-control" required value="{{$modelo->altura}}" inputmode="numeric">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="peso">Peso (Digite apenas números)</label>
                            <input type="text" name="peso" id="peso" class="form-control" required value="{{$modelo->peso}}" inputmode="numeric">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="genero">Gênero</label>
                            <select name="genero" id="genero" class="form-control" required>
                                <option value="">Escolha</option>
                                <option value="mulher" {{$modelo->genero == 'mulher' ? 'selected' : ''}}>Mulher</option>
                                <option value="trans" {{$modelo->genero == 'trans' ? 'selected' : ''}}>Trans</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="genitalia">Genitália</label>
                            <select name="genitalia" id="genitalia" class="form-control" required>
                                <option value="">Escolha</option>
                                <option value="v" {{$modelo->genitalia == 'v' ? 'selected' : ''}}>Vagina</option>
                                <option value="p" {{$modelo->genitalia == 'p' ? 'selected' : ''}}>Pênis</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="quem_atende">Quem atendo</label>
                            <select name="quem_atende" id="silicone" class="form-control" required>
                                <option value="">Escolha</option>
                                <option value="apenas_homens" {{$modelo->quem_atende == 'apenas_homens' ? 'selected' : ''}}>Apenas homens</option>
                                <option value="apenas_mulheres" {{$modelo->quem_atende == 'apenas_mulheres' ? 'selected' : ''}}>Apenas mulheres</option>
                                <option value="homens_mulheres_trans" {{$modelo->quem_atende == 'homens_mulheres_trans' ? 'selected' : ''}}>Homens, mulheres e trans</option>
                            </select>
                        </div>  
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="atende_grupo">Atende grupo?</label>
                            <select name="atende_grupo" id="atende_grupo" class="form-control" required>
                                <option value="">Escolha</option>
                                <option value="1" {{$modelo->atende_grupo == 1 ? 'selected' : ''}}>Sim</option>
                                <option value="0" {{$modelo->atende_grupo == 0 ? 'selected' : ''}}>Não</option>
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cor_pele">Cor da pele</label>
                            <select name="cor_pele" id="cor_pele" class="form-control" required>
                                <option value="">Escolha</option>
                                @foreach ($pele as $p)
                                    <option value="{{$p->id}}" {{$modelo->cor_pele_id == $p->id ? 'selected' : ''}}>{{$p->descricao}}</option>                                
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cor_cabelo">Cor do cabelo</label>
                            <select name="cor_cabelo" id="cor_cabelo" class="form-control" required>
                                <option value="">Escolha</option>
                                @foreach ($cabelo as $c)
                                    <option value="{{$c->id}}" {{$modelo->cor_cabelo_id == $c->id ? 'selected' : ''}}>{{$c->descricao}}</option>                                
                                @endforeach
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cor_olho">Cor dos olhos</label>
                            <select name="cor_olho" id="cor_olho" class="form-control" required>
                                <option value="">Escolha</option>
                                @foreach ($olhos as $o)
                                    <option value="{{$o->id}}" {{$modelo->cor_olho_id == $o->id ? 'selected' : ''}}>{{$o->descricao}}</option>                                
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="piercing">Possui piercing?</label>
                            <select name="piercing" id="piercing" class="form-control" required>
                                <option value="">Escolha</option>
                                <option value="sim_poucos" {{$modelo->piercing == 'sim_poucos' ? 'selected' : ''}}>Sim, poucos</option>
                                <option value="sim_muitos" {{$modelo->piercing == 'sim_muitos' ? 'selected' : ''}}>Sim, muitos</option>
                                <option value="nao" {{$modelo->piercing == 'nao' ? 'selected' : ''}}>Não</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tatuagem">Possui tatuagem?</label>
                            <select name="tatuagem" id="tatuagem" class="form-control" required>
                                <option value="">Escolha</option>
                                <option value="sim_poucas" {{$modelo->tatuagem == 'sim_poucas' ? 'selected' : ''}}>Sim, poucas</option>
                                <option value="sim_muitas" {{$modelo->tatuagem == 'sim_muitas' ? 'selected' : ''}}>Sim, muitas</option>
                                <option value="nao" {{$modelo->tatuagem == 'nao' ? 'selected' : ''}}>Não</option>
                            </select>
                        </div>            
                    </div>
                    <div class="col-md-4">        
                        <div class="form-group">
                            <label for="silicone">Possui silicone?</label>
                            <select name="silicone" id="silicone" class="form-control" required>
                                <option value="">Escolha</option>
                                <option value="1" {{$modelo->silicone == 1 ? 'selected' : ''}}>Sim</option>
                                <option value="0" {{$modelo->silicone == 0 ? 'selected' : ''}}>Não</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="servicos">Serviços oferecidos</label>
                        @foreach ($servicos as $s)
                            <div class="form-check">
                                <input 
                                    type="checkbox" 
                                    name="servicos[]" 
                                    value="{{$s->id}}" 
                                    class="form-check-input"
                                    @if(in_array($s->id, $servicosSelecionados)) checked @endif
                                >
                                <label class="form-check-label">{{$s->nome}}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <label for="valor_hora">Valor Hora</label>
                        <input type="number" name="valor_hora" id="valor_hora" class="form-control" step="0.01" value="{{$modelo->valor_hora}}" required></label>
                    </div>
                    <div class="form-group">
                        <label for="frase_impacto">Frase de impacto</label>
                        <input type="text" name="frase_impacto" id="frase_impacto" class="form-control" value="{{$modelo->frase_impacto}}" required>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea name="descricao" id="descricao" cols="30" rows="10" class="form-control" required>{{$modelo->descricao}}</textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-primary">Salvar</button>
                        <a href="{{route('servicos.index')}}" class="btn btn-sm btn-secondary">Voltar</a>                          
                        @if ($role == 'admin')
                            <a href="{{route('fotos_admin.index',$modelo->id)}}" class="btn btn-sm btn-success">Fotos</a>
                            <a href="{{route('videos_admin.index',$modelo->id)}}" class="btn btn-sm btn-warning">Vídeos</a>
                        @endif
                      
                    </div>
                </form>
            </div>
        </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@stop

@section('js')
    <script src="{{ asset('js/bsCustomFileInput.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"> </script>

    <script>
        function buscarEndereco() {
            const cep = document.getElementById('cep').value;
    
            // Valida o formato do CEP (somente números e comprimento de 8)
            if (cep.length !== 8 || isNaN(cep)) {
                toastr.error('CEP inválido. Insira um CEP com 8 dígitos.');
                return;
            }
    
            // Consulta à API ViaCEP
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao consultar o CEP.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.erro) {
                        toastr.error('CEP não encontrado.');
                        return;
                    }
    
                    // Preenche os campos de endereço
                    document.getElementById('logradouro').value = data.logradouro || '';
                    document.getElementById('bairro').value = data.bairro || '';
                    document.getElementById('cidade').value = data.localidade || '';
                    document.getElementById('uf').value = data.uf || '';
                })
                .catch(error => {
                    console.error(error);
                    toastr.error('Erro ao buscar o CEP. Tente novamente.');
                });
        }

        $('#uf_atendimento').change(function() {
            carregarCidades();
        });
        
        function carregarCidades() {
            var uf = $('#uf_atendimento').val(); // Obtém a UF selecionada

            if (uf) {
                // Faz a requisição AJAX para buscar as cidades da UF
                $.ajax({
                    url: "{{$url}}/" + uf, // Rota para buscar as cidades
                    method: 'GET',
                    success: function(data) {
                        // Verifica se há cidades no retorno
                        if (data && data.length > 0) {
                            // Inicializa o autocomplete
                            $('#cidade_atendimento').autocomplete({
                                source: data.map(function(cidade) {
                                    return cidade.nome; // Retorna apenas o nome da cidade
                                }),
                                minLength: 2, // Número mínimo de caracteres para iniciar o autocomplete
                                select: function(event, ui) {
                                    // Função chamada quando uma cidade é selecionada
                                    console.log("Cidade selecionada: " + ui.item.value);
                                }
                            });
                        } else {
                            // Caso não haja cidades, exibe uma mensagem
                            $('#cidade_atendimento').autocomplete({
                                source: ["Nenhuma cidade encontrada"]
                            });
                        }
                    },
                    error: function() {
                        alert('Erro ao carregar as cidades. Tente novamente.');
                    }
                });
            } else {
                // Caso nenhuma UF seja selecionada, limpa o campo de cidades
                $('#cidade_atendimento').autocomplete({
                    source: []
                });
            }
        }

        $(document).ready(function() {
            // Função para mostrar ou ocultar os campos com base na seleção de "possui_local"
            function toggleCamposEndereco() {
                if ($('#possui_local').val() == 1) {
                    // Se "Sim" for selecionado, mostra os campos
                    $('#cep, #logradouro, #numero, #complemento, #bairro, #cidade, #uf').closest('.form-group').show();
                } else {
                    // Se "Não" for selecionado, oculta os campos
                    $('#cep, #logradouro, #numero, #complemento, #bairro, #cidade, #uf').closest('.form-group').hide();
                }
            }

            // Executa a função ao carregar a página
            toggleCamposEndereco();

            // Adiciona o evento change ao campo "possui_local"
            $('#possui_local').change(function() {
                toggleCamposEndereco();
            });

            function formatarAltura(input) {
                let valor = input.value.replace(/\D/g, '');

                if (valor.length > 3) {
                    valor = valor.substring(0, 3);
                }

                if (valor.length > 1) {
                    valor = valor.substring(0, 1) + ',' + valor.substring(1);
                }

                input.value = valor;
            }

            // Adiciona o evento de input ao campo de altura
            $('#altura').on('input', function() {
                formatarAltura(this);
            });

            function formatarPeso(input) {
                let valor = input.value.replace(/[^\d,]/g, ''); // Remove tudo que não for número ou vírgula
                valor = valor.replace(',', '.'); // Substitui a vírgula por ponto para manipulação numérica
                
                if (valor.includes('.')) {
                    let partes = valor.split('.');
                    partes[0] = partes[0].substring(0, 3); // Máximo de 3 dígitos na parte inteira
                    partes[1] = partes[1].substring(0, 1); // Apenas 1 casa decimal
                    
                    valor = partes[0] + ',' + partes[1]; // Junta novamente com vírgula
                } else {
                    valor = valor.substring(0, 3); // Máximo de 3 dígitos na parte inteira
                }

                input.value = valor;
            }

            // Adiciona o evento de input ao campo de peso
            $('#peso').on('input', function() {
                formatarPeso(this);
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
            
            $('input, select').focus(function() {
                $(this).css('background-color', '#f0f0f0');
            }).blur(function() {
                $(this).css('background-color', '');
            });

            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000"
            };            

            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @elseif (session('error'))
                toastr.error('{{ session('error') }}');
            @endif   
            
            // Adicionar loader ao enviar o formulário
            $('form').on('submit', function() {
                // Desabilitar o botão de envio para evitar múltiplos cliques
                $('button[type="submit"]').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
            });    
            
            let formChanged = false;
            $('form :input').on('change', function() {
                formChanged = true;
            });

            $('form').on('submit', function() {
                formChanged = false;
            });

            $(window).on('beforeunload', function() {
                if (formChanged) {
                    return "Você tem alterações não salvas. Deseja realmente sair desta página?";
                }
            });            

        });     

    </script>
@stop