<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AssinaturaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\File; // Importe a classe File

Route::get('/', function () {
    // Caminho absoluto para o arquivo, um nível acima da pasta admin
    $path = base_path('../index.html'); 

    if (File::exists($path)) {
        return File::get($path);
    } else {
        abort(404); // Retorna erro 404 se o arquivo não for encontrado
    }
});

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/'); 
})->name('logout');


// Rota temporária para criar o link simbólico
Route::get('/gerar-link-storage', function () {
    try {
        Artisan::call('storage:link');
        return 'O link simbólico foi criado com sucesso!';
    } catch (\Exception $e) {
        return 'Erro ao criar o link: ' . $e->getMessage();
    }
});


// Route::get('/', function () {
//     return view('index'); // 'index' refere-se ao arquivo index.blade.php
// });

//jornada inicial
//1 - cadastro
Route::get('/registro', [App\Http\Controllers\JornadaController::class, 'cadastro'])->name('inicio.cadastro');
Route::post('/cadastrar', [App\Http\Controllers\JornadaController::class, 'cadastrar'])->name('inicio.cadastrar');

//2 - mostrar planos
Route::get('/escolherPlano', [App\Http\Controllers\JornadaController::class, 'escolherPlano'])->name('inicio.escolherPlano');
Route::post('/gravarPlanoEscolhido', [App\Http\Controllers\JornadaController::class, 'gravarPlanoEscolhido'])->name('inicio.gravarPlanoEscolhido');   

//3 - assinar contrato
Route::post('/fazerPagamento', [App\Http\Controllers\JornadaController::class, 'fazerPagamento'])->name('inicio.fazerPagamento'); 

//4 - pagamento
Route::get('/pagamento', [App\Http\Controllers\JornadaController::class, 'pagamento'])->name('inicio.pagamento');

Route::get('/criar_preferencia', [App\Http\Controllers\MercadoPagoController::class, 'criar_preferencia'])->name('criar_preferencia');
Route::post('/registrar', [App\Http\Controllers\UserController::class, 'store'])->name('usuario.registrar');

Route::get('/pagamento/success', [App\Http\Controllers\MercadoPagoController::class, 'success'])->name('pagamento.success');
Route::get('/pagamento/failure', [App\Http\Controllers\MercadoPagoController::class, 'failure'])->name('pagamento.failure');
Route::get('/pagamento/pending', [App\Http\Controllers\MercadoPagoController::class, 'pending'])->name('pagamento.pending');

Route::get('/novopagamento', [App\Http\Controllers\ViewController::class, 'pagamento'])->name('inicio.novopagamento');




Route::middleware('auth')->prefix('area_restrita')->group(function () {
    // Route::get('/area_restrita/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/area_restrita/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/area_restrita/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //jornada inicial
    Route::get('/escolher_plano', [\App\Http\Controllers\ViewController::class, 'escolher_plano'])->name('inicio.escolher_plano');
    Route::get('/contrato', [\App\Http\Controllers\ConfigController::class, 'showContract'])->name('inicio.contrato');
    Route::get('/pagamento', [App\Http\Controllers\ViewController::class, 'pagamento'])->name('inicio.pagamento');
    Route::get('/boasvindas', [App\Http\Controllers\ViewController::class, 'boasvindas'])->name('inicio.boasvindas');

    Route::get('/dashboard', [\App\Http\Controllers\ViewController::class, 'dashboard'])->name('dashboard');
    //Route::get('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    Route::get('/modelo/{user_id}/instrucoes', [App\Http\Controllers\ModeloController::class, 'instrucoes_acesso'])->name('modelo.instrucoes-acesso');
    
    Route::get('/admins', [App\Http\Controllers\UserController::class, 'index_admins'])->name('usuarios_admins.index');
    Route::get('/acompanhantes', [App\Http\Controllers\UserController::class, 'index_acompanhantes'])->name('usuarios_acompanhantes.index');
    Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('usuarios.create');
    Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('usuarios.store');
    Route::get('/users/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('usuarios.show');
    Route::get('/users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('usuarios.update');
    Route::get('/users/{id}/delete', [App\Http\Controllers\UserController::class, 'delete'])->name('usuarios.delete');
    Route::get('/users/{id}/ativar', [App\Http\Controllers\UserController::class, 'ativar'])->name('usuarios.ativar');
    Route::get('/users/{id}/banir', [App\Http\Controllers\UserController::class, 'banir'])->name('usuarios.banir');
    Route::get('/users/{id}/destroy', [App\Http\Controllers\UserController::class, 'destroy'])->name('usuarios.destroy');

    Route::get('/planos', [App\Http\Controllers\PlanoController::class, 'index'])->name('planos.index');
    Route::get('/planos/create', [App\Http\Controllers\PlanoController::class, 'create'])->name('planos.create');
    Route::post('/planos', [App\Http\Controllers\PlanoController::class, 'store'])->name('planos.store');
    Route::get('/planos/{id}', [App\Http\Controllers\PlanoController::class, 'show'])->name('planos.show');
    Route::get('/planos/{id}/edit', [App\Http\Controllers\PlanoController::class, 'edit'])->name('planos.edit');
    Route::put('/planos/{id}', [App\Http\Controllers\PlanoController::class, 'update'])->name('planos.update');
    Route::delete('/planos/{id}/delete', [App\Http\Controllers\PlanoController::class, 'delete'])->name('planos.delete');
    Route::post('/planos/set_ativo/{id}', [App\Http\Controllers\PlanoController::class, 'set_ativo'])->name('planos.set_ativo');

    Route::get('/logs', [App\Http\Controllers\LogController::class, 'index'])->name('logs.index');
    Route::get('/logs/{id}', [App\Http\Controllers\LogController::class, 'show'])->name('logs.show');

    Route::get('/servicos', [App\Http\Controllers\ServicoController::class, 'index'])->name('servicos.index');
    Route::get('/servicos/create', [App\Http\Controllers\ServicoController::class, 'create'])->name('servicos.create');
    Route::post('/servicos', [App\Http\Controllers\ServicoController::class, 'store'])->name('servicos.store');
    Route::get('/servicos/{id}', [App\Http\Controllers\ServicoController::class, 'show'])->name('servicos.show');
    Route::get('/servicos/{id}/edit', [App\Http\Controllers\ServicoController::class, 'edit'])->name('servicos.edit');
    Route::put('/servicos/{id}', [App\Http\Controllers\ServicoController::class, 'update'])->name('servicos.update');
    Route::delete('/servicos/{id}/delete', [App\Http\Controllers\ServicoController::class, 'delete'])->name('servicos.delete');    

    Route::get('/seo', [App\Http\Controllers\SeoController::class,'index'])->name('seo.index');
    Route::get('/seo/create', [App\Http\Controllers\SeoController::class, 'create'])->name('seo.create');
    Route::post('/seo/store', [App\Http\Controllers\SeoController::class, 'store'])->name('seo.store');
    Route::get('/seo/edit/{id}', [App\Http\Controllers\SeoController::class, 'edit'])->name('seo.edit');
    Route::put('/seo/update/{id}', [App\Http\Controllers\SeoController::class, 'update'])->name('seo.update');
    Route::delete('/seo/{id}/delete', [App\Http\Controllers\SeoController::class, 'delete'])->name('seo.delete');    
    Route::post('/seo/update-exibir', [App\Http\Controllers\SeoController::class, 'updateExibir'])->name('seo.updateExibir');

    Route::get('/config', [App\Http\Controllers\ConfigController::class, 'edit'])->name('config');
    Route::post('/config/store', [App\Http\Controllers\ConfigController::class, 'store'])->name('config.store');
    Route::put('/config/update/{id}', [App\Http\Controllers\ConfigController::class, 'update'])->name('config.update');
    Route::get('/config/edit_contrato', [App\Http\Controllers\ConfigController::class, 'edit_contrato'])->name('config.edit_contrato');

    Route::get('/modelo/delete', [App\Http\Controllers\ModeloController::class, 'apagarPerfil'])->name('modelo.delete');
    Route::get('/modelo/ativar', [App\Http\Controllers\ModeloController::class, 'ativarPerfil'])->name('modelo.ativar');
    Route::get('/modelo/visualizar', [App\Http\Controllers\ModeloController::class, 'visualizarPerfil'])->name('modelo.visualizar');
    Route::get('/modelo/create', [App\Http\Controllers\ModeloController::class, 'create'])->name('modelo.create');
    Route::get('/modelo', [App\Http\Controllers\ModeloController::class, 'index'])->name('modelos.index');
    Route::post('/modelo/store', [App\Http\Controllers\ModeloController::class, 'store'])->name('modelo.store');
    Route::get('/modelo/{id}', [App\Http\Controllers\ModeloController::class, 'show'])->name('modelo.show');
    Route::get('/modelo/', [App\Http\Controllers\ModeloController::class, 'edit'])->name('modelo.edit');
    Route::put('/modelo/{id}', [App\Http\Controllers\ModeloController::class, 'update'])->name('modelo.update');

    Route::get('/olhos/create', [App\Http\Controllers\CorOlhoController::class, 'create'])->name('olhos.create');
    Route::post('/olhos', [App\Http\Controllers\CorOlhoController::class, 'store'])->name('olhos.store');
    Route::get('/olhos/{id}', [App\Http\Controllers\CorOlhoController::class, 'show'])->name('olhos.show');
    Route::get('/olhos/{id}/edit', [App\Http\Controllers\CorOlhoController::class, 'edit'])->name('olhos.edit');
    Route::put('/olhos/{id}', [App\Http\Controllers\CorOlhoController::class, 'update'])->name('olhos.update');
    Route::delete('/olhos/{id}/delete', [App\Http\Controllers\CorOlhoController::class, 'delete'])->name('olhos.delete'); 

    Route::get('/olhos', [App\Http\Controllers\CorOlhoController::class, 'index'])->name('olhos.index');
    Route::get('/olhos/create', [App\Http\Controllers\CorOlhoController::class, 'create'])->name('olhos.create');
    Route::post('/olhos', [App\Http\Controllers\CorOlhoController::class, 'store'])->name('olhos.store');
    Route::get('/olhos/{id}', [App\Http\Controllers\CorOlhoController::class, 'show'])->name('olhos.show');
    Route::get('/olhos/{id}/edit', [App\Http\Controllers\CorOlhoController::class, 'edit'])->name('olhos.edit');
    Route::put('/olhos/{id}', [App\Http\Controllers\CorOlhoController::class, 'update'])->name('olhos.update');
    Route::delete('/olhos/{id}/delete', [App\Http\Controllers\CorOlhoController::class, 'delete'])->name('olhos.delete'); 
    
    Route::get('/pele', [App\Http\Controllers\CorPeleController::class, 'index'])->name('pele.index');
    Route::get('/pele/create', [App\Http\Controllers\CorPeleController::class, 'create'])->name('pele.create');
    Route::post('/pele', [App\Http\Controllers\CorPeleController::class, 'store'])->name('pele.store');
    Route::get('/pele/{id}', [App\Http\Controllers\CorPeleController::class, 'show'])->name('pele.show');
    Route::get('/pele/{id}/edit', [App\Http\Controllers\CorPeleController::class, 'edit'])->name('pele.edit');
    Route::put('/pele/{id}', [App\Http\Controllers\CorPeleController::class, 'update'])->name('pele.update');
    Route::delete('/pele/{id}/delete', [App\Http\Controllers\CorPeleController::class, 'destroy'])->name('pele.delete'); 
    
    Route::get('/cabelo', [App\Http\Controllers\CorCabeloController::class, 'index'])->name('cabelo.index');
    Route::get('/cabelo/create', [App\Http\Controllers\CorCabeloController::class, 'create'])->name('cabelo.create');
    Route::post('/cabelo', [App\Http\Controllers\CorCabeloController::class, 'store'])->name('cabelo.store');
    Route::get('/cabelo/{id}', [App\Http\Controllers\CorCabeloController::class, 'show'])->name('cabelo.show');
    Route::get('/cabelo/{id}/edit', [App\Http\Controllers\CorCabeloController::class, 'edit'])->name('cabelo.edit');
    Route::put('/cabelo/{id}', [App\Http\Controllers\CorCabeloController::class, 'update'])->name('cabelo.update');
    Route::delete('/cabelo/{id}/delete', [App\Http\Controllers\CorCabeloController::class, 'delete'])->name('cabelo.delete');     

    Route::get('/fotos', [App\Http\Controllers\FotoController::class, 'index'])->name('fotos.index');
    Route::get('/fotos/principal', [App\Http\Controllers\FotoController::class, 'principal'])->name('fotos.principal');
    Route::post('/fotos', [App\Http\Controllers\FotoController::class, 'store'])->name('fotos.store');
    Route::get('/fotos/{id}', [App\Http\Controllers\FotoController::class, 'show'])->name('fotos.show');
    Route::delete('/fotos/{id}/delete', [App\Http\Controllers\FotoController::class, 'delete'])->name('fotos.delete');     
    Route::delete('/fotos/deleteSelected', [App\Http\Controllers\FotoController::class, 'deleteSelected'])->name('fotos.deleteSelected');
    Route::get('/fotos_admin/{id}', [App\Http\Controllers\FotoController::class, 'fotos_admin'])->name('fotos_admin.index');
    Route::post('/fotos/updatePrincipal', [App\Http\Controllers\FotoController::class, 'updatePrincipal'])->name('fotos.updatePrincipal');

    Route::get('/videos', [App\Http\Controllers\VideoController::class, 'index'])->name('videos.index');
    Route::get('/videos/enviar/{principal}', [App\Http\Controllers\VideoController::class, 'enviar'])->name('videos.enviar');
    Route::get('/videos/principal', [App\Http\Controllers\VideoController::class, 'principal'])->name('videoprincipal.index');
    Route::post('/videos', [App\Http\Controllers\VideoController::class, 'store'])->name('videos.store');
    Route::get('/videos/{id}', [App\Http\Controllers\VideoController::class, 'show'])->name('videos.show');
    Route::delete('/videos/{id}/delete', [App\Http\Controllers\VideoController::class, 'delete'])->name('videos.delete');     
    Route::delete('/videos/deleteSelected', [App\Http\Controllers\VideoController::class, 'deleteSelected'])->name('videos.deleteSelected');
    Route::get('/videos_admin/{id}', [App\Http\Controllers\VideoController::class, 'videos_admin'])->name('videos_admin.index');


    Route::get('/modelos/{modelo_id}/fotos', [App\Http\Controllers\FotoController::class, 'index'])->name('fotos2.index');
    Route::get('/modelos/{modelo_id}/videos', [App\Http\Controllers\VideoController::class, 'index'])->name('videos2.index');
    
    Route::post('/modelos/{modelo_id}/fotos', [App\Http\Controllers\FotoController::class, 'store2'])->name('fotos2.store');
    Route::delete('/fotos/{id}', [App\Http\Controllers\FotoController::class, 'destroy'])->name('fotos2.destroy');
    Route::post('/fotos/{id}/inativar', [App\Http\Controllers\FotoController::class, 'inativar'])->name('fotos2.inativar');


    Route::get('/planos/{plano}/checkout', [App\Http\Controllers\MercadoPagoController::class, 'checkout'])
        ->name('planos.checkout');
});

require __DIR__.'/auth.php';
