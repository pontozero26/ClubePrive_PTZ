<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Modelo;
use App\Models\PlanoAssinante;
use App\Models\Servico;
use App\Models\Plano;
use App\Models\User;
use App\Models\Midia;
use App\Models\Config;


class ViewController extends Controller
{
    public function dashboard(){
        $user = auth()->user();

        if ($user->role == 'admin') {
            $modelos = User::where('role', 'user')
                ->where('is_active', true)
                ->count();

            $servicos = Servico::where('ativo', true)
                ->count();

            $planos = Plano::where('ativo', true)
                ->count();

            return view('admin.dashboard', compact('modelos', 'servicos', 'planos'));
        }
        if ($user->role == 'user') {

            // if(!$user->escolheu_plano){
            //     $planos = Plano::where('ativo', true)->get();

            //     return view('inicio.escolher_plano', compact('planos', 'user'));
            // }

            $modelo = Modelo::where('user_id', $user->id)->first();

            $fotos_enviadas = Midia::where('modelo_id', $modelo->id)->where('tipo', 'foto')->count();
            $videos_enviados = Midia::where('modelo_id', $modelo->id)->where('tipo', 'video')->count();

            $historicoPlano = PlanoAssinante::where('modelo_id', $modelo->id)
            ->where('ativo', true)
            ->first();
            
            if ($historicoPlano)
                $plano = Plano::find($historicoPlano->plano_id);
            else
                $plano = null;

            $video = Midia::where('modelo_id', $modelo->id)
                ->where('tipo', '=', 'video')
                ->where('principal', '=', 1)
                ->first();

            $video_principal = Midia::where('modelo_id', $modelo->id)
                ->where('tipo', '=', 'video')
                ->where('principal', '=', 1)
                ->first();

            $atendeRequisitos = $modelo->atendeRequisitos();                
            
            return view('modelo.perfil', compact('video',
            'plano', 
                        'historicoPlano',
                        'modelo', 
                        'fotos_enviadas', 
                        'videos_enviados',
                        'video_principal',
                        'atendeRequisitos',
                        'user'
                    ));
        }
    }

    public function escolher_plano(){
        $user = auth()->user();
        $planos = Plano::where('ativo', true)->get();
        return view('inicio.escolher_plano', compact('planos', 'user'));
    }

    public function contrato(){
        $user = auth()->user();
        $config = Config::first();
        $texto_contrato = $config->texto_contrato;
        return view('inicio.contrato', compact('user', 'texto_contrato'));
    }

    public function boasvindas(){
        $user = auth()->user();
        return view('inicio.boasvindas', compact('user'));
    }

    public function pagamento(Request $request){

        $planoId = $request->plano_id;
        $user = $request->user_id;

        $user = User::find($user);  

        $modelo = Modelo::where('user_id', $user->id)->first();
        $plano_modelo = $request->planoId;
        $plano = Plano::find($planoId );

        $mercadoPagoController = new \App\Http\Controllers\MercadoPagoController();
        $link_pagamento = $mercadoPagoController->createPreference($plano, $user);

        return view('inicio.pagamento', compact('user', 'modelo', 'plano', 'link_pagamento'));        
    }

    // public function teste(){
    //     return view('testes.teste1');
    // }
}
