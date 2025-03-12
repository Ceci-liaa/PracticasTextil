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
    public function update(Request $request, User $user)
    {
        // Validación de los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'about' => 'nullable|string',
            'status' => 'required|boolean',
            'role' => 'required|string|exists:roles,name',
        ]);
    
        // Datos a actualizar
        $data = $request->only(['name', 'email', 'location', 'phone', 'about', 'status']);
    
        // Verificar si hay cambios
        if ($user->only(array_keys($data)) === $data && $user->roles->pluck('name')->first() === $request->role) {
            return redirect()->back()->with('info', 'No se realizaron cambios.');
        }
    
        try {
            // Actualizar el usuario
            $user->update($data);
    
            // Actualizar rol solo si es diferente
            if (!$user->hasRole($request->role)) {
                $user->syncRoles([$request->role]);
            }
    
            // Verificar si es la edición del perfil o de otro usuario
            if ($request->has('profile_update')) {
                // Si viene desde la edición del perfil
                return redirect()->route('user-profile')->with('success', 'Perfil actualizado correctamente.');
            } else {
                // Si viene desde la edición de otro usuario
                return redirect()->route('users-management')->with('success', 'Usuario actualizado correctamente.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar la información.');
        }
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
