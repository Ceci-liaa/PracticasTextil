<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileName extends Model
{
    use HasFactory;

    protected $table = 'file_names'; // Asegura que apunta a la tabla correcta

    protected $fillable = ['name', 'activo']; // Campos que se pueden asignar masivamente
}
