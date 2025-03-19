<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name_id',
        'name_original',
        'type',
        'folder_id',
        'user_id'
    ];



    // Relaciones
    public function file_name()
    {
        return $this->belongsTo(FileName::class, 'file_name_id'); // Verifica que la clave foránea sea la correcta
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullPathAttribute()
    {
        $folder = $this->folder;
        $path = [];
    
        while ($folder) {
            array_unshift($path, $folder->name); // Agregar al inicio del array
            $folder = $folder->parent;
        }
    
        // Si el archivo está en una carpeta padre, mostrar solo su nombre
        return count($path) === 1 ? $path[0] : implode('\\', $path);
    }
       

}
