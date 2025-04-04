<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Primero se crean los roles y permisos
        $this->call(RolesAndPermissionsSeeder::class);

        // Usuario Admin
        $admin = User::factory()->create([
            'name' => 'Alec Thompson',
            'email' => 'admin@corporateui.com',
            'password' => Hash::make('secret'),
            'about' => "Hi, I’m Alec Thompson, Decisions: If you can’t decide, the answer is no...",
        ]);
        $admin->assignRole('Administrador');

        // Usuario normal
        $usuario = User::factory()->create([
            'name' => 'María López',
            'email' => 'usuario@corporateui.com',
            'password' => Hash::make('1234567'),
            'about' => 'Soy una usuaria con permisos para explorar archivos.',
        ]);
        $usuario->assignRole('Usuario');

        // Usuario auditor
        $auditor = User::factory()->create([
            'name' => 'Carlos Pérez',
            'email' => 'auditor@corporateui.com',
            'password' => Hash::make('12345678'),
            'about' => 'Auditor del sistema, acceso solo a auditorías.',
        ]);
        $auditor->assignRole('Auditor');
    }
}
