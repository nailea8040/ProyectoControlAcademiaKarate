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
                <a href="{{ route('principal') }}">Inicio</a>
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
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
                <div>
                    <strong>Error:</strong> {{ session('error') }}
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
            <form id="registroAlumno" method="POST" action="{{ route('alumnos.store') }}" class="form-body" enctype="multipart/form-data"> 
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
    @foreach($usuarios_candidatos as $user)
        {{-- Usamos $user->id y concatenamos nombre/apellido --}}
        <option value="{{ $user->id_usuario }}">{{ $user->nombre_completo }}</option>
    @endforeach
</select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="id_Tutor">
                            Tutor Responsable <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="bi bi-person-lines-fill input-icon"></i>
                            <select id="id_Tutor" class="form-select" name="id_Tutor" required>
    <option value="">Seleccione un tutor</option>
    @foreach($tutores as $tutor)
        <option value="{{ $tutor->id_Tutor }}">{{ $tutor->nombre_completo }}</option>
    @endforeach
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
                            Grado Actual<span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="bi bi-trophy input-icon"></i>
                            <select id="grado" class="form-select" name="grado" required>
                                <option value="">Seleccione un grado</option>
                                 @foreach($grados as $grado)
                                <option value="{{ $grado->id_Grado }}">{{ $grado->nombreGrado }}</option>
                                @endforeach
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
        <label class="form-label" for="documento_medico">
            Documento Médico (PDF) <span class="required">*</span>
        </label>
        
        <!-- Área de Drag & Drop -->
        <div class="upload-area" id="uploadArea">
            <div class="upload-content">
                <i class="bi bi-cloud-arrow-up upload-icon"></i>
                <p class="upload-text">Arrastra archivos aquí o haz clic para seleccionar</p>
                <button type="button" class="btn-upload" id="selectFileBtn">
                    Seleccionar Archivos
                </button>
                <small class="upload-info">Formatos aceptados: PDF, JPG, PNG (máx. 10MB)</small>
            </div>
            <input type="file" 
                   id="documento_medico" 
                   name="documento_medico" 
                   accept=".pdf"
                   style="display: none;"
                   required>
        </div>
        
        <!-- Preview del archivo seleccionado -->
        <div id="file-preview" class="file-preview">
            <div class="file-preview-content">
                <i class="bi bi-file-earmark-pdf file-icon"></i>
                <div class="file-details">
                    <span id="file-name" class="file-name"></span>
                    <span id="file-size" class="file-size"></span>
                </div>
                <button type="button" class="btn-remove" id="removeFileBtn">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    </div>
    
    <small style="color: #757575; margin-top: 5px; display: block;">
        Esta información es confidencial y solo será utilizada para garantizar la seguridad del alumno
    </small>
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
                            <th>Doc. Médico</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    {{-- Usamos tbody de tu código original, ahora con la clase alumnosTable --}}
                    <tbody id="alumnosTable">
                        {{-- Aquí iría un loop de Blade para mostrar los datos reales --}}
                         @foreach($alumnos_registrados as $alumno)
    <tr>
        <td>{{ $alumno->nombre_alumno }}</td>
        <td>{{ $alumno->nombreGrado }}</td>
        <td>{{ $alumno->nombre_tutor }}</td>
        <td>{{ \Carbon\Carbon::parse($alumno->Fecha_inscrip)->format('d/m/Y') }}</td>
        <td>
            @if($alumno->condiciones_medicas)
                <a href="{{ asset('storage/' . $alumno->condiciones_medicas) }}" 
                   target="_blank" 
                   class="btn btn-sm btn-info"
                   title="Ver documento médico">
                    <i class="bi bi-file-earmark-pdf"></i> Ver PDF
                </a>
            @else
                <span class="badge badge-secondary">Sin documento</span>
            @endif
        </td>
        <td><span class="badge badge-success">Activo</span></td>
        <td>
            <button class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i> Editar
            </button>
        </td>
    </tr>
    @endforeach
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
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('documento_medico');
    const selectFileBtn = document.getElementById('selectFileBtn');
    const filePreview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const removeFileBtn = document.getElementById('removeFileBtn');

    // Click en el área de upload
    uploadArea.addEventListener('click', function(e) {
        if (e.target !== selectFileBtn) {
            fileInput.click();
        }
    });

    // Click en el botón de seleccionar
    selectFileBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        fileInput.click();
    });

    // Drag & Drop events
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('drag-over');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    // Cuando se selecciona un archivo
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            handleFileSelect(file);
        }
    });

    // Función para manejar la selección de archivos
    function handleFileSelect(file) {
        // Validar tipo de archivo
        if (file.type !== 'application/pdf') {
            alert('Por favor seleccione solo archivos PDF');
            fileInput.value = '';
            return;
        }
        
        // Validar tamaño (10MB = 10485760 bytes)
        if (file.size > 10485760) {
            alert('El archivo es demasiado grande. Tamaño máximo: 10MB');
            fileInput.value = '';
            return;
        }
        
        // Mostrar preview
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        
        uploadArea.style.display = 'none';
        filePreview.classList.add('active');
    }

    // Remover archivo
    removeFileBtn.addEventListener('click', function() {
        fileInput.value = '';
        uploadArea.style.display = 'block';
        filePreview.classList.remove('active');
    });

    // Formatear tamaño del archivo
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
});
</script>
</body>
</html>