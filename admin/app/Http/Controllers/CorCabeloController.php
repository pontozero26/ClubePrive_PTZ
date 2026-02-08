<?php

namespace App\Http\Controllers;

use App\Models\CorCabelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CorCabeloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $itens = CorCabelo::all();

        return view('admin.cabelos.index', compact('itens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cabelos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $item = new CorCabelo();

        $item->descricao = $request->descricao;

        try {
            $item->save();
            Log::info('Cor cabelo criada',[
                'usuario' => auth()->user()->name,
                'editado' => $item->nome,
            ]);
            return redirect()->route('cabelo.index')->with('success', 'Cor cabelo criado com sucesso.');
        }
        catch (\Throwable $th) {
            return redirect()->route('cabelo.index')->with('error', 'Erro ao criar nova cor cabelo. Código erro:'.$th->getMessage());
        }       

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = CorCabelo::find($id);
        return view('admin.cabelos.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $item = CorCabelo::find($id);

        $item->descricao = $request->descricao;

        try {
            $item->save();
            Log::info('Cor cabelo editado',[
                'usuario' => auth()->user()->name,
                'editado' => $item->nome,
            ]);
            return redirect()->route('cabelo.index')->with('success', 'Cor cabelo editada com sucesso.');
        }
        catch (\Throwable $th) {
            return redirect()->route('cabelo.index')->with('error', 'Erro ao editar cor cabelo. \nCódigo erro:'.$th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $item = CorCabelo::find($id);

        try {
            $item->delete();
            Log::info('Cor cabelo excluída',[
                'usuario' => auth()->user()->name,
                'editado' => $item->descricao,
            ]);
            return redirect()->route('cabelo.index')->with('success', 'Cor cabelo excluído com sucesso.');
        }
        catch (\Throwable $th) {
            return redirect()->route('cabelo.index')->with('error', 'Erro ao excluir cor cabelo. \nCódigo erro:'.$th->getMessage());
        }
    }
}
