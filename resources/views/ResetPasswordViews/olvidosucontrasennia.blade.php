<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema Almacén</title>

     <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <!-- SweetAlert2 -->
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

                       <form id="registroForm" action="/login" method="post">
                @csrf
               
                
                <label for="correo">Correo institucional</label>
                <input type="text" id="correo" class="form-control" name="correo" placeholder="Ingresa tu correo institucional">
                
                <button type="submit">SOLICITAR RESTABLECIMIENTO</button>

                <div class="links" style="text-align: center; margin-top: 15px;">
                    <a href="{{route('login')}}">Volver a Iniciar Sesión</a>
                </div>
           
                <p class="mt-3 mb-1 text-center">
                    <a href="{{route('login')}}" class="text-center">Iniciar Sesión</a>
                </p>
            </div>
            </div>
        </div>

<!-- /.login-box -->

<div id="preloader">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden"> </span>
    </div>
</div>

{{--*******************************************--}}
{{--Zona para registrar archivos JS de Jascript--}}
{{--*******************************************--}}
<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- jquery-validation -->
<script src="../../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../../plugins/jquery-validation/additional-methods.min.js"></script>

{{--************************************************--}}
{{--Zona para cargar mensajes de error de tipo Toast--}}
{{--************************************************--}}
<script>
    $(document).ready(function () {
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        @if (session('sessionRecuperarContrasennia') == 'false')
            Toast.fire({
                icon: 'success',
                title: '{{session('mensaje')}}'
            })
        @endif

        $(function () {
            $.validator.setDefaults({
                submitHandler: function () {
                    $('#preloader').css('display', 'flex'); // Muestra el preloader
                    $('#registroForm').submit();
                }
            });

            $('#registroForm').validate({
                rules: {
                    correo: {
                        required: true,
                        email: true
                    },
                },
                messages: {
                    correo: {
                        required: "Ingresa tu correo electrónico",
                        email: "Ingresa un correo electrónico válido"
                    },
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.input-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    });
</script>
</body>
</html>
