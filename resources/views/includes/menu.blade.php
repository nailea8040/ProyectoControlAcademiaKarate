<nav class="menu">
    <ul>
        <li><a href="#">Inicio</a></li>
        <li><a href="{{ route('alumnos.index') }}">Alumnos</a></li>
        <li><a href="{{ route('tutor.index') }}">Tutores</a></li>
        <li><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
        <li><a href="{{ route('pagos.index') }}">Pagos</a></li>
        {{-- Aquí puedes añadir más enlaces o iconos --}}
    </ul>
</nav>