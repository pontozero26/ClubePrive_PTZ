<?php

namespace App\Observers;

use App\Models\PlanoAssinante;
use App\Models\Modelo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PlanoAssinanteObserver
{
    public function updated(PlanoAssinante $planoAssinante)
    {
        $this->checkExpiration($planoAssinante);
    }
    
    public function created(PlanoAssinante $planoAssinante)
    {
        $this->checkExpiration($planoAssinante);
    }
    
    public function saved(PlanoAssinante $planoAssinante)
    {
        $this->checkExpiration($planoAssinante);
    }
    
    private function checkExpiration(PlanoAssinante $planoAssinante)
    {
        $now = Carbon::now();
        
        // Verificar se o plano expirou
        if ($planoAssinante->expira_em && $planoAssinante->expira_em < $now && $planoAssinante->ativo) {
            // Desativar a assinatura
            $planoAssinante->ativo = false;
            $planoAssinante->saveQuietly(); // Salva sem disparar eventos para evitar loop
            
            Log::info('Plano expirado automaticamente', [
                'plano_assinante_id' => $planoAssinante->id,
                'modelo_id' => $planoAssinante->modelo_id,
                'plano_id' => $planoAssinante->plano_id,
                'data_expiracao' => $planoAssinante->expira_em
            ]);
            
            // Verificar se o modelo tem outros planos ativos
            $modelo = Modelo::find($planoAssinante->modelo_id);
            if ($modelo) {
                $this->updateUserVisibility($modelo);
            }
        }
    }
    
    private function updateUserVisibility($modelo)
    {
        $now = Carbon::now();
        
        $hasActivePlan = PlanoAssinante::where('modelo_id', $modelo->id)
            ->where('ativo', true)
            ->whereNotNull('expira_em')
            ->where('expira_em', '>', $now)
            ->exists();
        
        // Se não tiver planos ativos, torna o usuário invisível
        if (!$hasActivePlan) {
            $user = User::find($modelo->user_id);
            if ($user && $user->visivel) {
                $user->visivel = false;
                $user->save();
                
                Log::info('Usuário tornado invisível por expiração de plano (via observer)', [
                    'user_id' => $user->id,
                    'modelo_id' => $modelo->id
                ]);
            }
        }
    }
}