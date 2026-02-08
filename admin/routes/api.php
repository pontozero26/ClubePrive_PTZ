<?php

use App\Http\Controllers\AssinaturaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SubscriptionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/login', [LoginController::class, 'login']);

Route::get('/acompanhante/{slug}', [\App\Http\Controllers\ModeloController::class, 'show'])->name('acompanhante.show');
Route::get('/acompanhante/{slug}/fotos', [\App\Http\Controllers\ModeloController::class, 'fotos'])->name('acompanhante.fotos');
Route::get('/acompanhantes', [\App\Http\Controllers\ModeloController::class, 'index'])->name('acompanhante.index');
Route::get('/cidades/{uf}', [\App\Http\Controllers\CidadeController::class, 'cidadesPorUf']);
Route::get('acompanhantes/{cidade}/{uf}/{genero}', [\App\Http\Controllers\ModeloController::class, 'busca'])->name('acompanhantes.busca');

Route::get('/config', [\App\Http\Controllers\ConfigController::class, 'show'])->name('config.show');

Route::post('/create-preference', [App\Http\Controllers\MercadoPagoController::class, 'createPreference'])->name('create.preference');

Route::post('/webhook/mercadopago', [App\Http\Controllers\MercadoPagoController::class, 'handleWebhook'])->name('api.webhook.mercadopago');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});