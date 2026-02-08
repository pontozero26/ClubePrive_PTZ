@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <a href="{{route('usuarios_acompanhantes.index')}}" class="small-box-footer">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{$modelos}}</h3>
                            <p>Assinantes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-6">
                <a href="{{route('planos.index')}}">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{$planos}}</h3>
                            <p>Planos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-fw fa-hand-holding-usd"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-6">
                <a href="{{route('servicos.index')}}">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{$servicos}}</h3>
                            <p>Serviços</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-fw fa-concierge-bell"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    <link rel="stylesheet" href={{ asset('css/admin_custom.css') }}
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop