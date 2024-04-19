
<!-- СПИСОК ПОЛЬЗОВАТЕЛЕЙ -->
@extends('layouts.admin_index')

@section('title', 'MyTestTracker - Главная')

@section('content')

<div class="my-3 p-3 bg-body rounded shadow">
    <div class="border-bottom pb-2 mb-0">
    <h4>Пользователи</h4>
    <a class="btn btn-primary btn-sm" href="{{route('admin.users.create')}}">Добавить пользователя</a>
    @if(session('success'))
        <p class="alert alert-success" role="alert">{{ session('success') }}</p>
    @endif
    </div>
    @foreach($users as $user)
    <div class="d-flex text-body-secondary pt-3">
    <svg class="bd-placeholder-img flex-shrink-0 me-2 mt-1 rounded" width="30" height="30" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Заполнитель: 32&nbsp;x&nbsp;32." preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#007bff"></rect></svg>
    <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
        <div class="d-flex justify-content-between">
        <strong class="text-gray-dark">{{$user->email}}</strong>
        <form action="{{route('admin.users.destroy', $user->id)}}" method="POST">
            @csrf
            @method('DELETE')
            <button class="btn btn-link__delete" type="submit">Удалить</button>
        </form>
        </div>
        <span class="d-block">Роль: {{$user->name}}</span>
        <span class="d-block">Дата создания: {{$user->created_at}}</span>
    </div>
    </div>
    @endforeach  
    {{$users->links()}}
</div>

@endsection