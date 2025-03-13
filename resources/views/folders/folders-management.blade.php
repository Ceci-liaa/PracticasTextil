<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />

        <div class="px-5 py-4 container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-dark text-sm" role="alert">
                        <strong style="font-size: 24px;">Gestión de Carpetas</strong>
                    </div>

                    <a href="{{ route('folders.create') }}" class="btn btn-success mb-3">Nueva Carpeta</a>

                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover table-bordered text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Carpeta Padre</th>
                                        <th>Creado por</th>
                                        <th>Fecha/hora</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($folders as $folder)
                                        <tr>
                                            <td>{{ $folder->id }}</td>
                                            <td>
                                                <a href="{{ route('folders.show', $folder->id) }}">
                                                    📁 {{ $folder->name }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $folder->parent ? $folder->parent->name : 'Raíz' }}
                                            </td>
                                            <td>{{ $folder->user->name }}</td>
                                            <td>{{ $folder->created_at }}</td>
                                            <td>
                                                <a href="{{ route('folders.edit', $folder) }}" class="btn btn-primary btn-sm">Editar</a>
                                                <form action="{{ route('folders.destroy', $folder) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
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

        <x-app.footer />
    </main>
</x-app-layout>
