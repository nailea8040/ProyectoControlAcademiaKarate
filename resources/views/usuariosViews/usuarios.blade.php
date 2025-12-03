<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="/css/estilo3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <nav class="menu">...</nav>
    <div class="main-content">
        <header>
            <h1>Sistema de Gestión de Dojo</h1>
        </header>

        <div class="content">

            @if(session('mensaje'))
                <div id="alerta-temp" class="alert {{ session('sessionInsertado') == 'true' ? 'alert-success' : 'alert-danger' }} text-center" role="alert">
                    {{ session('mensaje') }}
                </div>
            @endif

            <form id="registroForm" class="form-registro" action="{{ route('usuarios.store') }}" method="POST">
                @csrf
                <div style="text-align: center;">
                    <h2 style="color: #111;">Registro de Usuario</h2>
                </div>

                <input type="text" id="nombre" name="nombre" placeholder="Nombre(s)" required value="{{ old('nombre') }}">
                <input type="text" id="apaterno" name="apaterno" placeholder="Apellido Paterno" required value="{{ old('apaterno') }}">
                <input type="text" id="amaterno" name="amaterno" placeholder="Apellido Materno" required value="{{ old('amaterno') }}">
                <input type="date" id="fecha_naci" name="fecha_naci" required value="{{ old('fecha_naci') }}">
                <input type="text" id="tel" name="tel" placeholder="Teléfono (10 dígitos)" maxlength="20" required value="{{ old('tel') }}">
                <input type="email" id="correo" name="correo" placeholder="Correo electrónico" required value="{{ old('correo') }}">
                <input type="password" id="pass" name="pass" placeholder="Contraseña (mín. 6 caracteres)" minlength="6" required value="{{ old('pass') }}">

                <select id="rol" name="rol" required>
                    <option value="">Selecciona tipo de usuario</option>
                    <option value="administrador">Administrador</option>
                    <option value="sensei">Sensei</option>
                    <option value="tutor">Tutor</option>
                    <option value="alumno">Alumno</option>
                </select>

                <input type="date" id="fecha_registro" name="fecha_registro" required>

                <button type="submit">Registrar</button>
            </form>

            <div class="table-responsive mt-5">
                <h2 class="text-center text-dark mb-4">Usuarios Registrados</h2>
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Fecha Nac.</th>
                            <th>Fecha Reg.</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->nombre }}</td>
                                <td>{{ $usuario->apaterno }}</td>
                                <td>{{ $usuario->amaterno }}</td>
                                <td>{{ $usuario->correo }}</td>
                                <td>{{ $usuario->tel }}</td>
                                <td>{{ $usuario->rol }}</td>
                                <td>{{ $usuario->fecha_naci }}</td>
                                <td>{{ $usuario->fecha_registro }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <footer class="footer">
            <p>© 2025 Sistema de Gestión del Dojo</p>
        </footer>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        @if(session('sessionInsertado'))
            const icono = '{{ session('sessionInsertado') == 'true' ? 'success' : 'error' }}';
            const titulo = '{{ session('mensaje') }}';
            
            Swal.fire({
                icon: icono,
                title: titulo,
                showConfirmButton: false,
                timer: 2000
            });
            // Ocultar el div de alerta temporal después de mostrar SweetAlert
            document.getElementById('alerta-temp').style.display = 'none';
        @endif
    </script>
</body>
</html>