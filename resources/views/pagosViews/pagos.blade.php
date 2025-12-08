<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Pagos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="{{ asset('css/estilo2.css') }}">
</head>
<body>

 @include('includes.menu') 

  <div class="main-content">
    <header>
      <h1>Gestión de Pagos</h1>
    </header>

    <div class="content">

        @if(session('mensaje'))
            <div id="alerta-temp" class="alert {{ session('sessionInsertado') == 'true' ? 'alert-success' : 'alert-danger' }} text-center" role="alert">
                {{ session('mensaje') }}
            </div>
        @endif

      <form id="registroPago" method="POST" action="{{ route('pagos.store') }}">
        @csrf
        <h2>Registrar Pago</h2>

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
            @error('id_alumno')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo de Pago</label>
            <input type="text" name="tipo" class="form-control" placeholder="Ej: Mensualidad, Inscripción" value="{{ old('tipo') }}" required>
            @error('tipo')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Monto</label>
            <input type="number" step="0.01" name="monto" class="form-control" placeholder="Monto" value="{{ old('monto') }}" required>
            @error('monto')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha de Pago</label>
            <input type="date" name="fechaPago" class="form-control" value="{{ old('fechaPago', date('Y-m-d')) }}" required>
            @error('fechaPago')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Motivo de Pago</label>
            <input type="text" name="motivoPago" class="form-control" placeholder="Motivo" value="{{ old('motivoPago') }}" required>
            @error('motivoPago')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Referencia de Pago (Opcional)</label>
            <input type="text" name="referenciaPago" class="form-control" placeholder="Referencia/Voucher" value="{{ old('referenciaPago') }}">
            @error('referenciaPago')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Estado de Pago</label>
            <select name="estadoPago" required class="form-control">
                <option value="">Seleccionar Estado</option>
                <option value="Pendiente" {{ old('estadoPago') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="Completado" {{ old('estadoPago') == 'Completado' ? 'selected' : '' }}>Completado</option>
                <option value="Fallido" {{ old('estadoPago') == 'Fallido' ? 'selected' : '' }}>Fallido</option>
            </select>
            @error('estadoPago')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn-primary">Registrar Pago</button>
      </form>

     <div class="table-container">
                <h2>Usuarios Registrados</h2>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
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
                  <td>{{ $pago->alumno ?? 'N/A' }}</td>
                  <td>{{ $pago->tipo }}</td>
                  <td><strong>${{ number_format($pago->monto, 2) }}</strong></td>
                  <td>{{ $pago->fechaPago }}</td>
                  <td>{{ $pago->motivoPago }}</td>
                  <td>{{ $pago->referenciaPago }}</td>
                  <td>
                    @if($pago->estadoPago == 'Completado')
                        <span class="badge badge-success">Completado</span>
                    @elseif($pago->estadoPago == 'Pendiente')
                        <span class="badge badge-warning">Pendiente</span>
                    @else
                        <span class="badge badge-danger">Fallido</span>
                    @endif
                  </td>
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
            
            const alertaTemp = document.getElementById('alerta-temp');
            if (alertaTemp) {
                alertaTemp.style.display = 'none';
            }
        @endif
    </script>
</body>
</html>