<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd('TES DARI UserController@index: Method ini dipanggil.'); // Langkah debug 1
        $users = User::with('roles')->orderBy('name')->paginate(10);
        // dd($users); // Langkah debug 2: Periksa isi $users
        
        if ($users === null) {
            // Ini seharusnya tidak terjadi dengan paginate, tapi sebagai jaga-jaga
            // Log atau dd() di sini jika $users null
            // \Log::error('Variabel $users bernilai null di UserController@index');
        }
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }
}
