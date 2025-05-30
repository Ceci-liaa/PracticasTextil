<x-guest-layout>

    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <x-guest.sidenav-guest />
            </div>
        </div>
    </div>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="position-absolute w-40 top-0 start-0 h-100 d-md-block d-none">
                                <div class="oblique-image position-absolute d-flex fixed-top ms-auto h-100 z-index-0 bg-cover me-n8"
                                    style="background-image:url('../assets/img/image-sign-up.jpg')">
                                    <div class="my-auto text-start max-width-350 ms-7">
                                        <h1 class="mt-3 text-white font-weight-bolder">Start your <br> new journey.</h1>
                                        <p class="text-white text-lg mt-4 mb-4">Use these awesome forms to login or
                                            create new account in your project for free.</p>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-group d-flex">
                                                <a href="javascript:;" class="avatar avatar-sm rounded-circle"
                                                    data-bs-toggle="tooltip" data-original-title="Jessica Rowland">
                                                    <img alt="Image placeholder" src="../assets/img/team-3.jpg"
                                                        class="">
                                                </a>
                                                <a href="javascript:;" class="avatar avatar-sm rounded-circle"
                                                    data-bs-toggle="tooltip" data-original-title="Audrey Love">
                                                    <img alt="Image placeholder" src="../assets/img/team-4.jpg"
                                                        class="rounded-circle">
                                                </a>
                                                <a href="javascript:;" class="avatar avatar-sm rounded-circle"
                                                    data-bs-toggle="tooltip" data-original-title="Michael Lewis">
                                                    <img alt="Image placeholder" src="../assets/img/marie.jpg"
                                                        class="rounded-circle">
                                                </a>
                                                <a href="javascript:;" class="avatar avatar-sm rounded-circle"
                                                    data-bs-toggle="tooltip" data-original-title="Audrey Love">
                                                    <img alt="Image placeholder" src="../assets/img/team-1.jpg"
                                                        class="rounded-circle">
                                                </a>
                                            </div>
                                            <p class="font-weight-bold text-white text-sm mb-0 ms-2">Join 2.5M+ users
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-start position-absolute fixed-bottom ms-7">
                                        <h6 class="text-white text-sm mb-5">Copyright © 2022 Corporate UI Design System
                                            by Creative Tim.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex flex-column mx-auto">
                            <div class="card card-plain mt-8">
                                <div class="card-header pb-0 text-left bg-transparent">
                                    <h3 class="font-weight-black text-dark display-6">Registrarse</h3>
                                    <p class="mb-0">Mucho gusto! Por favor ingrese sus datos.</p>
                                </div>
                                <div class="card-body">
                                    <form role="form" method="POST" action="sign-up">
                                        @csrf
                                        <label>Nombres completos</label>
                                        <div class="mb-3">
                                            <input type="text" id="name" name="name" class="form-control"
                                                placeholder="Ingrese su nombre completo" value="{{old("name")}}" aria-label="Name"
                                                aria-describedby="name-addon">
                                            @error('name')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <label>Correo</label>
                                        <div class="mb-3 position-relative">
                                            <input type="email" id="email-register" name="email" class="form-control pe-5"
                                                placeholder="Ingrese su correo"
                                                value="{{ old('email') }}"
                                                aria-label="Email" aria-describedby="email-addon">

                                            <div id="tooltip-email-register" class="tooltip-box card p-2 shadow-sm bg-white text-sm text-danger position-absolute">
                                                Ingresa un correo electrónico válido (ej: nombre@dominio.com)
                                            </div>
                                        </div>



                                        <!--                                         <label>Contraseña</label>
                                        <div class="mb-3">
                                            <input type="password" id="password" name="password" class="form-control"
                                                placeholder="Crea una contraseña" aria-label="Password"
                                                aria-describedby="password-addon">
                                            @error('password')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
 -->
                                        <label>Contraseña</label>
                                        <div class="mb-3 position-relative">
                                            <input type="password" id="password" name="password" class="form-control pe-5"
                                                placeholder="Crea una contraseña" aria-label="Password">

                                            <span class="position-absolute end-0 top-50 translate-middle-y me-3" style="cursor: pointer;" onclick="togglePassword()">
                                                <i id="toggle-icon" class="fas fa-eye text-secondary"></i>
                                            </span>

                                            @error('password')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror

                                            <div id="password-tooltip" class="tooltip-box card p-2 shadow-sm bg-white text-sm position-absolute">
                                                <ul class="mb-0 ps-3">
                                                    <li id="req-length" class="text-danger">Mínimo 8 caracteres</li>
                                                    <li id="req-lower" class="text-danger">Una letra minúscula</li>
                                                    <li id="req-upper" class="text-danger">Una letra mayúscula</li>
                                                    <li id="req-number" class="text-danger">Un número</li>
                                                    <li id="req-symbol" class="text-danger">Un carácter especial (@$!%*?&)</li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="form-check form-check-info text-left mb-0">
                                            <input class="form-check-input" type="checkbox" name="terms"
                                                id="terms" required>
                                            <label class="font-weight-normal text-dark mb-0" for="terms">
                                                Estoy de acuerdo <a href="javascript:;"
                                                    class="text-dark font-weight-bold">Terminos y Condiciones</a>.
                                            </label>
                                            @error('terms')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-dark w-100 mt-4 mb-3">Registrarse</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-xs mx-auto">
                                        ¿Ya tienes una cuenta?
                                        <a href="{{ route('sign-in') }}" class="text-dark font-weight-bold">Iniciar Sesión</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <style>
        .tooltip-box {
            display: none;
            top: calc(100% + 4px);
            left: 0;
            width: 100%;
            z-index: 1050;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }

        .tooltip-box.show {
            display: block;
        }
    </style>

    <script>
        const passwordInput = document.getElementById('password');
        const tooltip = document.getElementById('password-tooltip');

        passwordInput?.addEventListener('focus', () => tooltip.classList.add('show'));
        passwordInput?.addEventListener('blur', () => tooltip.classList.remove('show'));
        passwordInput?.addEventListener('input', updateTooltip);

        function setClass(id, valid) {
            const el = document.getElementById(id);
            el.classList.toggle('text-success', valid);
            el.classList.toggle('text-danger', !valid);
        }

        function updateTooltip() {
            const value = passwordInput.value;
            setClass('req-length', value.length >= 8);
            setClass('req-lower', /[a-z]/.test(value));
            setClass('req-upper', /[A-Z]/.test(value));
            setClass('req-number', /[0-9]/.test(value));
            setClass('req-symbol', /[@$!%*?&]/.test(value));
        }
    </script>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('toggle-icon');
            const isVisible = input.type === 'text';
            input.type = isVisible ? 'password' : 'text';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    </script>
    <style>
        .tooltip-box {
            display: none;
            top: calc(100% + 4px);
            left: 0;
            width: 100%;
            z-index: 1050;
            border: 1px solid #dc3545;
            border-radius: 0.5rem;
        }

        .tooltip-box.show {
            display: block;
        }
    </style>

    <script>
        const emailInputRegister = document.getElementById('email-register');
        const tooltipRegister = document.getElementById('tooltip-email-register');

        function validateEmailRegister() {
            const value = emailInputRegister.value;
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            tooltipRegister.classList.toggle('show', value !== '' && !regex.test(value));
        }

        emailInputRegister?.addEventListener('focus', validateEmailRegister);
        emailInputRegister?.addEventListener('blur', () => tooltipRegister.classList.remove('show'));
        emailInputRegister?.addEventListener('input', validateEmailRegister);
    </script>


</x-guest-layout>