
<!-- СПИСОК ВСЕХ ВОЗМОЖНЫХ ОФФЕРОВ ДЛЯ ПОДПИСКИ -->
@extends('layouts.index')

@section('title', 'MyTestTracker - Список доступных офферов для подписки')

@section('content')

<h3 class="border-bottom pb-2 mb-3 ms-4">Список доступных офферов для подписки:</h3>

<div class="form">
        <div class="row row-cols-1 row-cols-sm-3 row-cols-md-1 g-5"> 
            @foreach($offer as $list)
                <div class="col">
                    <div class="card">
                        <div class="card-header py-3">
                            <h3 class="card-header--text my-0">{{$list->name}}</h3>
                            <a class="btn btn-delete btn btn-primary" href="{{route('subscribe-to-offer', $list->id)}}">Подписаться</a>
                        </div>
                        <div class="card-body">
                            <h4 class="mb-2">Описание:</h4>
                            <p class="card-text">{{$list->description}}</p>
                            <div class="card-cost">
                                <p class="text-body-secondary ">Стоимость перехода, руб.(без учета комиссии - 10%): <br> <span class="fw-bold"> {{$list->transition_cost}}</span></p>
                            </div>
                            <div class="card-datails">   
                                <p class="text-body-secondary ">Дата создания: <br> <span class="fw-bold"> {{$list->created_at}}</span></p>
                            </div>
                        </div>
                    </div>
                </div>    
            @endforeach                         
        </div>
</div>
@endsection