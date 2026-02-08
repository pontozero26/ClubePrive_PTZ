@extends('adminlte::page')

@section('title', 'Minhas Fotos')

@section('content')
<br>
    <div class="container-fluid">
        <h3>Upload de fotos</h3>
        <ul>
            <li>Somente fotos no formato vertical do seu celular</li>
        </ul>
        <form action="{{ route('fotos.store') }}" method="post" enctype="multipart/form-data" class="dropzone" id="my-dropzone">
            @csrf
            <input type="hidden" name="principal" value="0">
            <div class="dz-message">
                Arraste as fotos aqui ou clique para fazer upload
            </div>
            <!-- Pré-visualizações das fotos já enviadas -->
            <div class="dropzone-previews">
                @foreach($itens as $foto)
                    <div class="dz-preview dz-processing dz-image-preview dz-success dz-complete">
                        <div class="dz-image">
                            <img src="{{ asset($foto->caminho) }}" alt="{{ $foto->nome_arquivo }}">
                        </div>
                        <div class="dz-details">
                            <div class="dz-size"><span>{{ round(filesize(public_path($foto->caminho)) / 1024, 2) }} KB</span></div>
                            <div class="dz-filename"><span>{{ $foto->nome_arquivo }}</span></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </form>
    </div>
@stop

@section('js')
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;
        const myDropzone = new Dropzone("#my-dropzone", {
            maxFilesize: 5, // 5MB
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            dictDefaultMessage: "Arraste as fotos aqui ou clique para fazer upload",
            dictRemoveFile: "Remover arquivo",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(file, response) {
                // Adiciona o caminho da foto ao input hidden (opcional)
                file.previewElement.classList.add("dz-success");
                console.log(response);
            },
            error: function(file, response) {
                file.previewElement.classList.add("dz-error");
                alert(response);
            },
            init: function() {
                // Adiciona as fotos já enviadas ao Dropzone
                @foreach ($itens as $foto)
                    this.emit("addedfile", {
                        name: "{{ $foto->nome_arquivo }}",
                        size: 100, // Tamanho fictício para o preview
                        previewElement: '<img src="{{ asset($foto->caminho) }}" />'
                    });
                    this.emit("thumbnail", {
                        name: "{{ $foto->nome_arquivo }}",
                        size: 100, // Tamanho fictício para o preview
                        previewElement: '<img src="{{ asset($foto->caminho) }}" />'
                    });
                @endforeach
            },
            thumbnailWidth: 150, // Largura do thumbnail
            thumbnailHeight: 266, // Altura do thumbnail
        });
    </script>
@stop

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <style>
        .dz-preview .dz-image img {
            width: 100% !important;
            height: auto !important;
            object-fit: contain;  /* Isso vai garantir que a imagem se ajuste sem cortar */
        }

        .dz-preview .dz-image {
            width: 150px !important;    /* Ajuste de largura do preview */
            height: 266px !important;   /* Ajuste de altura para manter a proporção 9:16 */
        }
    </style>
@stop
