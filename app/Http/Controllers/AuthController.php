<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // ФУНЦИЯ ОТОБРАЖЕНИЯ ФОРМЫ АВТОРИЗАЦИИ
    public function showAuthForm()
    {
        return view('auth.auth');
    }

    // ФУНКЦИЯ РЕГИСТРАЦИИ
    public function logIn(Request $request, Role $role)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);    
            if (Auth::attempt($validated)) {
                $request->session()->regenerate();
    
                return redirect(route('offers-list'));
            }

        return redirect(route('login'))->withErrors(['email'=> 'Ошибка. Пользователь не найден либо введены неверные данные'])->withInput($validated);       
    }

    // ФУНКЦИЯ ВЫХОДА
    public function logout()
    {
        auth()->logout();

        return redirect(route('login'));
    }
}
