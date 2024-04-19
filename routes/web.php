<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UrlRedirectController;
use Illuminate\Support\Facades\Route;


                                                        // ОБЩИЕ СТРАНИЦЫ
// Форма Авторизации/ГЛАВНАЯ
Route::get('/', function () {
    if(auth()->check())
    {
        return redirect(route('offers-list'));
    }

    return redirect(route('login'));
});

// URL РЕДИРЕКТОР
Route::get('subscription/link/{user_id}/offer/{offer_id}', [UrlRedirectController::class, 'redirectUrl'])->name('url-redirect');

    
                                                        // ПРИВАТНЫЕ СТРАНИЦЫ
Route::middleware('auth')->group(function(){
    
    //СТРАНИЦА СО СПИСКОМ СОЗДАННЫХ ОФФЕРОВ ДЛЯ ПОЛЬЗ-ЛЯ
    Route::get('/offers', [OfferController::class, 'showOfferList'])->name('offers-list');
    
    // СТРАНИЦА С ДЕТАЛЬНОЙ ИНФ-ЕЙ ОБ ОФФЕРЕ
    Route::match(['get', 'post'],'/offers/{id}', [OfferController::class, 'showOfferDetail'])->name('offer-detail');

    // СТРАНИЦА СО СПИСКОМ ВСЕХ ОФФЕРОВ ВСЕХ ПОЛЬЗ-ЛЕЙ
    Route::get('/all-offers', [OfferController::class, 'showAllOffers'])->name('show-all-offers');

    // ФОРМА ДЛЯ СОЗДАНИЯ НОВОГО ОФФЕРА 
    Route::get('/offer-form', [OfferController::class, 'showOfferForm'])->name('offer-form');

    // ОБРАБОТКА ФОРМЫ НОВОГО ОФФЕРА
    Route::post('/create-offer', [OfferController::class, 'createOffer'])->name('create-offer');


    // ФОРМА ДЛЯ ИЗМЕНЕНИЯ ОФФЕРА 
    Route::get('/offer-update/{id}', [OfferController::class, 'showOfferEdit'])->name('offer-update');

    // ОБРАБОТКА ФОРМЫ ИЗМЕНЕННОГО ОФФЕРА
    Route::post('/offer-update-process/{id}', [OfferController::class, 'updateOffer'])->name('offer-update-process');


    // ПОДПИСКА НА ОФФЕР
    Route::get('/subscribe-to-offer/{id}', [OfferController::class, 'subscribeToOffer'])->name('subscribe-to-offer');
    
    // ОТПИСКА ОТ ОФФЕРА
    Route::get('/unsubscribe-from-offer/{id}', [OfferController::class, 'unsubscribeFromOffer'])->name('unsubscribe-from-offer');
    // УДАЛЕНИЕ ОФФЕРА
    Route::get('/delete-offer/{id}', [OfferController::class, 'deleteOffer'])->name('delete-offer');

    // ОБРАБОТКА AJAX ЗАПРОСА НА ДЕТАЛЬНУЮ ИНФ-ЦИЮ
    Route::post('/offer-info', [OfferController::class, 'getDetailInfo'])->name('offer-info');

    // ВЫХОД ИЗ ПОЛЬЗОВАТЕЛЯ
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');       
});


                                                        // ГОСТЕВЫЕ СТРАНИЦЫ
Route::middleware('guest')->group(function(){
    // фОРМА АВТОРИЗАЦИИ
    Route::get('/login', [AuthController::class, 'showAuthForm'])->name('login');
    // ОБРАБОТКА АВТОРИЗАЦИИ
    Route::post('/login_process', [AuthController::class, 'logIn'])->name('login-process');

    // РЕГИСТРАЦИЯ
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    // ОБРАБОТКА РЕГИСТРАЦИИ
    Route::post('/register_process', [RegisterController::class, 'submit'])->name('registr-process'); 
});




















