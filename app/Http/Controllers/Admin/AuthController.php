<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // СТРАНИЦА С ФОРМОЙ АВТОРИЗАЦИИ
    public function index()
    {
        return view('admin.auth.auth');
    }

    // ФУНКЦИЯ РЕГИСТРАЦИИ
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth('admin')->attempt($validated)) {
            return redirect(route('admin.users.index'));
        }

        return redirect(route('admin.login'))->withErrors(['email'=> 'Ошибка. Пользователь не найден либо введены неверные данные'])->withInput($validated);     
    }
    
    // ФУНКЦИЯ ВЫХОДА
    public function logout()
    {
        auth('admin')->logout();

        return redirect(route('admin.login'));
    }
}
