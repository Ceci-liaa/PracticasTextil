<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />

        <div class="px-5 py-4 container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-dark text-sm" role="alert">
                        <strong style="font-size: 24px;">Crear Nueva Carpeta</strong>
                    </div>

                    <!-- 🔹 Mensajes de error y éxito
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show fade-message" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show fade-message" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif -->

                    <!-- 🔹 Mensajes de error y éxito -->
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show fade-message" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show fade-message" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('folders.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Nombre de la Carpeta</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Carpeta Padre (Opcional)</label>
                                        <select name="parent_id" class="form-control">
                                            <option value="">-- Sin Carpeta Padre --</option>
                                            @foreach ($folders as $folder)
                                                <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary mt-4">Crear Carpeta</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            setTimeout(function () {
                document.querySelectorAll('.fade-message').forEach(alert => {
                    alert.style.transition = "opacity 0.5s";
                    alert.style.opacity = 0;
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000); // Desaparece después de 5 segundos
        });
    </script>

</x-app-layout>
