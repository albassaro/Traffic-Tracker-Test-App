<!-- ФОРМА АВТОРИЗАЦИИ -->
@extends('layouts.index')

@section('title', 'MyTestTracker - Авторизация')

@section('content')
<div class="form">
    <form class="form__card" action="{{route('login-process')}}" method="post" enctype="multipart/form-data">
        <h2>Авторизация</h2>
        @csrf
        <fieldset>
            <label for="email">
                Логин
                <input 
                    type="text" 
                    id="email" 
                    name="email"
                    class="@error('email') is-invalid @enderror"
                    value="{{ old('email') }}"  
                    placeholder="Введите email"
                />
                @error('email')
                    <div class="alert alert-danger">{{ $message }} </div>
                @enderror

            </label>
            <label for="password">
                Пароль
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="@error('password') is-invalid @enderror" 
                    placeholder="Введите пароль"
                />
                @error('password')
                    <div class="alert alert-danger">{{ $message }} </div>
                @enderror
            </label>
        </fieldset>
        <button type="submit" id="submit">Войти</button>
    </form>
</div>
@endsection