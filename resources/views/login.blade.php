<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('/css/estiloindex.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
     
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
    // Usa esta variable para almacenar el mensaje y el tipo de error
    let errorTitle = null;
    let errorMessage = null;

    // ==============================================
    // 1. Manejo de Errores de Login con SweetAlert
    // ==============================================
    
    // ⭐ Caso A: Cuenta inactiva (Devuelto por el controlador si estado = 0)
    @if ($errors->has('cuenta_inactiva'))
        errorTitle = 'Acceso Denegado';
        errorMessage = '{{ $errors->first('cuenta_inactiva') }}'; // Mensaje de: Su cuenta está inactiva...
    
    // ⭐ Caso B: Credenciales incorrectas (Devuelto por el controlador si la autenticación falla)
    @elseif ($errors->has('login_fallido'))
        errorTitle = 'Error de Credenciales';
        errorMessage = '{{ $errors->first('login_fallido') }}'; // Mensaje de: Credenciales incorrectas...

    // ⭐ Caso C: Errores de Validación (Ej: Correo no es email, falta campo, etc.)
    @elseif ($errors->any())
        errorTitle = 'Faltan datos';
        // Para errores de validación, SweetAlert solo mostrará el primer error para no ser intrusivo.
        // Si quieres mostrar todos, necesitarías un bucle más complejo, pero este es el enfoque limpio.
        errorMessage = 'Por favor, corrige el error: {{ $errors->all()[0] }}';
    @endif


    // Muestra el SweetAlert si encontramos algún error de login o validación
    if (errorMessage) {
        Swal.fire({
            title: errorTitle,
            text: errorMessage,
            icon: 'error',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#e53935' // Usamos el color rojo del Dojo
        });
    }


    // ==============================================
    // 2. Manejo de Mensajes de Éxito (Status)
    // ==============================================

    // ⭐ Cierre de sesión exitoso o cualquier otro mensaje de 'status'
    @if (session('status'))
        Swal.fire({
            title: 'Éxito',
            text: '{{ session('status') }}',
            icon: 'success',
            confirmButtonText: 'Continuar',
            confirmButtonColor: '#4CAF50' // Color verde
        });
    @endif
    
</script>

</body>
</html>