<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />

        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="alert alert-dark text-sm" role="alert">
                        <strong>Editar, Asignar Roles y Cambiar Estado de Usuarios</strong> 
                    </div>
                    <div class="card">
                        <div class="pb-0 card-header">
                            <h5 class="">Gestión de Usuarios</h5>
                            <p class="mb-0 text-sm">
                                Aquí puedes gestionar a los Usuarios.
                            </p>
                        </div>

                        <div class="table-responsive">
                            <table class="table text-secondary text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Rol</th>
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
                                            <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                                            <td>
                                                <form action="{{ route('users.toggle-status', $user) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm {{ $user->active ? 'btn-success' : 'btn-danger' }}">
                                                        {{ $user->active ? 'Activo' : 'Inactivo' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">Editar</a>
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
        <x-app.footer />
    </main>
</x-app-layout>
