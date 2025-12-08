<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/estilo2.css') }}">
</head>
<body>
    @include('includes.menu') 
    
    <div class="main-content">
        <header>
            <h1>Gestión de Usuarios</h1>
        </header>

        <div class="content">

            @if(session('mensaje'))
                <div id="alerta-temp" class="alert {{ session('sessionInsertado') == 'true' ? 'alert-success' : 'alert-danger' }} text-center" role="alert">
                    {{ session('mensaje') }}
                </div>
            @endif

            <form id="registroForm" class="form-registro" action="{{ route('usuarios.store') }}" method="POST">
                @csrf
                <h2>Registro de Usuario</h2>

                <input type="text" id="nombre" name="nombre" placeholder="Nombre(s)" required value="{{ old('nombre') }}">
                
                <input type="text" id="apaterno" name="apaterno" placeholder="Apellido Paterno" required value="{{ old('apaterno') }}">
                
                <input type="text" id="amaterno" name="amaterno" placeholder="Apellido Materno" required value="{{ old('amaterno') }}">
                
                <input type="date" id="fecha_naci" name="fecha_naci" required value="{{ old('fecha_naci') }}">
                
                <input type="text" id="tel" name="tel" placeholder="Teléfono (10 dígitos)" maxlength="20" required value="{{ old('tel') }}">
                
                <input type="email" id="correo" name="correo" placeholder="Correo electrónico" required value="{{ old('correo') }}">
                
                <input type="password" id="pass" name="pass" placeholder="Contraseña (mín. 6 caracteres)" minlength="6" required>

                <select id="rol" name="rol" required>
                    <option value="">Selecciona tipo de usuario</option>
                    <option value="administrador">Administrador</option>
                    <option value="sensei">Sensei</option>
                    <option value="tutor">Tutor</option>
                    <option value="alumno">Alumno</option>
                </select>

                <input type="date" id="fecha_registro" name="fecha_registro" required value="{{ date('Y-m-d') }}">

                <button type="submit">Registrar Usuario</button>
            </form>

            <div class="table-container">
                <h2>Usuarios Registrados</h2>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido P.</th>
                                <th>Apellido M.</th>
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
                                    <td><span class="badge bg-info">{{ $usuario->rol }}</span></td>
                                    <td>{{ $usuario->fecha_naci }}</td>
                                    <td>{{ $usuario->fecha_registro }}</td>
                                    
                                    <td>
                                        <a href="{{ route('editarUsu', $usuario->id_usuario) }}" class="btn btn-sm btn-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        
                                        <form action="{{ route('usuarios.destroy', $usuario->id_usuario) }}" method="POST" style="display:inline;" onsubmit="return confirmarEliminacion(event);">
                                            @csrf
                                            @method('DELETE') 
                                            
                                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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

            const alertaTemp = document.getElementById('alerta-temp');
            if (alertaTemp) {
                alertaTemp.style.display = 'none';
            }
        @endif
        
        function confirmarEliminacion(event) {
            event.preventDefault();
            const form = event.target;
            
            Swal.fire({
                title: '¿Estás seguro de eliminar?',
                text: "¡No podrás recuperar este registro!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, ¡Eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
    </script>
</body>
</html>