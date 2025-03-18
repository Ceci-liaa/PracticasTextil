<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FileName;


class FileNameController extends Controller
{
    // Mostrar todos los nombres de archivos permitidos
    public function index()
    {
        $fileNames = FileName::all();
        return view('file-names.filename-index', compact('fileNames')); 
    }

    // Mostrar el formulario para crear un nuevo nombre de archivo
    public function create()
    {
        return view('file-names.filename-create');
    }

    // Guardar un nuevo nombre de archivo
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:file_names,name',
        ], [
            'name.unique' => ' Este nombre de archivo ya existe. Elige otro.',
            'name.required' => ' El campo "Nombre del Archivo" es obligatorio.',
        ]);

        FileName::create([
            'name' => $request->name,
        ]);

        return redirect()->route('file_names.index')->with('success', ' Nombre de archivo creado correctamente.');
    }



    // Mostrar formulario para editar un nombre de archivo
    public function edit(FileName $fileName)
    {
        return view('file-names.filename-edit', compact('fileName'));
    }

    // Actualizar un nombre de archivo
    public function update(Request $request, FileName $fileName)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:file_names,name,' . $fileName->id,
        ], [
            'name.unique' => ' Este nombre de archivo ya existe. Elige otro.',
            'name.required' => ' El campo "Nombre del Archivo" es obligatorio.',
        ]);
    
        $fileName->update([
            'name' => $request->name,
        ]);
    
        return redirect()->route('file_names.index')->with('success', ' Nombre de archivo actualizado correctamente.');
    }
    
    // Eliminar un nombre de archivo
    public function destroy(FileName $fileName)
    {
        $fileName->delete();
        return redirect()->route('file_names.index')->with('success', ' Nombre de archivo eliminado correctamente.');
    }
}
