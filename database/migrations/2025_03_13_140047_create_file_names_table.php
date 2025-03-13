<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('file_names', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nombre permitido para archivos
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_names');
    }
};
