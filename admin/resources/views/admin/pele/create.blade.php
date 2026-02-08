@extends('adminlte::page')

@section('title', 'Nova cor de pele')

@section('content')
    <br>
        <div class="card card-primary card-outline">
            <div class="card-header">
                Nova cor de pele
            </div>
            <div class="card-body">
                <form action="{{ route('pele.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="descricao">Cor</label>
                        <input type="text" name="descricao" id="descricao" class="form-control" required value="{{old('descricao')}}">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-primary">Salvar</button>
                        <a href="{{route('pele.index')}}" class="btn btn-sm btn-secondary">Voltar</a>
                    </div>
                </form>
            </div>
        </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop