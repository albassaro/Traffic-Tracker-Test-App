<?php

namespace App\Http\Controllers;

use App\Models\RejectedTransition;
use App\Models\UrlTransitionList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UrlRedirectController extends Controller
{
    // ОБРАБОТКА ССЫЛКИ И ПЕРЕНАПРВЛЕНИЕ НА ЦЕЛЕВОЙ URL
    public function redirectUrl(Request $request, $user_id, $offer_id)
    {
        // ПРОВЕРКА ПОДПИСАННОЙ СИГНАТУРЫ URL
        if (! $request->hasValidSignature()) {
            abort(404);
        }
        // ПРОВЕРКА ПОДПИСИ ПОЛЬЗ-ЛЯ  НА ОФФЕР
        if (DB::table('subscriptions')->where('offer_id', $offer_id)->value('user_id') != $user_id)
        {      
            $rejectedTransition = RejectedTransition::create([
                'user_id' => $user_id,
                'offer_id' => $offer_id,
            ]);
            abort(403);  
        }
        // ФИКСАЦИЯ ПЕРЕХОДА ПО ССЫЛКЕ В БД
        $transition = UrlTransitionList::create([
                'user_id' => $user_id,
                'offer_id' => $offer_id,
        ]);

        // ПОЛУЧЕНИЕ ЦЕЛЕВОЙ ССЫЛКИ  
        $targetUrl = DB::table('offers')->where('id', $offer_id)->value('url');
    
        return redirect()->away($targetUrl);

    }
}
