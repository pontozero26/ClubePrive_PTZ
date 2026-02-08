@extends('adminlte::page')

@section('title', 'Editar SEO')

@section('content')
    <div class="card card-primary card-outline" style="margin-top: 10px">
        <div class="card-header">
            Editar SEO
        </div>
        <div class="card-body">
            <form id="form1" action={{ route('seo.update', $seo->id) }} method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" class="form-control" placeholder="Nome" value="{{$seo->nome}}">
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo</label>
                    <select name="tipo" id="tipo" class="form-control">
                        @if ($seo->tipo == 'head')
                            <option value="head" selected>Head</option>
                            <option value="body">Body</option>
                        @else
                            <option value="head">Head</option>
                            <option value="body" selected>Body</option>
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        @if ($seo->status == '1')
                            <option value="1" selected>Sim</option>
                            <option value="0">Não</option>
                        @else
                            <option value="1">Sim</option>
                            <option value="0" selected>Não</option>
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label for="script">Script</label>
                    <textarea name="script" id="script" cols="30" rows="5" class="form-control">{{$seo->script}}</textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-sm btn-primary">Salvar</button>
                    <button type="button" class="btn btn-sm btn-danger" id="btn-excluir">Apagar</button>
                    <a href="{{route('generos.index')}}" class="btn btn-sm btn-secondary">Voltar</a>
                </div>
                <form id="delete-form" action="{{ route('seo.delete', $seo->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop