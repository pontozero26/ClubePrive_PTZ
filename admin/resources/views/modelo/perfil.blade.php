@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
<br>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h5 class="m-0">
                Minha área de trabalho
            </h5>
        </div>
        <div class="card-body">
            @empty($plano) 
                <p>Sem plano de assinatura.</p>
            @else
                <p>Plano ativo: <strong>{{$plano->nome}}</strong> <br>Início: <strong>{{\Carbon\Carbon::parse($historicoPlano->data_contratacao)->format('d/m/Y') }}</strong>
                 - Fim: <strong>{{\Carbon\Carbon::parse($historicoPlano->expira_em)->format('d/m/Y') }}</strong></p>
                <p>Quantidade de fotos permitidas pelo plano: {{$plano->qtd_imagens}}<br>Fotos enviadas: {{$fotos_enviadas}}</p>
                <p>Quantidade de videos permitidos pelo plano: {{$plano->qtd_videos}}<br>Videos enviados: {{$videos_enviados}}</p>
                @empty ($video)
                @else
                <p>Data do envio do vídeo comparativo: {{ $video_principal->created_at->format('d/m/Y') }} - 
                    Faltam {{ \Carbon\Carbon::now()->diffInDays($video_principal->created_at->addDays(30), false) }} dias para renovar o vídeo</p>
                @endempty
                @if ($atendeRequisitos)
                    @if ($user->visivel)
                        ✅ Seu perfil pode ser visualizado no site.
                        <br>
                        <br>
                        <p>
                        <a href="{{ route('modelo.visualizar') }}" class="btn btn-primary">Visualizar perfil</a>
                        </p>
                    @else
                        ⚠️ Seu perfil <strong>não está visível no site</strong>. Ative-o para que seja exibido.
                    @endif
                @else
                    ⚠️ <strong>Seu perfil não está visível no site</strong>. <br>Pendências:
                
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
                
                    @if ($modelo->temVideoPrincipal())
                    <br>✅ Vídeo principal enviado
                    @else
                    <br>⚠️ Envie um vídeo principal.
                    @endif
                
                    @if ($modelo->temPeloMenosUmVideo())
                    <br>✅ Vídeo adicional enviado
                    @else
                    <br>⚠️ Envie pelo menos um vídeo adicional.
                    @endif
                @endif               
            @endempty
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
                text: 'Seu perfil não será mais exibido no site. Seus dados não serão apagados. Deseja continuar?',
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