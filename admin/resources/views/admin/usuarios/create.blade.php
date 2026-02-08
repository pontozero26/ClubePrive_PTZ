@extends('adminlte::page')

@section('title', 'Novo usuário')

@section('content')
    <br>
        <div class="card card-primary card-outline">
            <div class="card-header">
                Novo Usuário
            </div>
            <div class="card-body">
                <form action="{{ route('usuarios.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="role" value="admin">
                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Nome de usuário (Único)</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Tipo</label>
                        <select name="role" id="role" class="form-control" required >
                            <option value="admin" selected>Administrador</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-primary">Salvar</button>
                        <a href="{{route('usuarios_admins.index')}}" class="btn btn-sm btn-secondary">Voltar</a>
                    </div>
                </form>
            </div>
        </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop





