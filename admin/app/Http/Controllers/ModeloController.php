<?php

namespace App\Http\Controllers;

use App\Http\Resources\ModeloResource;
use App\Http\Services\FotoService;
use App\Models\Modelo;
use Illuminate\Http\Request;
use App\Models\CorPele;
use App\Models\CorOlho;
use App\Models\CorCabelo;
use App\Models\Plano;
use App\Models\Servico;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Str;
use App\Models\Foto;
use App\Models\Config;
use App\Models\Midia;
use App\Models\PlanoAssinante;
use Illuminate\Support\Facades\DB;

class ModeloController extends Controller
{
    protected $fotoService;

    public function __construct(FotoService $fotoService){
        $this->fotoService = $fotoService;
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pele = CorPele::all();
        $olhos = CorOlho::all();
        $cabelo = CorCabelo::all();
        $servicos = Servico::all();

        $usuario = auth()->user()->id;
        return view('modelo.create', compact('pele', 'olhos', 'cabelo', 'servicos'));
    }

    public function index(Request $request){

        $query = Modelo::with(['user', 'midias'])
        ->whereHas('user', function($q) {
            $q->where('is_active', 1)
            ->where('visivel', 1);
        })
        ->inRandomOrder(); // Coloque aqui, fora do whereHas()


        // Selecionar os campos necessários
        $modelos = $query->get([
            'id',
            'nome_fantasia',
            'slug',
            'frase_impacto',
            'valor_hora',
            'possui_local',
            'cidade_atendimento',
            'id as modelo_id',
            'user_id',
            'telefone',
            'data_nascimento',
            'mostra_idade',
            'mostrar_telefone',
        ]);

        // Formatando a resposta
        $modelosFormatados = $modelos->map(function ($modelo) {
            // Acessando relacionamentos
            $imagemPrincipal = $modelo->midias
                ->where('tipo', 'foto')
                ->where('principal', 1)
                ->first();

            $qtdFotos = $modelo->midias
                ->where('tipo', 'foto')
                ->where('principal', 0)
                ->count();

            $qtdVideos = $modelo->midias
                ->where('tipo', 'video')
                ->where('principal', 0)
                ->count();

            $visivel = 1;

            return [
                'id' => $modelo->id,
                'nome' => $modelo->nome_fantasia,
                // 'imagem' => $imagemPrincipal ? url($imagemPrincipal->caminho) : null,
                'imagem' => $imagemPrincipal ? $imagemPrincipal->caminho : null,
                'slug' => $modelo->slug,
                'frase_impacto' => $modelo->frase_impacto,
                'valor_hora' => $modelo->valor_hora,
                'possui_local' => $modelo->possui_local,
                'cidade_atendimento' => $modelo->cidade_atendimento,
                'qtd_fotos' => $qtdFotos,
                'qtd_videos' => $qtdVideos,
                'telefone' => $modelo->telefone,
                'idade' => $modelo->get_idade(),
                'visivel' => $visivel,
                'mostra_idade' => $modelo->mostra_idade,
                'mostrar_telefone' => $modelo->mostrar_telefone,
            ];
        });

        if (str_contains($request->path(), 'api')) {
            return response()->json($modelosFormatados);
        }
        return view('modelo.show', compact('modelo'));
    }

    public function show(Request $request, $slug)
    {
        $modelo = Modelo::where('slug', $slug)->first();

        if(!$modelo) {
            if (str_contains($request->path(), 'api')) {
                return response()->json(['message'=> 'Modelo não encontrada'], 404);    
            }
            return abort(404,'Modelo não encontrada');
        }

        if(str_contains($request->path(), 'api')) {
            $retorno = new ModeloResource($modelo);
            return response()->json($retorno, 200);
        }
        return view('modelo.show', compact('modelo'));
    }

    public function visualizarPerfil()
    {
        $modelo = Modelo::where('user_id', auth()->user()->id)->first();

        return redirect()->away('http://localhost:8000/acompanhante/' . $modelo->slug);
        // return redirect()->away('https://www.clubeprive.com.br/acompanhante/' . $modelo->slug);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id=null)
    {
        $user = auth()->user()->id;
        $modelo = Modelo::where('user_id', $user)->firstOrFail();
        $pele = CorPele::all();
        $olhos = CorOlho::all();
        $cabelo = CorCabelo::all();
        $servicos = Servico::all();
        $servicosSelecionados = $modelo->servicos ? $modelo->servicos->pluck('id')->toArray() : [];
        $role = auth()->user()->role;

        $foto_principal = Midia::where('modelo_id', $modelo->id)
            ->where('tipo', 'foto')->where('principal', 1)->first();

        return view('modelo.edit', 
        compact('modelo','pele', 'olhos', 'cabelo', 'servicos', 'foto_principal', 'servicosSelecionados','role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $modelo = Modelo::find($id);

        $modelo->telefone = $request->input('telefone');
        $modelo->cep = $request->input('cep');
        $modelo->logradouro = $request->input('logradouro');
        $modelo->numero = $request->input('numero');
        $modelo->complemento = $request->input('complemento');
        $modelo->bairro = $request->input('bairro');
        $modelo->cidade = $request->input('cidade');
        $modelo->uf = strtoupper($request->input('uf'));
        $modelo->nome_fantasia = $request->input('nome_fantasia');
        $modelo->genero = $request->input('genero');
        $modelo->genitalia = $request->input('genitalia');
        $modelo->altura = $request->input('altura');
        $modelo->peso = $request->input('peso');
        $modelo->cor_pele_id = $request->input('cor_pele');
        $modelo->cor_cabelo_id = $request->input('cor_cabelo');
        $modelo->cor_olho_id = $request->input('cor_olho');
        $modelo->piercing = $request->input('piercing');
        $modelo->tatuagem = $request->input('tatuagem');
        $modelo->silicone = $request->input('silicone');
        $modelo->quem_atende = $request->input('quem_atende');
        $modelo->atende_grupo = $request->input('atende_grupo');
        $modelo->valor_hora = $request->input('valor_hora');
        $modelo->frase_impacto = $request->input('frase_impacto');
        $modelo->descricao = $request->input('descricao');
        $modelo->slug = Str::slug($request->input('nome_fantasia')) . '-cp' . str_pad($modelo->id, 4, '0', STR_PAD_LEFT);
        $modelo->cidade_atendimento = $request->input('cidade_atendimento');
        $modelo->cidade_atendimento_slug = strtolower(Str::slug($request->input('cidade_atendimento')));
        $modelo->possui_local = $request->input('possui_local');
        $modelo->uf_atendimento =  strtolower($request->input('uf_atendimento'));
        $modelo->mostra_idade = $request->input('mostra_idade');

        $user = User::find($modelo->user_id);
        $user->email = $request->input('email');
        $user->save();


        if($request->hasFile('foto_principal')) {
            $this->fotoService->processarFoto(
                $request->file('foto_principal'),
                $modelo->id,
                true
            );
        }

        if($request->has('servicos')) {
            $modelo->servicos()->sync($request->input('servicos'));
        }

        try {
            $modelo->save();
            Log::info('Cadastro atualizado',[
                'usuario' => auth()->user()->name,
                'editado' => $modelo->nome_fantasia,
            ]);
            return redirect()->route('dashboard')->with('success', 'Cadastro atualizado.');
        }
        catch (\Throwable $th) {
            return redirect()->route('modelo.edit', $id)->with('error', 'Erro ao cadastrar dados. Código erro:'.$th->getMessage());
        }
    }

    

    public function fotos($slug){
        $modelo = Modelo::where('slug', $slug)->first();


        $fotos = $modelo->user()->fotos();

        return response()->json(['fotos' => $fotos]);

    }


    public function instrucoes_acesso ($user_id){
        $user = User::find($user_id);

        $user->fez_pagamento = true;

        if ($user->escolheu_plano && $user->aceitou_contrato) {
            $user->is_active = true;
        }

        $user->save();

        return view('modelo.instrucoes-acesso', compact('user'));
    }

    public function showContract()
    {
        // Recupera o texto do contrato da tabela config
        $config = Config::first(); // Supondo que há apenas um registro na tabela config
        $contractText = $config->texto_contrato;

        // Recupera os dados do usuário
        $user = auth()->user();
        $cpf =$user->username;
        $hoje = date('d/m/Y');

        // Substitui os placeholders pelos dados do usuário
        $contractText = str_replace(
            ['[NOME]', '[CPF]', '[DATA]'],
            [$user->name, $user->cpf, $hoje],
            $contractText
        );

        // Passa o texto do contrato para a view
        return view('inicio.contrato', compact('contractText'));
    }    

    public function apagarPerfil()
    {
        $user = auth()->user();
        $id = $user->id;
        return view('modelo.apagar_perfil', compact('id'));
    }   

    public function ativarPerfil()
    {
        $user = auth()->user();
        $id = $user->id;

        $user = User::find($id);
        $modelo = Modelo::where('user_id', $id)->first();
        
        if (!$modelo) {
            return redirect()->route('dashboard')->with('error', 'Perfil não encontrado.');
        }

        $visivel = $user->visivel;
        return view('modelo.ativar', compact('id', 'visivel', 'modelo'));
    }   

    public function card(Request $request){

        $modelos = Modelo::join('users', 'modelos.user_id', '=', 'users.id')
            ->where('users.is_active', 1)
            ->where('users.visivel', 1)
            ->select('modelos.*')
            ->get()
            ->filter(fn($modelo) => $modelo->atendeRequisitos());

        if (str_contains($request->path(), 'api')) {
            return response()->json(ModeloResource::collection($modelos), 200);
        }
        return view('modelo.show', compact('modelo'));
    }    


    public function busca(Request $request, $cidade_slug = 'all', $uf = 'all', $genero = 'all')
    {
        // Consulta principal com Eloquent
        $query = Modelo::with([
            'user',
            'midias' => function ($q) {
                $q->where('tipo', 'foto'); // Já filtra fotos no banco
            }
        ])->whereHas('user', function ($q) {
            $q->where('is_active', 1)
              ->where('visivel', 1);
        });
    
        // Aplicar filtros opcionais
        foreach (['cidade_atendimento_slug' => $cidade_slug, 'uf_atendimento' => $uf, 'genero' => $genero] as $campo => $valor) {
            if ($valor !== 'all') {
                $query->where($campo, $valor);
            }
        }
    
        // Selecionar os campos necessários
        $modelos = $query->select([
            'id',
            'nome_fantasia',
            'slug',
            'frase_impacto',
            'valor_hora',
            'possui_local',
            'cidade_atendimento',
            'id as modelo_id',
            'user_id',
            'telefone',
            'data_nascimento',
            'mostra_idade',
            'mostrar_telefone',
        ])->inRandomOrder()->get();
    
        // Formatando a resposta
        $modelosFormatados = $modelos->map(function ($modelo) {
            $imagemPrincipal = $modelo->midias->firstWhere('principal', 1);
            $qtdFotos = $modelo->midias->where('principal', 0)->count();
            $qtdVideos = $modelo->midias->where('tipo', 'video')->count();
    
            return [
                'id' => $modelo->id,
                'nome' => $modelo->nome_fantasia,
                'imagem' => $imagemPrincipal ? url($imagemPrincipal->caminho) : null,
                'slug' => $modelo->slug,
                'frase_impacto' => $modelo->frase_impacto,
                'valor_hora' => $modelo->valor_hora,
                'possui_local' => $modelo->possui_local,
                'cidade_atendimento' => $modelo->cidade_atendimento,
                'qtd_fotos' => $qtdFotos,
                'qtd_videos' => $qtdVideos,
                'telefone' => $modelo->telefone,
                'idade' => $modelo->get_idade(),
                'visivel' => 1,
                'mostra_idade' => $modelo->mostra_idade,
                'mostrar_telefone' => $modelo->mostrar_telefone,
            ];
        });
    
        return response()->json([
            'cidade' => $modelos->first()?->cidade_atendimento,
            'uf_atendimento' => $uf,
            'genero' => $genero,
            'resultados' => $modelosFormatados
        ]);
    }

  
}
