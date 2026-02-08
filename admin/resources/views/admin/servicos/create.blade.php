@extends('adminlte::page')

@section('title', 'Novo serviço')

@section('content')
    <br>
        <div class="card card-primary card-outline">
            <div class="card-header">
                Novo tipo de serviço
            </div>
            <div class="card-body">
                <form action="{{ route('servicos.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control" required value="{{old('nome')}}">
                    </div>
                    <div class="form-group">
                        <label for="ativo">Está ativo?</label>
                        <select name="ativo" id="ativo" class="form-control">
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-primary">Salvar</button>
                        <a href="{{route('servicos.index')}}" class="btn btn-sm btn-secondary">Voltar</a>
                    </div>
                </form>
            </div>
        </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop