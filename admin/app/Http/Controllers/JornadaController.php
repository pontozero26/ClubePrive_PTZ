<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
Use Illuminate\Support\Facades\Log;
use  App\Models\Plano;
use  App\Models\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules;
use Carbon\Carbon;
use App\Http\Services\DatavalidService; 
use Illuminate\Support\Facades\Cache;
use App\Models\Modelo;
use App\Models\PlanoAssinante;
use App\Models\Precadastro;

class JornadaController extends Controller
{
    protected $dataValidService;

    public function __construct(DatavalidService $dataValidService)
    {
        $this->dataValidService = $dataValidService;
    }

    public function cadastro(){
        return view('inicio.cadastro');
    }


    public function cadastrar(Request $request)
    {
        $cpfRequest = preg_replace('/[.-]/', '', $request->username);
        // Substitui o valor do CPF no request
        $request->merge(['username' => $cpfRequest]);
        $validatedData = $request->validate([
            'username' => ['required', 'string', 'max:255','unique:users,username','cpf_ou_cnpj'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'telefone' => ['required','string','celular_com_ddd'],
            'data_nascimento' => ['required', 'date_format:d/m/Y', function ($attribute, $value, $fail) {
                    try {
                        $dataNascimento = Carbon::createFromFormat('d/m/Y', $value);
                        if ($dataNascimento->greaterThan(now()->subYears(18))) {
                            $fail('Você deve ter pelo menos 18 anos para se cadastrar.');
                        }
                    } catch (\Exception $e) {
                        $fail('A data informada não é válida.');
                    }
                }],            
        ],
        [
            'username.unique' => 'Este CPF já está em uso. Por favor, escolha outro.',
            'email.unique' => 'Este email já está em uso. Por favor, escolha outro.',
            'username.cpf_ou_cnpj' => 'Não é um CPF válido.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.required' => 'A senha é obrigatória.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'telefone'=> 'O telefone é origatório',
            'telefone.celular_com_ddd'=> 'Digite o telefone com ddd',
        ]);

        $dataNascimento  = Carbon::createFromFormat('d/m/Y', $request->data_nascimento)->format('Y-m-d');
        $telefone = $request->telefone;
        $validatedData['telefone'] = $telefone;
        $validatedData['username'] = $cpfRequest;
        $validatedData['data_nascimento'] = $dataNascimento;

        $ambiente = env('APP_ENV');
        // Consulta o CPF no Data Valid
        
        if ($ambiente == 'production') {
            $dataValidResponse = $this->dataValidService->consultarCPF($cpfRequest, $request->name, $dataNascimento);

            // // Verifica se a consulta foi bem-sucedida
            if (!$dataValidResponse || !isset($dataValidResponse)) {
                return back()->withErrors(['username' => 'CPF não corresponde aos dados informados.'])->withInput();
            }
        }

        $precadastro = new Precadastro();
        $precadastro->cpf = $request->username;
        $precadastro->nome = $request->name;
        $precadastro->email = $request->email;
        $precadastro->telefone = $request->telefone;
        $precadastro->data_nascimento = $request->data_nascimento;
        $precadastro->senha = $request->password;

        $precadastro->save();
        
        session()->put('registration_data', $validatedData);

        $planos = Plano::where('ativo', true)->orderBy('gratis', 'desc')->get();

        return view('inicio.escolherPlano', compact('planos'));
    }

    public function gravarPlanoEscolhido(Request $request)
    {   
        $dadosRegistro = session()->get('registration_data', []);
        $dadosRegistro['plano_id'] = $request->plano_id;
        session()->put('registration_data', $dadosRegistro);

        //mostrar o contrato
        $config = Config::first(); // Supondo que há apenas um registro na tabela config
        $contractText = $config->texto_contrato;

        $cpf =$dadosRegistro['username'];
        $hoje = date('d/m/Y');
        $nome = $dadosRegistro['name'];

        // Substitui os placeholders pelos dados do usuário
        $contractText = str_replace(
            ['[NOME]', '[CPF]', '[DATA]'],
            [$nome, $cpf, $hoje],
            $contractText
        );

        return view('inicio.contrato', compact('contractText'));
    }        

    public function associarPlano(Request $request){

        $planoId = $request->input('plano_id');
        $dadosRegistro['plano_id'] = $planoId;
        
        //mostrar o contrato
        $config = Config::first(); // Supondo que há apenas um registro na tabela config
        $contractText = $config->texto_contrato;

        $cpf =$dadosRegistro['username'];
        $hoje = date('d/m/Y');
        $nome = $dadosRegistro['name'];

        // Substitui os placeholders pelos dados do usuário
        $contractText = str_replace(
            ['[NOME]', '[CPF]', '[DATA]'],
            [$nome, $cpf, $hoje],
            $contractText
        );

        $request->session()->put('registration_data', $dadosRegistro);

        return view('inicio.contrato', compact('contractText'));        
    }    

    public function fazerPagamento(Request $request){

        $dadosRegistro = $request->session()->get('registration_data');
        $plano_id = $dadosRegistro['plano_id'];
        $cpf = $dadosRegistro['username'];
        $email = $dadosRegistro['email'];
        $nome = $dadosRegistro['name'];
        $telefone = $dadosRegistro['telefone'];

        $plano = Plano::find($plano_id);

        if($plano->gratis){
            $user = User::create([
                'name' => $dadosRegistro['name'],
                'email' => $dadosRegistro['email'],
                'password' => Hash::make($dadosRegistro['password']),
                'username' => $dadosRegistro['username'],
            ]);

            $user->fez_pagamento = true;
            $user->is_active = true;
            $user->visivel = false;
            $user->escolheu_plano = true;
            $user->aceitou_contrato = true;
            $user->save();

            event(new Registered($user));

            $modelo = new Modelo();
            $modelo->user_id = $user->id;
            $modelo->data_nascimento = $dadosRegistro['data_nascimento'];
            $modelo->telefone = $dadosRegistro['telefone'];
            $modelo->save();

            Log::info('Novo usuário cadastrado',[
                'usuario' => $request->name,
                'editado' => $user->name,
            ]);            
    
            Auth::login($user);

            $plano_assinante = new PlanoAssinante();
            $plano_assinante->modelo_id = $modelo->id;
            $plano_assinante->plano_id = $plano_id;
            $plano_assinante->data_contratacao = date('Y-m-d');
            $plano_assinante->ativo = true;
            $plano_assinante->payment_id = '';
            $plano_assinante->payment_status = '';
            $plano_assinante->payment_method = '';
            $plano_assinante->expira_em = Carbon::now()->addDays($plano->qtd_dias);
    
            $modelo->historico_planos()->save($plano_assinante);
            
            return view('inicio.boasvindas', compact('user', 'modelo', 'plano'));
        }

        //criar pagamento no mercado pago
        $mercadoPagoController = new \App\Http\Controllers\MercadoPagoController();
        $link_pagamento = $mercadoPagoController->createPreference($plano, $cpf, $email, $nome);

        return view('inicio.pagamento', compact('link_pagamento'));
    }    

    public function filtrarModelos($cidade = 'all', $uf = 'all', $genero = 'all')
    {
        $query = Modelo::query();

        if ($cidade !== 'all') {
            $query->where('cidade', 'like', '%' . $cidade . '%');
        }

        if ($uf !== 'all') {
            $query->where('uf', $uf);
        }

        if ($genero !== 'all') {
            $query->where('genero', $genero);
        }

        return $query->inRandomOrder()->get();
    }

    public function busca(Request $request)
    {
        $cidade = $request->input('cidade', 'all');
        $uf = $request->input('uf', 'all');
        $genero = $request->input('genero', 'all');

        $query = Modelo::query();

        if ($cidade !== 'all') {
            $query->where('cidade', 'like', '%' . $cidade . '%');
        }

        if ($uf !== 'all') {
            $query->where('uf', $uf);
        }

        if ($genero !== 'all') {
            $query->where('genero', $genero);
        }

        $modelos = $query->inRandomOrder()->get();

        return view('inicio.busca', compact('modelos'));
    }

    public function registrarPrecadastro(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:precadastros,cpf', 'cpf_ou_cnpj'],
            'data_nascimento' => ['required', 'date_format:d/m/Y'],
            'telefone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:precadastros,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // Formata o CPF removendo pontos e traços
        $cpf = preg_replace('/[.-]/', '', $validatedData['username']);
        
        // Formata a data de nascimento para o formato do banco
        $dataNascimento = Carbon::createFromFormat('d/m/Y', $validatedData['data_nascimento'])->format('Y-m-d');

        // Cria o registro no pré-cadastro
        $precadastro = Precadastro::create([
            'nome' => $validatedData['name'],
            'cpf' => $cpf,
            'data_nascimento' => $dataNascimento,
            'telefone' => $validatedData['telefone'],
            'email' => $validatedData['email'],
            'senha' => Hash::make($validatedData['password']),
            'status' => 'pendente'
        ]);

        return redirect()->route('inicio.escolherPlano')->with('success', 'Pré-cadastro realizado com sucesso!');
    }

}
