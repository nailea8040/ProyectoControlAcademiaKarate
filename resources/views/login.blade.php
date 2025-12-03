<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mensaje</title>
    <link rel="stylesheet" href="/css/estilos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <div class="container">
        <form class="login-form" action="#" method="POST">
            @csrf
            <h2>Iniciar sesión</h2>

            <input type="email" name="correo" placeholder="Correo" required>
            <input type="password" name="contra" placeholder="Contraseña" required>

            <button type="submit">Ingresar</button>
        </form>
    </div>

    <!-- Aquí podrías activar la alerta si lo deseas -->
    <script>
        /* Ejemplo de uso:
        Swal.fire({
            title: 'Bienvenido',
            text: 'Usuario de prueba',
            icon: 'success'
        });
        */
    </script>

</body>
</html>
