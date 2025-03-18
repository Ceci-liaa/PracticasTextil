<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />

        <div class="px-5 py-4 container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-dark text-sm" role="alert">
                        <strong style="font-size: 24px;">Gestión de Nombres de Archivos Permitidos</strong>
                    </div>

                    <!-- ✅ Mensajes de éxito y error -->
                    @if (session('success'))
                        <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div id="error-message" class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <a href="{{ route('file_names.create') }}" class="btn btn-success mb-3">Nuevo Nombre de Archivo</a>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered text-center">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th> <!-- Número secuencial -->
                                            <th>Nombre</th>
                                            <th>Última Modificación</th> <!-- Nueva columna -->
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($fileNames as $fileName)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td> <!-- 🔹 Muestra un número secuencial dinámico -->
                                                <td>{{ $fileName->name }}</td>
                                                <td>{{ $fileName->updated_at->format('d/m/Y H:i:s') }}</td> <!-- 🔹 Fecha y hora de actualización -->
                                                <td>
                                                    <a href="{{ route('file_names.edit', $fileName->id) }}" class="btn btn-primary btn-sm">Editar</a>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $fileName->id }}', '{{ $fileName->name }}')">🗑 Eliminar</button>

                                                    <form id="delete-form-{{ $fileName->id }}" action="{{ route('file_names.destroy', $fileName->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-app.footer />
    </main>

    <!-- ✅ Script para ocultar los mensajes después de 5 segundos -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            setTimeout(function () {
                let successMessage = document.getElementById('success-message');
                if (successMessage) {
                    successMessage.style.transition = "opacity 0.5s";
                    successMessage.style.opacity = 0;
                    setTimeout(() => successMessage.remove(), 500);
                }

                let errorMessage = document.getElementById('error-message');
                if (errorMessage) {
                    errorMessage.style.transition = "opacity 0.5s";
                    errorMessage.style.opacity = 0;
                    setTimeout(() => errorMessage.remove(), 500);
                }
            }, 5000);
        });

        function confirmDelete(id, name) {
            Swal.fire({
                title: "¿Eliminar nombre de archivo?",
                text: `¿Está seguro de que desea eliminar "${name}"? Esta acción no se puede deshacer.`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>
</x-app-layout>
