@extends('layouts.vistapadre')

<!-- titulo de la pagina  -->
@section('titulo')
    Usuarios | inicio
@endsection

@section('encabezado')
<link rel="stylesheet" href="{{ asset('assets/css/formularios.css') }}">
@endsection

<!-- foto de la barra lateral debajo del nombre HTOURS  -->
@section('foto-user1')
    @if (Cache::get('genero') == 'M')
        {{ asset('assets/images/varon.png') }}
    @else
        {{ asset('assets/images/dama.png') }}
    @endif
@endsection

<!-- nombre del usuario de la barra lateral  -->
@section('Usuario-Lateral')
    {{ Cache::get('user') }}
@endsection
<!-- rol del usuario de la barra lateral  -->
@section('rol-usuario')
    {{ Cache::get('rol') }}
@endsection

<!-- foto del menu de la derecha -->
@section('foto-user2')
    @if (Cache::get('genero') == 'M')
        {{ asset('assets/images/varon.png') }}
    @else
        {{ asset('assets/images/dama.png') }}
    @endif
@endsection
<!-- nombre del menu de la derecha  -->
@section('Usuario-Menu')
    {{ Cache::get('user') }}
@endsection

@section('contenido')

    @if(Session::has('correcto'))
    <script>
        Swal.fire(
            '',
            'Usuario Registrado Correctamente',
            'success'
        )
    </script>
    @endif
    @if (Session::has('actualizado'))
        <script>
            Swal.fire({
                icon: 'success',
                text: 'El usuario se actualizo correctamente'
                // footer: '<a href="">Why do I have this issue?</a>'
            })
        </script>
    @endif
    @if (Session::has('sinpermiso'))
        <script>
            Swal.fire({
                icon: 'error',
                text: 'No cuentas con  permiso para realizar esta accion'
                // footer: '<a href="">Why do I have this issue?</a>'
            })
        </script>
    @endif
    @if(Session::has('existe'))
    <script>
      Swal.fire({
        icon: 'error',
        text: 'El usuario y/o correo que indico, no esta disponible',
        // footer: '<a href="">Why do I have this issue?</a>'
      })
      alert('El usuario y/o correo que indico ya existe')
    </script>
    @endif
    @if (Session::has('correo_existe'))
    <script>
        Swal.fire({
        icon: 'error',
        text: 'El correo que indico, no esta disponible por que se encuentra en uso'
        // footer: '<a href="">Why do I have this issue?</a>'
    })
    alert('El correo que indico ya existe')
    </script>
    @endif
    @if(Session::has('caracteres'))
        <input type="hidden" id="msg" value="{{ Session::get('caracteres') }}">
        <script>
            msg = document.getElementById('msg').value
            Swal.fire({
            icon: 'error',
            text: `Error ${msg}`,
            // footer: '<a href="">Why do I have this issue?</a>'
            })
        </script>
    @endif

    <main>
        <div class="container-scroller">
            <div class="content-wrapper p-1">
                <center>
                    <h1>Usuarios H Tours Honduras</h1>
                </center>

                <!-- <ul class="nav nav-pills nav-stacked">
              <li class="active"><a href="#"></a></li>
            </ul> -->

                {{-- <p align="right" valign="baseline">
      <button type="button"  class="btn btn-success mr-3"  data-toggle="modal " data-target="#dialogo1">(+) Nuevo</button>
    </p> --}}
                <p align="right" valign="baseline">
                    <button type="button"  class="btn btn-info"  data-toggle="modal" data-target="#dialogo1">(+) Nuevo</button>
        
                    <a type="button" href="{{ route('usuarios.pdf') }}" class="btn btn-danger btn-sm"><i
                            class="mdi mdi-file-pdf"></i>Generar PDF</a>

                    <button id="btnExportar" class="btn btn-success btn-sm">
                        <i class="mdi mdi-file-excel"></i> Generar Excel
                    </button>

                <ul class="nav nav-pills nav-stacked">
                    <li class="active"><a href="#"></a></li>
                </ul>
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body p-1">
                            <center>
                                <h4 class="card-title">Registros de usuarios</h4>
                            </center>
                            <!-- <p class="card-description"> Add class <code>.table-striped</code> -->
                            {{-- </p> --}}
                            <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search">
                                <input class="form-control me-2 light-table-filter text-white" data-table="table_id"
                                    type="text" placeholder="Buscar un usuario">
                            </form>

                            <div class="table-responsive">
                                <table id="tabla" class="table table-bordered table-contextual table_id">
                                    <thead>
                                        <tr class="text-dark bg-white">
                                            <th class="text-dark bg-white"># Codigo</th>
                                            <th class="text-dark bg-white">Usuario</th>
                                            <th class="text-dark bg-white">Nombre usuario</th>
                                            <th class="text-dark bg-white">Estado</th>
                                            <th class="text-dark bg-white">Rol</th>
                                            <th class="text-dark bg-white">Tipo</th>
                                            <th class="text-dark bg-white">Ultima Conexión</th>
                                            {{-- <th class="text-dark bg-white">Preguntas contestadas</th> --}}
                                            <th class="text-dark bg-white">Ingresos</th>
                                            <th class="text-dark bg-white">Correo Electronico</th>
                                            <th class="text-dark bg-white">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @if (count($usrArr) <= 0)
                                            <tr>
                                                <td colspan="6">Sin resultados</td>
                                            </tr>
                                        @else
                                            @foreach ($usrArr as $usuario)
                                                <tr class="text-white bg-dark">
                                                    <td>{{ $usuario['CODIGO_USUARIO'] }}</td>
                                                    <td>{{ $usuario['USUARIO'] }}</td>
                                                    <td>{{ $usuario['NOMBRE_USUARIO'] }}</td>
                                                    <td>{{ $usuario['ESTADO_USUARIO'] }}</td>
                                                    <td>{{ $usuario['COD_ROL'] }}</td>
                                                    <td>{{ $usuario['TIPO'] }}</td>
                                                    <td>{{ substr( $usuario['FECHA_ULTIMO_ACCESO'],0,10 )  }}</td>
                                                    {{-- <td>{{$usuario['PREGUNTA_RESPONDIDA']}}</td> --}}
                                                    <td>{{ $usuario['PRIMER_ACCESO'] }}</td>
                                                    <td>{{ $usuario['CORREO_ELECTRONICO'] }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-info" data-toggle="modal"
                                                            data-target="#modal-editar-{{ $usuario['CODIGO_USUARIO'] }}">Editar
                                                        </button>
                                                        {{-- <button 
                                                                    type="button"  
                                                                    class="btn btn-danger"  
                                                                    data-toggle="modal" 
                                                                    data-target="#modal-eliminar-{{$usuario['CODIGO_USUARIO']}}">Eliminar
                                                                </button>  --}}
                                                    </td>
                                                </tr>

                                                <!--MODAL EDITAR -->
                                                <div class="modal-container">
                                                    <div class="modal fade bd-example-modal-lg"
                                                        id="modal-editar-{{ $usuario['CODIGO_USUARIO'] }}">
                                                        <!-- COLOCARLE UN lg PARA TAMANO MEDIANO COLOCARLE UN sm PARA TAMANO PEQUENO -->
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content">
                                                                <!-- CABECERA DEL DIALOGO NUEVA-->
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Editar Usuarios</h4>
                                                                    <!-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button> -->
                                                                </div>
                                                                <!-- CUERPO DEL DIALOGO NUEVA -->
                                                                <div class="modal-body" style="width: 30rem;">
                                                                    <center>
                                                                        <form action="{{ route('usuarios.actualizar') }}"
                                                                            method="put">
                                                                            @csrf @method('PUT')
                                                                            <input name="COD_USR" type="hidden"
                                                                                value="{{ $usuario['CODIGO_USUARIO'] }}">
                                                                            <label class="form-label">
                                                                                Usuario
                                                                                <input type='text' name='USUARIO'
                                                                                    class="form-control text-info bg-dark font-weight-bold"
                                                                                    id="nom_usuario-edit-{{ $usuario['CODIGO_USUARIO'] }}" maxlength="50"
                                                                                    onkeyup="validarNomUsuario({{ $usuario['CODIGO_USUARIO'] }})"
                                                                                    value="{{ $usuario['USUARIO'] }}"
                                                                                    readonly>
                                                                                    <center>
                                                                                        <div style="background-color: white; opacity: 0.5;" id="divnomusuario-edt-{{ $usuario['CODIGO_USUARIO'] }}"></div>
                                                                                    </center>
                                                                            </label>
                                                                            <label class="form-label">
                                                                                Nombre del usuario
                                                                                <input type='text' name='NOMBRE_USUARIO'
                                                                                    class="form-control text-white"
                                                                                    id="usr_usuario-edit-{{  $usuario['CODIGO_USUARIO']  }}" maxlength="100"
                                                                                    onkeyup="validarUsrUsuario({{  $usuario['CODIGO_USUARIO']  }})"
                                                                                    value="{{ $usuario['NOMBRE_USUARIO'] }} "
                                                                                    required>
                                                                                    <center>
                                                                                        <div style="background-color: white; opacity: 0.5;" id="divusrusuario-edit-{{  $usuario['CODIGO_USUARIO']  }}"></div>
                                                                                    </center>
                                                                            </label>
                                                                            <label class="form-label">
                                                                                Seleccionar el estado
                                                                                <select class="form-control text-white"
                                                                                    name="ESTADO" id="">
                                                                                    <option hidden selected
                                                                                        value="{{ $usuario['ESTADO_USUARIO'] }}">
                                                                                        {{ $usuario['ESTADO_USUARIO'] }}
                                                                                    </option>
                                                                                    <option value="NUEVO">Nuevo</option>
                                                                                    <option value="ACTIVO">Activo</option>
                                                                                    <option value="INACTIVO">Inactivo
                                                                                    </option>
                                                                                    <option value="BLOQUEADO">Bloqueado
                                                                                    </option>
                                                                                </select>
                                                                            </label>
                                                                            <label class="form-label">
                                                                                Seleccionar el Rol
                                                                                <select class="form-control text-white"
                                                                                    name="ROL" id="">
                                                                                    <option
                                                                                        value="{{ $usuario['COD_ROL'] }}"
                                                                                        hidden selected>
                                                                                        {{ $usuario['TIPO'] }}</option>
                                                                                    @foreach ($usr_rol_Arr as $usr_rol)
                                                                                        <option
                                                                                            value="{{ $usr_rol['COD_ROL'] }}">
                                                                                            {{ $usr_rol['ROL'] }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </label>
                                                                            <br>
                                                                            <label class="form-label" >
                                                                                Correo Electronico
                                                                                <input type='email' name='CORREO' name="correo" id="correo_usuario"
                                                                                    class="form-control text-white"
                                                                                    id="correo_usuario-edit-{{  $usuario['CODIGO_USUARIO']  }}" 
                                                                                    onkeyup="validarCorreoEdit({{  $usuario['CODIGO_USUARIO']  }})"
                                                                                    value="{{ $usuario['CORREO_ELECTRONICO'] }}"
                                                                                    required>
                                                                                    <center>
                                                                                        <div style="background-color: white; opacity: 0.5;" id="divcorreo-edit-{{ $usuario['CODIGO_USUARIO']  }}"></div>
                                                                                    </center>
                                                                            </label>
                                                                            <br>

                                                                            <a href=""
                                                                                class="btn btn-secondary">Cancelar</a>
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Aceptar</button>
                                                                        </form>
                                                                        <script src="{{ asset('assets/js/registro.js') }}"></script>
                                                                        <script src="{{ asset('assets/js/ab-usuarios.js') }}"></script>
                                                                </div>
                                                                <button type="button" class="btn btn-danger"
                                                                    data-dismiss="modal">Cerrar</button>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- FIN DE MODAL PARA EDITAR  -->

                                                <!-- INICIO MODAL PARA BORRAR  -->
                                                {{-- <div class="modal-container">
                                                    <div class="modal fade bd-example-modal-lg"
                                                        id="modal-eliminar-{{ $usuario['CODIGO_USUARIO'] }}">
                                                        <!-- COLOCARLE UN lg PARA TAMANO MEDIANO COLOCARLE UN sm PARA TAMANO PEQUENO -->
                                                        <div class="modal-dialog modal-sm">
                                                            <div class="modal-content">
                                                                <!-- CABECERA DEL DIALOGO EDITAR -->
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Eliminar usuarios</h4>
                                                                    <!-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button> -->
                                                                </div>
                                                                <!-- CUERPO DEL DIALOGO BORRAR -->
                                                                <div class="modal-body">
                                                                    <center>
                                                                        <form action="" method="post">
                                                                            <label class="form-label">¿ Desea eliminar
                                                                                usuario ? </label>
                                                                            <br>
                                                                            <a href=""
                                                                                class="btn btn btn-primary">SI</a>
                                                                            <a href=""
                                                                                class="btn btn-secondary">NO</a>

                                                                        </form>
                                                                </div>
                                                                <button type="button" class="btn btn-danger"
                                                                    data-dismiss="modal">Cerrar</button>
                                                                </center>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <!-- FIN DE MODAL PARA BORRAR  -->
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div id="paginador"></div>
                        </div>
                    </div>
                </div>

                <!-- content-wrapper ends -->

                <!-- INICIO MODAL PARA NUEVO USR  -->
                <div class="modal-container">
                    <div class="modal fade bd-example-modal-lg" id="dialogo1">
                        <!-- COLOCARLE UN lg PARA TAMANO MEDIANO COLOCARLE UN sm PARA TAMANO PEQUENO -->
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <!-- CABECERA DEL DIALOGO NUEVA-->
                                <div class="modal-header">
                                    <h4 class="modal-title">Ingresar Usuarios</h4>
                                    <!-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button> -->
                                </div>
                                <!-- CUERPO DEL DIALOGO NUEVA -->
                                <div class="modal-body">
                                    <center>
                                        <form action="{{ route('Registrar.usuario') }}" method="POST" id="formregistro">
                                            @csrf @method('POST')
                                            <label class="form-group w-75 p-1">
                                                <label><H4><i class="mdi mdi-account"></i> Nombre completo</H4></label>
                                                <input  type="text" id="nom_usuario" name="nombre"                                                        placeholder="Ingrese nombre completo" 
                                                        class="form-control p_input text-white col-lg-12" 
                                                        onkeyup="validarUsrConfig(this)" required>
                                                        <center>
                                                            <div style="background-color: white; opacity: 0.5;" id="divusrconfig"></div>
                                                        </center>
                                            </label>
                                            <label class="form-group w-75 p-1">
                                                <label><H4><i class="mdi mdi-account"></i> Usuario</H4></label>
                                                <input  type="text" style="text-transform:uppercase"  
                                                        onkeyup="javascript:this.value=this.value.toUpperCase(); 
                                                        validarUsuario(this);" id="usr_usuario" name="user" 
                                                        placeholder="Ingrese nombre de usuario" 
                                                        class="form-control p_input text-white" required>
                                                        <center>
                                                            <div style="background-color: white; opacity: 0.5;" id="divusuario"></div>
                                                        </center>
                                            </label>
                                            <label class="form-label w-75 p-1">
                                                <label><H4><i class="mdi mdi-email"></i> Correo Electrónico</H4></label>
                                                <input  type="email" placeholder="Ingresa un Correo Electrónico"  
                                                        id="correo_usuario" name="correo"  
                                                        class="form-control p_input text-white" 
                                                        onkeyup="validarCorreoConfig(this)">
                                                        <center>
                                                            <div style="background-color: white; opacity: 0.5;" id="divcorreoconfig"></div>
                                                        </center>
                                            </label>
                                            <label class="form-label w-75 p-1">
                                                <label><H4><i class="mdi mdi-lock" onclick="mostrarContra()"></i> Contraseña</H4></label>
                                                <div class="form-row">
                                                    <div id="is-relative" class="col" style="div#is-relative{ max-width: 420px; position: relative;}">
                                                        <input  style="padding-right: 2.5rem;" 
                                                                class="form-control p_input text-white"
                                                                minlength="8" maxlength="32" 
                                                                onkeyup="muestra_requisitos_clave(this.value)" 
                                                                placeholder="Ingrese una contraseña" 
                                                                type="password" name="password1" id="password1" required>
                                                        <span id="icon" style="color: black; position: absolute; display: block; bottom: .2rem; right: 1rem; user-select: none;cursor: pointer;">
                                                        <i id="ojo1" class="mdi mdi-eye-outline" style="color:white" onclick="mostrarContra()"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div id="requisitos-clave"></div>
                                            </label>
                                            <label class="form-label w-75 p-1">
                                                <label><H4><i class="mdi mdi-lock"  onclick="mostrarContrasena()"></i> Repetir Contraseña</H4></label>
                                                <div class="form-row">
                                                  <div id="is-relative" class="col" style="div#is-relative{ max-width: 420px; position: relative;}">
                                                        <input    style="padding-right: 2.5rem;"    
                                                                    class="form-control p_input text-white"
                                                                    minlength="8" maxlength="32"  
                                                                    onchange="comparar();" 
                                                                    placeholder="Ingrese de nuevo la contraseña" 
                                                                    type="password" name="password2" id="password2" required>
                                                        <span id="icon" style="color: black; position: absolute; display: block; bottom: .2rem; right: 1rem; user-select: none;cursor: pointer;">
                                                            <i id="ojo2" class="mdi  mdi-eye-outline" style="color:white"onclick="mostrarContrasena()"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </label>
                                            <div class="text-center p-2">
                                                <a href="{{ route('usuarios.inicio') }}" class="btn btn-secondary">Cancelar</a>
                                                <button onclick="validacion();"  type="submit" class="btn btn-primary  enter-btn">Registrarse</button>
                                            </div>
                                            
                                        </form>
                                        <script>
                                            function mostrarContra(){
                                              var ojo = document.getElementById("ojo1");
                                              var tipo = document.getElementById("password1");
                                              if(tipo.type == "password"){
                                                tipo.type = "text";
                                                ojo.className = 'mdi mdi-eye-off-outline';
                                              }else{
                                                tipo.type = "password";
                                                ojo.className = 'mdi mdi-eye-outline';
                                              }
                                            }
                                        </script>
                                        <script>
                                            function mostrarContrasena(){
                                              var ojo = document.getElementById("ojo2");
                                              var tipo = document.getElementById("password2");
                                              if(tipo.type == "password"){
                                                ojo.className = 'mdi mdi-eye-off-outline';
                                                    tipo.type = "text";
                                                }else{
                                                  tipo.type = "password";
                                                  ojo.className = 'mdi mdi-eye-outline';
                                                }
                                            } 
                                        </script>
                                            {{-- seguridad --}}
                                        <script>
                                            function muestra_requisitos_clave(clave) {
                                                var mensaje = "La contraseña debe contener al menos:\n";
                                                var expresion = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[a-z])(?=.*[^\w\s]).{8,}$/;
                                                var requisitosDiv = document.getElementById("requisitos-clave");
                                        
                                                if (!clave.match(expresion)) {
                                                    if (!clave.match(/(?=.*[A-Z])/)) mensaje += "- Una letra mayúscula\n";
                                                    if (!clave.match(/(?=.*[0-9])/)) mensaje += "- Un número\n";
                                                    if (!clave.match(/(?=.*[a-z])/)) mensaje += "- Una letra minúscula\n";
                                                    if (!clave.match(/(?=.*[^\w\s])/)) mensaje += "- Un símbolo (!, @, #, etc.)\n";
                                                    if (clave.length < 8) mensaje += "- Mínimo 8 caracteres\n";
                                        
                                                    requisitosDiv.innerHTML = mensaje;
                                                } else {
                                                    // Si se cumplen todos los requisitos, se desaparece el mensaje
                                                    requisitosDiv.innerHTML = "";
                                                }
                                            }
                                        
                                        </script>

                                        <script src="{{ asset('assets/js/registro.js') }}"></script>
                                </div>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN DE MODAL PARA NUEVO USR  -->

                
                    <div class="modal fade bd-example-modal-lg" id="dialogo2">
                        <!-- COLOCARLE UN lg PARA TAMANO MEDIANO COLOCARLE UN sm PARA TAMANO PEQUENO -->
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <!-- CABECERA DEL DIALOGO EDITAR -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Editar Usuario</h4>
                                    <!-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button> -->
                                </div>
                                <!-- CUERPO DEL DIALOGO EDITAR -->
                                <div class="modal-body">
                                    <center>
                                        <form action="" method="post">
                                            <label class="form-label">
                                                Usuario
                                                <input type='text' name='Clasificacion'
                                                    class="form-control text-white" required></input>
                                            </label>
                                            <label class="form-label">
                                                Nombre usuario
                                                <input type='text' name='NOM USUARIO' class="form-control text-white"
                                                    required></input>
                                            </label>
                                            <label class="form-label">
                                                Seleccionar el Rol

                                                <select class="form-control text-white" name="" id="">
                                                    <option value=""></option>
                                                    <option value="">Administrador</option>
                                                    <option value="">Usuario</option>
                                                </select>
                                            </label>
                                            <br>

                                            <label class="form-label">
                                                Correo Electronico
                                                <input type='email' name='CORREO ELECTRONICO'
                                                    class="form-control text-white" required></input>
                                            </label>
                                            <label class="form-label">
                                                Contraseña
                                                <input type='password' name='CORREO ELECTRONICO'
                                                    class="form-control text-white" required></input>
                                            </label>
                                            <label class="form-label">
                                                Fecha de vencimiento
                                                <input type='date' name='COS PRODUCTO' class="form-control text-white"
                                                    required></input>
                                            </label>














                                            <!-- partial -->
                                            <div class="main-panel">

                                                <!-- partial:../../partials/_footer.html -->
                                                <footer class="footer">
                                                    <div
                                                        class="d-sm-flex justify-content-center justify-content-sm-between">
                                                        <span
                                                            class="text-muted d-block text-center text-sm-left d-sm-inline-block"></span>
                                                        <!-- <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/bootstrap-admin-template/" target="_blank">Bootstrap admin templates</a> from Bootstrapdash.com</span> -->
                                                    </div>
                                                </footer>
                                                <!-- partial -->
                                            </div>
                                            <!-- main-panel ends -->
                                </div>

                            </div>
    </main>

    @section('js')
        {{-- VALIDACIONES --}}
        <script src="{{ asset('assets/js/ab-usuarios.js') }}"></script>
        <script src="{{ asset('assets/js/registro.js') }}"></script>

         <!-- inject:js -->
        <script src="{{asset('assets/js/off-canvas.js')}}"></script>
        <script src="{{asset('assets/js/hoverable-collapse.js')}}"></script>
        <script src="{{asset('assets/js/misc.js')}}"></script>
        <script src="{{asset('assets/js/settings.js')}}"></script>
        <script src="{{asset('assets/js/todolist.js')}}"></script>
        <!-- endinject -->
        <script src="{{asset('assets/vendors/js/vendor.bundle.base.js')}}"></script>
  
        {{-- BUSCADOR --}}
        <script src="{{ asset('assets/js/ab-buscador.js') }}"></script>
        {{-- PAGINACIÓN --}}
        <script src="{{ asset('assets/js/ab-page.js') }}"></script>
        {{-- GENERADOR DE EXCEL --}}
        <script>
            const $btnExportar = document.querySelector("#btnExportar"),
                $tabla = document.querySelector("#tabla");

            $btnExportar.addEventListener("click", function() {
                let tableExport = new TableExport($tabla, {
                    exportButtons: false, // No queremos botones
                    filename: "Reporte de Usuarios", //Nombre del archivo de Excel
                    sheetname: "Reporte de Usuarios", //Título de la hoja
                    ignoreCols: 9,
                });
                let datos = tableExport.getExportData();
                let preferenciasDocumento = datos.tabla.xlsx;
                tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType,
                    preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento
                    .merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
            });
        </script>
    @endsection

@endsection
