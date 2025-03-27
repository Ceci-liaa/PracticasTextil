<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\File;

class FolderController extends Controller
{

    // Muestras todas las carpetas que existen
    public function index()
    {
        $folders = Folder::with('parent', 'user')->orderBy('created_at', 'asc')->get(); // Ordena por fecha de creación
        return view('folders.folders-management', compact('folders'));
    }

    // Muestra los archivos dentro de la carpeta seleccionada.
    public function show(Folder $folder)
    {
        // Cargar relaciones de subcarpetas y archivos
        $folder->load(['subfolders', 'files']);
    
        return view('folders.show-folder', compact('folder'));
    }    
    
    // Crear una carpeta
    // public function create()
    // {
    //     $folders = Folder::all(); // Obtener todas las carpetas para seleccionar una como padre
    //     return view('folders.create-folder', compact('folders'));
    // }

    public function create()
    {
        $folders = Folder::whereNull('parent_id')->get();
        return view('folders.create-folder', compact('folders'));        
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        // ❌ Evitar nombres duplicados dentro de la misma carpeta padre
        $exists = Folder::where('name', $request->name)
            ->where('parent_id', $request->parent_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Ya existe una carpeta con este nombre en la misma ubicación. Por favor, elija otro nombre.');
        }

        Folder::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('folders.index')->with('success', ' Carpeta creada correctamente.');
    }


    // Editar una carpeta
    public function edit(Folder $folder)
    {
        $folders = Folder::all(); // Para seleccionar una nueva carpeta padre si es necesario
        return view('folders.edit-folder', compact('folder', 'folders'));
    }
    
    public function update(Request $request, Folder $folder)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        // Evitar nombres duplicados dentro de la misma carpeta padre
        $exists = Folder::where('name', $request->name)
            ->where('parent_id', $request->parent_id)
            ->where('id', '!=', $folder->id)
            ->exists();

        if ($exists) {
            return redirect()->route('folders.index')->with('error', 'Ya existe una carpeta con este nombre en la misma ubicación. Por favor, elija otro nombre.');
        }

        // No permitir que la carpeta se seleccione a sí misma como padre
        if ($request->parent_id == $folder->id) {
            return redirect()->route('folders.index')->with('error', 'Una carpeta no puede ser su propio padre.');
        }

        // No permitir que una carpeta padre se mueva dentro de una de sus subcarpetas
        if ($this->isMovingIntoChild($folder->id, $request->parent_id)) {
            return redirect()->route('folders.index')->with('error', 'No puedes mover una carpeta dentro de una de sus subcarpetas.');
        }

        // Actualizar carpeta
        $folder->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        // ✅ Redirigir a la vista de gestión de carpetas con mensaje de éxito
        return redirect()->route('folders.index')->with('success', ' Carpeta actualizada correctamente.');
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

    /**
     * Función que verifica si una carpeta intenta moverse dentro de una de sus subcarpetas
    */
    private function isMovingIntoChild($folderId, $newParentId)
    {
        if (!$newParentId) {
            return false; // Si no hay un nuevo padre, no hay problema
        }

        $parent = Folder::find($newParentId);

        while ($parent) {
            if ($parent->id == $folderId) {
                return true; // Se encontró la carpeta padre en la jerarquía de subcarpetas
            }
            $parent = $parent->parent;
        }

        return false;
    }


    // Explorar carpetas y archivos
    // public function explorer(Folder $folder = null)
    // {
    //     if ($folder) {
    //         $subfolders = Folder::where('parent_id', $folder->id)->get();
    //         $files = collect(); // Agregamos esta línea para evitar el error
    //     } else {
    //         $subfolders = Folder::whereNull('parent_id')->get();
    //         $files = collect();
    //     }
    
    //     return view('folders.explorer', compact('folder', 'subfolders', 'files'));
    // }

    public function explorer($id = null)
    {
        $folder = $id ? Folder::with(['parent'])->find($id) : null;

        if ($id && !$folder) {
            return redirect()->route('folders.explorer')->with('error', 'Carpeta no encontrada.');
        }

        $subfolders = Folder::where('parent_id', $id)->get();

        // 🔄 Agregar "file_name" y "user" para asegurarnos de que los cambios se reflejan
        $files = File::with(['file_name', 'user'])->where('folder_id', $id)->latest()->get();

        return view('folders.explorer', compact('folder', 'subfolders', 'files'));
    }

}
