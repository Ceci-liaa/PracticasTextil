<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />

        <div class="px-5 py-4 container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-dark text-sm" role="alert">
                        <strong style="font-size: 24px;">Editar Carpeta</strong>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('folders.update', $folder->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Nombre de la Carpeta</label>
                                        <input type="text" name="name" class="form-control" value="{{ $folder->name }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Carpeta Padre (Opcional)</label>
                                        <select name="parent_id" class="form-control">
                                            <option value="">-- Sin Carpeta Padre --</option>
                                            @foreach ($folders as $parentFolder)
                                                <option value="{{ $parentFolder->id }}" 
                                                    @if($folder->parent_id == $parentFolder->id) selected @endif>
                                                    {{ $parentFolder->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary mt-4">Actualizar Carpeta</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-app.footer />
    </main>
</x-app-layout>
