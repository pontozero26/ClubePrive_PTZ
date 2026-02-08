@extends('adminlte::page')

@section('title', 'Galeria de Fotos')

@section('content_header')
    <h1>Galeria de Fotos</h1>
@stop


@section('content')
<div class="min-h-screen bg-gray-100 py-12">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg">
        @if(isset($error))
            <div class="bg-red-100 border-l-4 border-red-500 p-4 mb-4">
                <p class="text-red-700">{{ $error }}</p>
            </div>
        @endif

        @if(isset($payment))
            <div class="p-6">
                <div class="flex items-center justify-center mb-8">
                    @if($payment['status'] === 'approved')
                        <div class="bg-green-100 rounded-full p-3">
                            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    @elseif($payment['status'] === 'pending')
                        <div class="bg-yellow-100 rounded-full p-3">
                            <svg class="w-12 h-12 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    @else
                        <div class="bg-red-100 rounded-full p-3">
                            <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <h1 class="text-2xl font-bold text-center mb-6">
                    @if($payment['status'] === 'approved')
                        Pagamento Aprovado!
                    @elseif($payment['status'] === 'pending')
                        Pagamento Pendente
                    @else
                        Pagamento não Aprovado
                    @endif
                </h1>

                <div class="border-t border-gray-200 py-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-sm font-medium text-gray-500">ID do Pagamento</div>
                        <div class="text-sm text-gray-900">{{ $payment['id'] }}</div>

                        <div class="text-sm font-medium text-gray-500">Valor</div>
                        <div class="text-sm text-gray-900">R$ {{ number_format($payment['transaction_amount'], 2, ',', '.') }}</div>

                        <div class="text-sm font-medium text-gray-500">Data</div>
                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($payment['date_created'])->format('d/m/Y H:i') }}</div>

                        <div class="text-sm font-medium text-gray-500">Método de Pagamento</div>
                        <div class="text-sm text-gray-900">{{ $payment['payment_method']['type'] }}</div>

                        <div class="text-sm font-medium text-gray-500">Status</div>
                        <div class="text-sm text-gray-900">
                            @switch($payment['status'])
                                @case('approved')
                                    <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                        Aprovado
                                    </span>
                                    @break
                                @case('pending')
                                    <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">
                                        Pendente
                                    </span>
                                    @break
                                @default
                                    <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                        Não Aprovado
                                    </span>
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="p-6 text-center">
                <h1 class="text-2xl font-bold mb-4">Confirmação de Pagamento</h1>
                <p class="text-gray-600">Aguardando informações do pagamento...</p>
            </div>
        @endif

        <div class="bg-gray-50 px-6 py-4 flex justify-center space-x-4">
            <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Voltar para Home
            </a>
            @if(isset($payment) && $payment['status'] === 'approved')
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Imprimir Comprovante
                </button>
            @endif
        </div>
    </div>
</div>

@if(isset($payment) && $payment['status'] === 'pending')
    <script>
        // Verifica o status do pagamento a cada 30 segundos
        const checkPaymentStatus = () => {
            fetch(`/check-payment-status/{{ $payment['id'] }}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status !== 'pending') {
                        window.location.reload();
                    }
                });
        };

        setInterval(checkPaymentStatus, 30000);
    </script>
@endif
@stop