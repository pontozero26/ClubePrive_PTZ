@extends('adminlte::page')

@section('title', 'Vídeo comparativo')

@section('content')
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-secondary">
                <div class="card-header">
                    Vídeo de comparação
                </div>
                <div class="card-body">
                    <!-- Botões Inserir -->
                    <div class="d-flex mb-3">
                        {{-- <button type="button" class="btn btn-primary btn-sm me-2" data-toggle="modal" data-target="#inserirFotoModal">
                            Inserir novo vídeo
                        </button> --}}
                        <a href="{{ route('videos.enviar',['principal' => 1]) }}" class="btn btn-primary btn-sm me-2"><i class="fas fa-upload"></i>  Enviar video principal</a>                        
                    </div>

                    <!-- Modal para Inserir Nova Foto -->
                    <div class="modal fade" id="inserirFotoModal" tabindex="-1" role="dialog" aria-labelledby="inserirFotoModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="inserirFotoModalLabel">Inserir novo vídeo</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="uploadFotoForm" action="{{ route('videos.store') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="file">Selecione um vídeo:</label>
                                            <input type="file" class="form-control-file" id="file" name="file" accept="video/*" required>
                                            <input type="hidden" name="principal" value="1">
                                        </div>
                                        <div class="form-group">
                                            <video id="videoPreview" class="video-preview" controls>
                                                Seu navegador não suporta a exibição de vídeos.
                                            </video>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                    <button type="submit" form="uploadFotoForm" class="btn btn-primary">Enviar vídeo</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulário para seleção e exclusão de fotos -->
                    <form action="{{ route('videos.deleteSelected') }}" method="post">
                        @csrf
                        @method('DELETE')

                        <div class="row">
                            @foreach ($videos as $item)
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="{{ asset($item->thumbnail) }}" class="card-img-top" alt="Foto">
                                        <div class="card-body text-center">
                                            <input type="checkbox" name="selected_fotos[]" value="{{ $item->id }}">
                                            <label for="selected_fotos">Selecionar</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Botão Apagar Selecionadas -->
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-danger btn-sm">
                                Apagar Selecionado
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
    <script type="text/javascript">
        // Script para pré-visualizar a foto selecionada
        document.getElementById('file').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const video = document.getElementById('videoPreview');
                const objectURL = URL.createObjectURL(file);
                
                video.src = objectURL;  // Define a URL temporária para o vídeo
                video.style.display = 'block';
                video.load();  // Carrega o vídeo
            }
        });

        $(document).ready(function() {
            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @elseif (session('error'))
                toastr.error('{{ session('error') }}');
            @endif
        });
    </script>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style>
        .video-preview {
            display: none;
            width: 50%;
            height: 50%;
            object-fit: cover; /* Mantém a proporção sem distorcer */
            margin-top: 10px;
            border-radius: 8px; /* Borda arredondada opcional */
        }
    </style>
@stop
