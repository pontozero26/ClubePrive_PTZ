@extends('adminlte::page')

@section('title', 'Editando cor do olho')

@section('content')
    <br>
        <div class="card card-primary card-outline">
            <div class="card-header">
                Editando cor do olho: {{ $item->descricao }}
            </div>
            <div class="card-body">
                <form action="{{ route('olhos.update', $item->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="descricao">Nome</label>
                        <input type="text" name="descricao" id="descricao" class="form-control" required value="{{$item->descricao}}">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-primary">Salvar</button>
                        <button type="button" class="btn btn-sm btn-danger" id="btn-excluir">Apagar</button>
                        <a href="{{route('servicos.index')}}" class="btn btn-sm btn-secondary">Voltar</a>
                    </div>
                </form>
                <form id="delete-form" action="{{ route('olhos.delete', $item->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        document.getElementById('btn-excluir').addEventListener('click', function (event) {
            event.preventDefault();

            Swal.fire({
                title: 'Tem certeza?',
                text: "O item será apagado. Esta ação não pode ser desfeita.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, apagar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submete o formulário de exclusão se confirmado
                    document.forms['delete-form'].submit();
                }
            });
        });
    </script>
@stop