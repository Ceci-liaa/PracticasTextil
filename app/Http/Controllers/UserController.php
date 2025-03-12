<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('user.users-management', compact('users'));
    }

    // // Mostrar formulario para asignar rol
    // public function editRole(User $user)
    // {
    //     $roles = Role::all();
    //     return view('users.edit-role', compact('user', 'roles'));
    // }

    // Actualizar rol del usuario
    public function updateRole(Request $request, User $user)
    {
        $user->syncRoles($request->role);
        return redirect()->route('users.editRole', $user)->with('success', 'Rol actualizado correctamente.');
    }

    // Editar usuario
    public function update(Request $request, User $user)
    {
        // Validar los datos antes de actualizar
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'about' => 'nullable|string',
        ]);

        // Actualizar los datos del usuario
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'location' => $request->location,
            'phone' => $request->phone,
            'about' => $request->about,
        ]);

        return redirect()->route('users.profile')->with('success', 'Perfil actualizado correctamente.');
    }

    // Alternar estado del usuario (Activo/Inactivo)
    public function toggleStatus(User $user)
    {
        $user->active = !$user->active; // Cambia de 1 a 0 o viceversa
        $user->save();

        return redirect()->route('users-management')->with('success', 'Estado actualizado correctamente.');
    }


    // Mostrar formulario de edición

    public function edit(User $user)
    {
        $roles = Role::all(); // Obtiene todos los roles disponibles
        return view('user.user-edit', compact('user', 'roles')); // Retorna la vista de edición
    }

}
