<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        // Asegurar que los roles se carguen con los usuarios
        $users = User::with('roles')->get();

        return view('user.users-management', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        // Validar los datos antes de actualizar
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'about' => 'nullable|string',
            'status' => 'required|boolean',
            'role' => 'required|string|exists:roles,name', // Validar que el rol existe en la tabla roles
        ]);

        try {
            // 🔹 Obtener el ID del nuevo rol desde la tabla roles
            $role = Role::where('name', $request->role)->where('guard_name', 'web')->first();

            if ($role) {
                // 🔥 ACTUALIZAR EL CAMPO `role` EN `users`
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'location' => $request->location,
                    'phone' => $request->phone,
                    'about' => $request->about,
                    'status' => $request->status,
                    'role' => $role->name, // 🔥 Aquí forzamos que el campo `role` en `users` se actualice
                ]);

                // 🔥 SINCRONIZAR `model_has_roles`
                $user->syncRoles([$role->name]); // Esto asegurará que también se actualice en la tabla de roles

            } else {
                return redirect()->back()->with('error', 'El rol seleccionado no existe en la base de datos.');
            }

            return redirect()->route('users-management')->with('success', 'Usuario actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar la información: ' . $e->getMessage());
        }
    }

    public function toggleStatus(User $user)
    {
        try {
            $user->status = !$user->status; // Cambia de 1 a 0 o viceversa
            $user->save();
            return redirect()->route('users-management')->with('success', 'Estado actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el estado.');
        }
    }

    public function edit(User $user)
    {
        $roles = Role::all(); // Obtener todos los roles disponibles
        return view('user.user-edit', compact('user', 'roles')); // Retorna la vista de edición
    }
}
