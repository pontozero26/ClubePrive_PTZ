@extends('adminlte::page')

@section('title', 'Envio de vídeos')

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">Envie um vídeo de cada vez</h3>
        </div>
        <div class="card-body">
            <div id="actions" class="row">
                <div class="col-lg-6">
                    <div class="btn-group w-100">
                        <span class="btn btn-success col fileinput-button">
                            <i class="fas fa-plus"></i>
                            <span>Selecionar vídeo</span>
                        </span>
                        {{-- <button type="submit" class="btn btn-primary col start">
                            <i class="fas fa-upload"></i>
                            <span>Iniciar upload</span>
                        </button>
                        <button type="reset" class="btn btn-warning col cancel">
                            <i class="fas fa-times-circle"></i>
                            <span>Cancelar upload</span>
                        </button> --}}
                    </div>
                </div>
                <div class="col-lg-6 d-flex align-items-center">
                    <div class="fileupload-process w-100">
                        <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                            <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table table-striped files" id="previews">
                <div id="template" class="row mt-2">
                    <div class="col-auto">
                        <span class="preview">
                            <video width="120" height="90" controls>
                                <source data-dz-thumbnail />
                                Seu navegador não suporta a tag de vídeo.
                            </video>
                        </span>
                    </div>
                    <div class="col d-flex align-items-center">
                        <p class="mb-0">
                            <span class="lead" data-dz-name></span>
                            (<span data-dz-size></span>)
                        </p>
                        <strong class="error text-danger" data-dz-errormessage></strong>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="progress progress-striped active w-100" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                            <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                        </div>
                    </div>
                    <div class="col-auto d-flex align-items-center">
                        <div class="btn-group">
                            <button class="btn btn-primary start">
                                <i class="fas fa-upload"></i>
                                <span>Enviar</span>
                            </button>
                            <button data-dz-remove class="btn btn-warning cancel">
                                <i class="fas fa-times-circle"></i>
                                <span>Cancelar</span>
                            </button>
                            <button data-dz-remove class="btn btn-danger delete">
                                <i class="fas fa-trash"></i>
                                <span>Excluir</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <p>Os vídeos não devem ultrapassar de 30 segundos</p>
        </div>
        <div id="aguarde-mensagem" style="display: none;">
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Carregando...</span>
                </div>
                <p class="mt-2">Aguarde, você será redirecionado em breve...</p>
            </div>
        </div>        
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin_custom.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}"> --}}
    <!-- Dropzone CSS via CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
    <style>
        #template {
            display: none;
        }
        .dz-preview {
            margin: 10px 0;
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 5px;
        }
        video {
            background: #000;
        }
        #total-progress {
            opacity: 0; /* Oculta a barra de progresso inicialmente */
            transition: opacity 0.3s ease; /* Adiciona uma transição suave */
        }

        #aguarde-mensagem {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }        
    </style>
@stop

@section('js')
    <!-- Dropzone JS via CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
    
    <script>
        // Desativar a descoberta automática do Dropzone
        Dropzone.autoDiscover = false;
    
        // Configuração do Dropzone
        document.addEventListener('DOMContentLoaded', function () {
            // Remover o template do DOM e armazenar seu conteúdo
            const pathParts = window.location.pathname.split('/'); // Divide o caminho em partes
            const principal = pathParts[pathParts.length - 1]; // Pega o último segmento da URL
           
            var previewNode = document.querySelector("#template");
            previewNode.id = "";
            var previewTemplate = previewNode.parentNode.innerHTML;
            previewNode.parentNode.removeChild(previewNode);
    
            // Inicializar o Dropzone
            var myDropzone = new Dropzone(document.body, {
                url: "{{ route('videos.store') }}", // URL para onde os arquivos serão enviados
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
                },
                chunking: true,
                chunkSize: 5 * 1024 * 1024, // 5MB
                retryChunks: true,
                forceChunking: true, 

                init: function() {
                    this.on('sending', function(file, xhr, formData) {

                        formData.append('principal', principal);
                        // Calcular o índice do chunk atual
                        const chunkIndex = Math.floor(file.upload.bytesSent / this.options.chunkSize);
                        const totalChunkCount = Math.ceil(file.size / this.options.chunkSize);


                        // Gerar UUID se não existir
                        if (!file.upload.uuid) {
                            file.upload.uuid = Math.random().toString(36).substring(2) + Date.now().toString(36);
                        }

                        // Enviar informações do chunk
                        formData.append('dzuuid', file.upload.uuid);
                        formData.append('dzchunkindex', chunkIndex);
                        formData.append('dztotalchunkcount', totalChunkCount);
                        formData.append('dzchunksize', this.options.chunkSize);

                    });

                    //this.on('uploadprogress', function(file, progress, bytesSent) {
                    //    console.log('Upload Progress:', progress);
                    //    console.log('Bytes Sent:', bytesSent);
                    //});
                },
                acceptedFiles: 'video/*', // Aceitar apenas vídeos
                thumbnailWidth: 120,
                thumbnailHeight: 90,
                parallelUploads: 1,
                maxFiles: 1,
                previewTemplate: previewTemplate,
                autoQueue: false, // Não enfileirar automaticamente os arquivos
                previewsContainer: "#previews", // Contêiner para as prévias
                clickable: ".fileinput-button" // Elemento clicável para adicionar arquivos
            });
    
            // Configurar o botão de iniciar upload para cada arquivo
            myDropzone.on("addedfile", function(file) {
                // Configurar o elemento de vídeo para exibir o arquivo
                if (file.type.startsWith('video/')) {
                    const video = document.createElement('video'); // Cria um elemento de vídeo
                    video.src = URL.createObjectURL(file); // Define a fonte do vídeo

                    // Evento quando os metadados do vídeo são carregados
                    video.addEventListener('loadedmetadata', function () {
                        const duration = video.duration; // Duração do vídeo em segundos

                        if (duration > 30) {
                            alert('O vídeo deve ter no máximo 30 segundos.');
                            myDropzone.removeFile(file); // Remove o arquivo do Dropzone
                        } else {
                            // Configurar o elemento de vídeo para exibir o arquivo
                            var videoElement = file.previewElement.querySelector("video source");
                            videoElement.src = URL.createObjectURL(file);

                            // Configurar o botão de iniciar upload
                            file.previewElement.querySelector(".start").onclick = function () {
                                myDropzone.enqueueFile(file);
                            };
                        }
                    });
                } else {
                    alert('O arquivo selecionado não é um vídeo.');
                    myDropzone.removeFile(file); // Remove o arquivo do Dropzone
                }
            });
    
            // Atualizar a barra de progresso total
            myDropzone.on("totaluploadprogress", function(progress) {
                document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
            });
    
            // Mostrar a barra de progresso total ao iniciar o upload
            myDropzone.on("sending", function(file) {
                document.querySelector("#total-progress").style.opacity = "1";
                file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
            });
    
            // Esconder a barra de progresso total ao concluir o upload
            myDropzone.on("queuecomplete", function() {
                // Esconder a barra de progresso total
                document.querySelector("#total-progress").style.opacity = "0";

                // Exibir a mensagem de "Aguarde"
                const aguardeMensagem = document.querySelector("#aguarde-mensagem");
                aguardeMensagem.style.display = "flex";

                // Redirecionar para a rota 'videos.index' após 2 segundos
                setTimeout(function() {
                    if (principal == '1') {
                        window.location.href = "{{ route('videoprincipal.index') }}";
                    } else {
                        window.location.href = "{{ route('videos.index') }}";
                    }
                }, 1000); // 2000ms = 2 segundos
            });
    
            // Configurar os botões de iniciar e cancelar upload
            // document.querySelector("#actions .start").onclick = function() {
            //     myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
            // };
            // document.querySelector("#actions .cancel").onclick = function() {
            //     myDropzone.removeAllFiles(true);
            // };
        });
    </script>
@stop