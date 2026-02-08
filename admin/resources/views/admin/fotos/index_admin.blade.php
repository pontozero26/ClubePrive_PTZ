@extends('adminlte::page')

@section('title', 'Fotos')

@section('content')
<br>
<div class="container-fluid">
    <!-- Modal para Inserir Nova Foto -->
    <div class="modal fade" id="inserirFotoModal" tabindex="-1" role="dialog" aria-labelledby="inserirFotoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inserirFotoModalLabel">Inserir Nova Foto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="uploadFotoForm" action="{{ route('fotos.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="modelo_id" value="{{ $modelo->id }}">
                        <div class="form-group">
                            <label for="file">Selecione uma foto:</label>
                            <input type="file" class="form-control-file" id="file" name="file" accept="image/*" required>
                            <input type="hidden" name="principal" value="0">
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

    <div class="row">
        <div class="col-md-12">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Lista de Fotos</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" id="btnInserirFoto">
                            <i class="fas fa-plus"></i> Inserir Nova Foto
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-sm" id="table1">
                        <thead>
                            <tr>
                                <th style="text-align: left;">Foto</th>
                                <th>Principal</th>
                                <th width="3%"></th>                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($itens as $item)
                                <tr>
                                    <td style="text-align: left;"><img src="{{ asset($item->thumbnail) }}" style="max-width: 200px; max-height: 150px;" class="img-thumbnail"></td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="principal-toggle" 
                                                data-id="{{ $item->id }}"
                                                data-modelo-id="{{ $modelo->id }}"
                                                {{ $item->principal === 1 ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <a href="#" class="btn-delete" 
                                        data-id="{{ $item->id }}" 
                                        data-modelo-id="{{ $modelo->id }}" 
                                        title="Apagar">
                                            <i class="fas fa-trash text-danger"></i>
                                        </a>
                                    </td>                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
    
    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Limpar cache do navegador
            $.ajaxSetup({
                cache: false
            });
            
            // Configuração do DataTable
            const table = new DataTable('#table1', {
                paging: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json'
                }
            });
            
            // Botão para abrir o modal de inserção
            $('#btnInserirFoto').on('click', function() {
                $('#inserirFotoModal').modal('show');
            });

            // Pré-visualização da foto
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

            // Envio do formulário via AJAX
            $('#uploadFotoForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#inserirFotoModal').modal('hide');
                        Swal.fire({
                            title: 'Enviando...',
                            html: 'Por favor, aguarde enquanto a foto é processada.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        Swal.fire(
                            'Sucesso!',
                            'Foto enviada com sucesso!',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Erro!',
                            xhr.responseJSON?.message || 'Erro ao enviar foto',
                            'error'
                        );
                    }
                });
            });

            // Delete button functionality
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const deleteUrl = "{{ route('fotos.deleteSelected') }}";
                const modeloId = $(this).data('modelo-id'); 
                
                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Esta foto será permanentemente excluída!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sim, apagar!',
                    cancelButtonText: 'Cancelar',
                    backdrop: true,
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'POST',
                            data: {
                                selected_fotos: [id],
                                modelo_id: modeloId,
                                _token: "{{ csrf_token() }}",
                                _method: 'DELETE'
                            },
                            beforeSend: function() {
                                Swal.showLoading();
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Apagado!',
                                    'A foto foi removida com sucesso.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Erro!',
                                    'Ocorreu um erro ao tentar apagar a foto.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Toggle principal functionality
            $('.principal-toggle').on('change', function() {
                const fotoId = $(this).data('id');
                const modeloId = $(this).data('modelo-id');
                const isPrincipal = $(this).prop('checked');

                $.ajax({
                    url: "{{ route('fotos.updatePrincipal') }}",
                    type: 'POST',
                    data: {
                        foto_id: fotoId,
                        modelo_id: modeloId,
                        principal: isPrincipal ? 1 : 0,
                        _token: "{{ csrf_token() }}"
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Atualizando...',
                            html: 'Por favor, aguarde enquanto atualizamos o status da foto.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        Swal.fire(
                            'Sucesso!',
                            'Status da foto atualizado com sucesso!',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Erro!',
                            'Ocorreu um erro ao atualizar o status da foto.',
                            'error'
                        );
                    }
                });
            });

            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @elseif (session('error'))
                toastr.error('{{ session('error') }}');
            @endif
        });
    </script>
@stop

@section('css')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap4.min.css">
    
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin_custom.css') }}?v={{ time() }}">
    
    <style>
        .btn-delete {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }
        .img-thumbnail {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 4px;
            display: block;
        }
        #table1 td:first-child {
            text-align: left;
        }
        #table1 th:first-child {
            text-align: left;
        }
        .btn-delete:hover {
            opacity: 0.8;
            transform: scale(1.1);
            transition: all 0.2s;
        }
        .foto-preview {
            display: none;
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
            margin-top: 10px;
            border-radius: 4px;
        }

        /* Switch styles */
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 22px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            transform: translateX(18px);
        }

        .slider.round {
            border-radius: 22px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@stop