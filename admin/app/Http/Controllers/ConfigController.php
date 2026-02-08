<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ConfigController extends Controller
{
    public function edit()
    {
        $config = Config::findOrFail(1);
        return view("admin.config.edit", compact("config"));
    }

    public function update(Request $request, $id)
    {
        $item = Config::findOrFail($id);
        if ( $request->texto_contrato) {
            $item->texto_contrato = $request->texto_contrato;
        }
        else 
        {
            $item->celular = $request->celular;
            $item->whatsapp = $request->whatsapp;
            $item->fone1 = $request->fone1;
            $item->fone2 = $request->fone2;
            $item->facebook = $request->facebook;
            $item->instagram = $request->instagram;
            $item->email = $request->email;
            $item->twitter = $request->twitter;
            $item->endereco = $request->endereco;
            $item->maps = $request->maps;
            $item->youtube = $request->youtube;
            $item->linkedin = $request->linkedin;
            $item->form_email_to = $request->form_email_to;
            $item->email_port = $request->email_port;
            $item->email_username = $request->email_username; // email_username
            $item->email_password = $request->email_password;
            $item->email_host = $request->email_host;
            $item->cnpj = $request->cnpj;
        }
        
        try {
            $item->save();
            Log::info('Dados de configuração editados.',[
                'usuario' => auth()->user()->name,
                'edição' => $item->nome,
            ]);

            if ( $request->texto_contrato) {
                return redirect()->route('config.edit_contrato')->with('success', 'Texto do contrato editado com sucesso.');
            }
            return redirect()->route('config')->with('success', 'Item editado com sucesso.');
        }
        catch (\Throwable $th) {
            return redirect()->route('config')->with('error', 'Erro ao editar item. \nCódigo erro:'.$th->getMessage());
        }     
    }

    public function edit_lgpd()
    {
        $config = Config::findOrFail(1);
        if (request()->is('area_restrita*')) {
            return view("admin.lgpd.edit", compact("config"));
        }
        return response()->json(['config' => $config]);
    }

    public function edit_contrato()
    {
        $config = Config::findOrFail(1);
        return view("admin.config.edit_contrato", compact("config"));
    }

    public function show(){
        $config = Config::findOrFail(1)->makeHidden('texto_contrato');


        return response()->json(['config' => $config]);
    }
}