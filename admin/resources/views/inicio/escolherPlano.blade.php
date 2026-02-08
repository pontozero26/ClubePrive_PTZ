@extends('layouts.cp')

@section('content')
  <div class="screen-content">
    <div class="plans">
      @foreach ($planos as $plano)
        <div class="box box-plan">
          <div>
            <h2 class="box-plan-title">{{$plano->nome}}</h2>

            <div class="box-plan-price">{{ number_format($plano->valor, 2, ',', '.') }}  </div>

            <div class="box-plan-text">
              <p>{{$plano->descricao}}</p>
            </div>

            <ul class="box-plan-list">
              <li>Até {{$plano->qtd_imagens}} fotos</li>
              <li>
                @if ($plano->qtd_videos > 1)
                  {{$plano->qtd_videos}} vídeos
                @else 
                  {{$plano->qtd_videos}} video
                @endif
              </li>
              <li>Perfil ativo por {{ count(explode(',', $plano->dias_semana)) }} dias da semana</li>
            </ul>
          </div>

          <div class="center">
            <form id="form-{{$plano->id}}" action="{{ route('inicio.gravarPlanoEscolhido') }}" method="post">
              @csrf
              <input type="hidden" name="plano_id" value="{{$plano->id}}">
              <button class="btn btn-confirm" type="button" data-id="{{$plano->id}}">assinar</button>
            </form>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection

@section('steps')
    <div class="steps">
        <div class="steps-item" data-steps-number="1">
        <div class="steps-item-text">
            <small>Faça seu</small>
            Pré-cadastro
        </div>
        </div>
        <div class="steps-item active" data-steps-number="2">
        <div class="steps-item-text">
            <small>Escolha</small>
            Seu Plano
        </div>
        </div>
        <div class="steps-item" data-steps-number="3">
        <div class="steps-item-text">
            <small>Valide seu</small>
            Contrato
        </div>
        </div>
        <div class="steps-item" data-steps-number="4">
        <div class="steps-item-text">
            <small>Realize o</small>
            Pagamento
        </div>
        </div>
        <div class="steps-item" data-steps-number="5">
        <div class="steps-item-text">
            <small>Tudo</small>
            Pronto!
        </div>
        </div>
    </div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".btn-confirm").forEach(button => {
                button.addEventListener("click", function() {
                    let formId = this.getAttribute("data-id");
                    Swal.fire({
                        title: "Confirmar contratação?",
                        text: "Tem certeza que deseja contratar este pacote?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sim, confirmar!",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`form-${formId}`).submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection