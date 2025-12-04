<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Alumnos</title>
  <link rel="stylesheet" href="css/estilo2.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<head>
    <title>Usuario </title>
    <link rel="stylesheet" href="{{ asset('css/estilo2.css') }}"> 
    </head>

<body>
@include('includes.menu') 
  <!-- Menú general -->
  <div id="menu-container"></div>

  <div class="main-content">
    <header>
      <h1 class="text-center">Gestión de Alumnos</h1>
    </header>

    <div class="content">

      <!-- FORMULARIO PARA REGISTRAR ALUMNO -->
      <form id="registroAlumno">
        <h2 class="text-center">Registrar Alumno</h2>

        <div class="mb-3">
          <label class="form-label">Usuario Alumno</label>
          <select id="id_alumno" class="form-control" required></select>
        </div>

        <div class="mb-3">
          <label class="form-label">Tutor</label>
          <select id="id_tutor" class="form-control" required></select>
        </div>

        <div class="mb-3">
          <label class="form-label">Grado Actual</label>
          <select id="grado" class="form-control" required></select>
        </div>

        <div class="mb-3">
          <label class="form-label">Fecha de Inscripción</label>
          <input type="date" id="Fecha_inscrip" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Condiciones Médicas</label>
          <textarea id="condiciones" class="form-control" rows="3" placeholder="Describa condiciones médicas (opcional)"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Registrar Alumno</button>
      </form>

      <!-- TABLA DE ALUMNOS -->
      <div class="table-responsive" style="margin-left: 60px;">
        <h2 class="text-center mt-4">Alumnos Registrados</h2>

        <table class="table table-striped table-hover align-middle" style="color:black">
          <thead class="table-dark">
            <tr>
              <th>Nombre</th>
              <th>Tutor</th>
              <th>Grado Actual</th>
              <th>Fecha de Inscripción</th>
              <th>Condiciones Médicas</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="tabla-alumnos">
            <!-- Se llena dinámicamente desde JS -->
          </tbody>
        </table>
      </div>

    </div>

    <!-- Pie de página -->
    <div id="pie-container"></div>

  </div>

  <!-- JS Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Archivo JS separado -->
  <script src="js/alumnos.js"></script>

</body>
</html>
