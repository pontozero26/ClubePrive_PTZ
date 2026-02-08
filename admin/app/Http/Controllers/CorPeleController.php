<?php

namespace App\Http\Controllers;

use App\Models\CorOlho;
use App\Models\CorPele;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CorPeleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $itens = CorPele::all();

        return view('admin.pele.index', compact('itens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pele.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $item = new CorPele();

        $item->descricao = $request->descricao;

        try {
            $item->save();
            Log::info('Cor pele criada',[
                'usuario' => auth()->user()->name,
                'editado' => $item->descricao,
            ]);
            return redirect()->route('pele.index')->with('success', 'Cor pele criado com sucesso.');
        }
        catch (\Throwable $th) {
            return redirect()->route('pele.index')->with('error', 'Erro ao criar nova cor pele. Código erro:'.$th->getMessage());
        }       

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = CorPele::find($id);
        return view('admin.pele.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $item = CorPele::find($id);

        $item->descricao = $request->descricao;

        try {
            $item->save();
            Log::info('Cor pele editado',[
                'usuario' => auth()->user()->name,
                'editado' => $item->descricao,
            ]);
            return redirect()->route('pele.index')->with('success', 'Cor pele editado com sucesso.');
        }
        catch (\Throwable $th) {
            return redirect()->route('pele.index')->with('error', 'Erro ao editar cor pele. \nCódigo erro:'.$th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $item = CorPele::find($id);

        try {
            $item->delete();
            Log::info('Cor pele excluído',[
                'usuario' => auth()->user()->name,
                'editado' => $item->nome,
            ]);
            return redirect()->route('pele.index')->with('success', 'Cor pele excluído com sucesso.');
        }
        catch (\Throwable $th) {
            return redirect()->route('pele.index')->with('error', 'Erro ao excluir cor pele. \nCódigo erro:'.$th->getMessage());
        }
    }
}
