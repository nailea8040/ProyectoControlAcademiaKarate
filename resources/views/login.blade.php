<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('/css/estiloindex.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     
</head>
<body>

    
    <div class="auth-container">
        <form class="login-form" action="{{ route('login.attempt') }}" method="POST">
            @csrf
            <h2>Iniciar sesión</h2>

            <input type="email" name="correo" placeholder="Correo" required>
            <input type="password" name="contra" placeholder="Contraseña" required>

            <button type="submit">Ingresar</button>
            <a href="{{ route('password.request') }}" class="forgot-password-link">
            ¿Olvidaste tu contraseña?
        </a>
        </form>
    </div>

    <script>
    // Verifica si existe un mensaje de error de login en la sesión (que configuramos en el controlador)
    @if(session('error_login'))
        Swal.fire({
            title: 'Error de acceso',
            text: '{{ session('error_login') }}',
            icon: 'error',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#e53935' // Usamos el color rojo del Dojo
        });
    @endif
    
    // Si hay errores de validación (ej: campo vacío), los mostramos
    @if($errors->any())
        Swal.fire({
            title: 'Datos Faltantes',
            html: '<ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
            icon: 'warning',
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#f57c00' // Usamos un color de advertencia
        });
    @endif
</script>

</body>
</html>