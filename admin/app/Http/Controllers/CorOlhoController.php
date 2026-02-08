<?php

namespace App\Http\Controllers;

use App\Models\CorOlho;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CorOlhoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $itens = CorOlho::all();

        return view('admin.olhos.index', compact('itens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.olhos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $item = new CorOlho();

        $item->descricao = $request->descricao;

        try {
            $item->save();
            Log::info('Cor olho criada',[
                'usuario' => auth()->user()->name,
                'editado' => $item->nome,
            ]);
            return redirect()->route('olhos.index')->with('success', 'Cor olho criado com sucesso.');
        }
        catch (\Throwable $th) {
            return redirect()->route('olhos.index')->with('error', 'Erro ao criar nova cor olho. Código erro:'.$th->getMessage());
        }       

    }

    /**
     * Display the specified resource.
     */
    public function show(CorOlho $corOlho)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = CorOlho::find($id);
        return view('admin.olhos.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $item = CorOlho::find($id);

        $item->descricao = $request->descricao;

        try {
            $item->save();
            Log::info('Cor olho editado',[
                'usuario' => auth()->user()->name,
                'editado' => $item->nome,
            ]);
            return redirect()->route('olhos.index')->with('success', 'Cor olho editado com sucesso.');
        }
        catch (\Throwable $th) {
            return redirect()->route('olhos.index')->with('error', 'Erro ao editar cor olho. \nCódigo erro:'.$th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $item = CorOlho::find($id);

        try {
            $item->delete();
            Log::info('Cor olho excluído',[
                'usuario' => auth()->user()->name,
                'editado' => $item->descricao,
            ]);
            return redirect()->route('olhos.index')->with('success', 'Cor olho excluído com sucesso.');
        }
        catch (\Throwable $th) {
            return redirect()->route('olhos.index')->with('error', 'Erro ao excluir cor olho. \nCódigo erro:'.$th->getMessage());
        }
    }
}
