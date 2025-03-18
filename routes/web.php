<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\FileNameController;
use App\Http\Controllers\FileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/dashboard');
})->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');

Route::get('/tables', function () {
    return view('tables');
})->name('tables')->middleware('auth');

Route::get('/wallet', function () {
    return view('wallet');
})->name('wallet')->middleware('auth');

Route::get('/RTL', function () {
    return view('RTL');
})->name('RTL')->middleware('auth');

Route::get('/profile', function () {
    return view('account-pages.profile');
})->name('profile')->middleware('auth');

Route::get('/signin', function () {
    return view('account-pages.signin');
})->name('signin');

Route::get('/signup', function () {
    return view('account-pages.signup');
})->name('signup')->middleware('guest');

Route::get('/sign-up', [RegisterController::class, 'create'])
    ->middleware('guest')
    ->name('sign-up');

Route::post('/sign-up', [RegisterController::class, 'store'])
    ->middleware('guest');

Route::get('/sign-in', [LoginController::class, 'create'])
    ->middleware('guest')
    ->name('sign-in');

Route::post('/sign-in', [LoginController::class, 'store'])
    ->middleware('guest');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'store'])
    ->middleware('guest');


// Profile
// 1️⃣ Mostrar la vista del perfil del usuario autenticado
Route::get('/laravel-examples/user-profile', [ProfileController::class, 'index'])->name('users.profile')->middleware('auth');
// 2️⃣ Procesar la actualización del perfil del usuario autenticado
Route::put('/laravel-examples/user-profile/update', [ProfileController::class, 'update'])->name('users.profile.update')->middleware('auth');

// roles
Route::resource('roles', RoleController::class)->middleware(['auth']);
// RUTAS NO PROTEGIDAS
// Route::resource('roles', RoleController::class);

// //User routes gestion de usuarios (Admin)
// Route::get('/user/users-management', [UserController::class, 'index'])->name('users-management');
// Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
// Route::put('/user/{user}/update', [UserController::class, 'update'])->name('users.update');

// Route::put('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

// //Folder routes
// Route::get('/folders', [FolderController::class, 'index'])->name('folders.index'); // muestra todas las carpetas y subcarpetas disponibles
// Route::get('/folders/create', [FolderController::class, 'create'])->name('folders.create');
// Route::post('/folders', [FolderController::class, 'store'])->name('folders.store'); // Guarda la carpeta creada
// Route::get('/folders/folders-management/{folder}', [FolderController::class, 'show'])->name('folders.show');
// Route::get('/folders/{folder}/edit', [FolderController::class, 'edit'])->name('folders.edit'); // Edita la carpeta seleccionada
// Route::put('/folders/{folder}', [FolderController::class, 'update'])->name('folders.update'); // Actualiza la carpeta seleccionada
// Route::delete('/folders/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy'); // Elimina la carpeta seleccionada

// // Ruta del explorador de archivos
// // Mostrar los archivos dentro de la carpeta seleccionada
// Route::get('/explorer/{folder?}', [FolderController::class, 'explorer'])->name('folders.explorer');

// // Ruta para los nombres de los archivos
// // Route::resource('file_names', FileNameController::class);   //Esto creará automáticamente las rutas para listar, crear, editar, actualizar y eliminar nombres de archivos.

// Route::get('/file_names', [FileNameController::class, 'index'])->name('file_names.index'); // Listar todos los nombres de archivo permitidos
// Route::get('/file_names/create', [FileNameController::class, 'create'])->name('file_names.create'); // Mostrar el formulario para crear un nuevo nombre de archivo
// Route::post('/file_names', [FileNameController::class, 'store'])->name('file_names.store'); // Guardar un nuevo nombre de archivo en la base de datos
// Route::get('/file_names/{fileName}/edit', [FileNameController::class, 'edit'])->name('file_names.edit'); // Mostrar el formulario para editar un nombre de archivo existente
// Route::put('/file_names/{fileName}', [FileNameController::class, 'update'])->name('file_names.update'); // Actualizar un nombre de archivo en la base de datos
// Route::delete('/file_names/{fileName}', [FileNameController::class, 'destroy'])->name('file_names.destroy'); // Eliminar un nombre de archivo

// Rutas protegidas: solo usuarios autenticados
Route::middleware(['auth'])->group(function () {

    // Gestión de usuarios (Admin)
    Route::get('/user/users-management', [UserController::class, 'index'])->name('users-management');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/user/{user}/update', [UserController::class, 'update'])->name('users.update');
    Route::put('/user/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Gestión de Carpetas
    Route::get('/folders', [FolderController::class, 'index'])->name('folders.index');
    Route::get('/folders/create', [FolderController::class, 'create'])->name('folders.create');
    Route::post('/folders', [FolderController::class, 'store'])->name('folders.store');
    Route::get('/folders/{folder}/edit', [FolderController::class, 'edit'])->name('folders.edit');
    Route::put('/folders/{folder}', [FolderController::class, 'update'])->name('folders.update');
    Route::delete('/folders/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');

    // Explorador de carpetas
    Route::get('/folders/{folder}', [FolderController::class, 'show'])->name('folders.show');
    // Route::get('/explorer', [FolderController::class, 'explorer'])->name('folders.explorer');
    Route::get('/explorer/{id?}', [FolderController::class, 'explorer'])->name('folders.explorer');


    // Gestión de nombres de archivos
    Route::get('/file_names', [FileNameController::class, 'index'])->name('file_names.index');
    Route::get('/file_names/create', [FileNameController::class, 'create'])->name('file_names.create');
    Route::post('/file_names', [FileNameController::class, 'store'])->name('file_names.store');
    Route::get('/file_names/{fileName}/edit', [FileNameController::class, 'edit'])->name('file_names.edit');
    Route::put('/file_names/{fileName}', [FileNameController::class, 'update'])->name('file_names.update');
    Route::delete('/file_names/{fileName}', [FileNameController::class, 'destroy'])->name('file_names.destroy');

    // Gestión de Archivos
    Route::get('/files', [FileController::class, 'index'])->name('files.index');
    Route::get('/files/create', [FileController::class, 'create'])->name('files.create');
    Route::post('/files', [FileController::class, 'store'])->name('files.store');
    Route::get('/files/{file}', [FileController::class, 'show'])->name('files.show');
    Route::get('/files/{file}/edit', [FileController::class, 'edit'])->name('files.edit');
    Route::put('/files/{file}', [FileController::class, 'update'])->name('files.update');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');
});


