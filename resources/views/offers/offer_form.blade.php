
<!-- ФОРМА СОЗДАНИЯ НОВОГО ОФФЕРА -->
@extends('layouts.index')

@section('title', 'MyTestTracker - Создание нового оффера')

@section('content')
<div class="form">
    <form class="form__card offer-form" action="{{route('create-offer')}}" method="post" enctype="multipart/form-data">
        <h2>Создание оффера</h2>
        @csrf
        <fieldset>
            <label for="name">
                Имя
                <input 
                    type="text" 
                    id="name" 
                    name="name"
                    class="@error('name') is-invalid @enderror"
                    value="{{ old('name') }}"  
                    placeholder="Введите имя"
                />
                @error('name')
                    <div class="alert alert-danger">{{ $message }} </div>
                @enderror

            </label>
            <label for="description">
                Описание
                <textarea 
                    id="description" 
                    name="description" 
                    class="@error('description') is-invalid @enderror"
                    placeholder="Описание оффера"
                >{{ old('description') }}</textarea>
                @error('description')
                    <div class="alert alert-danger">{{ $message }} </div>
                @enderror
            </label>
            <label for="transition_cost">
                Стоимость перехода, руб.
                <input 
                    type="number" 
                    name="transition_cost"
                    class="@error('transition_cost') is-invalid @enderror"
                    value="{{ old('transition_cost') }}" 
                    placeholder="Введите стоимость перехода" 
                />
                @error('transition_cost')
                    <div class="alert alert-danger">{{ $message }} </div>
                @enderror
            </label>
            <label for="url">
                Целевой URL
                <input 
                    type="url" 
                    name="url"
                    class="@error('url') is-invalid @enderror"
                    value="{{ old('url') }}" 
                    placeholder="Введите URL ссылку " 
                />
                @error('url')
                    <div class="alert alert-danger">{{ $message }} </div>
                @enderror
            </label>
        </fieldset>
        <button type="submit" id="submit">Создать</button>
    </form>
</div>
@endsection