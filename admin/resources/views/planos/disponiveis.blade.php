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
                                <th width="3%"></th>
                                <th width="5%" align="center">Id</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Dias Semana</th>
                                <th>Hora início</th>
                                <th>Hora fim</th>
                                <th>Ativo?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($planos as $item)
                                <tr  class="clickable-row" data-id="{{ $item->id }}">
                                    <td style="text-align: center;"><a href="{{ route('planos.edit', $item->id) }}" title="Editar"><i class="fas fa-edit"></i></a>
                                        </form>
                                    </td>
                                    <td style="text-align: center;">{{$item->id}}</td>
                                    <td>{{$item->nome}}</td>
                                    <td>{{$item->descricao}}</td>
                                    <td>{{ 'R$ '.number_format($item->valor,2,',','.') }}</td>
                                    <td>{{$item->dias_semana}}</td>
                                    <td>{{$item->hora_inicio}}</td>
                                    <td>{{$item->hora_fim}}</td>
                                    <td style="text-align: center;">
                                        @if ($item->ativo == 1)
                                            <i class="fas fa-check text-success"></i>
                                        @else
                                            <i class="fas fa-ban text-danger"></i>
                                        @endif
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

    // table.on('init', function() {
    //     // Adicionando checkbox com formatação
    //     const filterActiveButton = document.querySelector('.dt-buttons');
        
    //     const divContainer = document.createElement('div');
    //     divContainer.className = 'form-check form-check-inline';  // Adiciona classes do Bootstrap
        
    //     const checkbox = document.createElement('input');
    //     checkbox.type = 'checkbox';
    //     checkbox.id = 'filter-active';
    //     checkbox.className = 'form-check-input';  // Classe Bootstrap
    //     checkbox.checked = true;

        // const label = document.createElement('label');
        // label.htmlFor = 'filter-active';
        // label.className = 'form-check-label';  // Classe Bootstrap
        // label.textContent = 'Mostrar apenas ativas';

        // divContainer.appendChild(checkbox);
        // divContainer.appendChild(label);

        // // Adiciona o container com checkbox e label à toolbar
        // filterActiveButton.appendChild(divContainer);

        // // Função para aplicar o filtro
        // function applyFilter() {    
        //     if (checkbox.checked) {
        //         table.columns(4).search('Sim').draw();
        //     } else {
        //         table.columns(4).search('').draw();
        //     }
        // }

        // applyFilter();
        // checkbox.addEventListener('change', applyFilter);
    // });
});
    </script>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@stop
