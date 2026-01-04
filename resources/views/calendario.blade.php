<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Actividades - Dojo Karate</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/estilo2.css') }}">
    
    <style>
        :root {
            --karate-red: #e85654;
            --karate-dark: #4A4A4A;
        }

        /* Calendario Moderno */
        .calendar-wrapper {
            background: white;
            border-radius: 25px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }

        .calendar-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8f9fa, #fff);
            border-radius: 20px;
            border: 2px solid #f0f0f0;
        }

        .calendar-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--karate-dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .calendar-title i {
            color: var(--karate-red);
        }

        .calendar-nav {
            display: flex;
            gap: 0.5rem;
        }

        .btn-calendar-nav {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: var(--karate-dark);
        }

        .btn-calendar-nav:hover {
            background: var(--karate-red);
            color: white;
            border-color: var(--karate-red);
            transform: translateY(-2px);
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background: #e9ecef;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .calendar-header {
            background: linear-gradient(135deg, var(--karate-dark), #5a5a5a);
            color: white;
            padding: 1rem;
            text-align: center;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .calendar-day {
            background: white;
            min-height: 130px;
            padding: 0.75rem;
            position: relative;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .calendar-day:hover {
            background: #f8f9fa;
            transform: scale(1.02);
            z-index: 10;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .calendar-day.other-month {
            background: #fafafa;
            opacity: 0.5;
        }

        .calendar-day.today {
            background: linear-gradient(135deg, rgba(232, 86, 84, 0.1), rgba(232, 86, 84, 0.05));
            border: 2px solid var(--karate-red);
        }

        .day-number {
            font-size: 1rem;
            font-weight: 700;
            color: var(--karate-dark);
            display: inline-block;
            width: 32px;
            height: 32px;
            line-height: 32px;
            text-align: center;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .calendar-day.today .day-number {
            background: var(--karate-red);
            color: white;
        }

        .event-badge {
            font-size: 0.7rem;
            padding: 0.4rem 0.6rem;
            border-radius: 8px;
            margin-top: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
            font-weight: 600;
            position: relative;
            overflow: hidden;
        }

        .event-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }

        .event-badge:hover::before {
            left: 100%;
        }

        .event-badge.event-tournament {
            background: linear-gradient(135deg, var(--karate-red), #d43f3d);
            color: white;
            border-left: 4px solid #b82e2c;
        }

        .event-badge.event-exam {
            background: linear-gradient(135deg, var(--karate-dark), #3a3a3a);
            color: white;
            border-left: 4px solid #2a2a2a;
        }

        .event-badge.event-class {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
            border-left: 4px solid #0f6674;
        }

        .event-badge.event-seminar {
            background: linear-gradient(135deg, #28a745, #218838);
            color: white;
            border-left: 4px solid #1c6d30;
        }

        .event-badge:hover {
            transform: translateX(3px) scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Popup de Detalles Moderno */
        .event-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            width: 90%;
            max-width: 500px;
            background: white;
            border-radius: 25px;
            box-shadow: 0 25px 80px rgba(0,0,0,0.3);
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .event-popup.show {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, -50%) scale(1);
        }

        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .popup-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .popup-header {
            background: linear-gradient(135deg, var(--karate-red), #d43f3d);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 25px 25px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .popup-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .btn-close-popup {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-close-popup:hover {
            background: rgba(255,255,255,0.3);
            transform: rotate(90deg);
        }

        .popup-body {
            padding: 2rem;
        }

        .event-detail-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.25rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .event-detail-item:hover {
            background: #f0f0f0;
            transform: translateX(5px);
        }

        .event-detail-icon {
            width: 40px;
            height: 40px;
            background: var(--karate-red);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .event-detail-content {
            flex: 1;
        }

        .event-detail-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .event-detail-value {
            font-size: 1rem;
            color: var(--karate-dark);
            font-weight: 600;
        }

        .event-description {
            background: linear-gradient(135deg, #f8f9fa, #fff);
            padding: 1.25rem;
            border-radius: 12px;
            border-left: 4px solid var(--karate-red);
            margin-top: 1.5rem;
        }

        .event-description p {
            margin: 0;
            color: #495057;
            line-height: 1.6;
        }

        /* Legend */
        .calendar-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 15px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 6px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .calendar-controls {
                flex-direction: column;
                gap: 1rem;
            }

            .calendar-day {
                min-height: 100px;
                padding: 0.5rem;
            }

            .event-badge {
                font-size: 0.6rem;
                padding: 0.3rem 0.5rem;
            }

            .calendar-title {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>

    @include('includes.menu')

    <div class="main-content">
        <header class="header mb-4">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div>
                    <h1 class="header-title">
                        <i class="bi bi-calendar3"></i>
                        Calendario de Eventos
                    </h1>
                    <div class="breadcrumb">
                        <a href="{{ route('principal') }}">Dashboard</a>
                        <i class="bi bi-chevron-right"></i>
                        <span>Calendario</span>
                    </div>
                </div>

                {{-- Solo el administrador ve el botón de agregar --}}
               @if(Auth::check() && Auth::user()->rol == 'administrador')
                <button class="btn btn-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addEventModal">
                    <i class="bi bi-plus-circle me-2"></i> Nuevo Evento
                </button>
                @endif
            </div>
        </header>

        <div class="content-wrapper">
            <div class="calendar-wrapper">
                <div class="calendar-controls">
                    <h2 class="calendar-title">
                        <i class="bi bi-calendar-event"></i>
                        <span id="currentMonth">Febrero 2025</span>
                    </h2>
                    <div class="calendar-nav">
                        <button class="btn-calendar-nav" onclick="previousMonth()">
                            <i class="bi bi-chevron-left"></i> Anterior
                        </button>
                        <button class="btn-calendar-nav" onclick="nextMonth()">
                            Siguiente <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div class="calendar-grid">
    <div class="calendar-header">Lun</div>
    <div class="calendar-header">Mar</div>
    <div class="calendar-header">Mié</div>
    <div class="calendar-header">Jue</div>
    <div class="calendar-header">Vie</div>
    <div class="calendar-header">Sáb</div>
    <div class="calendar-header">Dom</div>

    @for ($i = 1; $i <= 31; $i++)
        @php
            // Creamos la fecha actual del ciclo para comparar (Formato YYYY-MM-DD)
            // Ajusta '2025-02' según el mes que estés visualizando
            $fechaActual = "2025-02-" . str_pad($i, 2, '0', STR_PAD_LEFT);
            $esHoy = ($i == date('d') && date('m') == 2); 
        @endphp

        <div class="calendar-day {{ $esHoy ? 'today' : '' }}" 
            @if(Auth::check() && Auth::user()->rol == 'administrador')onclick="openAddEvent('{{ $fechaActual }}')" @endif>
            
            <span class="day-number">{{ $i }}</span>
            
            @foreach($eventos as $evento)
                @if($evento->fecha == $fechaActual)
                    <div class="event-badge event-{{ $evento->tipo }}" 
                         onclick="event.stopPropagation(); showEvent(
                             '{{ $evento->titulo }}', 
                             '{{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}', 
                             '{{ $evento->hora }}', 
                             '{{ $evento->ubicacion }}', 
                             '{{ $evento->descripcion }}', 
                             '{{ $evento->tipo }}'
                         )">
                        {{-- Icono según tipo --}}
                        @if($evento->tipo == 'tournament') 🏆 
                        @elseif($evento->tipo == 'exam') 🥋 
                        @elseif($evento->tipo == 'class') 📚 
                        @else 🛡️ @endif
                        
                        {{ $evento->titulo }}
                    </div>
                @endif
            @endforeach
        </div>
    @endfor
</div>
            </div>
        </div>
    </div>

   @if(Auth::check() && Auth::user()->rol == 'administrador')
<div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 25px; border: none; shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header bg-danger text-white" style="border-radius: 25px 25px 0 0;">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Nuevo Evento de Karate</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('eventos.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Título del Evento</label>
                        <input type="text" name="titulo" class="form-control" placeholder="Ej: Torneo Interno" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Fecha</label>
                            <input type="date" id="eventDateInput" name="fecha" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Hora</label>
                            <input type="time" name="hora" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ubicación</label>
                        <input type="text" name="ubicacion" class="form-control" placeholder="Ej: Dojo Central" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Categoría</label>
                        <select name="tipo" class="form-select">
                            <option value="class">📚 Clase Especial</option>
                            <option value="tournament">🏆 Torneo</option>
                            <option value="exam">🥋 Examen de Grado</option>
                            <option value="seminar">🛡️ Seminario</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" placeholder="Detalles adicionales..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger px-4 rounded-pill">Registrar Evento</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

    <script>
        // Función para que el administrador abra el modal al hacer clic en un número del calendario
        function openAddEvent(date) {
            const modal = new bootstrap.Modal(document.getElementById('addEventModal'));
            document.getElementById('eventDateInput').value = date;
            modal.show();
        }

        // Detener la propagación para que al hacer clic en el badge no se dispare el click del día
       function showEvent(title, date, time, location, description, type) {
    // IMPORTANTE: Prevenir que el clic llegue al div padre (el día)
    if (event) event.stopPropagation();

    document.getElementById('popupTitle').textContent = title;
    document.getElementById('popupDate').textContent = date;
    document.getElementById('popupTime').textContent = time;
    document.getElementById('popupLocation').textContent = location;
    document.getElementById('popupDescription').textContent = description;
    
    const header = document.getElementById('popupHeader');
    header.style.background = getEventColor(type);
    
    document.getElementById('popupOverlay').classList.add('show');
    document.getElementById('eventPopup').classList.add('show');
}
    </script>
</body>
</html>