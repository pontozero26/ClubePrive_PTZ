@extends('adminlte::page')

@section('title', 'Editar usuário')

@section('content')
    <br>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <strong>Editar Usuário</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('usuarios.update',$user->id) }}" method="post">
                @csrf
                @method('PUT')
                <input type="hidden" name="role" value="admin">
                <div class="form-group">
                    <label for="name">Nome da pessoa</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{$user->name}}">
                </div>
                <div class="form-group">
                    <div class="form-group">
                        <label for="username">Nome de usuário</label>
                        <input type="text" name="username" id="username" class="form-control" value={{$user->username}}>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value={{$user->email}}>
                    </div>
                </div>
                <div class="form-group">
                    <label for="role">Tipo</label>
                    <select name="role" id="role" class="form-control">
                        <option value="admin" {{$user->role == 'admin' ? 'selected' : ''}}>Administrador</option>
                        <option value="user" {{$user->role == 'user' ? 'selected' : ''}}>Regular</option>
                        <option value="guest"  {{$user->role == 'guest' ? 'selected' : ''}}>Visitante</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-sm btn-primary">Salvar</button>
                    <a href="{{route('usuarios.delete', $user->id)}}" class="btn btn-sm btn-danger">Apagar</a>
                    <a href="{{route('usuarios_admins.index')}}" class="btn btn-sm btn-secondary">Voltar</a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
