<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
