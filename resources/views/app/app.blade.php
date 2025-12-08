<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dojo Karate</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @include('partials.nav')
    <main>
        @yield('content')
    </main>
</body>
</html>

# resources/views/partials/nav.blade.php
<nav class="navbar">
    <div class="logo">Dojo Karate</div>
    <ul>
        <li><a href="/">Inicio</a></li>
        <li><a href="/nosotros">Nosotros</a></li>
        <li><a href="/clases">Clases</a></li>
        <li><a href="/horarios">Horarios</a></li>
        <li><a href="/maestros">Maestros</a></li>
        <li><a href="/contacto" class="btn-contacto">Contacto</a></li>
    </ul>
</nav>

# resources/views/home.blade.php
@extends('layouts.app')
@section('content')
<section class="hero">
    <div class="overlay"></div>
    <div class="hero-content">
        <h2>Descubre el Camino del Samurai</h2>
        <p>Entrena cuerpo y mente en nuestro dojo. Aprende karate con maestros expertos.</p>
        <div class="actions">
            <a href="/clases" class="btn-primary">Ver Clases</a>
            <a href="/registro" class="btn-secondary">Clase Gratuita</a>
        </div>
    </div>
</section>
@endsection

# public/css/style.css
body { margin: 0; font-family: Arial; }
.navbar { display: flex; justify-content: space-between; padding: 20px; background: #333; color: #fff; }
.navbar ul { list-style: none; display: flex; gap: 20px; }
.navbar a { color: #fff; text-decoration: none; }
.hero { height: 90vh; background: url('/images/karate.jpg') center/cover no-repeat; position: relative; }
.overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.4); }
.hero-content { position: relative; top: 40%; text-align: center; color: #fff; }
.btn-primary { padding: 10px 20px; background: #e74c3c; color: #fff; border-radius: 5px; }
.btn-secondary { padding: 10px 20px; border: 1px solid #fff; color: #fff; border-radius: 5px; }

# resources/views/nosotros.blade.php
@extends('layouts.app')
@section('content')
<section class="section">
    <h2 class="title">Sobre Nosotros</h2>
    <p class="text">Somos un dojo dedicado a la enseñanza del karate tradicional, promoviendo disciplina, respeto y fortaleza física y mental.</p>
    <div class="info-grid">
        <div class="card">
            <h3>Misión</h3>
            <p>Formar estudiantes íntegros mediante la práctica disciplinada del karate.</p>
        </div>
        <div class="card">
            <h3>Visión</h3>
            <p>Ser un dojo reconocido por su calidad académica y formativa.</p>
        </div>
        <div class="card">
            <h3>Valores</h3>
            <p>Respeto, disciplina, perseverancia y humildad.</p>
        </div>
    </div>
</section>
@endsection

# resources/views/clases.blade.php
@extends('layouts.app')
@section('content')
<section class="section">
    <h2 class="title">Clases</h2>
    <div class="cards">
        <div class="class-card">
            <h3>Principiantes</h3>
            <p>Enfocado en técnicas básicas, postura y disciplina.</p>
        </div>
        <div class="class-card">
            <h3>Intermedios</h3>
            <p>Desarrollo de katas, combate básico y fortalecimiento físico.</p>
        </div>
        <div class="class-card">
            <h3>Avanzados</h3>
            <p>Entrenamientos intensivos y perfeccionamiento técnico.</p>
        </div>
    </div>
</section>
@endsection

# resources/views/horarios.blade.php
@extends('layouts.app')
@section('content')
<section class="section">
    <h2 class="title">Horarios</h2>
    <table class="tabla-horarios">
        <tr><th>Día</th><th>Clase</th><th>Horario</th></tr>
        <tr><td>Lunes</td><td>Principiantes</td><td>4:00 PM - 5:00 PM</td></tr>
        <tr><td>Martes</td><td>Intermedios</td><td>5:00 PM - 6:00 PM</td></tr>
        <tr><td>Miércoles</td><td>Avanzados</td><td>6:00 PM - 7:00 PM</td></tr>
    </table>
</section>
@endsection

# resources/views/maestros.blade.php
@extends('layouts.app')
@section('content')
<section class="section">
    <h2 class="title">Maestros</h2>
    <div class="maestros-grid">
        <div class="maestro-card">
            <img src="/images/maestro1.jpg" class="maestro-img">
            <h3>Sensei Akira</h3>
            <p>Experto en karate Shotokan con más de 20 años de experiencia.</p>
        </div>
        <div class="maestro-card">
            <img src="/images/maestro2.jpg" class="maestro-img">
            <h3>Sensei Miyagi</h3>
            <p>Especialista en combate y técnicas avanzadas.</p>
        </div>
    </div>
</section>
@endsection

# resources/views/contacto.blade.php
@extends('layouts.app')
@section('content')
<section class="section">
    <h2 class="title">Contacto</h2>
    <form class="form-contacto">
        <input type="text" placeholder="Nombre" required>
        <input type="email" placeholder="Correo" required>
        <textarea placeholder="Mensaje" required></textarea>
        <button class="btn-primary" type="submit">Enviar</button>
    </form>
</section>
@endsection
