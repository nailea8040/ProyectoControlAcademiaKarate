<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Principal | Sistema de Gestión de Dojo</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/estilo2.css') }}"> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
    @include('includes.menu') 

    <div class="main-container">
        
        <header class="main-header">
            <h1>Sistema de Gestión de Dojo</h1>
        </header>

        <div class="content-area">
            <div class="hero-section">
                <div class="hero-content">
                    <h2>Descubre el Camino del Samurai</h2>
                    <p>Entrena cuerpo y mente en nuestro dojo. Aprende karate con maestros expertos y alcanza tu máximo potencial.</p>
                    
                    <div class="stats-container">
                        <div class="stat-box">
                            <span class="stat-number">100+</span>
                            <span class="stat-label">Alumnos</span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-number">15</span>
                            <span class="stat-label">Años</span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-number">3</span>
                            <span class="stat-label">Maestros</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('includes.pie') 
    </div>
    
</body>
</html>