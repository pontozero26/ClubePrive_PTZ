@extends('adminlte::page')

@section('title', 'Cor de olhos')

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
                                <th>Cor</th>
                                <th width="3%"></th>                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($itens as $item)
                                <tr  class="clickable-row" data-id="{{ $item->id }}">
                                    <td>{{$item->descricao}}</td>
                                    <td><a href="{{ route('olhos.edit', $item->id) }}" title="Editar"><i class="fas fa-edit"></i></a></td>                                    
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
                            window.location.href = "{{ route('olhos.create') }}";
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
        const editUrl = `{{ url('servicos') }}/` + id + `/edit`;
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
    <link rel="stylesheet" href={{ asset('css/admin_custom.css') }}>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@stop
