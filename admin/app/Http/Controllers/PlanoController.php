<?php

namespace App\Http\Controllers;

use App\Models\Plano;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\PlanoAssinante;
use App\Models\Modelo;

class PlanoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $user_id = $user->id;

        // Subquery para buscar o id da modelo com user_id = 13
        $modeloId = Modelo::where('user_id', $user_id)->value('id');

        // Subquery para buscar o id do histórico de planos ativo da modelo
        $historicoPlanoId = PlanoAssinante::where('ativo', 1)
            ->where('modelo_id', $modeloId)
            ->value('plano_id');

        // Consulta principal para buscar os planos cujo id seja diferente do retornado pela subquery
        $planos = Plano::where('id', '!=', $historicoPlanoId)
            ->where('ativo', 1)
            ->get();

        if ($user->role == 'admin') {
            return view('admin.planos.index', compact('planos'));
        } else {
            return view('admin.planos.index_planos', compact('planos','user_id'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.planos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'dias_semana' => 'nullable|array',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fim' => 'required|date_format:H:i|after:hora_inicio',
            'qtd_videos' => 'nullable|integer|min:0',
            'qtd_imagens' => 'nullable|integer|min:0',
        ], [
            'nome.required' => 'O campo Nome é obrigatório.',
            'hora_inicio.required' => 'O campo Hora de Início é obrigatório.',
            'hora_fim.required' => 'O campo Hora de Fim é obrigatório.',
            'hora_fim.after' => 'O campo Hora Fim deve ser maior que a Hora de Início.',
            // Adicione mensagens personalizadas para outros campos, se necessário
        ]);

        $plano = new Plano();

        $plano->nome = $request->nome;       
        $plano->descricao = $request->descricao;
        $dias_semana = $request->input('dias_semana', []);
        $plano->dias_semana = implode(',', $dias_semana);
        $plano->slug = Str::slug($request->nome);
        $plano->ativo = 1;
        $plano->hora_inicio = $request->hora_inicio;
        $plano->hora_fim = $request->hora_fim;
        $plano->qtd_videos = $request->qtd_videos;
        $plano->qtd_imagens = $request->qtd_imagens;
        $plano->gratis = $request->gratis;
        $plano->qtd_dias = $request->qtd_dias;
        $plano->valor = ($request->gratis == 0)?$request->valor:0; 

        try {
 
            // if ($request->gratis == 0) {
            // //criação no mercado pago
            //     $mp = new MercadoPagoController();
            //     $response = $mp->createPreapprovalPlan($plano->nome, $plano->valor);

            //     if ($response['http_code'] == 201 && isset($response['response']['id'])) {
            //         $idPlano = $response['response']['id'];
            //         $plano->mp_plan_id = $idPlano;
                    
            //     } else {
            //         echo "Erro ao criar plano: " . json_encode($response);
            //     }
            // }

            $plano->save();
            Log::info('Plano criado',[
                'usuario' => auth()->user()->name,
                'criado' => $plano->nome,
            ]);

            return redirect()->route('planos.index')->with('success', 'Plano criado com sucesso.');
        }
        catch (\Throwable $th) {
            return redirect()->route('planos.index')->with('error', 'Erro ao criar novo plano. Código erro:'.$th->getMessage());
        }        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $plano = Plano::find($id);
        $user = auth()->user();
        $modeloId = Modelo::where('user_id', $user->id)->first()->id;
        return view('admin.planos.show', compact('plano', 'modeloId'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $plano = Plano::find($id);
        return view('admin.planos.edit', compact('plano'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nome' => 'required|string|max:255',
                'descricao' => 'nullable|string',
                'dias_semana' => 'nullable|array',
                'hora_inicio' => 'required',
                'hora_fim' => 'required|after:hora_inicio',
                'qtd_videos' => 'nullable|integer|min:0',
                'qtd_imagens' => 'nullable|integer|min:0',
            ], [
                'nome.required' => 'O campo Nome é obrigatório.',
                'hora_inicio.required' => 'O campo Hora de Início é obrigatório.',
                'hora_fim.required' => 'O campo Hora de Fim é obrigatório.',
                'hora_fim.after' => 'O campo Hora Fim deve ser maior que a Hora de Início.',
                // Adicione mensagens personalizadas para outros campos, se necessário
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors());
        }

        $plano = Plano::find($id);
        
        if (!$plano) {
            return redirect()->route('planos.index')->with('error', 'Plano não encontrado.');
        }   

        $plano->nome = $request->nome;
        $plano->descricao = $request->descricao;
        $dias_semana = $request->input('dias_semana', []);
        $plano->dias_semana = implode(',', $dias_semana);
        $plano->slug = Str::slug($request->nome);
        $plano->hora_inicio = $request->hora_inicio;
        $plano->hora_fim = $request->hora_fim;
        $plano->qtd_videos = $request->qtd_videos;
        $plano->qtd_imagens = $request->qtd_imagens;
        $plano->gratis = $request->gratis;
        $plano->valor = ($request->gratis == 0)?$request->valor:0;     

        try {
            $plano->save();
            Log::info('Plano editado',[
                'usuario' => auth()->user()->name,
                'editado' => $plano->nome,
            ]);
            return redirect()->route('planos.index')->with('success', 'Plano editado com sucesso.');
        }
        catch (\Throwable $th) {
            return redirect()->route('planos.index')->with('error', 'Erro ao editar plano. \nCódigo erro:'.$th->getMessage());
        }        
    }

    public function set_ativo($id, Request $request){
        $plano = Plano::findOrFail($id);
        $plano->ativo = $request->input('ativo', 0); // Define como 0 se não enviado
        $plano->save();
    
        Log::info($plano->ativo ? 'Plano ativado' : 'Plano desativado', [
            'usuario' => auth()->user()->name,
            'editado' => $plano->nome,
        ]);
    
        return response()->json([
            'success' => true,
            'ativo' => $plano->ativo
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $plano = Plano::find($id);

        try {
            $plano->delete();
            Log::info('Plano deletado' . auth()->user()->name,[
                'usuario' => auth()->user()->name,
                'editado' => $plano->nome,
            ]);
            return redirect()->route('planos.index')->with('success', 'Plano deletado com sucesso.');
        } catch (\Throwable $th) {
            return redirect()->route('planos.index')->with('error', 'Erro ao deletar plano. \nCódigo erro:'.$th->getMessage());
        }
    }

    public function planos_disponiveis(){
        $planos = Plano::all();
        return view('planos.disponiveis', compact('planos'));
    }    
}
