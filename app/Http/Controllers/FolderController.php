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
    // public function edit(Folder $folder)
    // {
    //     $folders = Folder::all(); // Para seleccionar una nueva carpeta padre si es necesario
    //     return view('folders.edit-folder', compact('folder', 'folders'));
    // }

    public function edit($id)
    {
        $folder = Folder::findOrFail($id);
        $folders = Folder::whereNull('parent_id')->get();
        return view('folders.edit-folder', compact('folder', 'folders'));
    }

    // public function update(Request $request, Folder $folder)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'parent_id' => 'nullable|exists:folders,id',
    //     ]);

    //     // Evitar nombres duplicados dentro de la misma carpeta padre
    //     $exists = Folder::where('name', $request->name)
    //         ->where('parent_id', $request->parent_id)
    //         ->where('id', '!=', $folder->id)
    //         ->exists();

    //     if ($exists) {
    //         return redirect()->route('folders.index')->with('error', 'Ya existe una carpeta con este nombre en la misma ubicación. Por favor, elija otro nombre.');
    //     }

    //     // No permitir que la carpeta se seleccione a sí misma como padre
    //     if ($request->parent_id == $folder->id) {
    //         return redirect()->route('folders.index')->with('error', 'Una carpeta no puede ser su propio padre.');
    //     }

    //     // No permitir que una carpeta padre se mueva dentro de una de sus subcarpetas
    //     if ($this->isMovingIntoChild($folder->id, $request->parent_id)) {
    //         return redirect()->route('folders.index')->with('error', 'No puedes mover una carpeta dentro de una de sus subcarpetas.');
    //     }

    //     // Actualizar carpeta
    //     $folder->update([
    //         'name' => $request->name,
    //         'parent_id' => $request->parent_id,
    //     ]);

    //     // ✅ Redirigir a la vista de gestión de carpetas con mensaje de éxito
    //     return redirect()->route('folders.index')->with('success', ' Carpeta actualizada correctamente.');
    // }
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
        return redirect()->back()->withInput()->with('error', 'Ya existe una carpeta con este nombre en la misma ubicación.');
    }

    if ($folder->id == $request->parent_id || $this->isMovingIntoChild($folder->id, $request->parent_id)) {
        return redirect()->route('folders.index')->with('error', 'No puedes mover una carpeta dentro de una de sus subcarpetas.');
    }

    $folder->update([
        'name' => $request->name,
        'parent_id' => $request->parent_id,
    ]);

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
    // private function isMovingIntoChild($folderId, $newParentId)
    // {
    //     if (!$newParentId) {
    //         return false; // Si no hay un nuevo padre, no hay problema
    //     }

    //     $parent = Folder::find($newParentId);

    //     while ($parent) {
    //         if ($parent->id == $folderId) {
    //             return true; // Se encontró la carpeta padre en la jerarquía de subcarpetas
    //         }
    //         $parent = $parent->parent;
    //     }

    //     return false;
    // }
    private function isMovingIntoChild($folderId, $newParentId)
    {
        if (!$newParentId) return false;
        if ($folderId == $newParentId) return true;
        $folder = Folder::with('subfoldersRecursive')->find($folderId);
        return $folder->hasDescendant($newParentId);
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

    // Modificado el 27/3/2025 para poner el buscador de archivos
    // public function explorer($id = null)
    // {
    //     $folder = $id ? Folder::with(['parent'])->find($id) : null;

    //     if ($id && !$folder) {
    //         return redirect()->route('folders.explorer')->with('error', 'Carpeta no encontrada.');
    //     }

    //     $subfolders = Folder::where('parent_id', $id)->get();

    //     // 🔄 Agregar "file_name" y "user" para asegurarnos de que los cambios se reflejan
    //     $files = File::with(['file_name', 'user'])->where('folder_id', $id)->latest()->get();

    //     return view('folders.explorer', compact('folder', 'subfolders', 'files'));
    // }

    public function explorer(Request $request, $id = null)
    {
        $search = $request->input('search');
        $folder = $id ? Folder::with('parent')->find($id) : null;
    
        if ($id && !$folder) {
            return redirect()->route('folders.explorer')->with('error', 'Carpeta no encontrada.');
        }
    
        $folderIds = [$id];
        if ($search && $id !== null) {
            $folderIds = array_merge($folderIds, $this->getAllDescendantFolderIds($id));
        }
    
        // Carpetas hijas inmediatas o por búsqueda
        $querySubfolders = Folder::where('parent_id', $id);
        if ($search) {
            $querySubfolders = Folder::whereIn('parent_id', $folderIds)
                ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
        }
    
        $subfolders = $querySubfolders->get();
    
        // Archivos
        $queryFiles = File::with(['file_name', 'user'])->whereIn('folder_id', $folderIds);
    
        if ($search) {
            $queryFiles->where(function ($q) use ($search) {
                $search = strtolower($search);
    
                $q->whereRaw("LOWER(prefix || ' ' || COALESCE((SELECT name FROM file_names WHERE id = file_name_id), '') || ' ' || suffix) LIKE ?", ["%{$search}%"]);
            });
        }
    
        $files = $queryFiles->latest()->get();
    
        return view('folders.explorer', compact('folder', 'subfolders', 'files'));
    }    
    
    public function getSubfolders(Request $request)
    {
        $parentId = $request->input('parent_id');
        $currentFolderId = $request->input('current_folder_id');
        $folder = Folder::with('subfoldersRecursive')->find($currentFolderId);
    
        $subfolders = Folder::where('parent_id', $parentId)->get();
    
        $filtered = $subfolders->reject(function ($f) use ($folder) {
            return $folder->id === $f->id || $folder->hasDescendant($f->id);
        })->values();
    
        return response()->json($filtered);
    }

// Función para obtener IDs de todas las subcarpetas recursivamente
    private function getAllDescendantFolderIds($parentId)
    {
        $ids = Folder::where('parent_id', $parentId)->pluck('id')->toArray();
        foreach ($ids as $childId) {
            $ids = array_merge($ids, $this->getAllDescendantFolderIds($childId));
        }
        return $ids;
    }

    // Función para obtener sugerencias de búsqueda
    public function searchSuggestions(Request $request)
    {
        $term = $request->input('term');
    
        // Asegurar que no sea nulo o completamente vacío (pero permitir "0")
        if (!isset($term) || trim($term) === '') {
            return response()->json([]);
        }
    
        $term = strtolower($term); // Convertir para comparación insensible a mayúsculas
        $results = [];
    
        // 🔍 Buscar carpetas
        $folderMatches = Folder::whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
            ->limit(5)
            ->get();
    
        foreach ($folderMatches as $folder) {
            $results[] = [
                'label' => '📁 ' . $folder->name,
                'value' => $folder->name,
                'url' => route('folders.explorer', $folder->id)
            ];
        }
    
        // 🔍 Buscar archivos por nombre completo
        $fileMatches = File::with('file_name')
            ->get()
            ->filter(function ($file) use ($term) {
                $nombreCompleto = strtolower(trim(
                    ($file->prefix ? $file->prefix . ' ' : '') .
                    ($file->file_name->name ?? '') .
                    ($file->suffix ? ' ' . $file->suffix : '')
                ));
                return str_contains($nombreCompleto, $term);
            });
    
        foreach ($fileMatches as $file) {
            $nombreCompleto = trim(
                ($file->prefix ? $file->prefix . ' ' : '') .
                ($file->file_name->name ?? '') .
                ($file->suffix ? ' ' . $file->suffix : '')
            );
    
            $results[] = [
                'label' => '📄 ' . $nombreCompleto,
                'value' => $nombreCompleto,
                'url' => route('files.show', ['file' => $file->id, 'from' => 'explorer'])
            ];
        }
    
        // ⚠️ Si no hay resultados
        if (empty($results)) {
            $results[] = [
                'label' => '❌ Carpeta o archivo no encontrado',
                'value' => '',
                'url' => null
            ];
        }
    
        return response()->json($results);
    }
    
}
