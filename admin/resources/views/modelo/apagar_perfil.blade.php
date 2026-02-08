@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
<br>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h5 class="m-0">
                Apagar perfil
            </h5>
        </div>
        <div class="card-body">
            <p>Você tem certeza que quer fazer isso?</p>
            <p>Se você apagar o perfil, não será possível recuperá-lo.</p>
            <p>Se você tem certeza, clique no botão abaixo.</p>
            <a href="{{route('usuarios.destroy',$id)}}" class="btn btn-danger">Apagar perfil</a>
        </div>
    </div>    
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    <link rel="stylesheet" href="{{ asset('css/admin_custom.css') }}">    
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    
@stop

@section('js')
<script>
    document.getElementById('flexCheckChecked').addEventListener('change', function() {
        if (this.checked) {
            // Exibe um SweetAlert de sucesso quando o checkbox é selecionado
            Swal.fire({
                icon: 'success',
                title: 'Perfil ativado!',
                text: 'O perfil foi ativado com sucesso.',
                confirmButtonText: 'OK'
            });
        } else {
            // Exibe um SweetAlert de confirmação antes de desativar
            Swal.fire({
                icon: 'warning',
                title: 'Tem certeza?',
                text: 'Você está prestes a desativar o perfil. Deseja continuar?',
                showCancelButton: true,
                confirmButtonText: 'Sim, desativar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Se o usuário confirmar, exibe uma mensagem de sucesso
                    Swal.fire({
                        icon: 'success',
                        title: 'Perfil desativado!',
                        text: 'O perfil foi desativado com sucesso.',
                        confirmButtonText: 'OK'
                    });
                } else {
                    // Se o usuário cancelar, mantém o checkbox selecionado
                    this.checked = true;
                }
            });
        }
    });
    </script>
@stop