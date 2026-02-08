<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Support\Facades\Event;

use App\Models\PlanoAssinante;
use App\Observers\PlanoAssinanteObserver;
use App\Observers\UserObserver;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {

            $userId = auth()->id(); // Obtém o ID do usuário logado

            // $slug = auth()->user()->modelo->slug;
    
            // $event->menu->addAfter('meus_videos',[
            //     'text' => 'Ver meu perfil',
            //     'icon' => 'fas fa-fw fa-eye',
            //     'can' => 'user-access',
            //     'url' => '/acompanhante/'.$slug,
            // ]);

            PlanoAssinante::observe(PlanoAssinanteObserver::class);
            User::observe(UserObserver::class);
        });      
        // $userCount = \App\Models\User::count();
        // $planos = \App\Models\PlanoPagamento::count();
        // $generos = \App\Models\Genero::count();
        // $servicos = \App\Models\Servico::count();

        // // Carregando o menu
        // $menu = Config::get('adminlte.menu');
        // // Percorrendo os itens do menu e alterando os valores de 'label' conforme o necessário
        // foreach ($menu as $key => $item) {
        //     // Verificando se o item tem a chave 'label' e alterando os valores conforme o nome do item
        //     if (isset($item['text'])) {
        //         if ($item['text'] == 'Usuários') {
        //             $menu[$key]['label'] = $userCount;
        //         } elseif ($item['text'] == 'Planos') {
        //             $menu[$key]['label'] = $planos;
        //         } elseif ($item['text'] == 'Serviços') {
        //             $menu[$key]['label'] = $servicos;
        //         } elseif ($item['text'] == 'Generos') {
        //             $menu[$key]['label'] = $generos;
        //         }
        //     }
        // }

        // // Salvando as alterações no menu
        // Config::set('adminlte.menu', $menu);
    }
}
