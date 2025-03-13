<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('file_name_id'); // Relación con nombres predefinidos
            $table->string('name_original'); // Nombre original del archivo
            $table->string('type', 50); // Tipo de archivo (PDF, JPG, PNG, etc.)
            $table->unsignedBigInteger('folder_id'); // Relación con carpeta
            $table->unsignedBigInteger('user_id'); // Usuario que subió el archivo
            $table->timestamps();

            // Relaciones
            $table->foreign('file_name_id')->references('id')->on('file_names')->onDelete('restrict');
            $table->foreign('folder_id')->references('id')->on('folders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
