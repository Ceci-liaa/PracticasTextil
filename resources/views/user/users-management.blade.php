<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />

        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <!-- 🔹 TÍTULO SIEMPRE VISIBLE -->
                    <div class="alert alert-dark text-sm" role="alert" style="margin-bottom: 1rem;">
                        <strong style="font-size: 24px;">Gestión de Usuarios</strong>
                    </div>

                    <!-- 🔹 MENSAJES TEMPORALES QUE SE OCULTAN DESPUÉS DE 3 SEGUNDOS -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show alert-temporal" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="alert alert-warning alert-dismissible fade show alert-temporal" role="alert">
                            {{ session('info') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show alert-temporal" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="card">
                        <div class="pb-0 card-header">
                            <h5 class="">Usuarios</h5>
                        </div>

                        <!-- 🔹 Hacemos la tabla 100% responsiva -->
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table class="table table-hover table-bordered text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Ubicación</th> <!-- Nueva columna -->
                                        <th>Rol</th> <!-- Se mantiene el rol -->
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone ?? 'N/A' }}</td>
                                            <td>{{ $user->location ?? 'No especificada' }}</td> <!-- Nueva fila -->
                                            <td>
                                                @if($user->roles->isNotEmpty())
                                                    {{ $user->roles->pluck('name')->implode(', ') }}
                                                @else
                                                    <span class="text-danger">Sin Rol</span>
                                                @endif
                                            </td>

                                            <!-- Se obtiene el rol del usuario -->
                                            <td>
                                                <div class="p-1 text-white rounded" 
                                                     style="background-color: {{ $user->status ? '#28a745' : '#dc3545' }};">
                                                    {{ $user->status ? 'Activo' : 'Inactivo' }}
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">
                                                    Editar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- Fin de la tabla responsiva -->
                    </div>
                </div>
            </div>
        </div>
        
        <x-app.footer />

        <!-- 🔹 Script para ocultar SOLO los mensajes después de 3 segundos -->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                setTimeout(function () {
                    document.querySelectorAll('.alert-temporal').forEach(alert => {
                        alert.style.transition = "opacity 0.5s";
                        alert.style.opacity = 0;
                        setTimeout(() => alert.remove(), 500);
                    });
                }, 3000);
            });
        </script>

    </main>
</x-app-layout>
