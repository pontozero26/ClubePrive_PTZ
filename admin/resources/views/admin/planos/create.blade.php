@extends('adminlte::page')

@section('title', 'Novo plano')

@section('content')
    <br>
        <div class="card card-primary card-outline">
            <div class="card-header">
                Novo plano de pagamento
            </div>
            <div class="card-body">
                <form action="{{ route('planos.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control" required value="{{old('nome')}}">
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <input type="text" name="descricao" id="descricao" class="form-control" required value="{{old('descricao')}}">
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="gratis">Período grátis?</label>
                            <select name="gratis" id="gratis" class="form-control">
                                <option value="0">Não</option>
                                <option value="1">Sim</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4" id="dias">
                        <div class="form-group">
                            <label for="dias">Quantidade de dias</label>
                            <input type="number" name="dias" id="dias" class="form-control" value="{{old('dias')}}">
                        </div>
                    </div>
                    <div class="col-lg-4">           
                        <div class="form-group" id="valor_container">
                            <label for="valor">Valor</label>
                            <input type="number" min="1" step="any" name="valor" id="valor" class="form-control" value="{{old('valor')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dias_semana">Dias da semana</label>
                        <div class="form-check">
                            <input type="checkbox" name="dias_semana[]" value="Dom" class="form-check-input">
                            <label for="" class="form-check-label">Domingo</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="dias_semana[]" value="Seg" class="form-check-input">
                            <label for="" class="form-check-label">Segunda</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="dias_semana[]" value="Ter" class="form-check-input">
                            <label for="" class="form-check-label">Terça</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="dias_semana[]" value="Qua" class="form-check-input">
                            <label for="" class="form-check-label">Quarta</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="dias_semana[]" value="Qui" class="form-check-input">
                            <label for="" class="form-check-label">Quinta</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="dias_semana[]" value="Sex" class="form-check-input">
                            <label for="" class="form-check-label">Sexta</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="dias_semana[]" value="Sab" class="form-check-input">
                            <label for="" class="form-check-label">Sábado</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="hora_inicio">Hora início</label>
                                <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required value="{{old('hora_inicio')}}">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="hora_fim">Hora fim</label>
                                <input type="time" name="hora_fim" id="hora_fim" class="form-control" required value="{{old('hora_fim')}}">   
                                @if ($errors->has('hora_fim'))
                                    <div class="text-danger">{{ $errors->first('hora_fim') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="qtd_videos">Quantidade de vídeos permitidos</label>
                                <input type="number" name="qtd_videos" id="qtd_videos" class="form-control" required value="{{old('qtd_videos')}}">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="qtd_imagens">Quantidade de imagens permitidas</label>
                                <input type="number" name="qtd_imagens" id="qtd_imagens" class="form-control" required value="{{old('qtd_imagens')}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-primary">Salvar</button>
                        <a href="{{route('planos.index')}}" class="btn btn-sm btn-secondary">Voltar</a>
                    </div>
                </form>
            </div>
        </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var selectGratis = document.getElementById('gratis');
        var qtdDiasGratisContainer = document.getElementById('qtd_dias_gratis_container');
        var valorContainer = document.getElementById('valor_container');

        function toggleFields() {
            if (selectGratis.value == "1") {
                qtdDiasGratisContainer.style.display = "block"; // Mostra qtd_dias_gratis
                valorContainer.style.display = "none"; // Esconde valor
            } else {
                qtdDiasGratisContainer.style.display = "none"; // Esconde qtd_dias_gratis
                valorContainer.style.display = "block"; // Mostra valor
            }
        }

        // Executar ao carregar a página
        toggleFields();

        // Monitorar mudanças no select
        selectGratis.addEventListener('change', toggleFields);
    });
</script>
@stop   