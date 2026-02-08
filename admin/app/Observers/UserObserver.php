<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Modelo;
use App\Models\Midia;
use Illuminate\Support\Facades\Storage;

class UserObserver
{
    public function deleting(User $user)
    {
        // Encontra o modelo associado ao usuário
        $modelo = Modelo::where('user_id', $user->id)->first();
        
        if ($modelo) {
            // Busca todas as mídias do modelo
            $midias = Midia::where('modelo_id', $modelo->id)->get();
            
            foreach ($midias as $midia) {
                // Deleta o arquivo do disco
                if (Storage::exists($midia->caminho)) {
                    Storage::delete($midia->caminho);
                }
                // Deleta o registro da mídia
                $midia->delete();
            }
        }
    }
} 