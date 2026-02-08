@extends('adminlte::page')

@section('title', 'Ativar/Desativar Perfil')

@section('content')
<br>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h5 class="m-0">
                Ativar/Desativar perfil
            </h5>
        </div>
        <div class="card-body">
            <p>Seu perfil está <strong>{{ $visivel ? 'ATIVO' : 'INATIVO' }}</strong>.</p>
            
            @if (!$modelo->atendeRequisitos())
                    <p>⚠️ Existem pendências antes de ativar:</p>
                    
                    @if ($modelo->estaCompleto())
                        <br>✅ Campos preenchidos
                    @else
                        <br>⚠️ Preencha todos os campos obrigatórios.
                    @endif
                    
                    @if ($modelo->temFotoPrincipal())
                        <br>✅ Foto principal enviada
                    @else
                        <br>⚠️ Envie uma foto principal.
                    @endif
                    
                    @if ($modelo->temPeloMenosUmaFotoNaoPrincipal())
                        <br>✅ Foto adicional enviada
                    @else
                        <br>⚠️ Envie pelo menos uma foto adicional.
                    @endif
                    
                    @if ($modelo->temPeloMenosUmVideo())
                        <br>✅ Vídeo adicional enviado
                    @else
                        <br>⚠️ Envie pelo menos um vídeo adicional.
                    @endif

                    <br><br>
                    <a href="{{ route('modelo.edit') }}" class="btn btn-primary">Completar perfil</a>
            @else
                @if ($visivel)
                    <p>Você tem certeza que quer fazer isso?</p>
                    <p>Se você desativar o perfil, sua página será desativada e não será mais visível para o público.</p>
                    <p>Seus dados não serão apagados, você pode ativar o perfil novamente a qualquer momento.</p>
                    <p>Se você tem certeza, clique no botão abaixo.</p>
                    <a href="{{route('usuarios.ativar',$id)}}" class="btn btn-danger">Desativar perfil</a>
                @else
                    <p>Se você ativar o perfil, sua página será ativada e será visível para o público.</p>
                    <p>Seus dados não serão apagados, você pode desativar o perfil novamente a qualquer momento.</p>
                    <p>Se você tem certeza, clique no botão abaixo.</p>
                    <a href="{{route('usuarios.ativar',$id)}}" class="btn btn-success">Ativar perfil</a>
                @endif
            @endif
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