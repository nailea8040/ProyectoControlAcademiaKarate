<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request){
        $correo=$request->correo;

        try{
            $res=DB::connection('mysql')
                ->table('usuarios')
                ->where("correo_electronico","=",$correo)
                ->get();

            if (!$res->isEmpty()) {

                $res=DB::connection('mysql')
                    ->table('usuarios')
                    ->select("id","nombre_completo")
                    ->where("correo_electronico","=",$correo)
                    ->where('activo', '=', 1)
                    ->first();

                if ($res) {
                    $nombre=$res->nombre_completo;

                    $token = Str::uuid()->toString();

                    // Calcular la fecha y hora de expiración con 10 minutos de expiración
                    $expiraEn = Carbon::now()->addMinutes(10);

                    //insertar en la base de datos el token y la fecha de expiración
                    DB::connection('mysql')
                        ->table('usuarios')
                        ->where('correo_electronico', $correo)
                        ->update([
                            'token_recuperacion' => $token,
                            'token_expiracion' => $expiraEn,
                        ]);

                    //enviar  el correo con el mensaje de recuperación
                    Mail::to($correo)
                        ->send(new cambiarcontrasenniaMailable($nombre,$token));

//                    echo "Nombre: $nombre ----  Token = $token.  expira el token: $expiraEn";

                    $MensajeError = "¡Listo! Revisa tu correo";
                    return redirect('/login')
                        ->with('sessionRecuperarContrasennia', 'true')
                        ->with('mensaje', $MensajeError) //With envía en una session slash dos claves y sus valores
                        ->with('token',$token);
                }
                else{
                    $MensajeError = "Aun no confirmas tu correo";
                    return redirect(route('password.request'))
                        ->with('sessionRecuperarContrasennia', 'false')
                        ->with('mensaje', $MensajeError); //With envía en una session flash dos claves y sus valores
                }
            }
            else {
                $MensajeError = "Este correo no existe";
                return redirect(route('password.request'))
                    ->with('sessionRecuperarContrasennia', 'false')
                    ->with('mensaje', $MensajeError); //With envía en una session flash dos claves y sus valores
            }
        }
        catch (\Swift_TransportException $e){ //Esta excepción se lanza si hay un problema con la conexión al servidor de correo.
            $MensajeError="Hubo un error con las credenciales de correo";
            return redirect(route('password.request'))
                ->with('sessionRecuperarContrasennia', 'false')
                ->with('mensaje', $MensajeError); //With envía en una session flash dos claves y sus valores
        }
        catch (\Exception $e){
            $MensajeError="Hubo un error en el servidor";
//            dd($e->getMessage());
            return redirect(route('password.request'))
                ->with('sessionRecuperarContrasennia', 'false')
                ->with('mensaje', $MensajeError); //With envía en una session flash dos claves y sus valores
        }
    }
}
