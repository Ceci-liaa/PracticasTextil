<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileName;
use App\Models\Folder;
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

    public function create()
    {
        $fileNames = FileName::all();
        $folders = Folder::all();

        return view('files.create', compact('fileNames', 'folders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'uploaded_file' => 'required|file|max:5120', // max 5MB por ejemplo
            'file_name_id' => 'required|exists:file_names,id',
            'folder_id' => 'required|exists:folders,id',
        ]);
    
        $uploadedFile = $request->file('uploaded_file');
        $originalName = $uploadedFile->getClientOriginalName();
        $extension = $uploadedFile->getClientOriginalExtension();
    
        // Aquí decides dónde almacenar físicamente tu archivo
        $uploadedFile->store('public/files');
    
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

    // public function update(Request $request, File $file)
    // {
    //     // 🔹 Validación correcta
    //     $request->validate([
    //         'file_name_id' => 'required|exists:file_names,id',
    //         'type' => 'required|string|max:50',
    //     ]);
    
    //     $file->update($request->all());
    
    //     // 🔄 Recargar la relación file_name para reflejar el cambio
    //     $file->refresh();
    //     $file->load('file_name');
    
    //     return $request->input('from') === 'explorer'
    //         ? redirect()->route('folders.explorer', ['id' => $file->folder_id])->with('success', 'Archivo actualizado correctamente')
    //         : redirect()->route('files.index')->with('success', 'Archivo actualizado correctamente');
    // }        
    
    public function update(Request $request, File $file)
    {
        $request->validate([
            'file_name_id' => 'required|exists:file_names,id',
            'folder_id' => 'required|exists:folders,id',
        ]);

        // 🔄 Asignar valores manualmente
        $file->file_name_id = $request->file_name_id;
        $file->folder_id = $request->folder_id;
        $file->save(); // Guardar cambios en la base de datos

        // 🔄 Refrescar el modelo para asegurarnos de que muestra los datos correctos
        $file->refresh();

        return redirect()->route('folders.explorer', ['id' => $file->folder_id])
            ->with('success', 'Archivo actualizado correctamente.');
    }

}
