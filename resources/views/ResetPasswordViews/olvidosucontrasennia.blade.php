<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema Almacén</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="{{ asset('css/estilo2.css') }}">
    
    <style>
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
</head>
<body class="dojo-background hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-danger"> 
            
            <div class="card-header text-center">
                <h1>Recuperar Contraseña</h1>
            </div>

            <div class="card-body">
                <p class="login-message text-center">¿Olvidaste tu contraseña? <br>Aquí puedes recuperarla fácilmente.</p>

                <form id="registroForm" action="{{ route('password.email') }}" method="POST">
                    @csrf
                    
                    <div class="input-group mb-3">
                        <input type="email" id="correo" class="form-control" name="correo" placeholder="Ingresa tu correo">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-danger btn-block">SOLICITAR RESTABLECIMIENTO</button>

                    <div class="links" style="text-align: center; margin-top: 15px;">
                        <a href="{{route('login')}}">Volver a Iniciar Sesión</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="preloader">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden"></span>
        </div>
    </div>

    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../dist/js/adminlte.min.js"></script>
    <script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="../../plugins/jquery-validation/additional-methods.min.js"></script>

    <script>
        $(document).ready(function () {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000
            });

            @if (session('sessionRecuperarContrasennia') == 'true')
                Toast.fire({
                    icon: 'success',
                    title: '{{session('mensaje')}}'
                })
            @elseif (session('sessionRecuperarContrasennia') == 'false')
                Toast.fire({
                    icon: 'error',
                    title: '{{session('mensaje')}}'
                })
            @endif

            $.validator.setDefaults({
                submitHandler: function () {
                    $('#preloader').css('display', 'flex');
                    $('#registroForm')[0].submit();
                }
            });

            $('#registroForm').validate({
                rules: {
                    correo: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    correo: {
                        required: "Ingresa tu correo electrónico",
                        email: "Ingresa un correo electrónico válido"
                    }
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.input-group').append(error);
                },
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
</body>
</html>