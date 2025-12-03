<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Pagos</title>

  <link rel="stylesheet" href="/public/css/estilo2.css"> 

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

  <nav class="menu">...</nav> 

  <div class="main-content">
    <header>
      <h1 class="text-center">Gestión de Pagos</h1>
    </header>

    <div class="content">

        @if(session('mensaje'))
            <div id="alerta-temp" class="alert {{ session('sessionInsertado') == 'true' ? 'alert-success' : 'alert-danger' }} text-center" role="alert">
                {{ session('mensaje') }}
            </div>
        @endif

      <form id="registroPago" method="POST" action="{{ route('pagos.store') }}">
        @csrf
        <h2 class="text-center">Registrar Pago</h2>

        <div class="mb-3">
            <label class="form-label">Alumno</label>
            <select name="id_alumno" required class="form-control">
                <option value="">Seleccione Alumno</option>
                @foreach($alumnos as $alumno)
                    <option value="{{ $alumno->id_alumno }}" {{ old('id_alumno') == $alumno->id_alumno ? 'selected' : '' }}>
                        {{ $alumno->nombre_completo }}
                    </option>
                @endforeach
            </select>
            @error('id_alumno')<div style="color: red; font-size: 0.9em;">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo de Pago</label>
            <input type="text" name="tipo" placeholder="Ej: Mensualidad, Inscripción" value="{{ old('tipo') }}" required class="form-control">
            @error('tipo')<div style="color: red; font-size: 0.9em;">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Monto</label>
            <input type="number" step="0.01" name="monto" placeholder="Monto" value="{{ old('monto') }}" required class="form-control">
            @error('monto')<div style="color: red; font-size: 0.9em;">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha de Pago</label>
            <input type="date" name="fechaPago" value="{{ old('fechaPago') }}" required class="form-control">
            @error('fechaPago')<div style="color: red; font-size: 0.9em;">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Motivo de Pago</label>
            <input type="text" name="motivoPago" placeholder="Motivo" value="{{ old('motivoPago') }}" required class="form-control">
            @error('motivoPago')<div style="color: red; font-size: 0.9em;">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Referencia de Pago (Opcional)</label>
            <input type="text" name="referenciaPago" placeholder="Referencia/Voucher" value="{{ old('referenciaPago') }}" class="form-control">
            @error('referenciaPago')<div style="color: red; font-size: 0.9em;">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Estado de Pago</label>
            <select name="estadoPago" required class="form-control">
                <option value="">Seleccionar Estado</option>
                <option value="Pendiente" {{ old('estadoPago') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="Completado" {{ old('estadoPago') == 'Completado' ? 'selected' : '' }}>Completado</option>
                <option value="Fallido" {{ old('estadoPago') == 'Fallido' ? 'selected' : '' }}>Fallido</option>
            </select>
            @error('estadoPago')<div style="color: red; font-size: 0.9em;">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-success mt-3">Registrar Pago</button>
      </form>

      <div class="table-responsive mt-5">
        <h2 class="text-center mb-4">Pagos Registrados</h2>
        <table class="table table-striped table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>Alumno</th>
              <th>Tipo</th>
              <th>Monto</th>
              <th>Fecha de Pago</th>
              <th>Motivo</th>
              <th>Referencia</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pagos as $pago)
                <tr>
                  <td>{{ $pago->alumno ?? 'N/A' }}</td> <td>{{ $pago->tipo }}</td>
                  <td>${{ number_format($pago->monto, 2) }}</td>
                  <td>{{ $pago->fechaPago }}</td>
                  <td>{{ $pago->motivoPago }}</td>
                  <td>{{ $pago->referenciaPago }}</td>
                  <td>{{ $pago->estadoPago }}</td>
                  <td>
                    <button class="btn btn-sm btn-warning" title="Editar">
                      <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" title="Eliminar">
                      <i class="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <footer class="footer">
        <p>© 2025 Sistema de Gestión del Dojo</p>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <script>
        @if(session('sessionInsertado'))
            const icono = '{{ session('sessionInsertado') == 'true' ? 'success' : 'error' }}';
            const titulo = '{{ session('mensaje') }}';
            
            Swal.fire({
                icon: icono,
                title: titulo,
                showConfirmButton: false,
                timer: 2000
            });
            document.getElementById('alerta-temp').style.display = 'none';
        @endif
    </script>
</body>
</html>