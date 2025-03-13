<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;

class FolderController extends Controller
{

    // Mostrar carpetas y subcarpetas

    // antiguo
    // public function index()
    // {
    //     $folders = Folder::with('parent', 'user')->get();
    //     return view('folders.folders-management', compact('folders'));
    // }
    
    // Muestras todas las carpetas que existen
    public function index()
    {
        $folders = Folder::with('parent', 'user')->get(); // Obtiene todas las carpetas con sus relaciones
        return view('folders.folders-management', compact('folders'));
    }

    // Muestra los archivos dentro de la carpeta seleccionada.
    public function show($id)
    {
        $folder = Folder::with('subfolders', 'files')->findOrFail($id);
        return view('folders.show-folder', compact('folder'));
    }

    // Crear una carpeta
    public function create()
    {
        $folders = Folder::all(); // Obtener todas las carpetas para seleccionar una como padre
        return view('folders.create-folder', compact('folders'));
    }

    // Guardar una carpeta 
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        Folder::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('folders.index')->with('success', 'Carpeta creada correctamente.');
    }

    // Editar una carpeta
    public function edit(Folder $folder)
    {
        $folders = Folder::all(); // Para seleccionar una nueva carpeta padre si es necesario
        return view('folders.edit-folder', compact('folder', 'folders'));
    }
    
    // Actualizar una carpeta
    public function update(Request $request, Folder $folder)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        $folder->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('folders.index')->with('success', 'Carpeta actualizada correctamente.');
    }

    // Eliminar una carpeta
    public function destroy(Folder $folder)
    {
        try {
            $folder->delete(); // Elimina la carpeta y sus subcarpetas (por el `cascade` en la migración)
            return redirect()->route('folders.index')->with('success', 'Carpeta eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('folders.index')->with('error', 'No se pudo eliminar la carpeta.');
        }
    }

}
