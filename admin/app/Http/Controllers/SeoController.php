<?php

namespace App\Http\Controllers;

use App\Models\SEO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SeoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$seo = SEO::where('status',1)->get();
		if (request()->is('area_restrita*')) {
			return view('admin.seo.index', compact('seo'));
		}
        	return response()->json(['seo' => $seo]);
    }

    public function create()
    {
        return view('admin.seo.create');
    }

    public function store(Request $request)
    {
        $seo = new SEO();
        $seo->tipo = $request->input('tipo');
        $seo->script = $request->input('script');
        $seo->status = $request->input('status');
        $seo->nome = $request->input('nome');

        $seo->save();

        return redirect()->route('seo.index')->with('success', 'SEO criado com sucesso!');
    }
    public function edit($id)
    {
        $seo = SEO::find($id);
        return view('admin.seo.edit', compact('seo'));
    }

    public function update(Request $request, $id)
    {
        $seo = SEO::find($id);
        $seo->tipo = $request->input('tipo');
        $seo->script = $request->input('script');
        $seo->status = $request->input('status');
        $seo->nome = $request->input('nome');

        $seo->save();

        return redirect()->route('seo.index')->with('success', 'SEO atualizado com sucesso!');
    }
    public function delete($id)
    {
        $item = SEO::find($id);

        try {
            $item->delete();
            Log::info('Seo apagado ',[
                'usuario' => auth()->user()->name,
                'editado' => $item->nome,
            ]);
            return redirect()->route('seo.index')->with('success', 'SEO deletado com sucesso.');
        } catch (\Throwable $th) {
            return redirect()->route('seo.index')->with('error', 'Erro ao deletar SEO. \nCódigo erro:'.$th->getMessage());
        }
    }
}