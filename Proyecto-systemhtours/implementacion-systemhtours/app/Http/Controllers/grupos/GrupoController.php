<?php

namespace App\Http\Controllers\grupos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class GrupoController extends Controller
{
    /*
    ======================================
    Pantalla de inicio HOME
    ======================================
    */
    protected $url = 'http://localhost:3000';

    /**
     * Función mostrar vista de Grupos
     */
    public function vista()
    {
        /**
         * Seguridad de roles y permisos método GET
         */

        try {
            //code...
            $search = Http::withToken(Cache::get('token'))->post($this->url . '/permisos/sel_per_obj', [
                "PV_ROL" => Cache::get('rol'),
                "PV_OBJ" => "GRUPOS"
            ]);

            $permisos = $search->json();
            $consultar = 0;
            foreach ($permisos as $key) {
                $consultar = $key['PER_CONSULTAR'];
            }
        } catch (\Exception $e) {
            Log::debug('Error procedimiento de permisos y roles: '.$e->getMessage());
            Session::flash('error', 'ERR-001 Ocurrió un error de conexión');
            return view('Bitacoras.error');
        }

        if ($consultar == '1') {
            try {
                $clasificacion = http::withToken(Cache::get('token'))->get($this->url . '/clasificacion');
                $grupo = http::withToken(Cache::get('token'))->get($this->url . '/grupos');

                $clasificacionArr = $clasificacion->json();
                $grupoArr = $grupo->json();
            } catch (\Exception $e) {
                Log::debug('Error procedimiento de seleccionar clasificación: '.$e->getMessage());
                Session::flash('error', 'ERR-001 Ocurrió un error de conexión');
                return view('Bitacoras.error');
            }

            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'PANTALLA CONSULTAR',
                    "DES" => Cache::get('user') . ' INGRESO A LA PANTALLA DE GRUPOS',
                    "OBJETO" => 'GRUPOS'

                ]);
            } catch (\Exception $e) {
                Log::debug('Error procedimiento al insertar en bitacoras: '.$e->getMessage());
                Session::flash('error', 'ERR-001 Ocurrió un error de conexión');
                return view('Bitacoras.error');
            }
        } else {
            try {
                //code...
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'ACCESO NO AUTORIZADO GRUPOS METODO CONSULTAR',
                    "DES" => Cache::get('user') . ' INTENTO INGRESAR A LA PANTALLA DE GRUPOS',
                    "OBJETO" => 'GRUPOS'
                ]);
            } catch (\Exception $e) {
                Log::debug('Error procedimiento al insertar en bitacoras: '.$e->getMessage());
                Session::flash('error', 'ERR-001 Ocurrió un error de conexión');
                return view('Bitacoras.error');
            }
            return view('Auth.no-auth');
        }



        return view('grupos.grupo', compact('grupoArr', 'clasificacionArr'));
    }

    /**
     * Función Insertar datos en la vista
     */
    public function insertar(Request $request)
    {
        /**
         * Seguridad de roles y permisos método GET
         */

        try {
            //code...
            $search = Http::withToken(Cache::get('token'))->post($this->url . '/permisos/sel_per_obj', [
                "PV_ROL" => Cache::get('rol'),
                "PV_OBJ" => "GRUPOS"
            ]);

            $permisos = $search->json();
            $insercion = 0;
            foreach ($permisos as $key) {
                $insercion = $key['PER_INSERCION'];
            }
        } catch (\Exception $e) {
            Log::debug('Error procedimiento de permisos y roles en pantalla de grupos: '.$e->getMessage());
            Session::flash('error', 'ERR-001 Ocurrió un error de conexión');
            return view('Bitacoras.error');
        }
        if ($insercion == '1') {
            try {
                $insertar = Http::withToken(Cache::get('token'))->post($this->url . '/grupos/insertar', [
                    "PV_CLASIFICACION" => $request->clasificacion,
                    "PV_NUM_GRUPO" => $request->grupo,
                    "PV_NOM_GRUPO" => $request->name
                ]);
                $duplicado = strrpos($insertar, "NUMERO DE GRUPO DUPLICADO");
                $duplicado2 = strrpos($insertar, "NOMBRE DE GRUPO DUPLICADO");

                if ($duplicado > 0) {
                    Session::flash('duplicada', '1');
                    return back();
                }

                if ($duplicado2 > 0) {
                    Session::flash('duplicada2', '1');
                    return back();
                }


                Session::flash('insertado', '1');
            } catch (\Exception $e) {
                Log::debug('Error procedimiento de insertar en pantalla de grupos: ' . $e->getMessage());
                Session::flash('error', 'ERR-001 Ocurrió un error de conexión');
                return view('Bitacoras.error');
            }

            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'PANTALLA METODO PARA INSERTAR',
                    "DES" => Cache::get('user') . ' INSERTO EL DATO DE ' . $request->name . ' EN LA PANTALLA DE GRUPOS',
                    "OBJETO" => 'GRUPOS'

                ]);
            } catch (\Exception $e) {
                Log::debug('Error procedimiento de insertar en bitacora de la pantalla de grupos: ' . $e->getMessage());
                Session::flash('error', 'ERR-001 Ocurrió un error de conexión');
                return view('Bitacoras.error');
            }
        } else {
            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'SIN PERMISO PARA INSERTAR',
                    "DES" => Cache::get('user') . ' INTENTO INSERTAR EL DATO ' . $request->name . ' EN LA PANTALLA DE GRUPOS',
                    "OBJETO" => 'GRUPOS'

                ]);

                Session::flash('sinpermiso', '1');
            } catch (\Exception $e) {
                Log::debug('Error procedimiento de insertar en bitacora no autorizado de la pantalla de grupos: ' . $e->getMessage());
                Session::flash('error', 'ERR-001 Ocurrió un error de conexión');
                return view('Bitacoras.error');
            }
        }

        return back();
    }
    /**
     * Función actualizar datos en la vista
     */
    public function actualizar(Request $request)
    {

        try {
            //code...
            $search = Http::withToken(Cache::get('token'))->post($this->url . '/permisos/sel_per_obj', [
                "PV_ROL" => Cache::get('rol'),
                "PV_OBJ" => "GRUPOS"
            ]);

            $permisos = $search->json();
            $update = 0;
            foreach ($permisos as $key) {
                $update = $key['PER_ACTUALIZACION'];
            }
        } catch (\Exception $e) {
            Log::debug('Error procedimiento de roles y permisos en pantalla de  grupos: '.$e->getMessage());
            Session::flash('error', 'ERR-001 Ocurrió un error de conexión');
            return view('Bitacoras.error');
        }
        if ($update == '1') {
            try {
                //code...
                $actualizar = Http::withToken(Cache::get('token'))->put($this->url . '/grupos/actualizar/' . $request->cod, [
                    "PV_CLASIFICACION" => $request->clasificacion,
                    "PV_NUM_GRUPO" => $request->grupo,
                    "PV_NOM_GRUPO" => $request->name
                ]);
            } catch (\Exception $e) {
                Log::debug('Error procedimiento de actualización de grupos: '.$e->getMessage());
                Session::flash('error', 'ERR-001 Ocurrió un error de conexión');
                return view('Bitacoras.error');
            }

            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'ACTUALIZO UN DATO EN PANTALLA ',
                    "DES" => Cache::get('user') . ' ACTUALIZO EL DATO DE ' . $request->name . ' EN LA PANTALLA DE GRUPOS',
                    "OBJETO" => 'GRUPOS'

                ]);
            } catch (\Exception $e) {
                Log::debug('Error procedimiento de bitácoras insertar en la actualización de grupos: '.$e->getMessage());
                Session::flash('error', 'ERR-001 Ocurrió un error de conexión');
                return view('Bitacoras.error');
            }
            Session::flash('actualizo', '1');
        } else {
            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'SIN PERMISO METODO ACTUALIZAR',
                    "DES" => Cache::get('user') . ' INTENTO ACTUALIZAR EL DATO ' . $request->name . ' EN LA PANTALLA DE GRUPOS',
                    "OBJETO" => 'GRUPOS'

                ]);

                Session::flash('sinpermiso', '1');
            } catch (\Exception $e) {
                Log::debug('Error procedimiento de bitácoras no autorizado  en la actualización de grupos: '.$e->getMessage());
                Session::flash('error', 'ERR-001 Ocurrió un error de conexión');
                return view('Bitacoras.error');
            }
        }
        return back();
    }

    /**
     * Función eliminar datos en la vista
     */

    public function eliminar(Request $request)
    {

        /**
         * Seguridad de roles y permisos método delete
         */

        try {
            //code...
            $search = Http::withToken(Cache::get('token'))->post($this->url . '/permisos/sel_per_obj', [
                "PV_ROL" => Cache::get('rol'),
                "PV_OBJ" => "GRUPOS"
            ]);

            $permisos = $search->json();
            $eliminacion = 0;
            foreach ($permisos as $key) {
                $eliminacion = $key['PER_ELIMINACION'];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return 'Error Periodo 21';
        }

        if ($eliminacion == '1') {


            try {
                $respuesta = 0;
                $eliminar = http::withToken(Cache::get('token'))->delete($this->url . '/grupos/eliminar/' . $request->cod);

                $respuesta = strrpos($eliminar, 'ESTA EN USO');
                if ($respuesta > 0) {
                    Session::flash('nopuedes', "1");
                    $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                        "USR" => Cache::get('user'),
                        "ACCION" => 'SE INTENTO ELIMINAR GRUPO EN USO',
                        "DES" => Cache::get('user') . ' INTENTO ELIMININAR EL DATO  con codigo' . $request->f . ' EN LA PANTALLA DE GRUPOS',
                        "OBJETO" => 'GRUPOS'

                    ]);
                    return back();
                }
                Session::flash('eliminado', '1');
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error periodo 250';
            }


            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'ELIMINO UN DATO',
                    "DES" => Cache::get('user') . ' ELIMINO EL DATO CON CODIGO ' . $request->f . ' EN LA PANTALLA DE GRUPOS',
                    "OBJETO" => 'GRUPOS'

                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error GRUPOS 250';
            }
        } else {
            # code...
            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'SIN PERMISO METODO ELIMINADO',
                    "DES" => Cache::get('user') . ' INTENTO ELIMININAR EL DATO  con codigo' . $request->f . ' EN LA PANTALLA DE GRUPOS',
                    "OBJETO" => 'GRUPOS'

                ]);

                Session::flash('sinpermiso', '1');
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error GRUPOS 268';
            }
        }
        return back();
    }

    /**
     * Función buscar por grupos los datos en la vista
     */

    public function gruposSearch(Request $request)
    {
        try {
            //code...
            $grupo = http::withToken(Cache::get('token'))->post($this->url . '/grupos/unidades', [
                "NATURALEZA" => $request->NATURALEZA
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return 'busqueda de grupos';
        }
        return $grupo;
    }

    /**
     * Generar el pdf 
     */

    public function pdf()
    {
        try {
            //code...
            $grupo = http::withToken(Cache::get('token'))->get($this->url . '/grupos');
            $grupoArr = $grupo->json();
            return view('grupos.grupopdf', compact('grupoArr'));
        } catch (\Exception $e) {
            Log::debug('Error procedimiento de seleccionar grupos en  la pantalla de grupos: '.$e->getMessage());
            Session::flash('error', 'ERR-001 Ocurrió un error de conexión');
            return view('Bitacoras.error');
        }
    }
}
