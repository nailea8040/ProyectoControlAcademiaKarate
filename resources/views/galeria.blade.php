<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Galería Multimedia - Dojo Karate</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/estilo2.css') }}">
    
    <style>
        :root {
            --karate-red: #e85654;
            --karate-dark: #4A4A4A;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .gallery-wrapper {
            background: white;
            border-radius: 30px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        /* Filtros */
        .filter-pills {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2.5rem;
            justify-content: center;
        }

        .filter-pill {
            background: #f8f9fa;
            border: 2px solid transparent;
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-pill:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }

        .filter-pill.active {
            background: linear-gradient(135deg, var(--karate-red), #d43f3d);
            color: white;
            border-color: var(--karate-red);
            box-shadow: 0 8px 25px rgba(232, 86, 84, 0.4);
        }

        /* Grid de Galería */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .gallery-item {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            background: #f8f9fa;
            cursor: pointer;
        }

        .gallery-item:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
        }

        .gallery-item img,
        .gallery-item video {
            width: 100%;
            height: 300px;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }

        .gallery-item:hover img,
        .gallery-item:hover video {
            transform: scale(1.1);
        }

        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.8));
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 1.5rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-info {
            color: white;
            margin-bottom: 1rem;
        }

        .gallery-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .gallery-meta {
            font-size: 0.85rem;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .gallery-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-gallery-action {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: none;
            color: white;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-gallery-action:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }

        .btn-gallery-action.delete:hover {
            background: var(--karate-red);
        }

        /* Badge de tipo */
        .media-type-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: linear-gradient(135deg, rgba(0,0,0,0.8), rgba(0,0,0,0.6));
            backdrop-filter: blur(10px);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            z-index: 10;
        }

        /* Lightbox */
        .lightbox-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.95);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .lightbox-overlay.show {
            display: flex;
        }

        .lightbox-content {
            max-width: 90%;
            max-height: 90%;
            position: relative;
        }

        .lightbox-content img,
        .lightbox-content video {
            max-width: 100%;
            max-height: 90vh;
            border-radius: 15px;
            box-shadow: 0 30px 90px rgba(0,0,0,0.5);
        }

        .btn-close-lightbox {
            position: absolute;
            top: -50px;
            right: 0;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-close-lightbox:hover {
            background: var(--karate-red);
            transform: rotate(90deg);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 5rem;
            color: #dee2e6;
            margin-bottom: 1.5rem;
        }

        .empty-state h3 {
            color: var(--karate-dark);
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: #6c757d;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1.5rem;
            }

            .filter-pills {
                flex-direction: column;
            }

            .filter-pill {
                width: 100%;
                justify-content: center;
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
                        <i class="bi bi-images"></i>
                        Galería Multimedia
                    </h1>
                    <div class="breadcrumb">
                        <a href="{{ route('principal') }}">Inicio</a>
                        <i class="bi bi-chevron-right"></i>
                        <span>Galería</span>
                    </div>
                </div>

                @if(Auth::check() && Auth::user()->rol == 'administrador')
                <button class="btn btn-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="bi bi-cloud-upload me-2"></i> Subir Archivo
                </button>
                @endif
            </div>
        </header>

        <div class="content-wrapper">
            <div class="gallery-wrapper">
                <!-- Filtros -->
                <div class="filter-pills">
                    <button class="filter-pill active" data-filter="all">
                        <i class="bi bi-grid-3x3-gap"></i>
                        <span>Todos</span>
                    </button>
                    <button class="filter-pill" data-filter="image">
                        <i class="bi bi-image"></i>
                        <span>Imágenes</span>
                    </button>
                    <button class="filter-pill" data-filter="video">
                        <i class="bi bi-play-circle"></i>
                        <span>Videos</span>
                    </button>
                </div>

                <!-- Grid de Galería -->
                <div class="gallery-grid" id="galleryGrid">
                    @forelse($archivos as $archivo)
                    <div class="gallery-item" data-type="{{ $archivo->tipo }}" onclick="openLightbox('{{ asset('storage/' . $archivo->ruta) }}', '{{ $archivo->tipo }}')">
                        <!-- Badge de tipo -->
                        <div class="media-type-badge">
                            @if($archivo->tipo == 'image')
                                <i class="bi bi-image-fill"></i> Imagen
                            @else
                                <i class="bi bi-play-circle-fill"></i> Video
                            @endif
                        </div>

                        <!-- Contenido -->
                        @if($archivo->tipo == 'image')
                            <img src="{{ asset('storage/' . $archivo->ruta) }}" alt="{{ $archivo->titulo }}">
                        @else
                            <video>
                                <source src="{{ asset('storage/' . $archivo->ruta) }}" type="video/mp4">
                            </video>
                        @endif

                        <!-- Overlay con información -->
                        <div class="gallery-overlay" onclick="event.stopPropagation();">
                            <div class="gallery-info">
                                <div class="gallery-title">{{ $archivo->titulo }}</div>
                                <div class="gallery-meta">
                                    <i class="bi bi-calendar3"></i>
                                    {{ \Carbon\Carbon::parse($archivo->created_at)->format('d/m/Y') }}
                                </div>
                            </div>
                            
                            <div class="gallery-actions">
                                <button class="btn-gallery-action" onclick="event.stopPropagation(); openLightbox('{{ asset('storage/' . $archivo->ruta) }}', '{{ $archivo->tipo }}')">
                                    <i class="bi bi-zoom-in"></i>
                                </button>
                                
                                @if(Auth::check() && Auth::user()->rol == 'administrador')
                                <button class="btn-gallery-action delete" onclick="event.stopPropagation(); deleteMedia({{ $archivo->id_gal }}, '{{ $archivo->titulo }}')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-images"></i>
                            <h3>No hay archivos en la galería</h3>
                            <p>Sube tu primera imagen o video para comenzar</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Lightbox -->
    <div class="lightbox-overlay" id="lightbox" onclick="closeLightbox()">
        <div class="lightbox-content" onclick="event.stopPropagation()">
            <button class="btn-close-lightbox" onclick="closeLightbox()">×</button>
            <div id="lightboxMedia"></div>
        </div>
    </div>

    <!-- Modal de Subida (Solo Administradores) -->
    @if(Auth::check() && Auth::user()->rol == 'administrador')
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 25px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, var(--karate-red), #d43f3d); border-radius: 25px 25px 0 0;">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-cloud-upload me-2"></i>Subir Archivo Multimedia
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <form action="{{ route('galeria.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Título del Archivo</label>
                            <input type="text" name="titulo" class="form-control" placeholder="Ej: Torneo Regional 2025" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tipo de Archivo</label>
                            <select name="tipo" id="tipoArchivo" class="form-select" required onchange="updateFileAccept()">
                                <option value="">Selecciona el tipo</option>
                                <option value="image">📸 Imagen (JPG, PNG)</option>
                                <option value="video">🎥 Video (MP4)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Archivo</label>
                            <input type="file" name="archivo" id="archivoInput" class="form-control" required>
                            <div class="form-text">Tamaño máximo: 50MB</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción (Opcional)</label>
                            <textarea name="descripcion" class="form-control" rows="2" placeholder="Agrega detalles sobre este archivo..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger px-4 rounded-pill">
                            <i class="bi bi-upload me-2"></i>Subir Archivo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filtros
        const filterButtons = document.querySelectorAll('.filter-pill');
        const galleryItems = document.querySelectorAll('.gallery-item');

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Actualizar botón activo
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                // Filtrar items
                const filter = button.getAttribute('data-filter');
                
                galleryItems.forEach(item => {
                    const itemType = item.getAttribute('data-type');
                    
                    if (filter === 'all' || itemType === filter) {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'scale(1)';
                        }, 10);
                    } else {
                        item.style.opacity = '0';
                        item.style.transform = 'scale(0.8)';
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });

        // Lightbox
        function openLightbox(src, type) {
            const lightbox = document.getElementById('lightbox');
            const mediaContainer = document.getElementById('lightboxMedia');
            
            if (type === 'image') {
                mediaContainer.innerHTML = `<img src="${src}" alt="Imagen ampliada">`;
            } else {
                mediaContainer.innerHTML = `
                    <video controls autoplay style="max-width: 100%; max-height: 90vh; border-radius: 15px;">
                        <source src="${src}" type="video/mp4">
                    </video>
                `;
            }
            
            lightbox.classList.add('show');
        }

        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            const mediaContainer = document.getElementById('lightboxMedia');
            
            lightbox.classList.remove('show');
            
            // Detener video si está reproduciéndose
            const video = mediaContainer.querySelector('video');
            if (video) {
                video.pause();
            }
            
            setTimeout(() => {
                mediaContainer.innerHTML = '';
            }, 300);
        }

        // Cerrar lightbox con tecla ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });

        // Actualizar accept del input file según el tipo
        function updateFileAccept() {
            const tipo = document.getElementById('tipoArchivo').value;
            const fileInput = document.getElementById('archivoInput');
            
            if (tipo === 'image') {
                fileInput.setAttribute('accept', 'image/jpeg,image/jpg,image/png');
            } else if (tipo === 'video') {
                fileInput.setAttribute('accept', 'video/mp4');
            } else {
                fileInput.removeAttribute('accept');
            }
        }

        // Eliminar archivo
        function deleteMedia(id_gal, titulo) {
            if (confirm(`¿Estás seguro de que deseas eliminar "${titulo}"?\n\nEsta acción no se puede deshacer.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/galeria/${id_gal}`;
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
    </script>

</body>
</html>