@extends('adminlte::page')

@section('title', 'Minhas Fotos')

@section('content')
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-secondary">
                <div class="card-header">
                    Minhas fotos
                </div>                
                <div class="card-body">
                    <!-- Botões Inserir -->
                    @if ($role != 'admin')
                        @if($mostra_botao)
                            <div class="d-flex mb-3">
                                <button type="button" class="btn btn-primary btn-sm me-2" data-toggle="modal" data-target="#inserirFotoModal">
                                    Inserir Nova Foto
                                </button>
                            </div>                        
                        @else
                            <p>Você atingiu o número de fotos permitido. Caso queira enviar mais, selecione e apague as fotos que não deseja mais.</p>
                        @endif
                    @endif


                    <!-- Modal para Inserir Nova Foto -->
                    <div class="modal fade" id="inserirFotoModal" tabindex="-1" role="dialog" aria-labelledby="inserirFotoModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="inserirFotoModalLabel">Inserir Nova Foto da {{ $modelo->id }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="uploadFotoForm" action="{{ route('fotos.store') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="file">Selecione uma foto:</label>
                                            <input type="file" class="form-control-file" id="file" name="file" accept="image/*" required>
                                            <input type="hidden" name="principal" value="0">
                                            <input type="hidden" name="modelo_id" value="{{ $modelo->id }}">
                                        </div>
                                        <div class="form-group">
                                            <img id="fotoPreview" src="#" alt="Pré-visualização da foto" class="foto-preview">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                    <button type="submit" form="uploadFotoForm" class="btn btn-primary">Enviar Foto</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulário para seleção e exclusão de fotos -->
                    <form action="{{ route('fotos.deleteSelected') }}" method="post">
                        @csrf
                        @method('DELETE')

                        <div class="row">
                            @foreach ($fotos as $item)
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="{{ asset($item->caminho) }}" class="card-img-top" alt="Foto">
                                        @if ($role != 'admin')
                                            <div class="card-body text-center">
                                                <input type="checkbox" name="selected_fotos[]" value="{{ $item->id }}">
                                                <label for="selected_fotos">Selecionar</label>
                                            </div>
                                        @else
                                            @if ($item->principal == 1)
                                                <div class="card-body text-center">
                                                    Principal
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Botão Apagar Selecionadas -->
                        @if ($role != 'admin')
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Apagar Selecionadas
                                </button>
                            </div>                            
                        @endif
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
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('fotoPreview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
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
        .foto-preview {
            display: none;
            width: 50%;
            height: 50%;
            object-fit: cover; /* Mantém a proporção sem distorcer */
            margin-top: 10px;
            border-radius: 8px; /* Borda arredondada opcional */
        }
    </style>    
@stop
