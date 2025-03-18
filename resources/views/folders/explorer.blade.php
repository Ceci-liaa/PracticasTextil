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

                    <!-- 📁 Carpetas dentro de la carpeta actual -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">📁 Carpetas</h5>
                            <div class="row">
                                @foreach ($subfolders as $subfolder)
                                    <div class="col-md-3">
                                        <div class="card text-center p-3 folder-card">
                                            <a href="{{ route('folders.explorer', ['id' => $subfolder->id]) }}" class="text-decoration-none">
                                                <i class="fas fa-folder fa-4x folder-icon"></i>
                                                <p class="mt-2 folder-name">{{ $subfolder->name }}</p>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- 📄 Archivos dentro de la carpeta actual (formato tabla) -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-3">📄 Archivos</h5>

                            @if ($files->isEmpty())
                                <p class="text-center">📂 No hay archivos en esta carpeta.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered text-center">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre Original</th>
                                                <th>Tipo</th>
                                                <th>Usuario</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($files as $file)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $file->name_original }}</td>
                                                    <td>{{ $file->type }}</td>
                                                    <td>{{ $file->user->name }}</td>
                                                    <td>
                                                        <a href="{{ route('files.show', $file) }}" class="btn btn-sm btn-info">Ver</a>
                                                        <a href="{{ route('files.edit', $file) }}" class="btn btn-sm btn-warning">Editar</a>

                                                        <!-- Botón de eliminar -->
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="confirmDelete('{{ $file->id }}', '{{ $file->name_original }}')">
                                                            🗑 Eliminar
                                                        </button>

                                                        <!-- Formulario oculto para eliminar -->
                                                        <form id="delete-form-{{ $file->id }}" 
                                                            action="{{ route('files.destroy', $file->id) }}" 
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-app.footer />
    </main>

    <!-- ✅ Estilos personalizados (manteniendo el diseño original de carpetas) -->
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

    <!-- ✅ Script para eliminar archivos con confirmación -->
    <script>
        function confirmDelete(fileId, fileName) {
            Swal.fire({
                title: "¿Eliminar archivo?",
                text: `¿Está seguro de que desea eliminar el archivo "${fileName}"? Esta acción no se puede deshacer.`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${fileId}`).submit();
                }
            });
        }
    </script>

</x-app-layout>
