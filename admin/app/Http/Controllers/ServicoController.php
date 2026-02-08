<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ServicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $itens = Servico::all();

        return view('admin.servicos.index', compact('itens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.servicos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $item = new Servico();

        $item->nome = $request->nome;
        $item->slug = Str::slug($request->nome);
        $item->ativo = $request->ativo;

        try {
            $item->save();
            Log::info('Serviço criado pelo usuário: ' . auth()->user()->name,[
                'usuario' => auth()->user()->name,
                'editado' => $item->nome,
            ]);
            return redirect()->route('servicos.index')->with('success', 'Serviço criado com sucesso.');
        }
        catch (\Throwable $th) {
            return redirect()->route('servicos.index')->with('error', 'Erro ao criar novo serviço. Código erro:'.$th->getMessage());
        }       
    }

    /**
     * Display the specified resource.
     */
    public function show(Servico $servico)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = Servico::find($id);
        return view('admin.servicos.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $item = Servico::find($id);

        $item->nome = $request->nome;
        $item->slug = Str::slug($request->nome);
        $item->ativo = $request->ativo;

        try {
            $item->save();
            Log::info('Serviço editado pelo usuário: ' . auth()->user()->name,[
                'usuario' => auth()->user()->name,
                'editado' => $item->nome,
            ]);
            return redirect()->route('servicos.index')->with('success', 'Serviço editado com sucesso.');
        }
        catch (\Throwable $th) {
            return redirect()->route('servicos.index')->with('error', 'Erro ao editar serviço. \nCódigo erro:'.$th->getMessage());
        }     
    }

    public function delete($id)
    {
        $item = Servico::find($id);

        try {
            $item->delete();
            Log::info('Serviço deletado pelo usuário: ' . auth()->user()->name,[
                'usuario' => auth()->user()->name,
                'editado' => $item->nome,
            ]);
            return redirect()->route('servicos.index')->with('success', 'Serviço deletado com sucesso.');
        } catch (\Throwable $th) {
            return redirect()->route('servicos.index')->with('error', 'Erro ao deletar serviço. \nCódigo erro:'.$th->getMessage());
        }
    }
}
