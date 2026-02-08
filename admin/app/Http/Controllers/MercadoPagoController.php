<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Models\Plano;
use App\Models\PlanoAssinante;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

class MercadoPagoController extends Controller
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));
    }

    /**
     * Exibe o formulário de pagamento
     */
    public function checkout($planoId)
    {
        $user = Auth::user();
        $modelo = Modelo::where('user_id', $user->id)->first();
        
        if (!$modelo) {
            return redirect()->route('perfil.edit')->with('error', 'Você precisa completar seu perfil primeiro.');
        }
        
        $plano = Plano::findOrFail($planoId);
        
        // Verificar se o plano está ativo
        if (!$plano->ativo) {
            return redirect()->back()->with('error', 'Este plano não está disponível no momento.');
        }
        
        // Criar preferência de pagamento
        $preferenceUrl = $this->createPreference($plano, $user);
        
        return view('payment.checkout', [
            'plano' => $plano,
            'preferenceUrl' => $preferenceUrl
        ]);
    }

    public function createPreference($plano, $cpf, $email, $nome)
    {
        $access_token = env('MERCADOPAGO_ACCESS_TOKEN');
        $url = 'https://api.mercadopago.com/checkout/preferences';
        
        // Identificador único para o pagamento
        $external_reference = 'plano_' . $plano->id . '_user_' . $cpf . '_' . time();
        $app_url = config('app.url');
        
        $data = [
            "items" => [
                [
                    "id" => "plano-" . $plano->id,
                    "title" => $plano->nome,
                    "description" => substr($plano->descricao ?? '', 0, 255),
                    "quantity" => 1,
                    "currency_id" => "BRL",
                    "unit_price" => (float)$plano->valor
                ]
            ],
            "payer" => [
                "name" => $nome,
                "email" => $email
            ],
            "external_reference" => $external_reference,
            "back_urls" => [
                "success" => $app_url . "/pagamento/success",
                "failure" => $app_url . "/pagamento/failure",
                "pending" => $app_url . "/pagamento/pending"
            ],
            "auto_return" => "approved",
            "notification_url" => $app_url . "/api/webhook/mercadopago",
            "statement_descriptor" => config('app.name'),
            "metadata" => [
                "plano_id" => $plano->id,
                "cpf" => $cpf
            ]
        ];
        
        try {
            // Usando o cliente HTTP do Laravel para fazer a requisição
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ])->post($url, $data);

            // Verificar se a requisição foi bem-sucedida
            if ($response->successful()) {
                $preference = $response->json();
                
                // // Registrar a tentativa de pagamento no log
                // \Illuminate\Support\Facades\Log::info('Preferência de pagamento criada', [
                //     'preference_id' => $preference['id'],
                //     'external_reference' => $external_reference,
                //     'user_id' => $user->id, 
                //     'plano_id' => $plano->id
                // ]);
                
                return $preference['init_point'];
            } else {
                // Registrar o erro
                Log::error('Erro ao criar preferência MP', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                
                throw new \Exception('Erro ao criar preferência: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Exceção ao criar preferência MP', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    /**
     * Manipula o webhook do Mercado Pago
     */
    public function handleWebhook(Request $request)
    {
        Log::info('Webhook recebido do Mercado Pago', $request->all());
        
        $type = $request->input('type');
        $data = $request->input('data');
        
        // Apenas processar notificações de pagamento
        if ($type !== 'payment') {
            return response()->json(['status' => 'ignored']);
        }
        
        $payment_id = $data['id'];
        
        try {
            // Recuperar os detalhes do pagamento
            $payment = $this->getPaymentInfo($payment_id);
            
            if (!$payment) {
                return response()->json(['status' => 'payment_not_found'], 404);
            }
            
            $external_reference = $payment['external_reference'] ?? null;
            
            if (!$external_reference) {
                Log::error('External reference não encontrada no pagamento', ['payment_id' => $payment_id]);
                return response()->json(['status' => 'missing_reference'], 400);
            }
            
            // Extrair IDs da external_reference
            if (preg_match('/plano_(\d+)_user_(\d+)/', $external_reference, $matches)) {
                $plano_id = $matches[1];
                $user_id = $matches[2];
                
                $this->processPaymentStatus($payment, $plano_id, $user_id);
                return response()->json(['status' => 'processed']);
            }
            
            Log::error('Formato de external_reference inválido', ['external_reference' => $external_reference]);
            return response()->json(['status' => 'invalid_reference'], 400);
            
        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook', [
                'payment_id' => $payment_id, 
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Busca informações de um pagamento na API do Mercado Pago
     */
    private function getPaymentInfo($payment_id)
    {
        $access_token = env('MERCADOPAGO_ACCESS_TOKEN');
        $url = "https://api.mercadopago.com/v1/payments/$payment_id";
        
        $response = \Illuminate\Support\Facades\Http::withToken($access_token)
            ->get($url);
        
        if ($response->successful()) {
            return $response->json();
        }
        
        Log::error('Erro ao buscar informações do pagamento', [
            'payment_id' => $payment_id,
            'status' => $response->status(),
            'response' => $response->json()
        ]);
        
        return null;
    }
    
    /**
     * Processa as mudanças de status de pagamento
     */
    private function processPaymentStatus($payment, $plano_id, $user_id)
    {
        $status = $payment['status'] ?? null;
        
        if (!$status) {
            Log::error('Status de pagamento não encontrado', ['payment' => $payment]);
            return;
        }
        
        Log::info('Processando status de pagamento', [
            'status' => $status,
            'plano_id' => $plano_id,
            'user_id' => $user_id
        ]);
        
        $plano = Plano::find($plano_id);
        $modelo = Modelo::where('user_id', $user_id)->first();
        
        if (!$plano || !$modelo) {
            Log::error('Plano ou modelo não encontrado', [
                'plano_id' => $plano_id,
                'user_id' => $user_id
            ]);
            return;
        }
        
        // Processar com base no status do pagamento
        switch ($status) {
            case 'approved':
                // Pagamento aprovado - ativar o plano para o modelo
                $this->activateSubscription($modelo, $plano, $payment);
                break;
                
            case 'pending':
            case 'in_process':
                // Pagamento pendente - registrar, mas não ativar
                $this->registerPendingPayment($modelo, $plano, $payment);
                break;
                
            case 'rejected':
            case 'cancelled':
            case 'refunded':
                // Pagamento rejeitado, cancelado ou estornado
                $this->cancelSubscription($modelo, $plano, $payment);
                break;
        }
    }
    
    /**
     * Ativa a assinatura de um plano para um modelo
     */
    private function activateSubscription($modelo, $plano, $payment)
    {
        // Verificar se já existe um plano ativo
        $existingSubscription = PlanoAssinante::where('modelo_id', $modelo->id)
            ->where('ativo', true)
            ->first();
            
        if ($existingSubscription) {
            // Desativar assinatura anterior
            $existingSubscription->update(['ativo' => false]);
        }
        
        // Criar nova assinatura ativa
        PlanoAssinante::create([
            'modelo_id' => $modelo->id,
            'plano_id' => $plano->id,
            'data_contratacao' => Carbon::now(),
            'ativo' => true,
            'payment_id' => $payment['id'],
            'payment_status' => $payment['status'],
            'payment_method' => $payment['payment_method_id'] ?? null,
            'expira_em' => Carbon::now()->addDays($plano->qtd_dias)
        ]);
        
        // Atualizar o plano atual do modelo
        $modelo->update(['plano_id' => $plano->id]);
        
        Log::info('Assinatura ativada com sucesso', [
            'modelo_id' => $modelo->id,
            'plano_id' => $plano->id,
            'payment_id' => $payment['id']
        ]);
    }
    
    /**
     * Registra um pagamento pendente
     */
    private function registerPendingPayment($modelo, $plano, $payment)
    {
        PlanoAssinante::create([
            'modelo_id' => $modelo->id,
            'plano_id' => $plano->id,
            'data_contratacao' => Carbon::now(),
            'ativo' => false, // Não ativar ainda
            'payment_id' => $payment['id'],
            'payment_status' => $payment['status'],
            'payment_method' => $payment['payment_method_id'] ?? null,
            'expira_em' => null // Será definido quando o pagamento for aprovado
        ]);
    }
    
    /**
     * Cancela uma assinatura
     */
    private function cancelSubscription($modelo, $plano, $payment)
    {
        // Verificar se existe um registro pendente para este pagamento
        $subscription = PlanoAssinante::where('payment_id', $payment['id'])->first();
        
        if ($subscription) {
            $subscription->update([
                'ativo' => false,
                'payment_status' => $payment['status']
            ]);
            
            Log::info('Assinatura cancelada/rejeitada', [
                'modelo_id' => $modelo->id,
                'plano_id' => $plano->id,
                'payment_id' => $payment['id'],
                'status' => $payment['status']
            ]);
        }
    }

    /**
     * Página de sucesso após pagamento
     */
    public function success(Request $request)
    {
        $paymentId = $request->get('payment_id');
        $status = $request->get('status');
        $externalReference = $request->get('external_reference');
        $paymentMethod = $request->get('payment_method_id');

        $cpf = explode('_', $externalReference)[3];
        $planoId = explode('_', $externalReference)[1];

        $user = User::where('username', $cpf)->first();
        $plano = Plano::find($planoId);
        $modelo = Modelo::where('user_id', $user->id)->first();

        $plano_assinante = new PlanoAssinante();
        $plano_assinante->modelo_id = $modelo->id;
        $plano_assinante->plano_id = $planoId;
        $plano_assinante->data_contratacao = date('Y-m-d');
        $plano_assinante->ativo = true;
        $plano_assinante->payment_id = $paymentId;
        $plano_assinante->payment_status = $status;
        $plano_assinante->payment_method = $paymentMethod;
        $plano_assinante->expira_em = Carbon::now()->addDays($plano->qtd_dias);

        $modelo->historico_planos()->save($plano_assinante);
        $user->escolheu_plano = true;
        $user->save();

        return view('inicio.boasvindas', compact('user'));
    }

    /**
     * Página de falha após pagamento
     */
    public function failure(Request $request)
    {
        $externalReference = $request->get('external_reference');
        $cpf = explode('_', $externalReference)[3];
        $planoId = explode('_', $externalReference)[1];

        $dadosRegistro = $request->session()->get('registration_data');
        dd($dadosRegistro);

        $user = User::where('username', $cpf)->first();
        $paymentId = $request->get('payment_id');
        $status = $request->get('status');

        Auth::login($user);
        
        return view('payment.failure', [
            'paymentId' => $paymentId,
            'status' => $status,
            'user' => $user,
            'planoId' => $planoId
        ]);
    }

    /**
     * Página de pagamento pendente
     */
    public function pending(Request $request)
    {
        $paymentId = $request->get('payment_id');
        $status = $request->get('status');
        $externalReference = $request->get('external_reference');
        $cpf = explode('_', $externalReference)[3];
        $planoId = explode('_', $externalReference)[1];
        $user = User::where('username', $cpf)->first();

        $dadosRegistro = $request->session()->get('registration_data');
        dd($dadosRegistro);

        $getPaymentInfo = $this->getPaymentInfo($paymentId);
        $status = $getPaymentInfo['status'];
        
        if($status == 'approved'){

            
            $user->escolheu_plano = true;
            $user->fez_pagamento = true;
            $user->is_active = true;
            $user->visivel = false;
            $user->save();

            $modelo = Modelo::where('user_id', $user->id)->first();
            Auth::login($user);
            return redirect()->route('inicio.boasvindas', ['user' => $user]);
        }
        
        return view('payment.pending', [
            'paymentId' => $paymentId,
            'status' => $status,
            'user' => $user,
            'planoId' => $planoId
        ]);
    }

    public function createUser($plano, $cpf, $email, $nome, $senha)
    {
        $user = User::create([
            'name' => $dadosRegistro['name'],
            'email' => $dadosRegistro['email'],
            'password' => Hash::make($dadosRegistro['password']),
            'username' => $dadosRegistro['username'],
        ]);

        $user->fez_pagamento = true;
        $user->is_active = true;
        $user->visivel = false;
        $user->escolheu_plano = true;
        $user->aceitou_contrato = true;
        $user->save();

        event(new Registered($user));

        $modelo = new Modelo();
        $modelo->user_id = $user->id;
        $modelo->data_nascimento = $dadosRegistro['data_nascimento'];
        $modelo->save();

        Log::info('Novo usuário cadastrado' .  $request->name,[
            'usuario' => $request->name,
            'editado' => $user->name,
        ]);            

        Auth::login($user);

        $plano_assinante = new PlanoAssinante();
        $plano_assinante->modelo_id = $modelo->id;
        $plano_assinante->plano_id = $plano_id;
        $plano_assinante->data_contratacao = date('Y-m-d');
        $plano_assinante->ativo = true;
        $plano_assinante->payment_id = '';
        $plano_assinante->payment_status = '';
        $plano_assinante->payment_method = '';
        $plano_assinante->expira_em = Carbon::now()->addDays($plano->qtd_dias);

        $modelo->historico_planos()->save($plano_assinante);
    }
    
}