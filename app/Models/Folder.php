<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// class Folder extends Model
// {
//     use HasFactory;

//     protected $fillable = ['name', 'parent_id', 'user_id'];

//     // Relación recursiva para subcarpetas
//     public function subfolders()
//     {
//         return $this->hasMany(Folder::class, 'parent_id');
//     }

//     // Relación con la carpeta padre
//     public function parent()
//     {
//         return $this->belongsTo(Folder::class, 'parent_id');
//     }

//     // Relación con los archivos dentro de la carpeta
//     public function files()
//     {
//         return $this->hasMany(File::class, 'folder_id');
//     }

//     // Relación con el usuario creador
//     public function user()
//     {
//         return $this->belongsTo(User::class);
//     }


// }

// nuevo 
class Folder extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id', 'user_id'];

    // Relación recursiva para subcarpetas
    public function subfolders()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    // Relación con la carpeta padre
    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    // Relación con los archivos dentro de la carpeta
    public function files()
    {
        return $this->hasMany(File::class, 'folder_id');
    }

    // Relación con el usuario creador
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAncestors()
    {
        $ancestors = collect();
        $folder = $this;

        while ($folder->parent) {
            $ancestors->prepend($folder->parent);
            $folder = $folder->parent;
        }

        return $ancestors;
    }

    public function isChild($folderId)
    {
        $parent = $this->parent;

        while ($parent) {
            if ($parent->id == $folderId) {
                return true; // Es una subcarpeta
            }
            $parent = $parent->parent;
        }

        return false;
    }

    public function getFullPathAttribute()
    {
        $folder = $this;
        $path = [];

        while ($folder) {
            array_unshift($path, $folder->name); // Agrega el nombre al inicio del array
            $folder = $folder->parent; // Mueve al padre
        }

        return implode('\\', $path); // Concatena la ruta con "\"
    }

    public function subfoldersRecursive()
    {
        return $this->subfolders()->with('subfoldersRecursive');
    }


    public function isDescendantOf($folderId)
    {
        $parent = $this->parent;
        while ($parent) {
            if ($parent->id == $folderId) {
                return true;
            }
            $parent = $parent->parent;
        }
        return false;
    }
    
    public function hasDescendant($folderId)
    {
        foreach ($this->subfoldersRecursive as $child) {
            if ($child->id == $folderId || $child->hasDescendant($folderId)) {
                return true;
            }
        }
        return false;
    }

}

