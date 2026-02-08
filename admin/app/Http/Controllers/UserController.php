<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
Use Illuminate\Support\Facades\Log;
use  App\Models\Modelo;
use App\Models\CorCabelo;
use App\Models\CorOlho;
use App\Models\CorPele;
use App\Models\Servico;
use App\Models\Midia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\PlanoAssinante;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules;
use Carbon\Carbon;
use App\Http\Services\DatavalidService; 
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index_admins()
    {
        $users = User::where('role', 'admin')->get();
        $tipo = "admin";
        return view('admin.usuarios.index', compact('users', 'tipo'));
    }

    public function index_acompanhantes()
    {
        $users = User::where('role', 'user')->get();
        $tipo = "user";
        return view('admin.usuarios.index', compact('users','tipo'));
    }    

    public function modelos()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.usuarios.index', compact('users'));
    }
    
    public function visitantes()
    {
        $users = User::where('role', 'guest')->get();
        return view('admin.usuarios.index', compact('users'));
    }    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->role = $request->role;

        if (str_contains($request->path(),'registrar')) {
            event(new Registered($user));
            Auth::login($user);

        }  

        try {
            $user->save();
            Log::info('Usuário criado',[
                'usuario' => auth()->user()->name,
                'novo' => $user->name,
            ]);
            return redirect()->route('usuarios_admins.index')->with('success', 'Usuário criado com sucesso.');
        } catch (\Throwable $th) {
            return redirect()->route('usuarios_admins.index')->with('error', 'Erro ao criar usuário.');
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::find($id);
        $role = auth()->user()->role;

        if ($user->role == 'user') {
            $modelo = Modelo::where('user_id', $user->id)->first();
            $pele = CorPele::all();
            $olhos = CorOlho::all();
            $cabelo = CorCabelo::all();
            $servicos = Servico::all();
            $servicosSelecionados = $modelo->servicos->pluck('id')->toArray();        

            return view('modelo.edit', compact('user', 
            'modelo', 
                        'pele', 
                        'olhos', 
                        'cabelo', 
                        'servicos', 
                        'servicosSelecionados',
                        'role'
                    )
                );
        }


        return view('admin.usuarios.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->role = $request->role;

        try {
            $user->save();
            Log::info('Usuário editado',[
                'usuario' => auth()->user()->name,
                'editado' => $user->name,
            ]);
            return redirect()->route('usuarios.index')->with('success', 'Usuário editado com sucesso.');
        }
        catch (\Throwable $th) {
            return redirect()->route('usuarios.index')->with('error', 'Erro ao editar usuário.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $user = User::find($id);

        if($user->role == 'user') {
            $modelo = Modelo::where('user_id', $user->id)->first();
            $midias = Midia::where('modelo_id', $modelo->id)->get();


            foreach ($midias as $midia) {
                $midia_path = public_path($midias->caminho);
                if (file_exists($midia_path)) {
                    unlink($midia_path);
                }
    
                $midia->delete();
            }
            
            $historico_planos = PlanoAssinante::where('modelo_id', $modelo->id)->get();
        }

        try {
            $user->delete();
            Log::info('Usuário deletado' . auth()->user()->name,[
                'usuario' => auth()->user()->name,
                'deletado' => $user->name,
            ]);
            return redirect()->route('usuarios.index')->with('success', 'Usuário deletado com sucesso.');
        }
        catch (\Throwable $th) {
            return redirect()->route('usuarios.index')->with('error', 'Erro ao deletar usuário.');
        }
    }
    

    public function banir($id)
    {
        $user = User::find($id);

        try {
            $user->is_active= !$user->is_active;
            $user->save();

            if ($user->is_active) {
                Log::info('Usuário desbanido',[
                    'usuario' => auth()->user()->name,
                    'desbanido' => $user->name,
                ]);
            }
            else {
                Log::info('Usuário banido',[
                    'usuario' => auth()->user()->name,
                    'banido' => $user->name,
                ]);
            }
            if ($user->role == 'admin') {
                return redirect()->route('usuarios_admins.index')->with('success', $msg);
            }
            return redirect()->route('usuarios_acompanhantes.index')->with('success', $msg);
        }
        catch (\Throwable $th) {
            if ($user->role == 'admin') {
                return redirect()->route('usuarios_admins.index')->with('error', 'Erro ao (Des)banir usuário.');
            }
            return redirect()->route('usuarios_acompanhantes.index')->with('error', 'Erro ao (Des)banir usuário.');
        }
    } 
    
    public function ativar($id)
    {
        $usuarioAutenticado = auth()->user();
        $user = User::find($id);

        try {
            $user->visivel= !$user->visivel;
            $user->save();

            if ($user->visivel) {
                Log::info('Usuário ativado',[
                    'usuario' => auth()->user()->name,
                    'inativado' => $user->name,
                ]);
                $msg = 'Usuário ativado' ;
            }
            else {
                Log::info('Usuário inativado',[
                    'usuario' => auth()->user()->name,
                    'ativado' => $user->name,
                ]);
                $msg = 'Usuário inativado' ;
            }
            if ($usuarioAutenticado->role == 'admin') {
                return redirect()->route('usuarios_admins.index')->with('success', $msg);
            }
            return redirect()->route('dashboard')->with('success', $msg);
        }
        catch (\Throwable $th) {
            if ($user->role == 'admin') {
                return redirect()->route('usuarios_admins.index')->with('error', 'Erro ao (in)ativar usuário.');
            }
            return redirect()->route('usuarios_acompanhantes.index')->with('error', 'Erro ao (in)ativar usuário.');
        }
    } 


    public function destroy($userId)
    {
        $user = User::withTrashed()->find($userId);
        $role = $user->role;
    
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado.'], 404);
        }

        // Verifica se o usuário autenticado está excluindo a si mesmo
        $isSelfDeletion = auth()->check() && auth()->user()->id == $userId;

        if($role == 'user') {
        //procura a modelo relacionada ao usuário
            $modelo = Modelo::where('user_id', $user->id)->first();
        
            if ($modelo) {  
                // Buscar todas as mídias associadas ao usuário
                $midias = Midia::whereHas('modelo', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->get();
            
                foreach ($midias as $midia) {
                    $caminhoArquivo = public_path($midia->caminho);
            
                    // Remover o arquivo físico se existir
                    if (file_exists($caminhoArquivo)) {
                        unlink($caminhoArquivo);
                    }
            
                    // Remover thumbnail (se existir)
                    if (!empty($midia->thumbnail)) {
                        $caminhoThumbnail = public_path($midia->thumbnail);
                        if (file_exists($caminhoThumbnail)) {
                            unlink($caminhoThumbnail);
                        }
                    }
            
                    // Remover o registro da mídia do banco
                    $midia->delete();
                }

                $planos_historico = PlanoAssinante::where('modelo_id', $modelo->id)->get();
                foreach ($planos_historico as $plano) {
                    $plano->delete();
                }

                $modelo->forceDelete();
            }
        }
    
        // Apagar usuário permanentemente
        $user->forceDelete();

        if ($isSelfDeletion) {
            auth()->logout(); // Finaliza a sessão do usuário
            return redirect()->route('login')->with('success', 'Seu perfil foi apagado com sucesso. Sentiremos sua falta!');
        }        

        if ($role == 'admin') {
            return redirect()->route('usuarios_admins.index','modelos')->with('success', 'Usuário e mídias removidos permanentemente.');                
        }
        return redirect()->route('usuarios_acompanhantes.index','modelos')->with('success', 'Usuário e mídias removidos permanentemente.');    
        
    }        
}
