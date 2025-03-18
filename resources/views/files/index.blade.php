<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />

        <div class="px-5 py-4 container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-dark text-sm" role="alert">
                        <strong style="font-size: 24px;">Gestión de Archivos</strong>
                    </div>

                    <!-- ✅ Mensaje de éxito -->
                    @if (session('success'))
                        <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <a href="{{ route('files.create') }}" class="btn btn-primary mb-3">Subir nuevo archivo</a>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered text-center">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre original</th>
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

                            <!-- Paginación -->
                            <div class="mt-4">
                                @if ($files->hasPages())
                                    <nav>
                                        <ul class="pagination justify-content-center">
                                            {{-- Botón "Anterior" --}}
                                            @if ($files->onFirstPage())
                                                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $files->previousPageUrl() }}" rel="prev">&laquo;</a>
                                                </li>
                                            @endif

                                            {{-- Número de páginas --}}
                                            @foreach ($files->getUrlRange(1, $files->lastPage()) as $page => $url)
                                                @if ($page == $files->currentPage())
                                                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                                @else
                                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                                @endif
                                            @endforeach

                                            {{-- Botón "Siguiente" --}}
                                            @if ($files->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $files->nextPageUrl() }}" rel="next">&raquo;</a>
                                                </li>
                                            @else
                                                <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                                            @endif
                                        </ul>
                                    </nav>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-app.footer />
    </main>

    <!-- ✅ Script para ocultar el mensaje después de 5 segundos -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            setTimeout(function () {
                let successMessage = document.getElementById('success-message');
                if (successMessage) {
                    successMessage.style.transition = "opacity 0.5s";
                    successMessage.style.opacity = 0;
                    setTimeout(() => successMessage.remove(), 500);
                }
            }, 5000);
        });

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
