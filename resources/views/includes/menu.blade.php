<nav class="sidebar">
    <ul>
        {{-- Enlace Inicio (Siempre visible) --}}
        <li>
            <a href="{{ route('principal') }}" 
               class="{{ request()->routeIs('principal') ? 'activo' : '' }}" 
               title="Inicio">
                <i class="bi bi-house-door"></i>
                @if(request()->routeIs('principal'))
                    <i class="bi bi-arrow-right-circle-fill active-indicator"></i>
                @endif
            </a>
        </li>
        
        {{-- Bloque de Enlaces Visibles SOLO para el Rol de Administrador --}}
        {{-- Asegúrate de que Auth::user()->rol es la forma correcta de acceder al rol --}}
        @if(true)
            
            {{-- Enlace Usuarios (Registro de Usuario) --}}
            <li>
                <a href="{{ route('usuarios.index') }}" 
                   class="{{ request()->routeIs('usuarios.*') ? 'activo' : '' }}" 
                   title="Usuarios">
                    <i class="bi bi-people"></i>
                    @if(request()->routeIs('usuarios.*'))
                        <i class="bi bi-arrow-right-circle-fill active-indicator"></i>
                    @endif
                </a>
            </li>
            
            {{-- Enlace Alumnos (Registro de Alumnos) --}}
            <li>
                <a href="{{ route('alumnos.index') }}" 
                   class="{{ request()->routeIs('alumnos.*') ? 'activo' : '' }}" 
                   title="Alumnos">
                    <i class="bi bi-person-badge"></i>
                    @if(request()->routeIs('alumnos.*'))
                        <i class="bi bi-arrow-right-circle-fill active-indicator"></i>
                    @endif
                </a>
            </li>
            
            {{-- Enlace Tutores (Registro de Tutores) --}}
            <li>
                <a href="{{ route('tutor.index') }}" 
                   class="{{ request()->routeIs('tutor.*') ? 'activo' : '' }}" 
                   title="Tutores">
                    <i class="bi bi-person-lines-fill"></i>
                    @if(request()->routeIs('tutor.*'))
                        <i class="bi bi-arrow-right-circle-fill active-indicator"></i>
                    @endif
                </a>
            </li>
            
        @endif
        
        {{-- Enlace Pagos (Visible para todos, incluido el administrador) --}}
        <li>
            <a href="{{ route('pagos.index') }}" 
               class="{{ request()->routeIs('pagos.*') ? 'activo' : '' }}" 
               title="Pagos">
                <i class="bi bi-cash-coin"></i>
                @if(request()->routeIs('pagos.*'))
                    <i class="bi bi-arrow-right-circle-fill active-indicator"></i>
                @endif
            </a>
        </li>
        
        {{-- Enlace Salir/Logout --}}
        <li>
            <a href="#" 
               title="Salir" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </li>
        
        {{-- Formulario oculto de Cierre de Sesión --}}
        {{-- Este formulario es el que dispara la solicitud POST a la ruta 'logout' --}}
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </ul>
</nav>