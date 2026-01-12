<nav class="sidebar">
    <div class="sidebar-logo">空手</div>
    
    @auth 
        <ul>
            {{-- Enlace Principal (Acceso Básico) --}}
            @can('acceso-basico')
            <li>
                <a href="{{ route('principal') }}" 
                   title="Inicio"
                   class="{{ Request::routeIs('principal') ? 'activo' : '' }}">
                    <i class="bi bi-house-door"></i>
                </a>
            </li>
            @endcan

            {{-- NUEVO: Enlace al Calendario (Visible para todos los usuarios autenticados) --}}
            @can('acceso-basico')
            <li>
                <a href="{{ route('calendario.index') }}" title="Calendario de Eventos" class="{{ Request::routeIs('calendario.*') ? 'activo' : '' }}">
                    <i class="bi bi-calendar3"></i>
                </a>
            </li>
            @endcan
            
            
            {{-- Bloque de Gestión (solo visible si el usuario puede 'acceso-gestion') --}}
            @can('acceso-gestion')
                
                {{-- Enlace Usuarios --}}
                <li>
                    <a href="{{ route('usuarios.index') }}" 
                       title="Usuarios"
                       class="@if(Request::routeIs('usuarios.*')) activo @endif">
                        <i class="bi bi-people"></i>
                    </a>
                </li>
                
                {{-- Enlace Alumnos --}}
                <li>
                    <a href="{{ route('alumnos.index') }}" 
                       title="Alumnos"
                       class="{{ Request::routeIs('alumnos.*') ? 'activo' : '' }}">
                        <i class="bi bi-person-badge"></i>
                    </a>
                </li>
                
                {{-- Enlace Tutores --}}
                <li>
                    <a href="{{ route('tutor.index') }}" 
                       title="Tutores"
                       class="{{ Request::routeIs('tutor.*') ? 'activo' : '' }}">
                        <i class="bi bi-person-lines-fill"></i>
                    </a>
                </li>
                
            @endcan
            
            {{-- Enlace Pagos (Acceso Básico) --}}
            @can('acceso-basico')
            <li>
                <a href="{{ route('pagos.index') }}" 
                   title="Pagos"
                   class="{{ Request::routeIs('pagos.*') ? 'activo' : '' }}">
                    <i class="bi bi-cash-coin"></i>
                </a>
            </li>
            @endcan

            {{-- Enlace Galería (Acceso Básico) --}}
            @can('acceso-basico')
            <li>
                <a href="{{ route('galeria.index') }}" 
                   title="Galería Multimedia"
                   class="{{ Request::routeIs('galeria.*') ? 'activo' : '' }}">
                    <i class="bi bi-images"></i>
                </a>
            </li>
            @endcan

            {{-- Enlace Perfil (Visible para todos los usuarios autenticados) --}}
            @can('acceso-basico')
            <li>
                <a href="{{ route('perfil') }}" 
                   title="Mi Perfil"
                   class="{{ Request::routeIs('perfil') ? 'activo' : '' }}">
                    <i class="bi bi-person-circle"></i>
                </a>
            </li>
            @endcan
            
            {{-- Enlace Salir --}}
            <li>
                <a href="#" 
                   title="Salir" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </li>
        </ul>
        
        {{-- Formulario de Logout --}}
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    
    @endauth
    
    @guest
        {{-- Enlace Login si no está autenticado --}}
        <ul>
            <li><a href="{{ route('login') }}" title="Ingresar"><i class="bi bi-box-arrow-in-right"></i></a></li>
        </ul>
    @endguest
</nav>