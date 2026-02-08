<?php

namespace App\Console\Commands;

use App\Models\PlanoAssinante;
use App\Models\Modelo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckExpiredPlans extends Command
{
    protected $signature = 'planos:check-expired';
    protected $description = 'Verifica e inativa usuários com planos expirados';

    public function handle()
    {
        $this->info('Verificando planos expirados...');

        $now = Carbon::now();
        
        // Buscar assinaturas que expiraram mas ainda estão ativas
        $expiredSubscriptions = PlanoAssinante::where('ativo', true)
            ->whereNotNull('expira_em')
            ->where('expira_em', '<', $now)
            ->get();
        
        $count = $expiredSubscriptions->count();
        $this->info("Encontradas {$count} assinaturas expiradas.");
        
        foreach ($expiredSubscriptions as $subscription) {
            // Desativar a assinatura
            $subscription->ativo = false;
            $subscription->save();
            
            // Verificar se o modelo tem planos ativos
            $modelo = Modelo::find($subscription->modelo_id);
            if ($modelo) {
                $hasActivePlan = PlanoAssinante::where('modelo_id', $modelo->id)
                    ->where('ativo', true)
                    ->whereNotNull('expira_em')
                    ->where('expira_em', '>', $now)
                    ->exists();
                
                // Se não tiver planos ativos, torna o usuário invisível
                if (!$hasActivePlan) {
                    $user = User::find($modelo->user_id);
                    if ($user) {
                        $user->visivel = false;
                        $user->save();
                        $this->info("Usuário #{$user->id} ({$user->name}) tornou-se invisível devido à expiração do plano.");
                        
                        Log::info('Usuário tornado invisível por expiração de plano', [
                            'user_id' => $user->id,
                            'modelo_id' => $modelo->id,
                            'plano_id' => $subscription->plano_id,
                            'data_expiracao' => $subscription->expira_em
                        ]);
                    }
                }
                
                $this->info("Plano #{$subscription->plano_id} para o modelo #{$modelo->id} foi marcado como inativo.");
                
                Log::info('Plano expirado', [
                    'modelo_id' => $modelo->id,
                    'plano_id' => $subscription->plano_id,
                    'data_expiracao' => $subscription->expira_em
                ]);
            }
        }
        
        $this->info('Verificação concluída.');
        return 0;
    }
}