<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        $users = User::where('id','>',0);

        if($req->input('keyword')){
            $users = $users->where('name', 'like', '%'.$req->input('keyword').'%')
            ->orWhere('lastname', 'like', '%'.$req->input('keyword').'%')
            ->orWhere('email', 'like', '%'.$req->input('keyword').'%');
        }

        if($req->input('role')){
            $users = $users->where('role',$req->input('role'));
        }

        $users = $users->paginate(50);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate(
            [
                'email' => ['required', 'unique_user'],
                'password' => ['required','confirmed'],
                'password_confirmation' => ['required']
            ],
            [
                'email.unique_user' => 'El correo electrónico está en uso',
                'password.required' => 'La contraseña es requerida',
                'password_confirmation.required' => 'La confirmación de la contraseña es requerida',
                'password.confirmed' => 'Las contraseñas no coinciden'
            ]
        );

        $data = $request->all();
        $data['password'] = Hash::make($request->input('password'));
        $data['create_user'] = $user->id;
        $data['edit_user'] = $user->id;
        $result = User::create($data);
        return redirect()->route('usuarios.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $current_user =  Auth::user();
        $request->validate(
            [
                'password' => ['confirmed'],
                'password_confirmation' => ['required_with:password']
            ],
            [
                'password_confirmation.required_with' => 'La confirmación de la contraseña es obligatoria cuando se proporciona una contraseña.',
                'password.confirmed' => 'Las contraseñas no coinciden'
            ]
        );
        $user = User::find($id);
        if ( $request->input('password') && $request->has('password')) {
            $data = $request->except('password1');
            $data['password'] = Hash::make($request->input('password'));
        } else {
            $data = $request->except(['password','password1']);
        }
        
        $data['edit_user'] = $current_user->id;
        $user->update($data);

        return redirect()->route('usuarios.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function export(){
        return Excel::download(new UsersExport,'usuarios.xlsx');
    }
}
