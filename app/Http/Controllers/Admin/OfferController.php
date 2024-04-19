<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{                                                   // =============== ОТРИСОВКА СТРАНИЦ =================
    // СТРАНИЦА СО СТАТИСТИКОЙ ПО ВЫБРАННОМУ ОФФЕРУ 
    public function showOfferList()
    {   
        // ВЫБОР ПОЛЬЗОВАТЕЛЕЙ С РОЛЬЮ "РЕКЛАМОДАТЕЛЬ" ДЛЯ ВЫВОДА СТАТИСТИКИ ПО ОФФЕРУ
        $users = DB::table('roles')->join('role_lists', 'role_lists.role_id', '=', 'roles.id')
        ->join('users','users.id', '=', 'role_lists.user_id')->where('name', 'advertiser')->get();

        return view('admin.offers.offer_list',['users'=>$users]);
    }
    // СТРАНИЦА С ОБЩЕЙ СТАТИСТИКОЙ
    public function showStatistic()
    {   
        // КОЛ-ВО ВЫДАННЫХ ССЫЛОК
        $urlCount = DB::table('subscriptions')->pluck('url_redirector')->count();
        // КОЛ-ВО ПЕРЕХОДОВ ПО ВСЕМ ССЫЛКАМ
        $transitionsCount = DB::table('url_transition_lists')->count();
        // КОЛ-ВО ОШИБОК, КОГДА ПЕРЕХОДИЛИ ПО ССЫЛКЕ НА КОТОРУЮ НЕ ПОДПИСАН ВЕБ-МАСТЕР
        $rejectedTransitionsCount = DB::table('rejected_transitions')->count();

        return view('admin.offers.statistics',['urlCount'=>$urlCount,'transitionsCount'=>$transitionsCount,'rejectedTransitionsCount'=>$rejectedTransitionsCount]);
    }


                                                    // =============== AJAX ЗАПРОСЫ =================

    //  ВЫВОД ВСЕХ ОФФЕРОВ, СОЗДАННЫХ ВЫБРАННЫМ РЕКЛАМОДАТЕЛЕМ
    public function getUserData(Request $request)
    {   
        $offers = DB::table('offers')->where('user_id',$request->id)->get();

        return response()->json([$offers]);
    }

    // ВЫВОД СТАТИСТИКИ ПО ВЫБРАННОМУ ОФФЕРУ
    public function getOfferDetails (Request $request)
    {   
        $validated = $request->validate([
            'user_id'=>['required','gt:0'],
            'offer_id' =>['required','gt:0'],
            'startDate' => ['required'],
            'endDate'=> ['required'],
        ]);

        // ОСНОВНА ИНФ-ЦИЯ ОБ ОФФЕРЕ
        $offer = DB::table('offers')->where('id',$validated['offer_id'])->where('user_id',$validated['user_id'])->get();
        // КОЛИЧЕСТВО ПОДПИСЧИКОВ НА ОФФЕР
        $subscriptionCount = DB::table('subscriptions')->where('offer_id', $validated['offer_id'])->count();
        // КОЛИЧЕСТВО ПЕРЕХОДОВ ПО ОФФЕРУ
        $transitions = DB::table('url_transition_lists')->where('offer_id', $validated['offer_id'])->whereBetween(DB::raw('CAST(created_at AS DATE)'),[$validated['startDate'], $validated['endDate']])->count();
        // ЗАПЛАТИТ РЕКЛАМОДАТЕЛЬ ЗА ВСЕ ПЕРЕХОДЫ (ОБЩИЙ ДОХОД)
        $fullPrice = round($offer->value('transition_cost') * $transitions , 1);
        // ПОЛУЧАТ ВЕБ-МАСТЕРА (ДОХОД ВЕБ-МАСТЕРОВ) (КОМИССИЯ СИСТЕМЫ - 10%)
        $webPrice = round($fullPrice * 0.9 , 1);
        // ПОЛУЧИТ СИСТЕМА (ДОХОД СИСТЕМЫ)
        $systemPrice = round($fullPrice - $webPrice ,1);

        return response()->json([$offer,$subscriptionCount,$transitions,$fullPrice,$webPrice,$systemPrice]);
    }

    // ВЫВОД ОБЩЕЙ СТАТИСТИКИ ЗА ВЫБРАННЫЙ ПЕРИОД
    public function getStatistics(Request $request)
    {   
        $validated = $request->validate([
            'startDate' => ['required'],
            'endDate'=> ['required'],
        ]);

        $urlCount = DB::table('subscriptions')->whereBetween(DB::raw('CAST(created_at AS DATE)'),[$validated['startDate'], $validated['endDate']])->pluck('url_redirector')->count();
        $transitionsCount = DB::table('url_transition_lists')->whereBetween(DB::raw('CAST(created_at AS DATE)'),[$validated['startDate'], $validated['endDate']])->count();
        $rejectedTransitionsCount = DB::table('rejected_transitions')->whereBetween(DB::raw('CAST(created_at AS DATE)'),[$validated['startDate'], $validated['endDate']])->count();
        
        return response()->json(['url'=>$urlCount,'transitions'=>$transitionsCount,'rejected'=>$rejectedTransitionsCount]);
    }
}
