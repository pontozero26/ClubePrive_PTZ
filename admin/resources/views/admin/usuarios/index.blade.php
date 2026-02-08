@extends('adminlte::page')

@section('title', 'Usuários')

@section('content')
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-secondary">
                <div class="card-body">
                    @if ($tipo == 'user')
                        <h3>Assinantes</h3>
                    @else
                        <h3>Administradores</h3>
                    @endif
                    {{-- <div class="d-flex align-items-center" style="gap: 80px; margin-left: 25px">
                        <div class="form-group">
                            <input class="form-check-input" type="checkbox" name="filter-users" id="filter-users">
                            <label for="filter-users" class="form-check-label">Acompanhantes</label>
                        </div>
                        <div class="form-group">
                            <input class="form-check-input" type="checkbox" name="filter-admin" id="filter-admin">
                            <label for="filter-admin" class="form-check-label">Administradores</label>
                        </div>
                        <div class="form-group">
                            <input class="form-check-input" type="checkbox" name="filter-guests" id="filter-guests">
                            <label for="filter-guests" class="form-check-label">Visitantes</label>
                        </div>
                    </div>                   --}}
                    <table class="table table-hover table-sm" id="table1">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                @if ($tipo == 'user')
                                    <th>Nome fantasia</th> 
                                    <th>Telefone</th>       
                                    <th>Data cadastro</th>                             
                                    <th>Pagamento</th>
                                @else
                                    <th>Usuário</th>
                                @endif 
                                <th style="text-align: center;">Ativo?</th>
                                @if ($tipo != 'admin')                           
                                    <th style="text-align: center;">Visível?</th>
                                @endif
                                <th width="10%"></th>                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $item)
                                <tr  class="clickable-row" data-id="{{ $item->id }}">
                                    <td>{{$item->name}}</td>     
                                    <td>{{$item->email}}</td>                                   
                                    @if ($tipo != 'admin')
                                        <td>{{ $item->modelo->nome_fantasia ?? 'Não preencheu' }}</td>
                                        <td>{{ $item->modelo->telefone }}</td>
                                        <td>{{\Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                        <td>
                                            {!! $item->fez_pagamento ? "<span class='badge badge-success'>Aprovado</span>" : 
                                            "<span class='badge badge-warning'>Pendente</span>" !!}
                                        </td>
                                    @else
                                        <td>{{ $item->username }}</td>
                                    @endif                                    
                                    <td style="text-align: center;">
                                        {!! $item->is_active ? "<span class='badge badge-success'>Ativo</span>" : 
                                            "<span class='badge badge-danger'>Banido</span>" !!}
                                    </td>
                                    @if ($tipo != 'admin')
                                        <td style="text-align: center;">
                                            {!! $item->visivel ? "<span class='badge badge-success'>Visível</span>" : 
                                                "<span class='badge badge-warning'>Invisível</span>" !!}
                                        </td>
                                    @endif
                                    <td><a href="{{ route('usuarios.edit', $item->id) }}" title="Editar"><i class="fas fa-edit"></i></a>
                                        @if ($item->is_active == '1')
                                            <a href="{{ route('usuarios.banir', $item->id) }}" title="Banir perfil">
                                                <i class="fas fa-user-minus"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('usuarios.banir', $item->id) }}" title="Desbanir perfil">
                                                <i class="fas fa-user-plus text-danger"></i>
                                            </a>
                                        @endif
                                        @if ($tipo != 'admin')
                                        @if ($item->visivel)
                                            <a href="{{ route('usuarios.ativar', $item->id) }}" title="Inativar perfil">
                                                <i class="fas fa-eye-slash"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('usuarios.ativar', $item->id) }}" title="Ativar perfil">
                                                    <i class="fas fa-eye text-warning"></i>
                                                </a>
                                            @endif
                                        @endif
                                        <a href="{{ route('usuarios.destroy', $item->id) }}" title="Remover"><i class="fas fa-trash"></i></a>
                                    </td>                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"> </script>
    <script>
        $(document).ready(function() {
            const tableConfig = {
                paging: false,
                layout: {
                    topStart: {
                        buttons: []
                    }
                },
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json'
                }
            };

            // Verifica se o tipo não é 'admin' para adicionar o botão "Inserir"
            @if ($tipo == 'admin')
                tableConfig.layout.topStart.buttons.push({
                    text: 'Inserir',
                    action: function (e, dt, node, config) {
                        window.location.href = "{{ route('usuarios.create') }}";
                    }
                });
            @endif

            const table = new DataTable('#table1', tableConfig);

            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @elseif (session('error'))
                toastr.error('{{ session('error') }}');
            @endif

            $('.clickable-row').on('dblclick', function () {
                const id = $(this).data('id');
                const editUrl = `{{ url('usuarios') }}/` + id + `/edit`;
                window.location.href = editUrl;
            });
            
            $('input[type="checkbox"]').on('change', function () {
                const filters = [];

                if ($('#filter-users').is(':checked')) {
                    filters.push('Acompanhante');
                }
                if ($('#filter-admin').is(':checked')) {
                    filters.push('Administrador');
                }
                if ($('#filter-guests').is(':checked')) {
                    filters.push('Visitante');
                }

                if (filters.length > 0) {
                    const regex = filters.join('|');
                    table.column(5).search(regex, true, false).draw();
                } else {
                    table.column(5).search('').draw();
                }
            });

            // Adiciona evento de clique nos ícones de Banir e Remover
            $('a[title="Remover"]').on('click', function(event) {
                event.preventDefault(); // Impede o redirecionamento imediato

                const url = $(this).attr('href'); // Obtém a URL do link
                const action = $(this).attr('title'); // Obtém o título da ação (Banir ou Remover)

                toastr.warning(
                    `<div class="text-center">
                        <p>Você <strong>tem certeza</strong> que deseja ${action.toLowerCase()} este usuário?</p>
                        <button type="button" class="btn btn-danger btn-sm" id="confirmAction">Sim</button>
                        <button type="button" class="btn btn-secondary btn-sm" id="cancelAction">Não</button>
                    </div>`,
                    "Confirmação",
                    {
                        timeOut: 0, // Impede que o alerta desapareça automaticamente
                        extendedTimeOut: 0,
                        closeButton: true,
                        tapToDismiss: false,
                        allowHtml: true, // Permite HTML no corpo do alerta
                        positionClass: "toast-top-center", // Posiciona no topo central
                    }
                );

                // Aguarda a resposta do usuário
                $(document).on('click', '#confirmAction', function() {
                    window.location.href = url; // Redireciona para a URL do link
                });

                $(document).on('click', '#cancelAction', function() {
                    toastr.clear(); // Fecha o alerta
                });
            });
        });


    </script>
@stop

@section('css')
    <link rel="stylesheet" href={{ asset('css/admin_custom.css') }}>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style>
        .checkbox-cell {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
    </style>
@stop
