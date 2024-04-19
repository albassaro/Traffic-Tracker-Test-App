
<!-- ДЕТАЛЬНАЯ ИНФОРМАЦИЯ ОБ ОФФЕРЕ -->
@extends('layouts.index')

@section('title', 'MyTestTracker - Информация об оффере')

@section('content')
<h3 class="border-bottom pb-2 mb-3 ms-4">Детальная информация об оффере: </h3>

<div class="form">
    <div class="row row-cols-1 row-cols-sm-3 row-cols-md-1 g-5">
        <br>
        <div class="col">
            <div class="card">
                <div class="card-header p-4">
                    <h3 class="card-header--text my-0">{{$data->name}}</h3>
                    @can('user-is-webmaster', $role)
                    <div>
                        <a class="btn btn-delete btn btn-primary" href="{{route('unsubscribe-from-offer', $data->id)}}">Отписаться</a>
                    </div>
                    @endcan
                    @can('user-is-advertiser', $role)
                    <div>
                        <a class="btn btn-delete btn btn-primary" href="{{route('offer-update', $data->id)}}">Редактировать</a>
                    </div>
                    @endcan    
                </div>
                @if(session('success'))
                    <p class="alert alert-success px-4 mx-0" role="alert">{{ session('success') }}</p>
                @endif
                <div class="card-body p-4">
                    <p class="mb-2">Описание:<br></p>
                    <p class="card-text">{{$data->description}}</p>
                    <div class="card-cost">
                        <p class="text-body-secondary ">Стоимость перехода, руб.: <br> <span class="fw-bold"> {{$data->transition_cost}}</span></p>
                    </div>
                    @can('user-is-advertiser', $role)
                    <div class="card-links">
                        <label>Целевая ссылка:<br></label>
                        <p class="card-links__url">{{$data->url}}</p>
                    </div>
                    @endcan
                    @can('user-is-webmaster', $role)
                    <div class="card-links">
                        <label>Специальная ссылка, размещаемая на сайте:<br></label>
                        <p class="card-links__url">{{$subscriptionUrl}}</p>
                    </div>
                    @endcan
                    @can('user-is-advertiser', $role)
                    <div class="card-cost">
                        <p class="text-body-secondary ">Количество подписанных веб-мастеров: <br> <span class="fw-bold"> {{$count}}</span></p>
                    </div>
                    @endcan
                    <div class="card-datails ">
                        <p class="text-body-secondary ">Дата создания: <br> <span class="fw-bold"> {{$data->created_at}}</span></p>
                    </div>
                    @can('user-is-webmaster', $role)
                        <div class="card-datails ">
                            <p class="text-body-secondary ">Дата подписки: <br> <span class="fw-bold"> {{$subscriptionDate}}</span></p>
                        </div> 
                    @endcan
                </div>
                <!-- ИНФОРМАЦИЯ ДЛЯ ПОЛЬЗОВАТЕЛЕЙ -->

                <div class="card-info px-4 pt-2">
                    <h5 class="mb-2">Вывод данных за указанное время:</h5>
                    <form class="card-info" method="post">
                            @csrf
                            <label for="startDate">С</label>
                            <input type="date" name="startDate" class="startDate"></input>
            
                            <label for="startDate">По</label>
                            <input type="date" name="endDate" class="endDate"></input>
                            
                        <button type="submit" class="card-info__submit">Вывести данные</button>
                    </form>
                    <br>
                    <div id="alert-form"></div>
                    @can('user-is-advertiser', $role)
                        <p class="text-body-secondary ">Расходы на данный оффер, руб.: <br> <span class="fw-bold" id="expenses"></span></p>
                        <p class="text-body-secondary ">Количество переходов по офферу: <br> <span class="fw-bold" id="transitions"></span></p>
                    @endcan
                    @can('user-is-webmaster', $role)
                        <p>Доход по данному офферу, руб.(с учетом комиссии - 10%): <br> <span class="fw-bold" id="expenses"></span></p>
                        <p>Количество переходов по офферу: <br> <span class="fw-bold" id="transitions"></span></p>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<script>

$(document).ready(function()
{
 $('form.card-info').on('submit',function(event)
    {
        event.preventDefault();

        let startDateValue = $('input.startDate').val();
        let endDateValue = $('input.endDate').val();
        let token = $("input[name='_token']").val();
        let idValue = {{$data->id}};

        $.ajax({
        url: '/offer-info',
        method: "POST",
        data: {
            _token: token,
            startDate: startDateValue,
            endDate: endDateValue,
            id:idValue,
        },
        success:function(data)
        {
            if($('#alert-dates').length !== 0)
            {
                $('#alert-dates').detach();
            }
            $('#expenses').html(data[0]);
            $('#transitions').html(data[1]);

        },
        error: function (data, textStatus, errorThrown) {
            if(422 == data.status) {
                if($('#alert-dates').length !== 0)
                {
                    $('#alert-dates').detach();
                }
                $('#alert-form').append($('<div class="alert alert-danger" id="alert-dates"></div>'));
                const {errors} = data.responseJSON;
                for (let error in errors) {
                    console.log(error, errors[error][0]);
                    $('#alert-dates').append($('<li>',{text: errors[error][0]}));
                }
            }
        },
        
        })     
    })
})

</script>

        

@endsection



   

