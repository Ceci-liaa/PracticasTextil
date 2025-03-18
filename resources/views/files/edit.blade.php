<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />

        <div class="px-5 py-4 container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-dark text-sm" role="alert">
                        <strong style="font-size: 24px;">Editar Archivo: {{ $file->name_original }}</strong>
                    </div>

                    <div class="card">
                        <div class="card-body">
                        <form action="{{ route('files.update', $file) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Nombre Predefinido:</label>
                                <select name="file_name_id" class="form-select" required>
                                    @foreach ($fileNames as $fileName)
                                        <option value="{{ $fileName->id }}" {{ $file->file_name_id == $fileName->id ? 'selected' : '' }}>
                                            {{ $fileName->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Carpeta:</label>
                                <select name="folder_id" class="form-select" required>
                                    @foreach ($folders as $folder)
                                        <option value="{{ $folder->id }}" {{ $file->folder_id == $folder->id ? 'selected' : '' }}>
                                            {{ $folder->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Actualizar Archivo</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancelar</a>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-app.footer />
    </main>
</x-app-layout>
