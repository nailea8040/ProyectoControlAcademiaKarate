<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\cambiarcontrasenniaMailable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{
    public function showResetForm()
    {
        return view('ResetPasswordViews.olvidosucontrasennia');
    }

    public function showResetFormWithToken($token)
    {
        try {
            $res = DB::table("usuario") 
                ->select("token_expiracion", "correo")
                ->where("token_recuperacion", "=", $token)
                ->first();
 
            if ($res) {
                $fechaExpiracion = Carbon::parse($res->token_expiracion);
                $fechaActual = Carbon::now();

                if ($fechaExpiracion->greaterThan($fechaActual)) { 
                    return view('ResetPasswordViews.cambiarcontrasennia', [
                        'token' => $token
                    ]);
                } else {
                    $MensajeError = "El enlace ha expirado. Por favor, solicita uno nuevo.";
                    return redirect(route('login'))
                        ->with('sessionCambiarContrasennia', 'false')
                        ->with('mensaje', $MensajeError);
                }
            } else {
                $MensajeError = "Enlace incorrecto o ha expirado.";
                return redirect(route('login'))
                    ->with('sessionCambiarContrasennia', 'false')
                    ->with('mensaje', $MensajeError);
            }
        } catch (\Exception $e) {
            Log::error('Error en showResetFormWithToken: ' . $e->getMessage());
            $MensajeError = "Hubo un error en el servidor.";
            return redirect(route('login'))
                ->with('sessionCambiarContrasennia', 'false')
                ->with('mensaje', $MensajeError); 
        }
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
        ], [
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'Por favor ingresa un correo electrónico válido.',
        ]);

        $correo = $request->input('correo');

        try {
            $res = DB::table('usuario')
                ->select("id_usuario", "nombre", "correo") 
                ->where("correo", "=", $correo)
                ->first();

            if ($res) {
                $nombre = $res->nombre; 
                $token = Str::uuid()->toString();
                $expiraEn = Carbon::now()->addMinutes(10);

                DB::table('usuario')
                    ->where('correo', $correo) 
                    ->update([
                        'token_recuperacion' => $token,
                        'token_expiracion' => $expiraEn,
                    ]);

                Mail::to($correo)->send(new cambiarcontrasenniaMailable($nombre, $token));

                Log::info("Correo de recuperación enviado a: {$correo} con token: {$token}");

                $Mensaje = "¡Listo! Revisa tu correo electrónico para el enlace de recuperación.";
                return redirect('/login')
                    ->with('sessionRecuperarContrasennia', 'true') 
                    ->with('mensaje', $Mensaje);
        
            } else {
                $Mensaje = "Si el correo existe en nuestros registros, recibirás instrucciones.";
                return redirect(route('password.request'))
                    ->with('sessionRecuperarContrasennia', 'true')
                    ->with('mensaje', $Mensaje);
            }
        } catch (\Exception $e) {
            Log::error('Error en sendResetLinkEmail: ' . $e->getMessage());
            
            $MensajeError = "Hubo un error al enviar el correo.";
            return redirect(route('password.request'))
                ->with('sessionRecuperarContrasennia', 'false')
                ->with('mensaje', $MensajeError); 
        }
    }

    public function resetPassword(Request $request)
    {
        // LOG PARA DEBUG
        Log::info('=== INICIO resetPassword ===');
        Log::info('Datos recibidos:', $request->all());
        Log::info('Token recibido:', ['token' => $request->mytoken]);
        
        try {
            // Validación
            $validated = $request->validate([
                'contrasennia' => 'required|min:8',
                'recontrasennia' => 'required|same:contrasennia',
                'mytoken' => 'required'
            ]);

            Log::info('Validación pasada correctamente');

            $contrasennia = $request->input('contrasennia'); 
            $token = $request->mytoken;

            Log::info('Token a buscar:', ['token' => $token]);

            // Buscar usuario con el token
            $usuario = DB::table('usuario')
                ->where('token_recuperacion', $token)
                ->first();

            Log::info('Usuario encontrado:', ['usuario' => $usuario ? 'SÍ' : 'NO']);

            if (!$usuario) {
                Log::warning('Token no encontrado en la BD');
                $MensajeError = "El enlace no es válido o ha expirado.";
                return redirect(route('login'))
                    ->with('sessionCambiarContrasennia', 'false')
                    ->with('mensaje', $MensajeError);
            }

            // Verificar expiración
            $fechaExpiracion = Carbon::parse($usuario->token_expiracion);
            $fechaActual = Carbon::now();

            Log::info('Verificación de fechas:', [
                'expiracion' => $fechaExpiracion->toDateTimeString(),
                'actual' => $fechaActual->toDateTimeString(),
                'valido' => $fechaExpiracion->greaterThan($fechaActual)
            ]);

            if (!$fechaExpiracion->greaterThan($fechaActual)) {
                Log::warning('Token expirado');
                $MensajeError = "El enlace ha expirado.";
                return redirect(route('login'))
                    ->with('sessionCambiarContrasennia', 'false')
                    ->with('mensaje', $MensajeError);
            }

            // Cifrar contraseña
            $contraseniaCifrada = Hash::make($contrasennia);
            Log::info('Contraseña cifrada correctamente');

            // Actualizar contraseña
            $affectedRows = DB::table('usuario') 
                ->where('token_recuperacion', $token)
                ->update([
                    'pass' => $contraseniaCifrada, 
                    'token_recuperacion' => null, 
                    'token_expiracion' => null, 
                ]);

            Log::info('Filas afectadas:', ['rows' => $affectedRows]);

            if ($affectedRows > 0) {
                Log::info('¡Contraseña actualizada exitosamente!');
                
                $Mensaje = "¡Contraseña cambiada exitosamente! Ya puedes iniciar sesión.";
                return redirect(route('login'))
                    ->with('sessionCambiarContrasennia', 'true') 
                    ->with('mensaje', $Mensaje);
            } else {
                Log::error('No se actualizó ninguna fila');
                throw new \Exception("No se pudo actualizar la contraseña.");
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación:', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('Error general en resetPassword:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $MensajeError = "Hubo un error al actualizar la contraseña: " . $e->getMessage();
            return redirect(route('login'))
                ->with('sessionCambiarContrasennia', 'false')
                ->with('mensaje', $MensajeError); 
        }
    }
}