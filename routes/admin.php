<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;




Route::middleware('guest:admin')->group(function(){

// ФОРМА АВТОРИЗАЦИИ
Route::get('login',[AuthController::class , 'index'])->name("login");

// ОБРАБОТКА ФОРМЫ АВТОРИЗАЦИИ
Route::post('login_process',[AuthController::class , 'login'])->name("login_process");
                                               
});

Route::middleware('auth:admin')->group(function(){

                                                            //===========  СТРАНИЦЫ ===========

    // ГЛАВНАЯ/ФОРМА И ЛОГИКА СОЗДАНИЯ ПОЛЬЗ-ЛЕЙ/ЛОГИКА УДАЛЕНИЯ ПОЛЬЗ-ЛЕЙ
    Route::resource('users', UserController::class);

    //СТРАНИЦА С ВЫБОРОМ ОФФЕРОВ
    Route::get('offers', [OfferController::class, 'showOfferList'])->name('offers-list');
    // СТРАНИЦА С ОТОБРАЖЕНИЕМ ОБЩЕЙ СТАТИСТИКИ
    Route::get('statistics', [OfferController::class, 'showStatistic'])->name('statistics-list');
    
     
                                                        //===========  ЛОГИКА ОБРАБОТКИ ИЛИ ВЫВОДА ИНФ-ЦИИ ===========
    
    // AJAX ЗАПРОСЫ НА ДЕТАЛЬНУЮ ИНФ-ИЮ ОБ ОФФЕРЕ
    Route::post('get-offers',[OfferController::class, 'getUserData'])->name('get-offers');
    // AJAX ЗАПРОСЫ НА ДЕТАЛЬНУЮ ИНФ-ИЮ ОБ ОФФЕРЕ
    Route::post('offers-details',[OfferController::class, 'getOfferDetails'])->name('offers-details');
    // AJAX ЗАПРОСЫ НА ДЕТАЛЬНУЮ ИНФ-ИЮ ОБ ОФФЕРЕ
    Route::post('statistics-details',[OfferController::class, 'getStatistics'])->name('statistics-details');

    // ВЫХОД ИЗ ПОЛЬЗОВАТЕЛЯ
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

});
















