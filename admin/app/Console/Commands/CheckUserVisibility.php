<?php

namespace App\Console\Commands;

use App\Models\PlanoAssinante;
use App\Models\Modelo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckUserVisibility extends Command
{
    protected $signature = 'users:check-visibility';
    protected $description = 'Verifica e corrige a visibilidade de usuários com base em planos ativos';

    public function handle()
    {
        $this->info('Verificando visibilidade de usuários...');

        $now = Carbon::now();
        
        // Buscar todos os modelos com usuários visíveis
        $modelos = Modelo::whereHas('user', function ($query) {
            $query->where('visivel', true);
        })->get();
        
        $count = $modelos->count();
        $this->info("Encontrados {$count} modelos com usuários visíveis.");
        
        $updatedCount = 0;
        
        foreach ($modelos as $modelo) {
            // Verificar se o modelo tem planos ativos
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
                    $updatedCount++;
                    
                    $this->info("Usuário #{$user->id} ({$user->name}) tornou-se invisível por não ter planos ativos.");
                    
                    Log::info('Usuário tornado invisível por não ter planos ativos', [
                        'user_id' => $user->id,
                        'modelo_id' => $modelo->id
                    ]);
                }
            }
        }
        
        $this->info("Total de {$updatedCount} usuários atualizados.");
        $this->info('Verificação concluída.');
        return 0;
    }
}