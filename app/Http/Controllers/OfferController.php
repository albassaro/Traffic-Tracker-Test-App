<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\RoleList;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;


class OfferController extends Controller
{
                                        // =============================== ОТРИСОВКА СТРАНИЦ =================================

    // ОТОБРАЖЕНИЕ СТРАНИЦЫ СО ВСЕМИ ДОСТУПНЫМИ ДЛЯ ПОДПИСКИ ОФФЕРАМИ 
    public function showAllOffers()
    {
        // ПОЛУЧЕНИЕ ID ОФФЕРОВ НА КОТОРЫЕ ПОДПИСАН ПОЛЬЗОВАТЕЛЬ
        $subcriptions = DB::table('subscriptions')->where('user_id', auth()->user()->id)->pluck('offer_id')->toArray();
        // ПОЛУЧЕНИЕ СПИСКА ОФФЕРОВ КРОМЕ ТЕХ НА КОТОРЫЕ ПОДПИСАН ПОЛЬЗОВАТЕЛЬ
        $data = DB::table('offers')->whereNotIn('id',$subcriptions)->orderByDesc('created_at')->get();

        return view('offers.offer_list_all', ['offer' => $data]);
    }

    // ОТОБРАЖЕНИЯ ГЛАВНОЙ СТРАНИЦЫ ДЛЯ ИДЕНТИФИЦИРОВАННЫХ ПОЛЬЗОВАТЕЛЕЙ
    public function showOfferList(RoleList $role)
    {  
        // РАЗДЕЛЕНИЕ ВЫВОДИМЫХ ДАННЫХ В ЗАВИСИМОСТИ ОТ РОЛИ

        // РЕКЛАМОДАТЕЛЬ
        if(Gate::allows('user-is-advertiser', $role))
        {
            // ПОЛУЧЕНИЕ ВСЕХ ОФФЕРОВ ДЛЯ РЕКЛАМОДАТЕЛЯ
            $data = DB::table('offers')->where('user_id', auth()->user()->id)->orderBy('created_at','desc')->get(); 
            return view('offers.offer_index', ['data' => $data, 'role'=> $role ]);
        }
        // ВЕБ-МАСТЕР
        if(Gate::allows('user-is-webmaster', $role)) {
            // ПОЛУЧЕНИЕ ВСЕХ ПОДПИСОК ДЛЯ ВЕБ-МАСТЕРА
            $subcriptions = DB::table('subscriptions')->where('user_id', auth()->user()->id)->pluck('offer_id')->toArray();
            $data = DB::table('offers')->join('subscriptions','subscriptions.offer_id','=','offers.id')->whereIn('offers.id',$subcriptions)->orderBy('subscriptions.created_at','desc')->get();
            return view('offers.offer_index', ['data' => $data, 'role'=> $role]);
        }
    }

    // ОТОБРАЖЕНИЕ СТРАНИЦЫ С ДЕТАЛЬНОЙ ИНФОРМАЦИИ
    public function showOfferDetail(RoleList $role, $id)
    {
        // ИЩЕМ ОФФЕР ПО ID 
        $data = Offer::findOrFail($id);
        // БЕРЕМ ИЗ БД ДАТУ ПОДПИСКИ (т.к. дата создания = дата подписки)
        $subscriptionDate = DB::table('subscriptions')->where('offer_id', $id)->where('user_id', auth()->user()->id)->value('created_at');
        $subscriptionUrl = DB::table('subscriptions')->where('offer_id', $id)->where('user_id', auth()->user()->id)->value('url_redirector');
        // СЧИТАЕМ КОЛИЧЕСТВО ПОДПИСЧИКОВ НА ОФФЕР
        $subscriptionCount = DB::table('subscriptions')->where('offer_id', $id)->count();
        return view('offers.offer_detail',['data'=> $data, 'count' => $subscriptionCount, 'role'=>$role, 'subscriptionUrl'=>$subscriptionUrl,'subscriptionDate'=>$subscriptionDate]);
    }

    // ОТОБРАЖЕНИЕ СТРАНИЦЫ С СОЗДАНИЕМ НОВОГО ОФФЕРА
    public function showOfferForm(RoleList $role)
    {   
        // ПРОВЕРКА ПОЛЬЗОВАТЕЛЯ
        Gate::authorize('user-is-advertiser', $role);

        return view('offers.offer_form');
    }

    // ОТОБРАЖЕНИЕ СТРАНИЦЫ РЕДАКТИРОВАНИЯ ОФФЕРА
    public function showOfferEdit(RoleList $role,$offer_id)
    {
        Gate::authorize('user-is-advertiser', $role);
        // ИЩЕМ ОФФЕР ПО ID 
        $data = Offer::findOrFail($offer_id);
        
        return view('offers.offer_edit',['data'=> $data,'id'=>$offer_id]);
    }

    
                                    // ===============================   ФУНКЦИИ  ====================================

    // ФУНКЦИЯ СОЗДАНИЯ НОВОГО ОФФЕРА
    public function createOffer(Request $request, RoleList $role)
    {
        Gate::authorize('user-is-advertiser', $role);

        if(auth()->check())
        {
            $validated = $request->validate([
                'name' => ['required'],
                'description' => ['required'],
                'transition_cost' => ['required','gte: 0'],
                'url' => ['required', 'url'],
            ]);

            $offer =  Offer::create([
                'user_id'=>Auth::user()->id,
                'name' => $validated['name'],
                'description' => $validated['description'],
                'transition_cost' => $validated['transition_cost'],
                'url' => $validated['url'],
            ]);

            return redirect(route('offers-list'));
        }       
        return redirect(route('offer-form'))->withErrors(['email'=>'Произошла ошибка при сохранении оффера']);
    }

    // ФУНКЦИЯ РЕДАКТИРОВАНИЯ ОФФЕРА
    public function updateOffer(Request $request, RoleList $role, Offer $offer, $offer_id)
    {
        Gate::authorize('user-is-advertiser', $role);
        Gate::authorize('delete-update-offer', [$offer, $offer_id]);

        $validated = $request->validate([
            'name' => ['required'],
            'description' => ['required'],
            'transition_cost' => ['required','gte: 0'],
            'url' => ['required', 'url'],
        ]);

        $offer =  Offer::where('id',$offer_id)->where('user_id',auth()->user()->id)->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'transition_cost' => $validated['transition_cost'],
            'url' => $validated['url'],
        ]);

        return redirect(route('offer-detail',$offer_id))->with('success', 'Оффер успешно обновлен');
    }

    // ФУНКЦИЯ УДАЛЕНИЯ ОФФЕРА
    public function deleteOffer(RoleList $role, Offer $offer, $id)
    {
        Gate::authorize('user-is-advertiser', $role);
        Gate::authorize('delete-update-offer', [$offer, $id]);

        $offer->where('id',$id)->delete();

        return redirect(route('offers-list'));
    }


    // ФУНКЦИЯ ПОДПИСКИ НА ОФФЕР
    public function subscribeToOffer($id)
    {
        $user = Subscription::create([
            'user_id' => auth()->user()->id,
            'offer_id' => $id,
            'subscriber_email' => auth()->user()->email,
            'url_redirector' => URL::signedRoute('url-redirect', ['user_id' => auth()->user()->id, 'offer_id'=> $id]),
        ]);

        return redirect(route('show-all-offers'));
    } 
    
    // ФУНКЦИЯ ОТПИСКИ ОТ ОФФЕРА
    public function unsubscribeFromOffer(Subscription $subscription, $id)
    {
        // ПРОВЕРКА ВОЗМОЖНОСТИ ОТПИСАТЬСЯ
        if(Gate::denies('unsubscribe-offer', [$subscription, $id]))
        {
            abort(403);
        }
        // ОТПИСКА ОТ ОФФЕРА ЧЕРЕЗ УДАЛЕНИЕ ЗАПИСИ В БД
        $unsubscribe = DB::table('subscriptions')->where('user_id', auth()->user()->id)->where('offer_id', $id)->delete();

        return redirect(route('offers-list'));
    }
    
    // ФУНКЦИЯ ДЛЯ ПОДСЧЕТА ДАННЫХ В ПРОМЕЖУТКЕ, ВВЕДЕННОМ ПОЛЬЗОВАТЕЛЕМ
    public function getDetailInfo(Request $request, RoleList $role)
    {
        $validated = $request->validate([
            'startDate' => ['required'],
            'endDate'=> ['required'],
        ]);
        
        if(Gate::allows('user-is-advertiser', $role)){
        // РЕКЛАМОДАТЕЛЬ

            // ПОДСЧЕТ КОЛИЧЕСТВА ВСЕХ ПЕРЕХОДОВ ПО ОФФЕРУ ЗА ВВЕДЕНОЕ ВРЕМЯ
            $transitions = DB::table('url_transition_lists')->where('offer_id', $request->id)->whereBetween(DB::raw('CAST(created_at AS DATE)'),[$validated['startDate'], $validated['endDate']])->count();
            // ПОЛУЧЕНИЕ ЦЕНЫ ЗА 1 ПЕРЕХОД
            $price = DB::table('offers')->where('id', $request->id)->value('transition_cost');
            // ПОДСЧЕТ ОБЩЕЙ СУММЫ РАСХОДОВ
            $sum = $transitions * $price;

            return  [$sum, $transitions];
        }

        if(Gate::allows('user-is-webmaster', $role)){
        // ВЕБ-МАСТЕР

            // ПОДСЧЕТ КОЛИЧЕСТВА ВСЕХ ОФФЕРОВ ЗА ВВЕДЕНОЕ ВРЕМЯ
            $transitions = DB::table('url_transition_lists')->where('offer_id', $request->id)->whereBetween(DB::raw('CAST(created_at AS DATE)'),[$validated['startDate'], $validated['endDate']])->count();
            // ПОЛУЧЕНИЕ ЦЕНЫ ЗА 1 ПЕРЕХОД
            $price = DB::table('offers')->where('id', $request->id)->value('transition_cost');
            //  ПОДСЧЕТ ОБЩЕЙ СУММЫ ДОХОДОВ (ПРОЦЕНТ СИСТЕМЫ РАВЕН 10, Т.Е УМНОЖАЕТСЯ НА 0,9)
            $sum = ($transitions * $price) * 0.9;

            return  [$sum, $transitions];
        }
    }
}
