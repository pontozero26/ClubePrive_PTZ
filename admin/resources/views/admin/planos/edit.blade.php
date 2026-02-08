@extends('adminlte::page')

@section('title', 'Editando plano')

@section('plugins.BootstrapSwitch', true)

@section('content')
    <br>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <p>Editando plano de pagamento: {{ $plano->nome }}</p>
            <p>Nem todos os campos podem ser editados.</p>
        </div>
        <div class="card-body">
            <form action="{{ route('planos.update', $plano->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control" readonly value="{{$plano->nome}}">
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <input type="text" name="descricao" id="descricao" class="form-control" readonly value="{{$plano->descricao}}">
                </div>
                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="gratis">Período grátis?</label>
                            <input type="text" name="gratis" id="gratis" class="form-control" value="{{$plano->gratis==1?'Sim':'Nao' }}" readonly>
                        </div>
                    </div>
                    <div class="col-lg-4" id="qtd_dias_gratis_container">
                        <div class="form-group">
                            <label for="qtd_dias_gratis">Quantidade de dias</label>
                            <input type="number" name="qtd_dias_gratis" id="qtd_dias_gratis" class="form-control" value="{{$plano->qtd_dias_gratis }}">
                        </div>
                    </div>
                </div>         
                <div class="row">
                    <div class="col-lg-4">           
                        <div class="form-group" id="valor_container">
                            <label for="valor">Valor</label>
                            <input type="number" min="1" step="any" name="valor" id="valor" class="form-control" value="{{$plano->valor}}" readonly>
                        </div>
                    </div>     
                </div>           
                <div class="form-group">
                    <label for="dias_semana">Dias da semana</label>
                    <div class="form-check">
                        <input type="checkbox" name="dias_semana[]" value="Dom" class="form-check-input" 
                            {{strpos($plano->dias_semana, 'Dom') !== false ? 'checked' : ''}}>
                        <label for="" class="form-check-label">Domingo</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="dias_semana[]" value="Seg" class="form-check-input"
                            {{strpos($plano->dias_semana, 'Seg') !== false ? 'checked' : ''}}>
                        <label for="" class="form-check-label">Segunda</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="dias_semana[]" value="Ter" class="form-check-input"
                            {{strpos($plano->dias_semana, 'Ter') !== false ? 'checked' : ''}}>
                        <label for="" class="form-check-label">Terça</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="dias_semana[]" value="Qua" class="form-check-input"
                            {{strpos($plano->dias_semana, 'Qua') !== false ? 'checked' : ''}}>
                        <label for="" class="form-check-label">Quarta</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="dias_semana[]" value="Qui" class="form-check-input"
                            {{strpos($plano->dias_semana, 'Qui') !== false ? 'checked' : ''}}>
                        <label for="" class="form-check-label">Quinta</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="dias_semana[]" value="Sex" class="form-check-input"
                            {{strpos($plano->dias_semana, 'Sex') !== false ? 'checked' : ''}}>
                        <label for="" class="form-check-label">Sexta</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="dias_semana[]" value="Sab" class="form-check-input"
                            {{strpos($plano->dias_semana, 'Sab') !== false ? 'checked' : ''}}>
                        <label for="" class="form-check-label">Sábado</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="hora_inicio">Hora início</label>
                            <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required value="{{$plano->hora_inicio}}">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="hora_fim">Hora fim</label>
                            <input type="time" name="hora_fim" id="hora_fim" class="form-control" required value="{{$plano->hora_fim}}">   
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="qtd_videos">Quantidade de vídeos permitidos</label>
                            <input type="number" name="qtd_videos" id="qtd_videos" class="form-control" required value="{{$plano->qtd_videos}}">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="qtd_imagens">Quantidade de imagens permitidas</label>
                            <input type="number" name="qtd_imagens" id="qtd_imagens" class="form-control" required value="{{$plano->qtd_imagens}}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-sm btn-primary">Salvar</button>
                    {{-- <button type="button" class="btn btn-sm btn-danger" id="btn-excluir">Apagar</button> --}}
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
    // function toggleGratisFields() {
    //     let gratis = document.getElementById('gratis').value;
    //     let qtdDiasGratisContainer = document.getElementById('qtd_dias_gratis_container');
    //     let valorContainer = document.getElementById('valor_container');

    //     if (gratis == "1") { // Se for 'Sim'
    //         qtdDiasGratisContainer.style.display = 'block';
    //         valorContainer.style.display = 'none';
    //     } else { // Se for 'Não'
    //         qtdDiasGratisContainer.style.display = 'none';
    //         valorContainer.style.display = 'block';
    //     }
    // }

    // document.addEventListener('DOMContentLoaded', function () {
    //     // Verifica o estado inicial ao carregar a página
    //     toggleGratisFields();

    //     // Adiciona o evento de mudança ao select
    //     document.getElementById('gratis').addEventListener('change', toggleGratisFields);
    // });
</script>
@stop