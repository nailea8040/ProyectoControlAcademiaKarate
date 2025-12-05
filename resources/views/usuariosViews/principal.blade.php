<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Principal | Sistema de Gestión de Dojo</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/estilo2.css') }}"> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Sobrescribimos .content-area y .main-content-panels para la vista principal */
        .main-container .content-area {
            padding: 0; /* Eliminamos el padding para que el fondo llegue a los bordes */
            height: calc(100vh - 76px); /* Altura total - altura del header */
        }
        
        /* Contenedor del fondo de imagen (reemplaza a la caja blanca) */
        .content-background {
            position: relative;
            height: 100%;
            width: 100%;
            /* Propiedades cruciales para el fondo de imagen completo */
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('img/pk1.jpg') }}') center/cover;
            
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            border-radius: 8px; /* Aplicamos el borde redondeado de la caja */
            overflow: hidden; /* Aseguramos que la imagen se respete el border-radius */
        }
        
        .content-background h2 {
            margin: 10px 20px;
            max-width: 700px;
            font-size: 24px;
            font-weight: 400;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
        }
    </style>
</head>
<body>
    
    @include('includes.menu') 

    <div class="main-container">
        
        <header class="main-header">
            <h1>Sistema de Gestión de Dojo</h1>
        </header>

        <div class="content-area">
            
            <div class="content-background">
                <h2>Descubre el camino del samurai</h2>
                <h2>Entrena cuerpo y mente en nuestro dojo. Aprende karate con maestros expertos y alcanza tu máximo potencial.</h2>
            </div>
            
        </div>
@include('includes.pie') 
    </div>

    
    
</body>
</html>