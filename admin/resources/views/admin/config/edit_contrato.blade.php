@extends('adminlte::page')

@section('title', 'Texto do contrato')

@section('plugins.Summernote', true)

@section('content')
    <br>
        <div class="card card-primary card-outline">
            <div class="card-body">
                <form action="{{ route('config.update',1) }}" method="post">
                    @csrf
                    @method('PUT')
                    <p><strong>Instruções</strong></p>
                    <p>Os campos entre colchetes ("[" e "]") não devem ser editados.</p>
                    <div class="form-group">
                        <label for="texto_contrato">Texto contrato</label>
                        <textarea id="summernote1"  name="texto_contrato">{{$config->texto_contrato}}</textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-primary">Salvar</button>
                        <button type="button" class="btn btn-sm btn-danger" id="btn-excluir">Apagar</button>
                        <a href="{{route('servicos.index')}}" class="btn btn-sm btn-secondary">Voltar</a>
                    </div>
                </form>
            </div>
        </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#summernote1').summernote(
                {
                    toolbar: [
                    ['font', ['bold', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['view', ['fullscreen', 'codeview']],
                    ],                    
                }
            );
        });
    </script>    
        
@stop