<?php

namespace App\Http\Controllers;

use App\Models\RoleList;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    // ФУНКЦИЯ 
    public function showRegisterForm()
    {
        return view('auth.registration');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required'],
            'role' => ['required'],
        ]);
        $user = User::create([
            'email'=>$validated['email'],
            'password'=>bcrypt($validated['password'])
        ]);

        $role = RoleList::create([
            'role_id'=>$validated['role'],
            'user_id'=>$user->id
        ]);

        if($user)
        {
            auth()->login($user);
            return redirect(route('offers-list'));
        }

        return redirect(route('register'))->withErrors(['email'=>'Произошла ошибка при сохранении пользователя']);
    }
}
