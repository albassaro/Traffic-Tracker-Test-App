<?php

namespace App\Providers;

use App\Models\Offer;
use App\Models\RoleList;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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

        Paginator::useBootstrapFour();

        // ПРОВЕРКА ЧТО ПОЛЬЗОВАТЕЛЬ - ADVERTISER
        Gate::define('user-is-advertiser', function(User $user, RoleList $roles)
        {
            return $roles->where('user_id',$user->id)->value('role_id') === 2;
        });
        
        // ПРОВЕРКА ЧТО ПОЛЬЗОВАТЕЛЬ - WEBMASTER
        Gate::define('user-is-webmaster', function(User $user, RoleList $roles)
        {
            return $roles->where('user_id',$user->id)->value('role_id') === 1;
        });


        // ПРОВЕРКА ПРИ УДАЛЕНИИ ОФФЕРА
        Gate::define('delete-update-offer', function(User $user, Offer $offer, $id)
        {
            return $offer->where('id',$id)->value('user_id') === $user->id;
        });

        // ПРОВЕРКА ПРИ ОТПИСКЕ ВЕБ-МАСТЕРА
        Gate::define('unsubscribe-offer', function(User $user, Subscription $subscription, $id)
        {
            // ПОИСК ВСЕХ ПОДПИСОК ПО ID ОФФЕРА И ВЫБОР ИЗ НИХ ТОЛЬКО ID ПОЛЬЗОВАТЕЛЕЙ
           $usersId = $subscription->where('offer_id',$id)->get('user_id');
            // ПЕРЕБОР ЗНАЧЕНИЙ ID  ЧТОБЫ НАЙТИ ЗАПИСЬ, ЕСЛИ НЕСКОЛЬКО ВЕБ-МАСТЕРОВ ПОДПИСАНЫ НА ОДИН ОФФЕР 
           foreach($usersId as $id)
           {
                // ЕСЛИ ХОТЬ 1 ЗАПИСЬ НАЙЛЕНА, РАЗРЕШАЕМ ОТПИСКУ
                if($id->user_id == $user->id)
                {
                    return true;
                }
           }
            return false;
        });
    }
}
