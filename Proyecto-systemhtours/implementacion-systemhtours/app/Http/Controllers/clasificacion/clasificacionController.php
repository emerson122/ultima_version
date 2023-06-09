<?php

namespace App\Http\Controllers\clasificacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class clasificacionController extends Controller
{

    /*
    ======================================
    Pantalla de inicio Clasificación 
    ======================================
    */
    protected $url = 'http://localhost:3000';

   /**
     * Función mostrar retorna la vista principal de la pantalla de bitacora
     */

    public function mostrar()
    {
        /**
         * Seguridad de roles y permisos método GET
         */

        try {
            //code...
            $search = Http::withToken(Cache::get('token'))->post($this->url . '/permisos/sel_per_obj', [
                "PV_ROL" => Cache::get('rol'),
                "PV_OBJ" => "CLASIFICACION"
            ]);

            $permisos = $search->json();
            foreach ($permisos as $key) {
                $consultar = $key['PER_CONSULTAR'];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return 'Error Clasificacion 21';
        }

        if ($consultar == '1') {
            try {
                //code...
                $clasificacion = http::withToken(Cache::get('token'))->get($this->url . '/clasificacion');

                $personArr = $clasificacion->json();
            } catch (\Throwable $th) {
                //throw $th;
                return 'error clasificacion 22';
            }

            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'PANTALLA METODO GET',
                    "DES" => Cache::get('user') . ' INGRESO A LA PANTALLA DE CLASIFICACION',
                    "OBJETO" => 'CLASIFICACION'

                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error clasificacion 32';
            }
        } else {
            return view('Auth.no-auth');
        }


        return view('clasificacion.clasificacion', compact('personArr'));
    }

    /**
     * Método para insertar clasificacion
     */
    public function insertar(Request $request)
    {
        /**
         * Seguridad de roles y perimisos metodo GET
         */

        try {
            //code...
            $search = Http::withToken(Cache::get('token'))->post($this->url . '/permisos/sel_per_obj', [
                "PV_ROL" => Cache::get('rol'),
                "PV_OBJ" => "CLASIFICACION"
            ]);

            $permisos = $search->json();
            foreach ($permisos as $key) {
                $insercion = $key['PER_INSERCION'];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return 'Error Clasificacion 21';
        }
        if ($insercion == '1') {
            try {
                $insertar = Http::withToken(Cache::get('token'))->post($this->url . '/clasificacion/insertar', [
                    "USR" => Cache::get('user'),
                    "NATURALEZA" => $request->clasificacion

                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error clasificacion 31';
            }

            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'PANTALLA METODO POST',
                    "DES" => Cache::get('user') . ' INSERTO EL DATO DE ' . $request->clasificacion . ' EN LA PANTALLA DE CLASIFICACION',
                    "OBJETO" => 'CLASIFICACION'

                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error clasificacion 32';
            }
            Session::flash('insertado', '1');
        } else {
            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'SIN PERMISO METODO POST',
                    "DES" => Cache::get('user') . ' INTENTO INSERTAR EL DATO ' . $request->clasificacion . ' EN LA PANTALLA DE CLASIFICACION',
                    "OBJETO" => 'CLASIFICACION'

                ]);

                Session::flash('sinpermiso', '1');
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error clasificacion 32';
            }
        }




        return back();
    }

    /**
     * Método para actualizar una clasificacion
     */

    public function actualizar(Request $request)
    {

        /**
         * Seguridad de roles y permisos método UPDATE
         */

        try {
            //code...
            $search = Http::withToken(Cache::get('token'))->post($this->url . '/permisos/sel_per_obj', [
                "PV_ROL" => Cache::get('rol'),
                "PV_OBJ" => "CLASIFICACION"
            ]);

            $permisos = $search->json();
            foreach ($permisos as $key) {
                $update = $key['PER_ACTUALIZACION'];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return 'Error Clasificacion 21';
        }
        //  return $update;
        if ($update == '1') {
            try {
                //code...
                $actualizar = Http::withToken(Cache::get('token'))->put($this->url . '/clasificacion/actualizar/' . $request->f, [
                    "USR" => Cache::get('user'),
                    "NATURALEZA" => $request->clasificacion,

                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return 'error periodo 50';
            }

            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'ACTUALIZO UN DATO EN PANTALLA ',
                    "DES" => Cache::get('user') . ' ACTUALIZO EL DATO DE ' . $request->clasificacion . ' EN LA PANTALLA DE CLASIFICACION',
                    "OBJETO" => 'CLASIFICACION'

                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error clasificacion 32';
            }
            Session::flash('actualizado', '1');
        } else {
            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'SIN PERMISO METODO PUT',
                    "DES" => Cache::get('user') . ' INTENTO ACTUALIZAR EL DATO ' . $request->clasificacion . ' EN LA PANTALLA DE CLASIFICACION',
                    "OBJETO" => 'CLASIFICACION'

                ]);

                Session::flash('sinpermiso', '1');
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error clasificacion 32';
            }
        }
        return back();
    }

    public function eliminar(Request $request)
    {

        /**
         * Seguridad de roles y permisos método delete
         */

        try {
            //code...
            $search = Http::withToken(Cache::get('token'))->post($this->url . '/permisos/sel_per_obj', [
                "PV_ROL" => Cache::get('rol'),
                "PV_OBJ" => "CLASIFICACION"
            ]);

            $permisos = $search->json();
            foreach ($permisos as $key) {
                $eliminacion = $key['PER_ELIMINACION'];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return 'Error Clasificacion 21';
        }

        if ($eliminacion == '1') {

            $delete = Http::withToken(Cache::get("token"))->delete($this->url . '/clasificacion/eliminar/' . $request->f);
            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'ELIMINO UN DATO',
                    "DES" => Cache::get('user') . ' ELIMINO EL DATO CON CODIGO ' . $request->f . ' EN LA PANTALLA DE CLASIFICACION',
                    "OBJETO" => 'CLASIFICACION'

                ]);

                $respuesta = strrpos($delete, 'ELIMINAR ESTA CLASIFICACION');
                if ($respuesta > 0) {
                    Session::flash('nopuedes', "1");
                    $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                        "USR" => Cache::get('user'),
                        "ACCION" => 'SE INTENTO ELIMINAR SUBCUENTAS EN USO',
                        "DES" => Cache::get('user') . ' INTENTO ELIMININAR EL DATO  con codigo' . $request->f . ' EN LA PANTALLA DE SUBCUENTAS',
                        "OBJETO" => 'SUBCUENTAS'

                    ]);
                    return back();
                }
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error Clasificacion 250';
            }


            Session::flash('eliminado', '1');
        } else {
            # code...
            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'SIN PERMISO METODO DELETE',
                    "DES" => Cache::get('user') . ' INTENTO ELIMININAR EL DATO  con codigo' . $request->f . ' EN LA PANTALLA DE CLASIFICACION',
                    "OBJETO" => 'CLASIFICACION'

                ]);

                Session::flash('sinpermiso', '1');
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error clasificacion 268';
            }
        }

        return back();
    }


    /*
    ======================================
    Función PDF de Clasificacion
    ======================================
    */
    public function mostrarPDF()
    {
        try {
            //code...
            $clasificacion = http::withToken(Cache::get('token'))->get($this->url . '/clasificacion');

            $clasificacion = $clasificacion->json();
        } catch (\Throwable $th) {
            //throw $th;
            return 'error clasificacion 22';
        }
        return view('clasificacion.clasificacionPDF', compact('clasificacion'));
    }
}
