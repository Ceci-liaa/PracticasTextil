<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileName;
use App\Models\Folder;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;



class FileController extends Controller
{
    public function index()
    {
    // Cargar archivos sin caché para asegurar que los cambios sean visibles en ambas interfaces
        $files = File::with('file_name', 'user')->latest()->paginate(10);

        return view('files.index', compact('files'));
    }

    // public function create()
    // {
    //     $fileNames = FileName::all();
    //     $folders = Folder::all();

    //     return view('files.create', compact('fileNames', 'folders'));
    // }
    public function create(Request $request)
    {
        $currentFolderId = $request->input('folder_id', null);
        $currentFolder = Folder::find($currentFolderId);
    
        // Obtener subcarpetas de la carpeta actual
        $folders = Folder::where('parent_id', $currentFolderId)->get();
        $fileNames = FileName::all();
    
        // Asegurar que $breadcrumb sea una colección de Laravel
        $breadcrumb = collect(); 
        if ($currentFolder) {
            $breadcrumb = collect($currentFolder->getAncestors())->map(function ($folder) {
                return ['id' => $folder->id, 'name' => $folder->name];
            });
    
            $breadcrumb->push(['id' => $currentFolder->id, 'name' => $currentFolder->name]);
        }
    
        return view('files.create', compact('folders', 'fileNames', 'breadcrumb', 'currentFolderId'));
    }    
    
    public function store(Request $request)
{
    $request->validate([
        'uploaded_file' => 'required|file|max:5120', // max 5MB
        'file_name_id' => 'required|exists:file_names,id',
        'folder_id' => 'required|exists:folders,id',
    ]);

    $uploadedFile = $request->file('uploaded_file');
    $originalName = $uploadedFile->getClientOriginalName();
    $extension = $uploadedFile->getClientOriginalExtension();
    
    // 🔹 Guardar el archivo con su nombre original en `storage/app/public/files/`
    $filePath = $uploadedFile->storeAs('public/files', $originalName);

    // 🔹 Guardar en la base de datos
    File::create([
        'file_name_id' => $request->file_name_id,
        'name_original' => $originalName,
        'type' => strtoupper($extension),
        'folder_id' => $request->folder_id,
        'user_id' => auth()->id(),
    ]);

    return redirect()->route('files.index');
}
    
    public function show(File $file, Request $request)
    {
        return view('files.show', compact('file'))
            ->with('from', $request->input('from'));
    }

    public function destroy(Request $request, File $file)
    {
        $folderId = $file->folder_id;
        $file->delete();
    
        return $request->input('from') === 'explorer'
            ? redirect()->route('folders.explorer', ['id' => $folderId])->with('success', 'Archivo eliminado correctamente')
            : redirect()->route('files.index')->with('success', 'Archivo eliminado correctamente');
    }    

    public function edit(File $file)
    {
        $fileNames = FileName::all();
        $folders = Folder::all();

        return view('files.edit', compact('file', 'fileNames', 'folders'));
    }      
    
    public function update(Request $request, File $file)
    {
        // dd($request->all()); // Verificar los datos enviados

        $request->validate([
            'file_name_id' => 'required|exists:file_names,id',
            'folder_id' => 'required|exists:folders,id',
        ]);
    
        // 🔄 Actualizar los datos manualmente
        $file->file_name_id = $request->file_name_id;
        $file->folder_id = $request->folder_id;
        $file->save();
    
        // 🔹 Verificar si el parámetro "from" fue enviado y redirigir correctamente
        if ($request->has('from') && $request->input('from') === 'explorer') {
            return redirect()->route('folders.explorer', ['id' => $file->folder_id])
                             ->with('success', 'Archivo actualizado correctamente.');
        }
    
        // 🔹 Si "from" no se envió correctamente, por defecto, se queda en Gestión de Archivos
        return redirect()->route('files.index')->with('success', 'Archivo actualizado correctamente.');
    }    

public function download(File $file)
{
    // 🔹 Si el archivo está en la nube, redirigir a su URL
    if ($file->storage_url) {
        return redirect($file->storage_url);
    }

    // 🔹 Buscar el archivo en `storage/app/public/files/`
    $filePath = storage_path('app/public/files/' . $file->name_original);

    // 🔹 Verificar si el archivo existe en la ruta correcta
    if (!file_exists($filePath)) {
        return redirect()->back()->with('error', 'El archivo no existe en el servidor.');
    }

    // 🔹 Descargar el archivo con el nombre predefinido y su extensión correcta
    return response()->download($filePath, $file->file_name->name . '.' . $file->type);
}

    // probar que datos no mas estan mostrandose en la vista
//     public function download(File $file)
// {
//     dd([
//         'file_id' => $file->id,
//         'file_name_original' => $file->name_original,
//         'file_name_predefined' => $file->file_name->name,
//         'file_type' => $file->type,
//         'file_path' => storage_path('app/public/files/' . $file->name_original),
//         'file_exists' => file_exists(storage_path('app/public/files/' . $file->name_original)) ? 'Sí' : 'No'
//     ]);
// }
}
