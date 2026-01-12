<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Calendario de Actividades - Dojo Karate</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/estilo2.css') }}">
    
    <style>
        :root {
            --karate-red: #e85654;
            --karate-dark: #4A4A4A;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .calendar-wrapper {
            background: white;
            border-radius: 30px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .calendar-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            padding: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .calendar-title {
            font-size: 2rem;
            font-weight: 900;
            color: white;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .calendar-title i {
            font-size: 2.2rem;
        }

        .calendar-nav {
            display: flex;
            gap: 0.75rem;
        }

        .btn-calendar-nav {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 15px;
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            transition: all 0.3s ease;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-calendar-nav:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.5);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
            background: #e9ecef;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }

        .calendar-header {
            background: linear-gradient(135deg, var(--karate-dark), #5a5a5a);
            color: white;
            padding: 1.25rem;
            text-align: center;
            font-weight: 800;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .calendar-day {
            background: white;
            min-height: 140px;
            padding: 1rem;
            position: relative;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .calendar-day:hover {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            transform: scale(1.03);
            z-index: 10;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-color: var(--karate-red);
        }

        .calendar-day.other-month {
            background: #fafafa;
            opacity: 0.4;
        }

        .calendar-day.other-month .day-number {
            color: #adb5bd;
        }

        .calendar-day.today {
            background: linear-gradient(135deg, rgba(232, 86, 84, 0.15), rgba(232, 86, 84, 0.05));
            border: 3px solid var(--karate-red);
            box-shadow: 0 0 20px rgba(232, 86, 84, 0.3);
        }

        .day-number {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--karate-dark);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
        }

        .calendar-day.today .day-number {
            background: var(--karate-red);
            color: white;
            box-shadow: 0 5px 15px rgba(232, 86, 84, 0.4);
        }

        .event-badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.7rem;
            border-radius: 10px;
            margin-top: 0.4rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-weight: 700;
            position: relative;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .event-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s ease;
        }

        .event-badge:hover::before {
            left: 100%;
        }

        .event-badge.event-tournament {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            border-left: 4px solid #c92a2a;
        }

        .event-badge.event-exam {
            background: linear-gradient(135deg, #4c6ef5, #364fc7);
            color: white;
            border-left: 4px solid #1c7ed6;
        }

        .event-badge.event-class {
            background: linear-gradient(135deg, #20c997, #12b886);
            color: white;
            border-left: 4px solid #0ca678;
        }

        .event-badge.event-seminar {
            background: linear-gradient(135deg, #fab005, #f59f00);
            color: white;
            border-left: 4px solid #e67700;
        }

        .event-badge:hover {
            transform: translateX(5px) scale(1.08);
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
        }

        /* Popup de Detalles */
        .event-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.8);
            width: 90%;
            max-width: 550px;
            background: white;
            border-radius: 30px;
            box-shadow: 0 30px 90px rgba(0,0,0,0.4);
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
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
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(8px);
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
            padding: 2rem;
            border-radius: 30px 30px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .popup-header h3 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 900;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-close-popup {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 42px;
            height: 42px;
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
            transform: rotate(90deg) scale(1.1);
        }

        .popup-body {
            padding: 2.5rem;
        }

        .event-detail-item {
            display: flex;
            align-items: flex-start;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
            padding: 1.25rem;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .event-detail-item:hover {
            background: linear-gradient(135deg, #e9ecef, #dee2e6);
            transform: translateX(8px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .event-detail-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--karate-red), #d43f3d);
            color: white;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            box-shadow: 0 5px 15px rgba(232, 86, 84, 0.3);
        }

        .event-detail-content {
            flex: 1;
        }

        .event-detail-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 0.4rem;
        }

        .event-detail-value {
            font-size: 1.1rem;
            color: var(--karate-dark);
            font-weight: 700;
        }

        .event-description {
            background: linear-gradient(135deg, #fff, #f8f9fa);
            padding: 1.5rem;
            border-radius: 15px;
            border-left: 5px solid var(--karate-red);
            margin-top: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .event-description p {
            margin: 0;
            color: #495057;
            line-height: 1.8;
            font-size: 1rem;
        }

        /* Leyenda */
        .calendar-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-top: 2.5rem;
            padding: 2rem;
            background: linear-gradient(135deg, #f8f9fa, white);
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--karate-dark);
            padding: 0.5rem 1rem;
            background: white;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .legend-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .legend-color {
            width: 24px;
            height: 24px;
            border-radius: 8px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.2);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .calendar-controls {
                flex-direction: column;
                gap: 1rem;
                padding: 1.5rem;
            }

            .calendar-title {
                font-size: 1.5rem;
            }

            .calendar-day {
                min-height: 110px;
                padding: 0.6rem;
            }

            .event-badge {
                font-size: 0.65rem;
                padding: 0.4rem 0.5rem;
            }

            .day-number {
                width: 30px;
                height: 30px;
                font-size: 0.95rem;
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
                        <span id="currentMonth"></span>
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

                <div class="calendar-grid" id="calendarGrid">
                    <div class="calendar-header">Lun</div>
                    <div class="calendar-header">Mar</div>
                    <div class="calendar-header">Mié</div>
                    <div class="calendar-header">Jue</div>
                    <div class="calendar-header">Vie</div>
                    <div class="calendar-header">Sáb</div>
                    <div class="calendar-header">Dom</div>
                </div>

                <div class="calendar-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background: linear-gradient(135deg, #20c997, #12b886);"></div>
                        <span>📚 Clase Especial</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: linear-gradient(135deg, #ff6b6b, #ee5a52);"></div>
                        <span>🏆 Torneo</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: linear-gradient(135deg, #4c6ef5, #364fc7);"></div>
                        <span>🥋 Examen de Grado</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: linear-gradient(135deg, #fab005, #f59f00);"></div>
                        <span>🛡️ Seminario</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popup de Detalles del Evento -->
    <div class="popup-overlay" id="popupOverlay" onclick="closePopup()"></div>
    <div class="event-popup" id="eventPopup">
        <div class="popup-header" id="popupHeader">
            <h3 id="popupTitle"></h3>
            <button class="btn-close-popup" onclick="closePopup()">×</button>
        </div>
        <div class="popup-body">
            <div class="event-detail-item">
                <div class="event-detail-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="event-detail-content">
                    <div class="event-detail-label">Fecha</div>
                    <div class="event-detail-value" id="popupDate"></div>
                </div>
            </div>

            <div class="event-detail-item">
                <div class="event-detail-icon">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="event-detail-content">
                    <div class="event-detail-label">Hora</div>
                    <div class="event-detail-value" id="popupTime"></div>
                </div>
            </div>

            <div class="event-detail-item">
                <div class="event-detail-icon">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div class="event-detail-content">
                    <div class="event-detail-label">Ubicación</div>
                    <div class="event-detail-value" id="popupLocation"></div>
                </div>
            </div>

            <div class="event-description">
                <div class="event-detail-label mb-2">
                    <i class="bi bi-card-text me-2"></i>Descripción
                </div>
                <p id="popupDescription"></p>
            </div>

            <!-- Botones de administrador -->
            <div class="popup-body">
            @if(Auth::check() && Auth::user()->rol == 'administrador')
            <div id="adminButtons" style="display: none; gap: 1rem; margin-top: 2rem;" data-event-id="" data-event-title="">
                <button onclick="editEvent()" class="btn btn-primary rounded-pill px-4" style="flex: 1;">
                    <i class="bi bi-pencil-square me-2"></i>Editar Evento
                </button>
                <button onclick="deleteEvent()" class="btn btn-danger rounded-pill px-4" style="flex: 1;">
                    <i class="bi bi-trash3 me-2"></i>Eliminar Evento
                </button>
            </div>
            @endif
</div>
        </div>
    </div>

    @if(Auth::check() && Auth::user()->rol == 'administrador')
    <!-- Modal Agregar Evento -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 25px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, var(--karate-red), #d43f3d); border-radius: 25px 25px 0 0;">
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

    <!-- Modal Editar Evento -->
    <div class="modal fade" id="editEventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 25px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #4c6ef5, #364fc7); border-radius: 25px 25px 0 0;">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Evento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <form id="editEventForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Título del Evento</label>
                            <input type="text" id="editTitulo" name="titulo" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Fecha</label>
                                <input type="date" id="editFecha" name="fecha" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Hora</label>
                                <input type="time" id="editHora" name="hora" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ubicación</label>
                            <input type="text" id="editUbicacion" name="ubicacion" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Categoría</label>
                            <select id="editTipo" name="tipo" class="form-select">
                                <option value="class">📚 Clase Especial</option>
                                <option value="tournament">🏆 Torneo</option>
                                <option value="exam">🥋 Examen de Grado</option>
                                <option value="seminar">🛡️ Seminario</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea id="editDescripcion" name="descripcion" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 rounded-pill">Actualizar Evento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Pasar eventos de PHP a JavaScript
        const eventosData = @json($eventos);
        
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();

        const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                          'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        function renderCalendar() {
    const firstDay = new Date(currentYear, currentMonth, 1);
    const lastDay = new Date(currentYear, currentMonth + 1, 0);
    const prevLastDay = new Date(currentYear, currentMonth, 0);
    
    // Ajuste para que la semana empiece en Lunes
    const firstDayIndex = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1;
    const lastDayDate = lastDay.getDate();
    const prevLastDayDate = prevLastDay.getDate();
    const nextDays = 7 - (lastDay.getDay() === 0 ? 7 : lastDay.getDay());

    document.getElementById('currentMonth').textContent = `${monthNames[currentMonth]} ${currentYear}`;

    let days = '';
    const icons = { 'tournament': '🏆', 'exam': '🥋', 'class': '📚', 'seminar': '🛡️' };

    // Días del mes anterior
    for (let x = firstDayIndex; x > 0; x--) {
        days += `<div class="calendar-day other-month">
            <span class="day-number">${prevLastDayDate - x + 1}</span>
        </div>`;
    }

    // Días del mes actual
    for (let i = 1; i <= lastDayDate; i++) {
        const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
        const today = new Date();
        const isToday = i === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear();
        
        const dayEvents = eventosData.filter(e => e.fecha === dateStr);
        
        let eventBadges = '';
        dayEvents.forEach(evento => {
            const idSeguro = evento.id ? evento.id : 'null';
            eventBadges += `
                <div class="event-badge event-${evento.tipo}" 
                     onclick="event.stopPropagation(); showEvent(
                        '${evento.titulo.replace(/'/g, "\\'")}', 
                        '${formatDate(evento.fecha)}', 
                        '${evento.hora}', 
                        '${evento.ubicacion.replace(/'/g, "\\'")}', 
                        '${evento.descripcion ? escapeQuotes(evento.descripcion) : ''}', 
                        '${evento.tipo}', 
                        ${idSeguro}
                     )">
                    ${icons[evento.tipo] || '📅'} ${evento.titulo}
                </div>`;
        });

        const isAdmin = {{ Auth::check() && Auth::user()->rol == 'administrador' ? 'true' : 'false' }};
        const clickHandler = isAdmin ? `onclick="openAddEvent('${dateStr}')"` : '';

        days += `
            <div class="calendar-day ${isToday ? 'today' : ''}" ${clickHandler}>
                <span class="day-number">${i}</span>
                ${eventBadges}
            </div>`;
    }

    // Días del mes siguiente
    for (let j = 1; j <= (nextDays < 7 ? nextDays : 0); j++) {
        days += `<div class="calendar-day other-month">
            <span class="day-number">${j}</span>
        </div>`;
    }

    document.getElementById('calendarGrid').innerHTML = `
        <div class="calendar-header">Lun</div>
        <div class="calendar-header">Mar</div>
        <div class="calendar-header">Mié</div>
        <div class="calendar-header">Jue</div>
        <div class="calendar-header">Vie</div>
        <div class="calendar-header">Sáb</div>
        <div class="calendar-header">Dom</div>
        ${days}
    `;
}

        function formatDate(dateStr) {
            const [year, month, day] = dateStr.split('-');
            return `${day}/${month}/${year}`;
        }

        function previousMonth() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar();
        }

        function nextMonth() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar();
        }

        function openAddEvent(date) {
            const modal = new bootstrap.Modal(document.getElementById('addEventModal'));
            document.getElementById('eventDateInput').value = date;
            modal.show();
        }

                function showEvent(title, date, time, location, description, type, eventId) {
            if (event) event.stopPropagation();

            // Llenar datos visuales del popup
            document.getElementById('popupTitle').innerHTML = getEventIcon(type) + ' ' + title;
            document.getElementById('popupDate').textContent = date;
            document.getElementById('popupTime').textContent = time;
            document.getElementById('popupLocation').textContent = location;
            document.getElementById('popupDescription').textContent = description || 'Sin descripción disponible';
            
            const header = document.getElementById('popupHeader');
            header.style.background = getEventColor(type);
            
            // GUARDAR ATRIBUTOS para la edición
            const adminButtons = document.getElementById('adminButtons');
            if (adminButtons) {
                adminButtons.style.display = 'flex';
                adminButtons.setAttribute('data-event-id', eventId);
                adminButtons.setAttribute('data-event-title', title);
                adminButtons.setAttribute('data-event-date', date); // Formato DD/MM/YYYY
                adminButtons.setAttribute('data-event-time', time);
                adminButtons.setAttribute('data-event-location', location);
                adminButtons.setAttribute('data-event-description', description || '');
                adminButtons.setAttribute('data-event-type', type);
            }
            
            document.getElementById('popupOverlay').classList.add('show');
            document.getElementById('eventPopup').classList.add('show');
        }

        function closePopup() {
            document.getElementById('popupOverlay').classList.remove('show');
            document.getElementById('eventPopup').classList.remove('show');
            const adminButtons = document.getElementById('adminButtons');
    if (adminButtons) {
        adminButtons.style.display = 'none';
    }
        }

        function getEventColor(type) {
            const colors = {
                'tournament': 'linear-gradient(135deg, #ff6b6b, #ee5a52)',
                'exam': 'linear-gradient(135deg, #4c6ef5, #364fc7)',
                'class': 'linear-gradient(135deg, #20c997, #12b886)',
                'seminar': 'linear-gradient(135deg, #fab005, #f59f00)'
            };
            return colors[type] || 'linear-gradient(135deg, var(--karate-red), #d43f3d)';
        }

        function getEventIcon(type) {
            const icons = {
                'tournament': '🏆',
                'exam': '🥋',
                'class': '📚',
                'seminar': '🛡️'
            };
            return icons[type] || '📅';
        }

        function escapeQuotes(str) {
            if (!str) return '';
            return str.replace(/'/g, "\\'").replace(/"/g, '\\"');
        }

                function editEvent() {
            const adminButtons = document.getElementById('adminButtons');
            
            // 1. Obtener los datos guardados en los atributos data-
            const eventId = adminButtons.getAttribute('data-event-id');
            const title = adminButtons.getAttribute('data-event-title');
            const dateFormatted = adminButtons.getAttribute('data-event-date');
            const time = adminButtons.getAttribute('data-event-time');
            const location = adminButtons.getAttribute('data-event-location');
            const description = adminButtons.getAttribute('data-event-description');
            const type = adminButtons.getAttribute('data-event-type');

            // 2. Convertir fecha de DD/MM/YYYY a YYYY-MM-DD para el input type="date"
            const parts = dateFormatted.split('/');
            const dateISO = `${parts[2]}-${parts[1]}-${parts[0]}`;

            // 3. Llenar el formulario del Modal de Edición
            document.getElementById('editTitulo').value = title;
            document.getElementById('editFecha').value = dateISO;
            document.getElementById('editHora').value = time;
            document.getElementById('editUbicacion').value = location;
            document.getElementById('editDescripcion').value = description;
            document.getElementById('editTipo').value = type;

            // 4. Configurar la URL de envío (Action) del formulario
            // Esto apunta a EventoController@update
            document.getElementById('editEventForm').action = `/eventos/${eventId}`;

            // 5. Interfaz: Cerrar el popup de detalles y abrir el modal de edición
            closePopup();
            const editModal = new bootstrap.Modal(document.getElementById('editEventModal'));
            editModal.show();
        }

        function deleteEvent() {
            const adminButtons = document.getElementById('adminButtons');
            const eventId = adminButtons.getAttribute('data-event-id');
            const title = adminButtons.getAttribute('data-event-title');
        
            if (!eventId || isNaN(eventId)) {
        alert("Error: No se pudo obtener el ID del evento.");
        return;
    }
            if (confirm(`¿Estás seguro de que deseas eliminar el evento "${title}"?\n\nEsta acción no se puede deshacer.`)) {
                // Crear formulario de eliminación dinámicamente
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/eventos/${eventId}`;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

       document.addEventListener('DOMContentLoaded', function() {
            renderCalendar();
        });
        
    </script>
</body>
</html>