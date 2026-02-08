@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
<br>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h5 class="m-0">
                Plano de assinatura
            </h5>
        </div>
        <div class="card-body">
            @empty($plano) 
                <p>Sem plano de assinatura.</p>
            @else
                <p>Plano ativo: {{$plano->nome}}</p>
                <p>Data de contratação: {{\Carbon\Carbon::parse($historicoPlano->data_contratacao)->format('d/m/Y') }}</p>
            @endempty
            <a href="{{route('planos.index')}}" class="btn btn-primary btn-small">Alterar</a>
        </div>
    </div>    
            
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h5 class="m-0">
                Vídeo de verificação
            </h5>
        </div>
        <div class="card-body">
            @if(!empty($video) && $video->created_at)
                <h6>Enviado em {{ $video->created_at->format('d/m/Y') }}</h6>
            @endif
            @if(!empty($video))
                <video controls width="250">
                    <source src="{{ asset($video->caminho.'/'.$video->nome_arquivo)}}" type="video/mp4" />
                </video>
                <br>
                <a href="#" class="btn btn-primary btn-small">Alterar</a>
            @else
                <p>Vídeo não enviado.</p>
                <a href="{{route('videos.create')}}" class="btn btn-primary btn-small">Enviar</a>
            @endif
        </div>
    </div>    

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    <link rel="stylesheet" href="{{ asset('css/admin_custom.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
                
            @if(session('error'))
                toastr.error('{{ session('error') }}');
            @endif
            @if(session('success'))
                toastr.success('{{ session('success') }}');
            @endif
        });
    </script>
@stop