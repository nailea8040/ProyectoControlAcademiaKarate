<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | Sistema de Gestión de Dojo</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/estilo2.css') }}"> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
    {{-- Menú Lateral Compacto --}}
    @include('includes.menu') 

    <div class="main-content">
        
        {{-- HEADER MODERNO con Título y Breadcrumb --}}
        <header class="header">
            <div>
                <h1 class="header-title">
                    <i class="bi bi-house-door-fill"></i>
                    Dashboard Principal
                </h1>
                <div class="breadcrumb">
                    <span>Inicio del Sistema</span>
                </div>
            </div>
        </header>

        <div class="content-wrapper">
            
            {{-- BLOQUE 1: TARJETAS DE ESTADÍSTICAS (WIDGETS) --}}
            <div class="widgets-grid">
                
                {{-- Tarjeta 1: Alumnos Activos (Clase 'alumnos' para el color verde) --}}
                <div class="widget-card alumnos">
                    <div class="widget-info">
                        {{-- Puedes usar una variable de Laravel aquí, ej: {{ $alumnosActivos }} --}}
                        <span class="widget-value">100+</span> 
                        <span class="widget-title">Alumnos Activos</span>
                    </div>
                    <div class="widget-icon-box">
                        <i class="bi bi-person-badge"></i>
                    </div>
                </div>
                
                {{-- Tarjeta 2: Pagos Pendientes (Clase 'pagos' para el color azul) --}}
                <div class="widget-card pagos">
                    <div class="widget-info">
                        <span class="widget-value">8</span>
                        <span class="widget-title">Pagos Pendientes</span>
                    </div>
                    <div class="widget-icon-box">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>

                {{-- Tarjeta 3: Clases Hoy (Clase 'asistencias' para el color naranja) --}}
                <div class="widget-card asistencias">
                    <div class="widget-info">
                        <span class="widget-value">3</span>
                        <span class="widget-title">Clases Programadas Hoy</span>
                    </div>
                    <div class="widget-icon-box">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>

                {{-- Tarjeta 4: Usuarios del Sistema (Clase 'gestion' para el color rojo) --}}
                <div class="widget-card gestion">
                    <div class="widget-info">
                        <span class="widget-value">15</span>
                        <span class="widget-title">Años de Trayectoria</span>
                    </div>
                    <div class="widget-icon-box">
                        <i class="bi bi-award"></i>
                    </div>
                </div>
                
            </div>
            
            {{-- BLOQUE 2: Contenido Secundario (Gráficos, Listados rápidos, Noticias) --}}
            
            <h3 class="section-title-header">
                <i class="bi bi-activity"></i>
                Actividad Reciente
            </h3>
            
            {{-- Aquí puedes colocar un widget de tabla de alumnos con actividad reciente --}}
            <div class="table-container">
                <div class="table-header">
                    <h2 class="table-title">
                        <i class="bi bi-calendar3"></i>
                        Próximos Eventos
                    </h2>
                </div>
                <p style="padding: 20px; color: #757575;">[Espacio para lista de eventos o gráficos]</p>
            </div>
            
        </div> {{-- Fin content-wrapper --}}

        {{-- Pie de página --}}
        @include('includes.pie') 
    </div>
    
</body>
</html>