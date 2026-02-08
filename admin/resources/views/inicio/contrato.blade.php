@extends('layouts.cp')

@section('content')
<div class="screen-content">
    <div class="box box-contract">
      <div class="box-head box-head-center">
        <h2>
          <small>Clube Privê</small>
          CONTRATO DE ADESÃO
        </h2>
      </div>

      <div class="box-content">
        <div class="box-scroll">
          {!! $contractText !!}
        </div>
      </div>
    </div>
    <div class="box box-contract box-contract-switch">
      <form action="{{ route('inicio.fazerPagamento') }}" method="post">
        @csrf
        <label class="swtich">
          <input type="checkbox" required>
          <span>
              Declaro que li, compreendi e concordo integralmente com todos os termos e condições estabelecidos neste Contrato de Prestação de Serviços. Reconheço que este aceite digital tem o mesmo valor legal de uma assinatura física, conforme previsto na legislação brasileira..
          </span>
        </label>
        <br>
        <button class="btn btn-confirm" type="submit" >confirmar</button>
      </form>
      
    </div>
  </div> 
@stop

@section('steps')
    <div class="steps">
        <div class="steps-item" data-steps-number="1">
        <div class="steps-item-text">
            <small>Faça seu</small>
            Pré-cadastro
        </diva>
        </div>
        <div class="steps-item" data-steps-number="2">
        <diva class="steps-item-text">
            <small>Escolha</small>
            Seu Plano
        </div>
        </div>
        <div class="steps-item active" data-steps-number="3">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Adiciona um evento de change ao checkbox
            $('#flexSwitchCheckDefault').change(function() {
                // Habilita ou desabilita o botão com base no estado do checkbox
                if (this.checked) {
                    $('#submitButton').prop('disabled', false); // Habilita o botão
                } else {
                    $('#submitButton').prop('disabled', true); // Desabilita o botão
                }
            });
        });
    </script>
@stop