<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoleList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // ГЛАВНАЯ СТРАНИЦА 
    public function index()
    {
        $users = DB::table('roles')->join('role_lists', 'role_lists.role_id', '=', 'roles.id')
        ->join('users','users.id', '=', 'role_lists.user_id')->orderBy('created_at', 'desc')->paginate(5);
        
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    
    // ФОРМА СОЗДАНИЯ НОВЫХ ПОЛЬЗОВАТЕЛЕЙ
    public function create()
    {        
        return view('admin.auth.registration');
    }

    /**
     * Store a newly created resource in storage.
     */
    
    // ЛОГИКА СОЗДАНИЯ ПОЛЬЗОВАТЕЛЕЙ
    public function store(Request $request)
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
            return redirect(route('admin.users.index'))->with('success', 'Пользователь успешно добавлен');
        }

        return redirect(route('register'))->withErrors(['email'=>'Произошла ошибка при сохранении пользователя']);
    }
    /**
     * Remove the specified resource from storage.
     */
    
    // УДАЛЕНИЕ ПОЛЬЗОВАТЕЛЕЙ
    public function destroy(string $id)
    {
        $user = User::where('id',$id)->delete();
        
        return redirect(route('admin.users.index'))->with('success', 'Пользователь успешно удален');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }    
}
