<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />

        <div class="px-5 py-4 container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-dark text-sm d-flex align-items-center justify-content-between" role="alert">
                        <strong style="font-size: 24px;">📂 Explorador de Archivos</strong>

                        <!-- 🔹 Breadcrumb de navegación -->
                        <span class="breadcrumb-container">
                            <strong>Ubicación:</strong>
                            @if ($folder)
                                <a href="{{ route('folders.explorer') }}">Inicio</a>
                                @foreach ($folder->getAncestors() as $ancestor)
                                    ➤ <a href="{{ route('folders.explorer', $ancestor->id) }}">{{ $ancestor->name }}</a>
                                @endforeach
                                ➤ <span class="current-folder">{{ $folder->name }}</span>
                            @else
                                <span class="current-folder">Inicio</span>
                            @endif
                        </span>
                    </div>

                    <!-- Botón para volver atrás -->
                    @if ($folder && $folder->parent_id)
                        <a href="{{ route('folders.explorer', $folder->parent_id) }}" class="btn btn-secondary mb-3">⬅ Volver</a>
                    @else
                        <a href="{{ route('folders.explorer') }}" class="btn btn-secondary mb-3">🏠 Volver a Inicio</a>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-3">📁 Carpetas</h5>
                            <div class="row">
                                @foreach ($subfolders as $subfolder)
                                    <div class="col-md-3">
                                        <div class="card text-center p-3 folder-card">
                                            <a href="{{ route('folders.explorer', $subfolder->id) }}" class="text-decoration-none">
                                                <i class="fas fa-folder fa-4x folder-icon"></i>
                                                <p class="mt-2 folder-name">{{ $subfolder->name }}</p>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-app.footer />
    </main>

    <!-- Estilos personalizados -->
    <style>
        .breadcrumb-container {
            font-size: 11px;
        }

        .breadcrumb-container a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .breadcrumb-container a:hover {
            text-decoration: underline;
        }

        .current-folder {
            font-weight: bold;
            color: #333;
        }

        .folder-card {
            background-color: #fff9c4; /* Fondo amarillo claro */
            border: 1px solid #ffeb3b;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .folder-card:hover {
            background-color: #ffe082; /* Color más intenso al pasar el mouse */
            transform: scale(1.05);
        }

        .folder-icon {
            color: #fbc02d; /* Amarillo intenso */
        }

        .folder-name {
            font-weight: bold;
            color: #795548; /* Marrón oscuro */
        }
    </style>

</x-app-layout>
