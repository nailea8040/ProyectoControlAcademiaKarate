<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\cambiarcontrasenniaMailable;
use Illuminate\Support\Facades\Hash;


class ResetPasswordController extends Controller
{
    public function showResetForm(){

        return view('ResetPasswordViews.olvidosucontrasennia');
    }

    public function showResetFormWithToken($token){
//        echo $token;
        try{
            $res=DB::connection('mysql')
                ->table("usuarios")
                ->select("token_expiracion")
                ->where("token_recuperacion","=",$token)
                ->first();

            if ($res) {
                $fechaExpiracion = Carbon::parse($res->token_expiracion);
                $fechaActual = Carbon::now();

                if ($fechaExpiracion->greaterThan($fechaActual)) { //Verifica si el token aún es vigente
                    return view('ResetPasswordViews.cambiarcontrasennia', [
                        'token' => $token
                    ]);
                }
                else{
                    $MensajeError="El enlace ha expirado";
                    return redirect(route('login'))
                        ->with('sessionCambiarContrasennia', 'false')
                        ->with('mensaje', $MensajeError);
                }
            }
            else {
                $MensajeError="Enlace incorecto o ha expirado";
                return redirect(route('login'))
                    ->with('sessionCambiarContrasennia', 'false')
                    ->with('mensaje', $MensajeError);
            }
        }
    
        catch (\Exception $e){
            $MensajeError="Hubo un error en el servidor";
            return redirect(route('password.reset'))
                ->with('sessionCambiarContrasennia', 'false')
                ->with('mensaje', $MensajeError); //With envía en una session flash dos claves y sus valores
        }
    }

    public function sendResetLinkEmail(Request $request){
    $correo = $request->input('correo'); // Usar input('email') ya que el campo en la vista es 'email'

    try{
        // 1. Buscar al usuario y obtener sus datos importantes
        $res = DB::table('usuario')
            ->select("id_usuario", "nombre", "activo", "correo") // Corregido: asumimos nombre para el Mailable
            ->where("correo", "=", $correo) // Corregido: Asumo que la columna es 'correo'
            ->first();

        if ($res) { // Corregido: Verificar si el resultado existe
            
            // 2. Comprobar si el usuario está activo (asumo que 1 es activo)
            if ($res->activo == 1) {
                $nombre = $res->nombre; // Usamos el nombre para el correo
                $token = Str::uuid()->toString();
                $expiraEn = Carbon::now()->addMinutes(10);

                // 3. Insertar en la base de datos el token y la fecha de expiración
                DB::table('usuario')
                    ->where('correo', $correo) // Corregido: Columna 'correo'
                    ->update([
                        'token_recuperacion' => $token,
                        'token_expiracion' => $expiraEn,
                    ]);

                // 4. Enviar el correo
                Mail::to($correo)
                    ->send(new cambiarcontrasenniaMailable($nombre, $token));

                $Mensaje = "¡Listo! Revisa tu correo electrónico para el enlace de recuperación.";
                return redirect('/login')
                    ->with('sessionRecuperarContrasennia', 'true')
                    ->with('mensaje', $Mensaje);
            } else {
                // Usuario encontrado, pero no activo
                $Mensaje = "Tu cuenta aún no ha sido confirmada o está inactiva.";
                return redirect(route('password.request'))
                    ->with('sessionRecuperarContrasennia', 'false')
                    ->with('mensaje', $Mensaje);
                 
            }
        } else {
            // Usuario no encontrado
            $Mensaje = "Este correo electrónico no existe en nuestros registros.";
            return redirect(route('password.request'))
                ->with('sessionRecuperarContrasennia', 'false')
                ->with('mensaje', $Mensaje);
        }
    }
    // ... Manejo de excepciones (Swift_TransportException y Exception general) ...
    catch (\Exception $e){ 
        $MensajeError="Hubo un error con las credenciales de correo (revisa tu archivo .env)";
        return redirect(route('password.request'))
            ->with('sessionRecuperarContrasennia', 'false')
            ->with('mensaje', $MensajeError); 
    }
    catch (\Exception $e){
        $MensajeError="Hubo un error en el servidor";
        return redirect(route('password.request'))
            ->with('sessionRecuperarContrasennia', 'false')
            ->with('mensaje', $MensajeError); 
    }
}

    public function resetPassword(Request $request){
    $contrasennia = $request->input('password');
    $contraseniaCifrada = Hash::make($contrasennia);
    $token = $request->mytoken;

    try{
        $affectedRows = DB::table('usuario') // Corregido: Usar 'usuarios'
            ->where('token_recuperacion', $token)
            ->update([
                'pass' => $contraseniaCifrada, // Corregido: Usar el nombre de columna correcto
                'token_recuperacion' => null, // Opcional pero recomendado: Invalidar el token después de usarlo
                'token_expiracion' => null, // Opcional pero recomendado: Invalidar la expiración
            ]);

        $MensajeError="Cambio de contraseña exitoso. Ya puedes iniciar sesión.";
        return redirect(route('login'))
            ->with('sessionCambiarContrasennia', 'true')
            ->with('mensaje', $MensajeError);

    }
    // ... Manejo de excepciones ...
    catch (\Exception $e){
        $MensajeError="Hubo un error al actualizar la contraseña.";
        return redirect(route('password.reset', ['token' => $token]))
            ->with('sessionCambiarContrasennia', 'false')
            ->with('mensaje', $MensajeError); 
    }
  }
}
