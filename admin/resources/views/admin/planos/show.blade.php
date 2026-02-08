@extends('adminlte::page')

@section('title', 'Planos')

@section('content')
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-secondary">
                <div class="card-header">
                    {{$plano->nome}}
                </div>
                <div class="card-body">
                    <p>{{$plano->descricao}}</p>
                    <p>{{ 'R$ '.number_format($plano->valor,2,',','.') }}</p>
                    <p>{{$plano->dias_semana}}</p>
                    <p>{{$plano->hora_inicio}} - {{$plano->hora_fim}}</p>
                    <p>ModeloId: {{$modeloId}}</p>
                    <p>plano_id: {{$plano->id}}</p>
                    <form action="{{ route('modelo.associar-plano',$modeloId) }}" method="POST" id="confirmarPlanoForm">
                        @csrf
                        <input type="hidden" name="plano_id" value="{{$plano->id}}">
                        <button type="submit" class="btn btn-sm btn-primary" id="confirmarPlanoBtn">Confirmar</button>
                        <a href="{{route('planos.index')}}" class="btn btn-sm btn-secondary">Voltar</a>
                    </form>
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


});

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@stop
