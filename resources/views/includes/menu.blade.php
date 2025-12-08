<nav class="sidebar">
    <div class="sidebar-logo">空手</div>
    
    <ul>
        {{-- Enlace Inicio --}}
        <li>
            <a href="{{ route('principal') }}" 
               class="{{ request()->routeIs('Principal') ? 'activo' : '' }}" 
               title="Inicio">
                <i class="bi bi-house-door"></i>
            </a>
        </li>
        
        {{-- Bloque visible solo para Administrador --}}
        @if(true)
            
            {{-- Enlace Usuarios --}}
            <li>
                <a href="{{ route('usuarios.index') }}" 
                   class="{{ request()->routeIs('usuarios.*') ? 'activo' : '' }}" 
                   title="Usuarios">
                    <i class="bi bi-people"></i>
                </a>
            </li>
            
            {{-- Enlace Alumnos --}}
            <li>
                <a href="{{ route('alumnos.index') }}" 
                   class="{{ request()->routeIs('alumnos.*') ? 'activo' : '' }}" 
                   title="Alumnos">
                    <i class="bi bi-person-badge"></i>
                </a>
            </li>
            
            {{-- Enlace Tutores --}}
            <li>
                <a href="{{ route('tutor.index') }}" 
                   class="{{ request()->routeIs('tutor.*') ? 'activo' : '' }}" 
                   title="Tutores">
                    <i class="bi bi-person-lines-fill"></i>
                </a>
            </li>
            
        @endif
        
        {{-- Enlace Pagos --}}
        <li>
            <a href="{{ route('pagos.index') }}" 
               class="{{ request()->routeIs('pagos.*') ? 'activo' : '' }}" 
               title="Pagos">
                <i class="bi bi-cash-coin"></i>
            </a>
        </li>
        
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
</nav>