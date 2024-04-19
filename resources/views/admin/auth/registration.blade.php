<!-- ФОРМА РЕГИСТРАЦИИ -->
@extends('layouts.admin_index')

@section('title', 'MyTestTracker - Регистрация')

@section('content')
<div class="form">
    <form class="form__card" action="{{route('admin.users.store')}}" method="post" enctype="multipart/form-data">       
        <h2>Регистрация пользователя</h2>
        @csrf
        <fieldset>
            <label for="email">
                Логин
                <input 
                    type="text" 
                    id="email" 
                    name="email"
                    class="@error('email') is-invalid @enderror" 
                    placeholder="Введите email"
                    value="{{ old('email') }}"
                    />

                @error('email')
                    <div class="alert alert-danger ">{{ $message }} </div>
                @enderror                   
            </label>

            <label for="password">
                Пароль
                <input type="password" id="password" name="password" class="@error('password') is-invalid @enderror" placeholder="Введите пароль"/>
                
                @error('password')
                    <div class="alert alert-danger">{{ $message }} </div>
                @enderror
            </label>
            <label>
                Кто вы?
            </label>

            <label for="role">
                Веб-мастер
                <input type='radio' id="role-1" name="role" value="1" checked />

                Рекламодатель  
                <input type='radio' id="role-2" name="role" value="2"/>
            </label>

        </fieldset>
        <button type="submit" id="submit">Зарегистрировать</button>
    </form>
</div>
@endsection