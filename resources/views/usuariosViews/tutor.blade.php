<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestión de Tutores - Dojo</title>
    
    <link rel="stylesheet" href="{{ asset('css/estilo2.css') }}"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    {{-- Eliminar el link a Bootstrap CSS para usar solo el estilo2.css --}}
</head>

<body>
{{-- Menú Lateral --}}
@include('includes.menu') 

<div class="main-content">
    
    {{-- HEADER MODERNO --}}
    <header class="header">
        <div>
            <h1 class="header-title">
                <i class="bi bi-person-lines-fill"></i>
                Gestión de Tutores
            </h1>
            <div class="breadcrumb">
                <a href="{{ route('principal') }}">Inicio</a>
                <i class="bi bi-chevron-right"></i>
                <span>Tutores</span>
            </div>
        </div>
    </header>

    <div class="content-wrapper">
        
        {{-- ALERTA DE ÉXITO (Usando Blade para mensajes de sesión) --}}
        {{-- Cambia la alerta de éxito para que sea más flexible --}}
@if(session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle-fill alert-icon"></i>
        <div><strong>¡Éxito!</strong> {{ session('success') }}</div>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div><strong>Error:</strong> {{ session('error') }}</div>
    </div>
@endif
        
        <div class="info-card">
            <h4>
                <i class="bi bi-info-circle-fill"></i>
                Información sobre Tutores
            </h4>
            <p>
                Los tutores son los responsables legales de los alumnos. Cada tutor debe tener un usuario registrado.
                Un tutor puede ser responsable de múltiples alumnos.
            </p>
        </div>

        {{-- Bloque para ver errores de validación (Crucial para depurar) --}}
@if ($errors->any())
    <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li><i class="bi bi-exclamation-circle"></i> {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Asegúrate de que el nombre del mensaje coincida con el controlador --}}
@if(session('mensaje'))
    <div class="alert alert-success">
        {{ session('mensaje') }}
    </div>
@endif


        {{-- FORMULARIO PARA REGISTRAR TUTOR --}}
        <div class="form-container">
            <div class="form-header">
                <h2>
                    <i class="bi bi-person-plus-fill"></i>
                    Registrar Nuevo Tutor
                </h2>
                <p>Complete la información del tutor y su relación con el estudiante</p>
            </div>
            
            <form id="registroTutor" method="post" action="{{ route('tutor.store') }}" class="form-body">
                @csrf 

                <h3 class="section-title-header">
                    <i class="bi bi-person-circle"></i>
                    Información del Tutor
                </h3>
                <div class="form-grid full-width">
                    <div class="form-group">
                        <label class="form-label" for="id_tutor">
                            Usuario del Tutor <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="bi bi-person-badge input-icon"></i>
                            <select name="id_Tutor" id="id_Tutor" class="form-select" required>
                                <option value="">Seleccione un usuario</option>
                                {{-- DATOS DINÁMICOS DEL CÓDIGO 1 --}}
                                @foreach($usuarios_tutor as $u) 
                                    <option value="{{ $u->id_Tutor }}">{{ $u->nombre_completo }}</option>
                                @endforeach
                                {{-- FIN DATOS DINÁMICOS --}}
                            </select>
                        </div>
                        <small style="color: #757575; margin-top: 5px; display: block;">
                            Solo se muestran usuarios con rol "Tutor"
                        </small>
                    </div>
                </div>
                
                <h3 class="section-title-header">
                    <i class="bi bi-briefcase-fill"></i>
                    Información Laboral
                </h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="ocupacion">
                            Ocupación <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="bi bi-briefcase input-icon"></i>
                            <input type="text" name="ocupacion" id="ocupacion" class="form-input" 
                                    placeholder="Ej: Ingeniero, Médico, Profesor, etc." required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="empresa">
                            Empresa / Institución (Opcional)
                        </label>
                        <div class="form-input-wrapper">
                            <i class="bi bi-building input-icon"></i>
                            <input type="text" id="empresa" class="form-input" name="empresa"
                                    placeholder="Nombre de la empresa o institución">
                        </div>
                    </div>
                </div>
                
                <h3 class="section-title-header">
                    <i class="bi bi-heart-fill"></i>
                    Relación con el Estudiante
                </h3>
                <div class="form-grid full-width">
                    <div class="form-group">
                        <label class="form-label">
                            Parentesco <span class="required">*</span>
                        </label>
                        {{-- Usaremos el campo 'relacion_estudiante' para guardar el valor --}}
                        <div class="relation-options" id="relationOptions">
                            <div class="relation-option" data-value="Padre">
                                <i class="bi bi-person-fill"></i>
                                <span>Padre</span>
                            </div>
                            <div class="relation-option" data-value="Madre">
                                <i class="bi bi-person-fill"></i>
                                <span>Madre</span>
                            </div>
                            <div class="relation-option" data-value="Abuelo/a">
                                <i class="bi bi-person-heart"></i>
                                <span>Abuelo/a</span>
                            </div>
                            <div class="relation-option" data-value="Tío/a">
                                <i class="bi bi-people-fill"></i>
                                <span>Tío/a</span>
                            </div>
                            <div class="relation-option" data-value="Hermano/a">
                                <i class="bi bi-people"></i>
                                <span>Hermano/a</span>
                            </div>
                            <div class="relation-option" data-value="Tutor Legal">
                                <i class="bi bi-shield-check"></i>
                                <span>Tutor Legal</span>
                            </div>
                        </div>
                        {{-- Este campo oculto guardará el valor seleccionado por JS --}}
                        <input type="hidden" name="relacion_estudiante" id="relacionInput" required> 
                    </div>
                </div>

                <div class="form-actions">
                    <button type="reset" class="btn btn-secondary">
                        <i class="bi bi-x-lg"></i>
                        Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i>
                        Registrar Tutor
                    </button>
                </div>
            </form>
        </div>

        {{-- TABLA DE TUTORES --}}
        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">
                    <i class="bi bi-table"></i>
                    Tutores Registrados ({{ count($tutores_registrados) ?? 0 }})
                </h2>
                <div class="search-box">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="search-input" id="searchInput" placeholder="Buscar tutores...">
                </div>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Tutor</th>
                            <th>Ocupación</th>
                            <th>Relación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tutoresTable">
                        {{-- DATOS DINÁMICOS DEL CÓDIGO 1 --}}
                        @forelse ($tutores_registrados as $t) 
                            <tr>
                                <td>
                                    <div class="tutor-info">
                                        {{-- Asumiendo que el nombre completo es la primera letra de Nombre y Apellido --}}
                                        <div class="tutor-avatar">{{ substr($t->nombre_completo, 0, 1) . substr(strstr($t->nombre_completo, ' '), 1, 1) }}</div>
                                        <div class="tutor-details">
                                            <span class="tutor-name">{{ $t->nombre_completo }}</span>
                                            {{-- Asumiendo que hay un campo de email en el objeto del tutor --}}
                                            <span class="tutor-email">{{ $t->email ?? 'sin.email@ejemplo.com' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-occupation">{{ $t->ocupacion }}</span></td>
                                <td><span class="badge badge-relation">{{ $t->relacion_estudiante }}</span></td>
                            
                                
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn btn-view" title="Ver detalles">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                        <button class="action-btn btn-edit" title="Editar">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button class="action-btn btn-delete" title="Eliminar">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No hay tutores registrados.</td>
                            </tr>
                        @endforelse
                        {{-- FIN DATOS DINÁMICOS --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pie de página --}}
    @include('includes.pie')
</div>

{{-- Script de selección de parentesco y alerta --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const relationOptions = document.querySelectorAll('.relation-option');
        const relacionInput = document.getElementById('relacionInput');
        
        relationOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Deseleccionar todos
                relationOptions.forEach(opt => opt.classList.remove('selected'));
                
                // Seleccionar el actual
                this.classList.add('selected');
                
                // Asignar valor al campo oculto
                relacionInput.value = this.getAttribute('data-value');
            });
        });

        // Opcional: Manejo de alerta de éxito después del envío con Laravel
        const form = document.getElementById('registroTutor');
        form.addEventListener('submit', function(e) {
            // Solo para simulación en caso de que no haya redirrecionamiento
            // e.preventDefault();
            // console.log('Formulario enviado, esperando respuesta del servidor...');
        });
    });
</script>

</body>
</html>