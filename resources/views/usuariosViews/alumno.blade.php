<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestión de Alumnos - Dojo</title>
    
    {{-- Usar el layout y CSS compartido --}}
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
                <i class="bi bi-person-badge-fill"></i>
                Gestión de Alumnos
            </h1>
            <div class="breadcrumb">
                <a href="{{ route('principal') }}">Dashboard</a>
                <i class="bi bi-chevron-right"></i>
                <span>Alumnos</span>
            </div>
        </div>
    </header>

    <div class="content-wrapper">
        
        {{-- ALERTA DE ÉXITO (Mantenemos la estructura moderna) --}}
        {{-- Usar Blade para alertas reales aquí, si existen errores o mensajes de sesión --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill alert-icon"></i>
                <div>
                    <strong>¡Éxito!</strong> {{ session('success') }}
                </div>
            </div>
        @endif
        
        <div class="info-card">
            <h4>
                <i class="bi bi-info-circle-fill"></i>
                Información Importante
            </h4>
            <p>
                Los alumnos deben tener un usuario previamente registrado en el sistema. 
                Asegúrate de que el tutor también esté registrado antes de crear el perfil del alumno.
            </p>
        </div>

        {{-- FORMULARIO PARA REGISTRAR ALUMNO (Adaptado de la estructura original) --}}
        <div class="form-container">
            <div class="form-header">
                <h2>
                    <i class="bi bi-person-plus-fill"></i>
                    Registrar Nuevo Alumno
                </h2>
                <p>Complete la información del alumno y sus datos académicos en el dojo</p>
            </div>
            
            {{-- Usamos #registroAlumno de tu código original, envuelto en la estructura moderna --}}
            <form id="registroAlumno" method="POST" action="{{-- URL de registro --}}" class="form-body"> 
                @csrf {{-- ¡No olvidar el token de seguridad! --}}

                <h3 class="section-title-header">
                    <i class="bi bi-person-circle"></i>
                    Información del Alumno
                </h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="id_alumno">
                            Usuario del Alumno <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="bi bi-person-badge input-icon"></i>
                            <select id="id_alumno" class="form-select" name="id_alumno" required>
                                <option value="">Seleccione un usuario</option>
                                {{-- Aquí iría un loop de Blade para usuarios --}}
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="id_tutor">
                            Tutor Responsable <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="bi bi-person-lines-fill input-icon"></i>
                            <select id="id_tutor" class="form-select" name="id_tutor" required>
                                <option value="">Seleccione un tutor</option>
                                {{-- Aquí iría un loop de Blade para tutores --}}
                            </select>
                        </div>
                    </div>
                </div>
                
                <h3 class="section-title-header">
                    <i class="bi bi-award-fill"></i>
                    Información Académica
                </h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="grado">
                            Grado Actual (Cinturón) <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="bi bi-trophy input-icon"></i>
                            <select id="grado" class="form-select" name="grado" required>
                                <option value="">Seleccione un grado</option>
                                {{-- Opciones de Grado --}}
                                <option value="Blanco">🤍 Cinturón Blanco</option>
                                <option value="Amarillo">💛 Cinturón Amarillo</option>
                                <option value="Naranja">🧡 Cinturón Naranja</option>
                                <option value="Verde">💚 Cinturón Verde</option>
                                <option value="Azul">💙 Cinturón Azul</option>
                                <option value="Morado">💜 Cinturón Morado</option>
                                <option value="Café">🤎 Cinturón Café</option>
                                <option value="Negro">🖤 Cinturón Negro</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="Fecha_inscrip">
                            Fecha de Inscripción <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="bi bi-calendar-check input-icon"></i>
                            <input type="date" id="Fecha_inscrip" class="form-input" name="Fecha_inscrip" required>
                        </div>
                    </div>
                </div>
                
                <h3 class="section-title-header">
                    <i class="bi bi-heart-pulse-fill"></i>
                    Información Médica
                </h3>
                <div class="form-grid full-width">
                    <div class="form-group">
                        <label class="form-label" for="condiciones">
                            Condiciones Médicas (Opcional)
                        </label>
                        <div class="form-input-wrapper">
                            <i class="bi bi-journal-medical input-icon" style="top: 20px;"></i>
                            <textarea id="condiciones" class="form-textarea" name="condiciones"
                                      placeholder="Describa cualquier condición médica relevante..."></textarea>
                        </div>
                        <small style="color: #757575; margin-top: 5px; display: block;">
                            Esta información es confidencial y solo será utilizada para garantizar la seguridad del alumno
                        </small>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="reset" class="btn btn-secondary">
                        <i class="bi bi-x-lg"></i>
                        Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i>
                        Registrar Alumno
                    </button>
                </div>
            </form>
        </div>

        {{-- TABLA DE ALUMNOS (Mantenemos la estructura moderna) --}}
        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">
                    <i class="bi bi-table"></i>
                    Alumnos Registrados (12)
                </h2>
                <div class="search-box">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="search-input" id="searchInput" placeholder="Buscar alumnos...">
                </div>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Alumno</th>
                            <th>Grado</th>
                            <th>Tutor</th>
                            <th>Inscripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    {{-- Usamos tbody de tu código original, ahora con la clase alumnosTable --}}
                    <tbody id="alumnosTable">
                        {{-- Aquí iría un loop de Blade para mostrar los datos reales --}}
                        
                        {{-- EJEMPLOS DE DATOS DEL DISEÑO MODERNO --}}
                        <tr>
                            <td>
                                <div class="student-info">
                                    <div class="student-avatar">JP</div>
                                    <div class="student-details">
                                        <span class="student-name">Juan Pérez González</span>
                                        <span class="student-tutor">juan.perez@ejemplo.com</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-yellow">Cinturón Amarillo</span></td>
                            <td>Ana García Torres</td>
                            <td>15/01/2024</td>
                            <td><span class="badge badge-success">Activo</span></td>
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
                        {{-- FIN EJEMPLOS --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pie de página --}}
    @include('includes.pie') {{-- Asumiendo que tienes un footer incluido --}}
</div>

{{-- Script de JS del diseño moderno (Puedes moverlo a un archivo .js) --}}
<script>
    document.getElementById('registroAlumno').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Simular lógica de éxito
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            successAlert.style.display = 'flex';
            window.scrollTo({ top: 0, behavior: 'smooth' });
            this.reset();
            
            setTimeout(() => {
                successAlert.style.display = 'none';
            }, 5000);
        }
    });

    // Lógica de búsqueda (si no usas Vue/Livewire)
    // document.getElementById('searchInput').addEventListener('input', function() { /* ... */ });
</script>

</body>
</html>