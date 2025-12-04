<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tutores</title>

  <link rel="stylesheet" href="{{ asset('css/estilo2.css') }}"> 
  <link rel="stylesheet" href="{{ asset('css/estilo6.css') }}"> 

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<head>
    <title>Usuario </title>
    <link rel="stylesheet" href="{{ asset('css/estilo2.css') }}"> 
    </head>

<body>

@include('includes.menu') 

  <div class="main-content">
    <header>
      <h1 class="text-center">Gestión de Tutores</h1>
    </header>

    <div class="content">

      <form id="registroTutor" method="post" action="{{ route('tutor.store') }}">
        @csrf {{-- ¡CRÍTICO! Token de seguridad de Laravel --}}
        
        <div class="text-center">
          <h2>Registro de Tutor</h2>
        </div>

        <div class="mb-3">
          <label for="id_tutor" class="form-label">Usuario Tutor</label>
          <select name="id_tutor" id="id_tutor" class="form-control" required>
            <option value="">Seleccione un usuario</option>
            
            {{-- ELIMINAMOS fetch_assoc() y usamos @foreach --}}
            @foreach($usuarios_tutor as $u) 
              <option value="{{ $u->id_tutor }}">{{ $u->nombre_completo }}</option>
            @endforeach
            
          </select>
        </div>

        <div class="mb-3">
          <label for="ocupacion" class="form-label">Ocupación</label>
          <input type="text" name="ocupacion" id="ocupacion" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="relacion_estudiante" class="form-label">Relación con el Estudiante</label>
          <input type="text" name="relacion_estudiante" id="relacion_estudiante" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Registrar</button>
      </form>

      <div class="table-responsive tabla-tutores">
        <h2 class="text-center mt-4">Tutores Registrados</h2>

        <table class="table table-striped table-hover align-middle tabla-custom">
          <thead class="table-dark">
            <tr>
              <th>Nombre</th>
              <th>Ocupación</th>
              <th>Relación con el Estudiante</th>
              <th>Acciones</th>
            </tr>
          </thead>

          <tbody>
            {{-- ELIMINAMOS fetch_assoc() y usamos @forelse para manejar el caso vacío --}}
            @forelse ($tutores_registrados as $t) 
              <tr>
                {{-- Usamos los nombres de columna del Controller (nombre_completo) --}}
                <td>{{ $t->nombre_completo }}</td> 
                <td>{{ $t->ocupacion }}</td>
                <td>{{ $t->relacion_estudiante }}</td>
                  
                <td>
                  <button class="btn btn-sm btn-warning" disabled>
                    <i class="bi bi-pencil-square"></i>
                  </button>

                  <button class="btn btn-sm btn-danger" disabled>
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center">No hay tutores registrados.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>

  @include('includes.pie')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>