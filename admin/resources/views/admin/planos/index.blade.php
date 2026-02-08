@extends('adminlte::page')

@section('title', 'Planos')

@section('content')
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-secondary">
                {{-- <div class="card-header">
                    <a href="{{ route('unidades.create') }}" class="btn btn-primary">Nova unidade</a>
                </div> --}}
                <div class="card-body">
                    <table class="table table-hover table-sm" id="table1">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Dias Semana</th>
                                <th>Hora início</th>
                                <th>Hora fim</th>
                                <th>Ativo?</th>
                                <th width="3%">Ações</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($planos as $item)
                                <tr  class="clickable-row" data-id="{{ $item->id }}">
                                    <td>{{$item->nome}}</td>
                                    <td>{{$item->descricao}}</td>
                                    <td>{{ 'R$ '.number_format($item->valor,2,',','.') }}</td>
                                    <td>{{$item->dias_semana}}</td>
                                    <td>{{$item->hora_inicio}}</td>
                                    <td>{{$item->hora_fim}}</td>
                                    <td style="text-align: center;">
                                        <input type="checkbox" class="toggle-status" data-id="{{ $item->id }}" 
                                            {{ $item->ativo? 'checked' : '' }} data-toggle="switch">
                                    </td>
                                    <td style="text-align: center;"><a href="{{ route('planos.edit', $item->id) }}" title="Editar"><i class="fas fa-edit"></i></a></td>                                    
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
        const table = new DataTable('#table1', {
        paging: false,
        layout: {
            topStart: {
                buttons: [
                    {
                        text: 'Inserir',
                        action: function (e, dt, node, config) {
                            window.location.href = "{{ route('planos.create') }}";
                        }
                    }
                ]
            }
        },
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json'
        }
        });

        @if (session('success'))
            toastr.success('{{ session('success') }}');
        @elseif (session('error'))
            toastr.error('{{ session('error') }}');
        @endif

        $('.clickable-row').on('dblclick', function () {
            const id = $(this).data('id');
            const editUrl = `{{ url('planos') }}/` + id + `/edit`;
            window.location.href = editUrl;
        });

        // Função para alternar o status do plano
        $('.toggle-status').on('change', function() {
            let planoId = $(this).data('id'); 
            let isChecked = $(this).is(':checked') ? 1 : 0; // Converte para 1 ou 0

            $.ajax({
                url: `{{ route('planos.set_ativo', '') }}/${planoId}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    ativo: isChecked // Enviar o valor correto
                },
                success: function(response) {
                    toastr.success('Status do plano atualizado com sucesso!');
                },
                error: function() {
                    toastr.error('Erro ao atualizar status do plano.');
                }
            });
        });
    });
    </script>
@stop

@section('css')
    <link rel="stylesheet" href={{ asset('css/admin_custom.css') }}>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style>
       .toggle-status {
            width: 36px;
            height: 16px;
            position: relative;
            appearance: none;
            background: #ddd;
            border-radius: 20px;
            cursor: pointer;
            outline: none;
            transition: 0.3s;
       }
        .toggle-status:checked {
            background: #28a745;
        }
        .toggle-status:before {
            content: "";
            width: 14px;
            height: 14px;
            position: absolute;
            top: 1px;
            left: 2px;
            background: #fff;
            border-radius: 50%;
            transition: 0.3s;
        }
        .toggle-status:checked:before {
            left: 20px;
        }

    </style>
@stop
