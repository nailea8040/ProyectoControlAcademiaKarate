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

/**
 * ResetPasswordController
 *
 * Usa tabla separada 'password_resets' (buena práctica, compatible con web y API móvil):
 *   id, correo, token, created_at, expires_at, used
 *
 * Flujo:
 *   1. Usuario solicita reset → se crea registro en password_resets
 *   2. Usuario recibe email con token → accede al formulario de cambio
 *   3. Usuario cambia contraseña → se marca el token como used=1
 *   4. Se purgan tokens expirados o usados periódicamente
 */
class ResetPasswordController extends Controller
{
    public function showResetForm()
    {
        return view('ResetPasswordViews.olvidosucontrasennia');
    }

    public function showResetFormWithToken($token)
    {
        try {
            // Buscar token en tabla password_resets (no en usuario)
            $reset = DB::table('password_resets')
                ->where('token', $token)
                ->where('used', 0)
                ->first();

            if (!$reset) {
                return redirect()->route('login')
                    ->with('sessionCambiarContrasennia', 'false')
                    ->with('mensaje', 'Enlace incorrecto o ya fue utilizado.');
            }

            $fechaExpiracion = Carbon::parse($reset->expires_at);

            if (!$fechaExpiracion->greaterThan(Carbon::now())) {
                return redirect()->route('login')
                    ->with('sessionCambiarContrasennia', 'false')
                    ->with('mensaje', 'El enlace ha expirado. Por favor, solicita uno nuevo.');
            }

            return view('ResetPasswordViews.cambiarcontrasennia', ['token' => $token]);

        } catch (\Exception $e) {
            Log::error('ResetPassword@showResetFormWithToken: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('sessionCambiarContrasennia', 'false')
                ->with('mensaje', 'Hubo un error en el servidor.');
        }
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
        ], [
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email'    => 'Por favor ingresa un correo electrónico válido.',
        ]);

        $correo = $request->input('correo');

        try {
            // Verificar que el correo exista en tabla usuario
            $usuario = DB::table('usuario')
                ->select('id_usuario', 'nombre', 'correo')
                ->where('correo', $correo)
                ->where('estado', 1)
                ->first();

            // Respuesta genérica para no revelar si el correo existe (seguridad)
            if (!$usuario) {
                return redirect()->route('password.request')
                    ->with('sessionRecuperarContrasennia', 'true')
                    ->with('mensaje', '¡Listo! Si el correo está registrado, recibirás el enlace de recuperación.');
            }

            $token     = Str::uuid()->toString();
            $expiraEn  = Carbon::now()->addMinutes(10);

            // Invalidar tokens anteriores del mismo correo
            DB::table('password_resets')
                ->where('correo', $correo)
                ->where('used', 0)
                ->update(['used' => 1]);

            // Insertar nuevo token en password_resets (no en usuario)
            DB::table('password_resets')->insert([
                'correo'     => $correo,
                'token'      => $token,
                'expires_at' => $expiraEn,
                'used'       => 0,
            ]);

            Mail::to($correo)->send(new cambiarcontrasenniaMailable($usuario->nombre, $token));

            Log::info("Correo de recuperación enviado a: {$correo}");

            return redirect()->route('login')
                ->with('sessionRecuperarContrasennia', 'true')
                ->with('mensaje', '¡Listo! Revisa tu correo para el enlace de recuperación.');

        } catch (\Exception $e) {
            Log::error('ResetPassword@sendResetLinkEmail: ' . $e->getMessage());
            return redirect()->route('password.request')
                ->with('sessionRecuperarContrasennia', 'false')
                ->with('mensaje', 'Hubo un error al enviar el correo.');
        }
    }

    public function resetPassword(Request $request)
    {
        Log::info('=== INICIO resetPassword ===');

        try {
            $validated = $request->validate([
                'contrasennia'   => 'required|min:8',
                'recontrasennia' => 'required|same:contrasennia',
                'mytoken'        => 'required',
            ]);

            $token = $request->mytoken;

            // Buscar token válido y no usado en password_resets
            $reset = DB::table('password_resets')
                ->where('token', $token)
                ->where('used', 0)
                ->first();

            if (!$reset) {
                Log::warning('Token no encontrado o ya usado.');
                return redirect()->route('login')
                    ->with('sessionCambiarContrasennia', 'false')
                    ->with('mensaje', 'El enlace no es válido o ya fue utilizado.');
            }

            // Verificar expiración
            $fechaExpiracion = Carbon::parse($reset->expires_at);
            if (!$fechaExpiracion->greaterThan(Carbon::now())) {
                Log::warning('Token expirado: ' . $token);
                return redirect()->route('login')
                    ->with('sessionCambiarContrasennia', 'false')
                    ->with('mensaje', 'El enlace ha expirado.');
            }

            $contraseniaCifrada = Hash::make($request->contrasennia);

            DB::beginTransaction();

            // Actualizar contraseña en tabla usuario
            $affectedRows = DB::table('usuario')
                ->where('correo', $reset->correo)
                ->update(['pass' => $contraseniaCifrada]);

            if (!$affectedRows) {
                throw new \Exception('No se pudo actualizar la contraseña del usuario.');
            }

            // Marcar token como usado en password_resets
            DB::table('password_resets')
                ->where('token', $token)
                ->update(['used' => 1]);

            DB::commit();

            Log::info('Contraseña actualizada para: ' . $reset->correo);

            return redirect()->route('login')
                ->with('sessionCambiarContrasennia', 'true')
                ->with('mensaje', '¡Contraseña cambiada exitosamente! Ya puedes iniciar sesión.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validación resetPassword: ', $e->errors());
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ResetPassword@resetPassword: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('sessionCambiarContrasennia', 'false')
                ->with('mensaje', 'Hubo un error al actualizar la contraseña.');
        }
    }

    /**
     * Purgar tokens expirados o usados (llamar desde un Command/Scheduler).
     * Ejemplo en Kernel.php: $schedule->call([ResetPasswordController::class, 'purgarTokens'])->daily();
     */
    public static function purgarTokens(): void
    {
        DB::table('password_resets')
            ->where(function ($q) {
                $q->where('used', 1)
                  ->orWhere('expires_at', '<', Carbon::now());
            })
            ->delete();

        Log::info('Tokens de recuperación purgados.');
    }
}