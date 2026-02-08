<?php

namespace App\Http\Middleware;

use App\Models\PlanoAssinante;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckExpiredPlanMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->modelo) {
            $modelo = Auth::user()->modelo;
            $now = Carbon::now();
            
            // Verificar se o modelo tem um plano_id
            if ($modelo->plano_id) {
                // Verificar se há uma assinatura ativa e não expirada
                $hasActivePlan = PlanoAssinante::where('modelo_id', $modelo->id)
                    ->where('plano_id', $modelo->plano_id)
                    ->where('ativo', true)
                    ->whereNotNull('expira_em')
                    ->where('expira_em', '>', $now)
                    ->exists();
                
                if (!$hasActivePlan) {
                    // Desativar o plano do modelo
                    $modelo->plano_id = null;
                    $modelo->save();
                    
                    // Tornar o usuário invisível
                    $modelo->user->visivel = false;
                    $modelo->user->save();
                }
            }
        }
        
        return $next($request);
    }
}