@extends('adminlte::page')

@section('title', 'Dados de contato')

@section('content')
    <br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Dados de contato</h3>
                    </div>
                    <form id="form1" action={{ route('config.update',$config->id) }} method="post">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="celular">Celular (99-9999-9999)</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-mobile"></i></i></span>
                                        </div>
                                        <input type="tel" pattern="[0-9]{2}-[0-9]{4}-[0-9]{4}" name="celular" id="celular" class="form-control" value="{{$config->celular}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="whatsapp">Whatsapp (99-9999-9999)</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                        </div>
                                        <input type="text" name="whatsapp" id="whatsapp" class="form-control" value="{{$config->whatsapp}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="fone1">Telefone 1 (99-9999-9999)</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="tel" pattern="[0-9]{2}-[0-9]{4}-[0-9]{4}" name="fone1" id="fone1" class="form-control" value="{{$config->fone1}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="facebook">Facebook</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                        </div>
                                        <input type="text" name="facebook" id="facebook" class="form-control" value="{{$config->facebook}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="fone2">Telefone 2 (99-9999-9999)</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="tel" pattern="[0-9]{2}-[0-9]{4}-[0-9]{4}" name="fone2" id="fone2" class="form-control" value="{{$config->fone2}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="instagram">Instagram</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                        </div>
                                        <input type="text" name="instagram" id="instagram" class="form-control" value="{{$config->instagram}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="email">E-mail</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                                        </div>
                                        <input type="email" name="email" id="email" class="form-control" value="{{$config->email}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="twitter">X (Twitter)</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                        </div>
                                        <input type="text" name="twitter" id="twitter" class="form-control" value="{{$config->twitter}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="endereco">Endereço</label>
                                        <textarea name="endereco" id="endereco" cols="10" rows="3" class="form-control">{{$config->endereco}}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="youtube">Youtube</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                        </div>
                                        <input type="text" name="youtube" id="youtube" class="form-control" value="{{$config->youtube}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="maps">Google Maps</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        </div>
                                        <input type="text" name="maps" id="maps" class="form-control" value="{{$config->maps}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="linkedin">Linkedin</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-linkedin"></i></span>
                                        </div>
                                        <input type="text" name="linkedin" id="linkedin" class="form-control" value="{{$config->linkedin}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="form_email_to">Email recebimento dos formulários</label>
                                        <input type="email" name="form_email_to" id="form_email_to" class="form-control" value="{{$config->form_email_to}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="email_port">Email port</label>
                                        <input type="text" name="email_port" class="form-control" value="{{$config->email_port}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="email_username">Email username</label>
                                        <input type="text" name="email_username" class="form-control" value="{{$config->email_username}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="email_password">Email password</label>
                                        <input type="text" name="email_password" class="form-control" value="{{$config->email_password}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="email_host">Email host</label>
                                        <input type="text" name="email_host" class="form-control" value="{{$config->email_host}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="cnpj">CNPJ</label>
                                        <input type="text" name="cnpj" id="cnpj" value="{{$config->cnpj}}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
                    <a href="{{route('config')}}" class="btn btn-secondary btn-sm">Voltar</a>
                </div>
                    </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function () {
            @if (session('success'))
                $(document).Toasts('create', {
                    title: 'Sucesso',
                    class: 'bg-success',
                    autohide: true,
                    delay: 2100,
                    body: 'OperaÃ§Ã£o realizada com sucesso.'
                })
            @endif
        });

        $(function() {
            $('#summernote1').summernote({
                toolbar: [
                    ['font', ['bold', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['view', ['fullscreen', 'codeview']],
                    ],
            })
        });
    </script>
@stop