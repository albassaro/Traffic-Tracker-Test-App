@extends('layouts.admin_index')

@section('title', 'MyTestTracker - Общая статистика')

@section('content')

<h3 class="border-bottom pb-2 mb-2 ms-5 me-5">Статистика</h3>
    <div class="container my-3 p-3 rounded shadow w-50">
        <h5 class="border-bottom pb-2 mb-0">Параметры выбора:</h5>
        <div class="pt-3 px-2 border-bottom">
            <form class="select-params" method="post">
                @csrf
                <div class="container text-center">
                    <div class="row gx-5">
                        <div class="col-8 text-start">
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
            <div class="row row-cols-2 gx-7">
                <div class="col-6 px-4">
                    <h6 class="my-3 pb-2 border-bottom">За все время: </h6> 
                    <div id="main-info">
                        <p class="my-3">Комиссия системы:<br> <span class="fw-bold">10%</span></p>
                        <p class="my-3">Количество выданных ссылок:<br> <span class="fw-bold">{{$urlCount}}</span></p>
                        <p class="my-3">Количество переходов:<br> <span class="fw-bold">{{$transitionsCount}}</span></p>
                        <p class="my-3">Количество отказов: <br> <span class="fw-bold">{{$rejectedTransitionsCount}}</span></p>
                    </div>      
                </div>
                <div class="col-6 px-3">
                    <h6 class="my-3 pb-2 border-bottom">За введенный промежуток: </h6> 
                    <div id="detail-info"></div> 
                </div>
            </div>
        </div>
    </div>

    <script>

$(document).ready(function()
{
    $('form.select-params').on('submit',function(event)
    {
        event.preventDefault();
        let token = $("input[name='_token']").val();
        let startDateValue = $('input.startDate').val();
        let endDateValue = $('input.endDate').val();

        $.ajax({
        url: 'statistics-details',
        method: "POST",
        data: {
            _token: token,
            startDate: startDateValue,
            endDate: endDateValue,
        },
        success:function(data)
        {
            if($('#alert-info').length !== 0)
            {
                $('#alert-info').detach();
            }

            if($("#detail-info").children().length > 0){
                $('#detail-info').empty();
            }

            $('#detail-info').append($('<p class="my-3">Количество выданных ссылок: <br><span class="fw-bold">' + data['url'] + '</span> </p>'));
            $('#detail-info').append($('<p class="my-3">Количество переходов по ссылкам: <br><span class="fw-bold">' + data['transitions'] + '</span> </p>'));
            $('#detail-info').append($('<p class="my-3">Количество отказов: <br><span class="fw-bold">' + data['rejected'] + '</span> </p>'));          
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