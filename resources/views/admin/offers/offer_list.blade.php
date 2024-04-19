<!-- СПИСОК ВСЕХ ВОЗМОЖНЫХ ОФФЕРОВ ДЛЯ ПОДПИСКИ -->
@extends('layouts.admin_index')

@section('title', 'MyTestTracker - Статистика по выбранному офферу')

@section('content')
<h3 class="border-bottom pb-2 mb-0 ms-4">Статистика по выбранному офферу</h3>
    <div class="container my-3 p-4 rounded shadow  mx-auto">
        <h5 class="border-bottom pb-2 mb-0">Параметры выбора:</h5>
        <div class="pt-3 border-bottom">
            <form class="select-params" method="post">
                @csrf
                <div class="container text-center">
                    <div class="row gx-5">
                        <div class="col-6 text-start gx-5">
                            <label for="user">Пользователь</label>
                            <select name="user" id="user-email">
                                <option>Выберите email</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->email}}</option>
                                @endforeach
                            </select>
                            <label for="offer">Оффер </label>
                            <select name="offer" id="offer-id">
                                    <option>Выберите оффер</option>
                            </select>
                        </div>
                        <div class="col-5 text-start">
                            <label for="startDate">С</label>
                            <input type="date" name="startDate" class="startDate"></input>
                        
                            <label for="startDate">По</label>
                            <input type="date" name="endDate" class="endDate"></input>
                        </div>
                    </div>
                </div>              
                <button type="submit" class="card-info__submit">Вывести данные</button>
                <div id="alert-form"></div>
            </form>
        </div>
        <div class="container">
            <div class="row row-cols-2">
                <div class="card-info px-3" >
                    <h6 class="my-4 pb-2 border-bottom color-black">Основная информация: </h6> 
                    <div id="main-info"></div>     
                </div>
                <div class="card-info px-3">
                    <h6 class="my-4 pb-2 border-bottom">Детальная информация за введенный промежуток времени: </h6> 
                    <div id="detail-info"></div> 
                </div>
            </div>
        </div>
    </div>

<script>

$(document).ready(function()
{
    $('#user-email').change(function(event)
    {
        let token = $("input[name='_token']").val();
        let user_id = $(this).val();

        $.ajax({
        url: 'get-offers',
        method: "POST",
        data: {
            _token: token,
            id:user_id,
        },
        success:function(data)
        {
            // УДАЛЕНИЕ ПРЕДЫДУЩИХ ОФФЕРОВ ЕСЛИ ОНИ БЫЛИ
            if($("select[id=offer-id] option").length > 0){
                $('#offer-id option').remove();
            }
            // ЕСЛИ ЕСТЬ ОФФЕРЫ ВЫВОДИМ ИНАЧЕ ПИШЕМ ЧТО ИХ НЕТ
            if (data[0].length != 0) {
               $.each(data[0], function (item) {
                    $('#offer-id').append($('<option>', {
                        value: data[0][item]['id'], 
                        text: 'Имя оффера:' + ' ' + data[0][item]['name'],
                    }));
                }); 
            }else{
                $('#offer-id').append($('<option>', { 
                        text: 'Офферы отсутствуют. Выберите другого пользователя',
                }));
            }
            
        },     
        })     
    });
 
    $('form.select-params').on('submit',function(event)
    {
        event.preventDefault();
        let token = $("input[name='_token']").val();
        let user_id = $('#user-email').val();
        let offer_id = $('#offer-id').val();
        let startDateValue = $('input.startDate').val();
        let endDateValue = $('input.endDate').val();

        $.ajax({
        url: 'offers-details',
        method: "POST",
        data: {
            _token: token,
            user_id: user_id,
            offer_id: offer_id,
            startDate: startDateValue,
            endDate: endDateValue,
        },
        success:function(data)
        {
            if($('#alert-info').length !== 0)
            {
                $('#alert-info').detach();
            }

            if($("#main-info").children().length > 0 || $("#detail-info").children().length > 0){
                $('#main-info').empty();
                $('#detail-info').empty();
            }
            
            // ОСНОВНАЯ ИНФ-ЦИЯ
            $('#main-info').append($('<p class="my-4">Название: <br> <span class="fw-bold">' + data[0][0]['name'] + '</span> </p>'));
            $('#main-info').append($('<p class="my-4">Описание: <br> <span class="fw-bold">' + data[0][0]['description'] + '</span> </p>'));
            $('#main-info').append($('<p class="my-4">Стоимость за переход по ссылке: <br> <span class="fw-bold">' + data[0][0]['transition_cost'] + '</span> </p>'));
            $('#main-info').append($('<p class="my-4">Целевой URL: <br> <span class="fw-bold">' + data[0][0]['url'] + '</span> </p>'));
            $('#main-info').append($('<p class="my-4">Дата создания: <br><span class="fw-bold">' + data[0][0]['created_at'] + '</span> </p>'));
            $('#main-info').append($('<p class="my-4">Количество подписавшихся на оффер: <br><span class="fw-bold">' + data[1] + '</span> </p>'));
            
            // ДЕТАЛЬНАЯ ИНФ-ЦИЯ
            $('#detail-info').append($('<p class="my-4">Количество переходов по офферу: <br><span class="fw-bold">' + data[2] + '</span> </p>'));
            $('#detail-info').append($('<p class="my-4">Общий доход, руб.: <br><span class="fw-bold">' + data[3] + '</span> </p>'));
            $('#detail-info').append($('<p class="my-4">Доход веб-мастеров, руб. (с учетом комиссии - 10%): <br> <span class="fw-bold">' + data[4] + '</span> </p>'));
            $('#detail-info').append($('<p class="my-4">Доход системы, руб. (10% от общего дохода):  <br> <span class="fw-bold">' + data[5] + '</span> </p>'));           
        },
        error: function (data, textStatus, errorThrown) {
            if(422 == data.status) {
                if($('#alert-info').length !== 0)
                {
                    $('#alert-info').detach();
                }
                $('#alert-form').append($('<div class="alert alert-danger" id="alert-info"></div>'));
                const {errors} = data.responseJSON;
                for (let error in errors) {
                    console.log(error, errors[error][0]);
                    $('#alert-info').append($('<li>',{text: errors[error][0]}));
                }
            }
        },
        
        })     
    })
})

</script>





@endsection



