<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />

        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="alert alert-dark text-sm" role="alert">
                        <strong>Editar Usuario</strong> 
                    </div>
                    <div class="card">
                        <div class="pb-0 card-header">
                            <h5 class="">Gestión de Usuarios</h5>
                            <p class="mb-0 text-sm">
                                Aquí puedes gestionar a los Usuarios.
                            </p>
                        </div>

                        <form action="{{ route('users.update', $user) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Nombre</label>
                                        <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Email</label>
                                        <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label>Teléfono</label>
                                        <input type="text" name="phone" value="{{ $user->phone ?? '' }}" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label>Rol</label>
                                        <select name="role" class="form-control">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}" @if($user->hasRole($role->name)) selected @endif>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label>Estado</label>
                                        <form action="{{ route('users.toggle-status', $user) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn {{ $user->active ? 'btn-success' : 'btn-danger' }}">
                                                {{ $user->active ? 'Activo' : 'Inactivo' }}
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary mt-4">Guardar Cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>
</x-app-layout>
