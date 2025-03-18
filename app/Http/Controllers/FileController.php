<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileName;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class FileController extends Controller
{
    public function index()
    {
        $files = File::with('user')->latest()->paginate(10);

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
    
    public function show(File $file)
    {
        $file->load('user', 'file_name', 'folder');
    
        return view('files.show', compact('file'));
    }    

    public function destroy(File $file)
    {
        // Opcional: eliminar el archivo físicamente del almacenamiento (si aplica)
        Storage::delete('public/files/' . $file->name_original);

        $file->delete();

        return redirect()->route('files.index')->with('success', 'Archivo eliminado exitosamente.');
    }

    public function edit(File $file)
    {
        $fileNames = FileName::all();
        $folders = Folder::all();

        return view('files.edit', compact('file', 'fileNames', 'folders'));
    }

    public function update(Request $request, File $file)
    {
        $request->validate([
            'file_name_id' => 'required|exists:file_names,id',
            'folder_id' => 'required|exists:folders,id',
        ]);

        $file->update([
            'file_name_id' => $request->file_name_id,
            'folder_id' => $request->folder_id,
        ]);

        return redirect()->route('files.index')->with('success', 'Archivo actualizado correctamente.');
    }

}
