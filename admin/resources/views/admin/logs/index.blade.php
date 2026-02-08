@extends('adminlte::page')

@section('title', 'Log de atividades')

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
                                <th>Data</th>                                
                                <th>Mensagem</th>
                                <th>Contexto</th>
                                <th></th>                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $item)
                                <tr  class="clickable-row" data-id="{{ $item->id }}">
                                    <td>{{ Carbon\Carbon::parse($item->logged_at)->format('d/m/Y H:i:s') }} </td>                                    
                                    <td>{{$item->message}}</td>
                                    <td>{{$item->context}}</td>
                                    <td><a href="{{ route('logs.show', $item->id) }}" title="Mostrar"><i class="fas fa-eye"></i></a>
                                    </form>
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
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json'
        }
    });

    $('.clickable-row').on('dblclick', function () {
        const id = $(this).data('id');
        const editUrl = `{{ url('planos') }}/` + id + `/edit`;
        window.location.href = editUrl;
    });
});
    </script>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@stop
