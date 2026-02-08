@extends('adminlte::page')

@section('title', 'Criar SEO')

@section('content')
    <div class="card card-primary card-outline" style="margin-top: 10px">
        <div class="card-header">
            Criar novo SEO
        </div>
        <div class="card-body">
            <form id="form1" action={{ route('seo.store') }} method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" class="form-control" placeholder="Nome" required>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo</label>
                    <select name="tipo" id="tipo" class="form-control">
                        <option value="head">Head</option>
                        <option value="body">body</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Ativo?</label>
                    <select name="status" id="status" class="form-control">
                        <option value="1" selected>Sim</option>
                        <option value="0">NÃ£o</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="script">Script</label>
                    <textarea name="script" id="script" cols="30" rows="5" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-sm">Enviar</button>
                    <a href="{{route('seo.index')}}" class="btn btn-secondary btn-sm">Voltar</a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop