<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />

        <div class="px-5 py-4 container-fluid">
            <div class="row">
                <div class="col-12">
                        @if (session('success'))
                            <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show fade-message" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

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

                    <!-- Botón para volver atrás y subir archivo -->
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            @if ($folder && $folder->parent_id)
                                <a href="{{ route('folders.explorer', $folder->parent_id) }}" class="btn btn-secondary">⬅ Volver</a>
                            @else
                                <a href="{{ route('folders.explorer') }}" class="btn btn-secondary">🏠 Volver a Inicio</a>
                            @endif
                        </div>
                            <!-- Buscador centrado -->
                            <form method="GET" action="{{ route('folders.explorer', $folder ? $folder->id : null) }}" class="flex-grow-1 mx-2" style="max-width: 500px;">
                                <div class="input-group">
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar archivos o carpetas...">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </form>
                        <div>
                            <a href="{{ route('files.create', ['folder_id' => $folder ? $folder->id : null, 'from' => 'explorer']) }}" class="btn btn-success mb-3">📤 Subir Archivo</a>
                        </div>
                    </div>

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
                                                <th>Nombre</th>
                                                <th>Tipo</th>
                                                <th>Usuario</th>
                                                <th>Fecha Subida</th>
                                                <th>Fecha Modificación</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($files as $file)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td><strong>{{ $file->file_name?->name ?? 'Sin nombre' }}</strong></td>
                                                    <td>{{ $file->type }}</td>
                                                    <td>{{ $file->user->name }}</td>
                                                    <td>{{ $file->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>{{ $file->updated_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('files.show', ['file' => $file, 'from' => 'explorer']) }}" class="btn btn-sm btn-info"><i class="fa-solid fa-eye" style="font-size: 0.75rem;"></i> </a>
                                                        <a href="{{ route('files.edit', ['file' => $file, 'from' => 'explorer']) }}" class="btn btn-sm btn-warning"> <i class="fa-solid fa-pen-to-square" style="font-size: 0.75rem;"></i> <!-- Icono de editar --> </a>
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="confirmDelete('{{ $file->id }}', '{{ $file->file_name?->name ?? 'Sin nombre' }}')">
                                                            <i class="fa-solid fa-trash" style="font-size: 0.75rem;"></i> <!-- Icono de eliminar -->  
                                                        </button>
                                                        <form id="delete-form-{{ $file->id }}" 
                                                            action="{{ route('files.destroy', $file->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="from" value="explorer">
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

    <!-- ✅ Estilos personalizados -->
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
            background-color: #fff9c4;
            border: 1px solid #ffeb3b;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .folder-card:hover {
            background-color: #ffe082;
            transform: scale(1.05);
        }

        .folder-icon {
            color: #fbc02d;
        }

        .folder-name {
            font-weight: bold;
            color: #795548;
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

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
$(function() {
    $('input[name="search"]').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "{{ route('folders.suggestions') }}",
                data: { term: request.term },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            window.location.href = ui.item.url;
        }
    });
});
</script>


</x-app-layout>
