<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestión de Alumnos - Dojo</title>
    
    {{-- Usar el layout y CSS compartido --}}
    <link rel="stylesheet" href="{{ asset('css/estilo2.css') }}"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
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
        <small style="color: #757575; margin-top: 5px; display: block;">
        Esta información es confidencial y solo será utilizada para garantizar la seguridad del alumno
    </small>
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
                            <input type="text" class="search-input" id="searchInput" placeholder="Buscar por nombre, correo o rol...">
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
            <button type="button" class="action-btn btn-edit edit-alumno-btn" 
    data-id="{{ $alumno->id_alumno }}"
    data-nombre="{{ $alumno->nombre_alumno }}"
    data-tutor="{{ $alumno->id_Tutor }}"
    data-grado="{{ $alumno->id_Grado }}"
    data-fecha="{{ $alumno->Fecha_inscrip }}"
    title="Editar">
    <i class="bi bi-pencil-fill"></i>
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
    @include('includes.pie') 
</div>

    <!-- Modal Moderno para Editar Alumno -->
<div id="editModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div>
                <h2 class="modal-title">
                    <i class="bi bi-pencil-square"></i>
                    Editar Alumno
                </h2>
                <p class="modal-subtitle" id="editNombreAlumno"></p>
            </div>
            <button type="button" class="modal-close" onclick="closeEditModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <form id="editForm" method="POST" enctype="multipart/form-data" class="modal-body">
            @csrf
            @method('PUT')
            
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="bi bi-person-circle"></i>
                    Información del Alumno
                </h3>
                
                <div class="form-row full-width">
                    <div class="form-field">
                        <label class="field-label" for="edit_id_Tutor">
                            Tutor Responsable <span class="required">*</span>
                        </label>
                        <div class="field-wrapper">
                            <i class="bi bi-person-lines-fill field-icon"></i>
                            <select id="edit_id_Tutor" name="id_Tutor" class="field-input" required>
                                @foreach($tutores as $tutor)
                                    <option value="{{ $tutor->id_Tutor }}">{{ $tutor->nombre_completo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-row full-width">
                    <div class="form-field">
                        <label class="field-label" for="edit_id_Grado">
                            Grado Actual <span class="required">*</span>
                        </label>
                        <div class="field-wrapper">
                            <i class="bi bi-award-fill field-icon"></i>
                            <select id="edit_id_Grado" name="id_Grado" class="field-input" required>
                                @foreach($grados as $grado)
                                    <option value="{{ $grado->id_Grado }}">{{ $grado->nombreGrado }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-row full-width">
                    <div class="form-field">
                        <label class="field-label" for="edit_Fecha_inscrip">
                            Fecha de Inscripción <span class="required">*</span>
                        </label>
                        <div class="field-wrapper">
                            <i class="bi bi-calendar-check field-icon"></i>
                            <input type="date" id="edit_Fecha_inscrip" name="Fecha_inscrip" class="field-input" required>
                        </div>
                    </div>
                </div>

                   
                <div class="form-row full-width">
                    <div class="form-field">
                        <label class="field-label" for="edit_documento_medico">
                            <i class="bi bi-file-earmark-pdf"></i>
                            Actualizar Documento Médico (Opcional)
                        </label>
                        <div class="file-upload-container">
                            <div class="file-upload-box">
                                <div class="file-upload-header">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                    <p>Seleccionar nuevo documento PDF</p>
                                </div>
                                <input type="file" id="edit_documento_medico" name="documento_medico" class="file-input-hidden" accept=".pdf">
                                <button type="button" class="btn-select-file" onclick="document.getElementById('edit_documento_medico').click()">
                                    <i class="bi bi-folder2-open"></i>
                                    Buscar archivo
                                </button>
                                <small class="file-upload-hint">PDF (máx. 5MB) - Si no selecciona, se mantendrá el actual</small>
                            </div>
                            <div id="edit-file-preview" class="edit-file-preview">
                                <i class="bi bi-file-earmark-pdf" style="font-size: 28px; color: #dc3545;"></i>
                                <span id="edit-file-name" style="flex: 1; color: #333; font-weight: 500;"></span>
                                <button type="button" class="btn-remove-file" onclick="removeEditFile()">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn-modal btn-cancel" onclick="closeEditModal()">
                    <i class="bi bi-x-circle"></i>
                    Cancelar
                </button>
                <button type="submit" class="btn-modal btn-save">
                    <i class="bi bi-check-circle"></i>
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>


<script>
// Script para manejo de carga de archivos (Drag & Drop)
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('documento_medico');
    const selectFileBtn = document.getElementById('selectFileBtn');
    const filePreview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const removeFileBtn = document.getElementById('removeFileBtn');

    if (uploadArea && fileInput) {
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
                showNotification('Por favor seleccione solo archivos PDF', 'error');
                fileInput.value = '';
                return;
            }
            
            // Validar tamaño (10MB = 10485760 bytes)
            if (file.size > 10485760) {
                showNotification('El archivo es demasiado grande. Tamaño máximo: 10MB', 'error');
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
        if (removeFileBtn) {
            removeFileBtn.addEventListener('click', function() {
                fileInput.value = '';
                uploadArea.style.display = 'block';
                filePreview.classList.remove('active');
            });
        }

        // Formatear tamaño del archivo
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
    }
});

// Script para manejo del Modal de Edición
document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editModal');
    const editForm = document.getElementById('editForm');
    const editNombreAlumno = document.getElementById('editNombreAlumno');

    if (editModal && editForm) {
        // Abrir Modal y llenar campos
        document.querySelectorAll('.edit-alumno-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nombre = this.getAttribute('data-nombre');
                const tutor = this.getAttribute('data-tutor');
                const grado = this.getAttribute('data-grado');
                const fecha = this.getAttribute('data-fecha');

                // Llenar campos
                if (editNombreAlumno) {
                    editNombreAlumno.textContent = nombre;
                }
                
                const tutorSelect = document.getElementById('edit_id_Tutor');
                const gradoSelect = document.getElementById('edit_id_Grado');
                const fechaInput = document.getElementById('edit_Fecha_inscrip');
                
                if (tutorSelect) tutorSelect.value = tutor;
                if (gradoSelect) gradoSelect.value = grado;
                if (fechaInput) fechaInput.value = fecha;

                // Cambiar dinámicamente el ACTION del form
                editForm.action = '{{ url("/alumnos") }}/' + id;

                // Mostrar modal con animación
                editModal.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevenir scroll del body
            });
        });
    }
});

// Función para cerrar modal
function closeEditModal() {
    const editModal = document.getElementById('editModal');
    if (editModal) {
        editModal.classList.remove('active');
        document.body.style.overflow = ''; // Restaurar scroll del body
    }
}

// Cerrar si hacen clic fuera del modal
window.addEventListener('click', function(event) {
    const editModal = document.getElementById('editModal');
    if (event.target === editModal) {
        closeEditModal();
    }
});

// Cerrar con tecla ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const editModal = document.getElementById('editModal');
        if (editModal && editModal.classList.contains('active')) {
            closeEditModal();
        }
    }
});

// Sistema de notificaciones (opcional - mejor que alert())
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="bi bi-${type === 'error' ? 'exclamation-triangle' : 'check-circle'}-fill"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    // Mostrar con animación
    setTimeout(() => notification.classList.add('show'), 10);
    
    // Ocultar después de 3 segundos
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Función de búsqueda en tabla
$(document).ready(function() {
    $('#searchInput').on('keyup', function() {
        const searchText = $(this).val().toLowerCase();
        $('#alumnosTable tbody tr').each(function() {
            const rowText = $(this).text().toLowerCase();
            if (rowText.includes(searchText)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
</script>

<style>
/* Estilos para las notificaciones */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    padding: 16px 20px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 9999;
    transform: translateX(400px);
    transition: transform 0.3s ease;
    min-width: 300px;
}

.notification.show {
    transform: translateX(0);
}

.notification i {
    font-size: 24px;
}

.notification-error {
    border-left: 4px solid #dc3545;
}

.notification-error i {
    color: #dc3545;
}

.notification-success {
    border-left: 4px solid #28a745;
}

.notification-success i {
    color: #28a745;
}

.notification-info {
    border-left: 4px solid #17a2b8;
}

.notification-info i {
    color: #17a2b8;
}

.notification span {
    font-size: 14px;
    color: #333;
    font-weight: 500;
}

/* Estilos para el body cuando el modal está activo */
body.modal-open {
    overflow: hidden;
}
</style>
</body>
</html>