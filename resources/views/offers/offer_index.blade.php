
<!-- СПИСОК ОФФЕРОВ(СОЗДАННЫХ ЛИБО ПОДПИСАННЫХ)/ГЛАВНАЯ ДЛЯ ИДЕНТИФ-НЫХ ПОЛЬЗОВАТЕЛЕЙ -->
@extends('layouts.index')

@section('title', 'MyTestTracker - Главная')

@section('content')

@can('user-is-advertiser', $role)
    <h3 class="border-bottom pb-2 mb-3 ms-4">Список созданных вами офферов:</h3>
@endcan
@can('user-is-webmaster', $role)
    <h3 class="border-bottom pb-2 mb-3 ms-4">Список офферов, на которые вы подписаны:</h3>
@endcan

<div class="form">
        <div class="row row-cols-1 row-cols-sm-3 row-cols-md-1 g-5">
            @foreach($data as $list)
                <div class="col">
                    <div class="card">
                        <div class="card-header py-3">
                            <h3 class="card-header--text my-0">{{$list->name}}</h3>
                            @can('user-is-advertiser', $role)
                                <a class="btn btn-delete btn btn-primary" href="{{route('delete-offer', $list->id)}}">Удалить</a>
                            @endcan
                            @can('user-is-webmaster', $role)
                                <a class="btn btn-delete btn btn-primary" href="{{route('unsubscribe-from-offer', $list->offer_id)}}">Отписаться</a>
                            @endcan
                        </div>
                        <div class="card-body">
                            <h4 class="mb-2">Описание:</h4>
                            <p class="card-text">{{$list->description}}</p>
                            @can('user-is-advertiser', $role)
                                <div class="card-cost">
                                    <p class="text-body-secondary ">Стоимость перехода, руб.: <br> <span class="fw-bold"> {{$list->transition_cost}}</span></p>
                                </div>
                            @endcan
                            @can('user-is-webmaster', $role)
                                <div class="card-cost">
                                    <p class="text-body-secondary ">Стоимость перехода, руб.(без учета комиссии - 10%): <br> <span class="fw-bold"> {{$list->transition_cost}}</span></p>
                                </div>
                            @endcan
                            <div class="card-datails">
                                @can('user-is-webmaster', $role)   
                                    <a class="btn btn-primary" href="{{route('offer-detail', $list->offer_id)}}">Подробнее</a>  
                                    <p class="text-body-secondary ">Дата подписки: <br> <span class="fw-bold"> {{$list->created_at}}</span></p>
                                @endcan
                                @can('user-is-advertiser', $role)
                                    <a class="btn btn-primary" href="{{route('offer-detail', $list->id)}}">Подробнее</a>     
                                    <p class="text-body-secondary ">Дата создания: <br> <span class="fw-bold"> {{$list->created_at}}</span></p>
                                @endcan
                                                     
                            </div>
                        </div>
                    </div>
                </div>   
            @endforeach                           
        </div>
</div>
@endsection