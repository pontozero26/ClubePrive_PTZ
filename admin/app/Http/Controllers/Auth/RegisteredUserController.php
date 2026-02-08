<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Modelo;  
use Illuminate\Database\QueryException;
Use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Http\Services\DatavalidService;

class RegisteredUserController extends Controller
{

    protected $dataValidService;

    public function __construct(DatavalidService $dataValidService)
    {
        $this->dataValidService = $dataValidService;
    }

    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255','unique:users,username','cpf_ou_cnpj'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
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
            'data_nascimento.before_or_equal' => 'Você deve ter pelo menos 18 anos para se cadastrar.',
            'username.cpf_ou_cnpj' => 'Não é um CPF válido.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.required' => 'A senha é obrigatória.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
        ]);

        $cpfRequest = preg_replace('/[.-]/', '', $request->username);
        $dataNascimento  = Carbon::createFromFormat('d/m/Y', $request->data_nascimento)->format('Y-m-d');

        $ambiente = env('APP_ENV');
        // Consulta o CPF no Data Valid
        
        if ($ambiente == 'production') {
            $dataValidResponse = $this->dataValidService->consultarCPF($cpfRequest, $request->name, $dataNascimento);

            // // Verifica se a consulta foi bem-sucedida
            if (!$dataValidResponse || !isset($dataValidResponse)) {
                return back()->withErrors(['username' => 'CPF não corresponde aos dados informados.'])->withInput();
            }
        }


        try {
            $user = User::create([
                'username' => preg_replace('/[.-]/', '', $request->username),
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            event(new Registered($user));
    
            $modelo = new Modelo();
            $modelo->user_id = $user->id;
            $modelo->data_nascimento = Carbon::createFromFormat('d/m/Y', $request->data_nascimento)->format('Y-m-d');
            $modelo->save();

            Log::info('Novo usuário cadastrado',[
                'usuario' => $request->name,
                'editado' => $user->name,
            ]);            
    
            Auth::login($user);

            return redirect()->route('mostrar_planos');
        } catch (QueryException $e) {
            // Verifica se a exceção é de violação de unicidade
            if ($e->getCode() == 23000) {
                return back()->withErrors(['username' => 'O nome de usuário já existe. Escolha outro.'])->withInput();
            }
    
            // Para outros erros, você pode lançar novamente
            throw $e;
        }
    }
}
