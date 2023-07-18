<?php
/*
 *  © 2013 Framelova. All rights reserved. Privacy Policy
 *  Creado: 31/03/2017
 *  Por: MJCS
 *  Descripción: This functions  are called via Ajax
 */
$dirname = dirname(__DIR__);
include_once $dirname.'/brules/catConfiguracionesObj.php';
include_once $dirname.'/brules/usuariosObj.php';
include_once $dirname.'/brules/catAyudasObj.php';
include_once $dirname.'/brules/comunicadosObj.php';
include_once $dirname.'/brules/utilsObj.php';
include_once $dirname.'/brules/clientesObj.php';
include_once $dirname.'/brules/catTipoCasosObj.php';
include_once $dirname.'/brules/casosObj.php';
include_once $dirname.'/brules/casoAccionesObj.php';
// include_once $dirname.'/brules/accionGastosObj.php';
include_once $dirname.'/brules/EmailFunctionsObj.php';
// include_once $dirname.'/libs/eventosCal/iCalEasyReader.php';
include_once $dirname.'/brules/conceptosObj.php';
include_once $dirname.'/brules/catConceptosObj.php';
include_once $dirname.'/brules/catJuiciosObj.php';
include_once $dirname.'/brules/catJuzgadosObj.php';
include_once $dirname.'/brules/catContactosObj.php';
include_once $dirname.'/brules/catDocumentosObj.php';
include_once $dirname.'/brules/catAccionesObj.php';
include_once $dirname.'/brules/mensajesObj.php';
include_once $dirname.'/brules/tareasObj.php';
include_once $dirname.'/brules/digitalesObj.php';
include_once $dirname.'/brules/digitalesObj.php';
include_once $dirname.'/brules/comentariosObj.php';
//include_once $dirname.'/libs/pdfmerge/vendor/autoload.php';
//include_once $dirname.'/libs/pdfmerge/vendor/composer/autoload_classmap.php';
//include_once $dirname.'/libs/pdfmerge/vendor/composer/autoload_namespaces.php';
include_once $dirname.'/libs/PDFMerger/PDFMerger.php';
use PDFMerger\PDFMerger;
include_once $dirname.'/brules/arraysObj.php';
include_once $dirname.'/brules/cuentasObj.php';
include_once $dirname.'/brules/pagosObj.php';
include_once $dirname.'/brules/audiosObj.php';
//Fisrt check the function name
$function= $_GET['funct'];
switch ($function){
    case "funcionEjemplo":
        funcionEjemplo($_GET['callback'], $_GET['id'],$_GET['valor']);
        break;
    case "verificaExisteEmail":
        verificaExisteEmail($_GET['callback'],$_GET['email']);
    break;

    case "obtDatosConfiguracion":
        obtDatosConfiguracion();
    break;    

    case "guardarConfiguracion":
        guardarConfiguracion($_POST['idConfiguracion'], $_POST['valor']);
    break;

    case "mostrarAyuda":
      mostrarAyuda($_GET['callback'],$_GET['alias']);
   break;

   case "guardarAyuda":
        guardarAyuda($_POST['alias'] ,$_POST['contenido']);
    break;

    case "generarPasswordUsuario":
        generarPasswordUsuario($_GET['callback']);
    break;

    case "verificaUsoTabla":
       verificaUsoTabla($_GET['callback'],$_GET['nombreTabla'],$_GET['idRegistro']);
    break;

     case "eliminarRegCatalogo":
       eliminarRegCatalogo($_GET['callback'],$_GET['elimTipo'],$_GET['elimRegId']);
    break;

    case "guardarComunicado":
        guardarComunicado($_POST);
    break;

    case "muestraTablaContenido":
    muestraTablaContenido($_GET['callback']);
    break;

    case "cargaSelector":
        cargaSelector($_GET['callback']);
      break;

      case "enviarMesanjeClientesMultiple":
        enviarMesanjeClientesMultiple();
    break;

    // Listados de clientes
    case "tblListaClientes": tblListaClientes(); break;
    case "obtListaContactos": obtListaContactos(); break;
    case "obtListaDocumentos": obtListaDocumentos(); break;
    case "obtListaDigitales": obtListaDigitales(); break;
    case "tblListaTitulares": tblListaTitulares(); break;
    case "crearCliente": crearCliente(); break;
    case "muestraEditarCliente": muestraEditarCliente(); break;
    case "crearContacto": crearContacto(); break;
    case "crearDocumento": crearDocumento(); break;
    case "crearDigital":crearDigital($_POST);break;
    case "crearTipo": crearTipo(); break;
    case "guardarCuenta": guardarCuenta(); break;
    case "guardarPago": guardarPago(); break;
    case "guardarCasosAsociadosCta": guardarCasosAsociadosCta(); break;
    case "guardarCobros": guardarCobros(); break;
    case "guardaComentarioExpediente": guardaComentarioExpediente(); break;
    case "crearCaso": crearCaso(); break;
    case "creaEditaAccion": creaEditaAccion(); break;
    case "creaEditaAccion2": creaEditaAccion2(); break;
    case "creaEditaTarea": creaEditaTarea(); break;
    case "creaComentarioTarea": creaComentarioTarea(); break;
    case "obtDatosAccion": obtDatosAccion(); break;
    case "tblListaGastos": tblListaGastos(); break;
    case "creaEditaGasto": creaEditaGasto(); break;
    case "obtDatosGasto": obtDatosGasto(); break;
    case "eliminarGasto": eliminarGasto(); break;
    case "eliminarAccion": eliminarAccion(); break;
    case "obtTotalGastos": obtTotalGastos(); break;
    case "obtEventosGoogleCal": obtEventosGoogleCal(); break;
    case "salvarEventos": salvarEventos(); break;

    case "verTablaConceptos": verTablaConceptos(); break;
    case "guardarConcepto": guardarConcepto(); break;
    case "marcarComoLeido": marcarComoLeido(); break;
    case "marcarLeidoComentario": marcarLeidoComentario(); break;
    case "unirPdf": unirPdf2(); break;
    case "ordenarDocs": ordenarDocs(); break;
    case "revisaArchivo": revisaArchivo(); break;
    case "crearCitaSimple": crearCitaSimple(); break;
    case "crearCitaSocial": crearCitaSocial(); break;
    case "eliminarCitaSimple": eliminarCitaSimple(); break;
    case "eliminarPagoSerie": eliminarPagoSerie(); break;
    case "obtProxEventosCaso": obtProxEventosCaso(); break;
    case "obtArbolCaso": obtArbolCaso(); break;
    case "salvarPadreId": salvarPadreId(); break;
    case "eliminarCliente": eliminarCliente(); break;
    //>>>>CMPB, 03/02/2023, cambios para eliminar nodos con hijos o sin hijos
    case "reasignarPadreId": reasignarPadreId(); break;
    case "eliminarDelPadreId": eliminarDelPadreId(); break;
    case "crearPagoAgenda": crearPagoAgenda(); break;//>>>>>>imp. CMPB 21/02/2022 crearCobroAgenda
    case "crearCobroAgenda": crearCobroAgenda(); break;
    case "guardarAudio": guardarAudio(); break;
    case "eliminarNota": eliminarNota(); break;
    case "eliminarCobro": eliminarCobro(); break;
    case "cambiarTipo2a1": cambiarTipo2a1(); break;
    case "cambiarTipo2a3": cambiarTipo2a3(); break;
    case "cambiarTipo2a4": cambiarTipo2a4(); break;
    case "cambiarTipo3a1": cambiarTipo3a1(); break;
    case "cambiarTipo3a2": cambiarTipo3a2(); break;
    case "cambiarTipo3a4": cambiarTipo3a4(); break;
    case "cambiarTipo4a1": cambiarTipo4a1(); break;
    case "cambiarTipo4a2": cambiarTipo4a2(); break;
    case "cambiarTipo4a3": cambiarTipo4a3(); break;
    default:
    echo "Not valid call";
}

// Obtener la lista de clientes
function obtListaContactos(){
  $callback = (isset($_GET['callback']) !="")?$_GET['callback']:"";
  $idTabla = (isset($_GET['idTabla']) !="")?$_GET['idTabla']:"";
  $expedienteId = (isset($_GET['expedienteId']) !="")?$_GET['expedienteId']:0;
  $idRol = (isset($_GET['idRol']) !="")?$_GET['idRol']:"";

  $catContactosObj = new catContactosObj();
  $colRes = $catContactosObj->ObtContactos("", $expedienteId);
  $arr = array("success"=>false);

  // echo "<pre>";
  // print_r($colRes);
  // echo "</pre>";
  // exit();
  if(count($colRes)>0){
    // table-striped
    $thAcciones = ($idRol == 5)?'':'<th>Acciones</th>';
    $html = '
        <table id="'.$idTabla.'" class="table table-bordered table-condensed dataTable no-footer dt-responsive hover" role="grid" cellspacing="0" width="100%" >
            <thead>
                <tr>
                    
                    <th>Nombre <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>Email <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>Telefono <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>Domicilio <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>Notas <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    '.$thAcciones.'
                </tr>
            </thead>
            <tbody>
            ';
              foreach($colRes as $item){
                // if($idUsuario!=$item->idUsuario && $item->idRol!=4){
                  $tdAcciones = ($idRol == 5)?'':'<a data-toggle="modal" data-target="#popup_modalCrearContacto"  class="grid_edit" onclick="editarContacto('.$item->idContacto.', \''.$item->nombre.'\', \''.$item->email.'\', \''.$item->telefono.'\', \''.$item->domicilio.'\', \''.$item->notas.'\')"  title="Editar"></a>';
                  $html .= '
                  <tr>
                      <td>'.$item->nombre.'</td>
                      <td>'.$item->email.'</td>
                      <td>'.$item->telefono.'</td>
                      <td>'.$item->domicilio.'</td>
                      <td>'.$item->notas.'</td>
                      <td>'.$tdAcciones.'</td>
                  </tr>
                  ';
                // }
              }
        $html .= '
            </tbody>
        </table>
    ';

    $arr = array("success"=>true, "tablaContactos"=>$html);
  }else{
    $html = '
    No hay registros que mostrar
';
$arr = array("success"=>true, "tablaContactos"=>$html);
  }

  echo $callback . '(' . json_encode($arr) . ');';
}

// Obtener la lista de documentos
function obtListaDocumentos(){
  $callback = (isset($_GET['callback']) !="")?$_GET['callback']:"";
  $idTabla = (isset($_GET['idTabla']) !="")?$_GET['idTabla']:"";
  $expedienteId = (isset($_GET['expedienteId']) !="")?$_GET['expedienteId']:0;

  $catDocumentosObj = new catDocumentosObj();
  $colRes = $catDocumentosObj->ObtCatDocumentos("", $expedienteId);
  $arr = array("success"=>false);

  if(count($colRes)>0){
    // table-striped
    $html = '
        <table id="'.$idTabla.'" class="table table-bordered table-condensed dataTable no-footer dt-responsive hover" role="grid" cellspacing="0" width="100%" >
            <thead>
                <tr>
                    
                    <th>Nombre <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>Condiciones <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>F. Recepcion <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>F. Retorno <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                </tr>
            </thead>
            <tbody>
            ';
              foreach($colRes as $item){
                $fechaRetorno = ($item->fechaRetorno != '')?convertirFechaVista($item->fechaRetorno):'';
                // if($idUsuario!=$item->idUsuario && $item->idRol!=4){
                  $html .= '
                  <tr>
                     
                      <td>'.$item->nombre.'</td>
                      <td>'.$item->condiciones.'</td>
                      <td>'.convertirFechaVista($item->fechaRecepcion).'</td>
                      <td>'.$fechaRetorno.'</td>
                  </tr>
                  ';
                // }
              }
        $html .= '
            </tbody>
        </table>
    ';

    $arr = array("success"=>true, "tablaDocumentos"=>$html);
  }else{
    $html = '
    No hay registros que mostrar
';
$arr = array("success"=>true, "tablaDocumentos"=>$html);
  }

  echo $callback . '(' . json_encode($arr) . ');';
}

function obtListaDigitales(){
  $callback = (isset($_GET['callback']) !="")?$_GET['callback']:"";
  $idTabla = (isset($_GET['idTabla']) !="")?$_GET['idTabla']:"";
  $expedienteId = (isset($_GET['expedienteId']) !="")?$_GET['expedienteId']:0;

  $digitalesObj = new digitalesObj();
  $colRes = $digitalesObj->ObtDigitales($expedienteId);
  $arr = array("success"=>false);

  if(count($colRes)>0){
    // table-striped
    $html = '
        <table id="'.$idTabla.'" class="table table-bordered table-condensed dataTable no-footer dt-responsive hover" role="grid" cellspacing="0" width="100%" >
            <thead>
                <tr>
                    
                    <th>Nombre <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>Tipo <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            ';
              foreach($colRes as $item){
                $btnVer = '';
                $folder = obtFolderDigital($item->tipo);
                $fileLink = '../upload/'.$folder.'/'.$item->url;
                if(file_exists($fileLink)){
                  $btnVer = '
                  <a target="_blank" class=""  href="'.$fileLink.'" title="Ver"><span class="material-icons">open_in_new</span></a>';
                }
                switch ($item->tipo) {
                  case 1: $tipoText = 'Escrito'; break;
                  case 2: $tipoText = 'Expediente'; break;
                  case 3: $tipoText = 'Audiencias'; break;
                  case 4: $tipoText = 'Otros'; break;
                  default:
                    # code...
                    break;
                }
                // if($idUsuario!=$item->idUsuario && $item->idRol!=4){
                  $html .= '
                  <tr>
                     
                      <td>'.$item->nombre.'</td>
                      <td>'.$tipoText.'</td>
                      <td>
                        '.$btnVer.'
                        <a class="grid_delete btnFancyBox" onclick="verificaUsoTabla(\'digitales\', '.$item->idDocumento.')" href="#fancyElimCat" title="Eliminar"></a>
                      </td>
                  </tr>
                  ';
                // }
              }
        $html .= '
            </tbody>
        </table>
    ';

    $arr = array("success"=>true, "tablaDigitales"=>$html);
  }else{
    $html = '
    No hay registros que mostrar
';
$arr = array("success"=>true, "tablaDigitales"=>$html);
  }

  echo $callback . '(' . json_encode($arr) . ');';
}


// Obtener la lista de clientes
function tblListaClientes(){
  $callback = (isset($_GET['callback']) !="")?$_GET['callback']:"";
  $idTabla = (isset($_GET['idTabla']) !="")?$_GET['idTabla']:"";
  // $idUsuario = (isset($_GET['idUsuario']) !="")?$_GET['idUsuario']:"";

  $clientesObj = new clientesObj();
  $colRes = $clientesObj->ObtClientes();
  $arr = array("success"=>false);

  // echo "<pre>";
  // print_r($colRes);
  // echo "</pre>";
  // exit();
  if(count($colRes)>0){
    // table-striped
    $html = '
        <table id="'.$idTabla.'" class="table table-bordered table-condensed dataTable no-footer dt-responsive hover" role="grid" cellspacing="0" width="100%" >
            <thead>
                <tr>
                    <th>ID <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>Nombre <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>Email <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>Telefono <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>Empresa <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>Direccion <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>Aka <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                </tr>
            </thead>
            <tbody>
            ';
              foreach($colRes as $item){
                // if($idUsuario!=$item->idUsuario && $item->idRol!=4){
                  $html .= '
                  <tr>
                      <td>'.$item->idCliente.'</td>
                      <td>'.$item->nombre.'</td>
                      <td>'.$item->email.'</td>
                      <td>'.$item->telefono.'</td>
                      <td>'.$item->empresa.'</td>
                      <td>'.$item->direccion.'</td>
                      <td>'.$item->aka.'</td>
                  </tr>
                  ';
                // }
              }
        $html .= '
            </tbody>
        </table>
    ';

    $arr = array("success"=>true, "tblListaClientes"=>$html);
  }

  echo $callback . '(' . json_encode($arr) . ');';
}

// Obtener la lista titulares
function tblListaTitulares(){
  $callback = (isset($_GET['callback']) !="")?$_GET['callback']:"";
  $idTabla = (isset($_GET['idTabla']) !="")?$_GET['idTabla']:"";
  // $idUsuario = (isset($_GET['idUsuario']) !="")?$_GET['idUsuario']:"";

  $usuariosObj = new usuariosObj();
  $colRes = $usuariosObj->obtTodosUsuarios(true, 4,"21,23","A.numAbogado",true);
  $arr = array("success"=>false);

   //echo "<pre>";
   //print_r($colRes);
   //echo "</pre>";
  // exit();
  if(count($colRes)>0){
    // table-striped
    $html = '
        <table id="'.$idTabla.'" class="table table-bordered table-condensed dataTable no-footer dt-responsive hover" role="grid" cellspacing="0" width="100%" >
            <thead>
                <tr>
                    <th class="col_hiddden">ID<i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>Num Abogado <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>Nombre <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                </tr>
            </thead>
            <tbody>
            ';
              foreach($colRes as $item){
                if($item->activo == 1){
                // if($idUsuario!=$item->idUsuario && $item->idRol!=4){
                  $html .= '
                  <tr>
                      <td class="col_hiddden">'.$item->idUsuario.'</td>
                      <td>'.$item->numAbogado.'</td>
                      <td>'.$item->nombre.'</td>
                  </tr>
                  ';
                // }
                }
              }
        $html .= '
            </tbody>
        </table>
    ';

    $arr = array("success"=>true, "tblListaTitulares"=>$html);
  }

  echo $callback . '(' . json_encode($arr) . ');';
}



function funcionEjemplo($callback, $id, $valor){
    $gamaModelosObj = new gamaModelosObj();
    $actualizacionesObj = new catActualizacionesObj();
    $actualizacionesObj->updActualizacion("gama_modelos");
    $res = $gamaModelosObj->ActCampoGamaModelo('activo', $valor, $id);
    $return_arr = array("success"=>true, "res"=>$res);
    echo $callback . '(' . json_encode($return_arr) . ');';
}

function verificaExisteEmail($callback, $email){
    $usuariosObj = new usuariosObj();
    //$prospectoObj = new clientesProspectosObj();

    $usuario = $usuariosObj->UserByEmail($email);
    //$prospecto = $prospectoObj->ObtClienteProspectoByEmail($email);

    $return_arr = array("success"=>true, "idUsuario"=>$usuario->idUsuario);

    echo $callback . '(' . json_encode($return_arr) . ');';
}

function guardarConfiguracion($idConfiguracion, $valor){  
  $dirname = dirname(__DIR__);
  include  $dirname.'/common/config.php';
  $valor = base64_decode($valor);
  $valor = convertirTextoEnriquecido($valor,$siteURL, true );
  
  $catConfiguracionesObj = new catConfiguracionesObj();
  $catConfiguracionesObj->valor = convertirTextoEnriquecido($valor);
  $catConfiguracionesObj->idConfiguracion = $idConfiguracion;
  $catConfiguracionesObj->nombre = $_POST["nombreConf"];

  $res = $catConfiguracionesObj->ActualizarConfiguracion();

  $return_arr = array("success"=>true, "res"=>$res);
  echo json_encode($return_arr);
}

// obtener datos de la configuracion imp 17/10/19
function obtDatosConfiguracion(){
  $catConfiguracionesObj = new catConfiguracionesObj();  

  $callback = (isset($_GET['callback']) !="")?$_GET['callback']:"";
  $idConfiguracion = (isset($_GET['idConfiguracion']) !="")?$_GET['idConfiguracion']:0;    
  $arr = array("success"=>false);

  if($idConfiguracion>0){
    $datosConf = $catConfiguracionesObj->ObtConfiguracionByID($idConfiguracion);
    if($datosConf->idConfiguracion>0){
      $arr = array("success"=>true, "datosConf"=>$datosConf);      
    }
  }

  echo $callback . '(' . json_encode($arr) . ');';
}


function mostrarAyuda($callback, $alias){
    $catAyudasObj = new catAyudasObj();

    $ayuda =$catAyudasObj->ObtAyudaPorAlias($alias);
    // var_dump($catAyudasObj->idAyuda);
    // die();
    if($ayuda->idAyuda > 0){
      $descripcion = str_replace("&#34;",'"', $ayuda->descripcion);
      $return_arr = array("success"=>true, "titulo"=>$ayuda->titulo, "descripcion"=>$descripcion);
    }
    else{
        $return_arr = array("success"=>false);
    }

    echo $callback . '(' . json_encode($return_arr) . ');';
}


function guardarAyuda($alias, $contenido){
    $catAyudasObj = new catAyudasObj();

    $res = $catAyudasObj->ActAyudaPorAlias('descripcion', convertirTextoEnriquecido(base64_decode($contenido)), $alias);

    if($res > 0){
        $return_arr = array("success"=>true);
    }
    else{
        $return_arr = array("success"=>false);
    }

  echo json_encode($return_arr);
}

function generarPasswordUsuario($callback){
    $return_arr = array("success"=>true, "password"=>generarPassword(5));
    echo $callback . '(' . json_encode($return_arr) . ');';
}

function verificaUsoTabla($callback, $nombreTabla, $idRegistro){
  $textoResult = '';
  $sePuedeEliminar = true;
  $palabrasTabla = array();
  $nombreReg = '';

  switch ($nombreTabla) {
    case 'usuarios':
      $usuariosObj = new usuariosObj();

      $palabrasTabla = array("pluralMayus"=>"Usuarios", "pluralMinus"=>"usuarios", "singularMayus"=>"Usuario", "singularMinus"=>"usuario");


      $usuario = $usuariosObj->UserByID($idRegistro);
      // $contactos = $catContactosObj->ObtContactosBuscar("", "", $idRegistro);
      // $cotizaciones = $cotizacionesObj->ObtTodosCotizaciones("", $idRegistro);
      $nombreReg = $usuario->nombre;
      $contactos = array();

      if(count($contactos) > 0){
        $sePuedeEliminar = false;
        $textoResult .= 'El usuario '.$nombreReg.', se utiliza en '.count($contactos).' contactos<br>';
      }

      if($idRegistro == 1){
        $sePuedeEliminar = false;
        $textoResult .= 'El usuario .'.$nombreReg.', es el usuario principal del sistema<br>';
      }




    break;
    case 'digitales':
      $digitalesObj = new digitalesObj();

      $palabrasTabla = array("pluralMayus"=>"Digitales", "pluralMinus"=>"digitales", "singularMayus"=>"Digital", "singularMinus"=>"digital");


      $digital = $digitalesObj->DigitalesPorId($idRegistro);
      // $contactos = $catContactosObj->ObtContactosBuscar("", "", $idRegistro);
      // $cotizaciones = $cotizacionesObj->ObtTodosCotizaciones("", $idRegistro);
      $nombreReg = $digital->nombre;
      $contactos = array();

      if(count($contactos) > 0){
        $sePuedeEliminar = false;
        $textoResult .= 'El '.$palabrasTabla["singularMinus"].' '.$nombreReg.', se utiliza en '.count($contactos).' contactos<br>';
      }

      if($idRegistro == 1){
        $sePuedeEliminar = false;
        $textoResult .= 'El '.$palabrasTabla["singularMinus"].' .'.$nombreReg.', es el usuario principal del sistema<br>';
      }




    break;
    default:break;
  }
  if($sePuedeEliminar){
    $textoResult = '&#191;Est&aacute; seguro de eliminar este '.$palabrasTabla["singularMinus"].'? ('.$nombreReg.')';
  }

  $return_arr = array("success"=>true, "texto"=>$textoResult, "sePuedeEliminar"=>$sePuedeEliminar, "palabrasTabla"=>$palabrasTabla, "nombreReg"=>$nombreReg);
  echo $callback . '(' . json_encode($return_arr) . ');';
}


function eliminarRegCatalogo($callback, $elimTipo, $elimRegId){
  $res = 0;
  switch ($elimTipo) {
    case 'usuarios':
      $usuariosObj = new usuariosObj();
      $res = $usuariosObj->EliminarUsuario($elimRegId);
      break;

    case 'digitales':
      $digitalesObj = new digitalesObj();
      $digital = $digitalesObj->DigitalesPorId($elimRegId);
      $res = $digitalesObj->Eliminar($elimRegId);
      if($res > 0){
        $folder = obtFolderDigital($digital->tipo);
        $fileLink = '../upload/'.$folder.'/'.$digital->url;
        if(file_exists($fileLink)){
            unlink($fileLink);
        }
      }
    break;
    
    default:break;
  }


  $return_arr = array("success"=>true, "res"=>$res);
  echo $callback . '(' . json_encode($return_arr) . ');';
}


// Guardar comunicado
function guardarComunicado($post){
    $dirname = dirname(__DIR__);
    include  $dirname.'/common/config.php';

    $comunicadosObj = new comunicadosObj();
    $add = 0;
    $res = 0;
    $res1 = 0;
    $res2 = 0;
    $opc = "";
    $save_folder = '../upload/comunicados/';
    $imgComunicado = (isset($_FILES))?subirArchivo('imgComunicado', $save_folder):"";
    $contenido = base64_decode($post["contenidoHd"]);
    $contenido = convertirTextoEnriquecido($contenido,$siteURL, true );
    $descripcionCorta = convertirTextoEnriquecido($post["descripcionCorta"],$siteURL, true );

    $comunicadosObj->titulo = convertirTextoEnriquecido($post["titulo"], "", false);
    $comunicadosObj->descripcionCorta = $descripcionCorta;
    $comunicadosObj->contenido = $contenido;
    $comunicadosObj->opcTipo = $post["opcTipo"];
    $comunicadosObj->activo = $post["activo"];
    $comunicadosObj->urlComunicado = convertirTextoEnriquecido($post["urlComunicado"]);
    $comunicadosObj->urlVideo = convertirTextoEnriquecido($post["urlVideo"]);
    
    if($post["idComunicado"] == 0){
        $comunicadosObj->imgComunicado = $imgComunicado;
        $comunicadosObj->GuardarComunicado();
        $add = $comunicadosObj->idComunicado;
        $opc = "add";
    }else{
        $comunicadosObj->idComunicado = $post["idComunicado"];
        $comunicadosObj->idUsuarioCmb = $post["idUsuario"];
        $res1 = $comunicadosObj->ActualizarComunicado();
        if($imgComunicado != ""){
            $res2 = $comunicadosObj->ActualizarCampoComunicado("imgComunicado", $imgComunicado, $post["idComunicado"]);
        }
        if($res1 > 0 || $res2 > 0){
            $res = 1;
        }
        $opc = "upd";
    }

    $return_arr = array("success"=>true, "post"=>$post, "add"=>$add, "res"=>$res, "opc"=>$opc, "files"=>$_FILES, "imgComunicado"=>$imgComunicado);

    echo json_encode($return_arr);
}


  function muestraTablaContenido($callback){
    $id = (isset($_GET["id"]))?$_GET["id"]:0;
    $tabla = (isset($_GET["tabla"]))?$_GET["tabla"]:'';

    $html = '';
    $titulo = '';

    switch($tabla){
      case 'dispositivos': 
        $regDispObj = new registroDispositivo();
        $usuariosObj = new usuariosObj();


        $regDispObj->usuarioId = $id;
        $dispositivos = $regDispObj->obtTodosRegDispositivoPorIdUsr2(); 
        $usuario = $usuariosObj->UserByID($id);
    
        $titulo = "Dispositivos del tutor ".$usuario->nombre;

        $html .= '<div class="table-responsive" id="tablaDetVentaRenov">';
        $html .= '<table class="table table-condensed table-hover ">';//table-striped: clase
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th class="text-center">#</th>';
        $html .= '<th class="text-center">Nombre</th>';
        $html .= '<th class="text-center">Fecha registro</th>';
        $html .= '</tr>';
        $html .= '</thead>';
    
        $html .= '<tbody>';
    
        foreach ($dispositivos as $dispositivo) {
      
            $html .= '<tr>';
            $html .= '<td class="text-right">'.$dispositivo->navegadorCont.'</td>';
            $html .= '<td class="">'.$dispositivo->alias.'</td>';
            $html .= '<td>'.convertirFechaVista($dispositivo->fechaCreacion).'</td>';
            $html .= '</tr>';
        }
    
        $html .= '</tbody>';
    
        $html .= '</table>';
        $html .= '</div>';
      break;

      case 'notificaciones':
        $historicosObj = new historicosObj();
        $alumnosObj = new alumnosObj();
        $usuariosObj = new usuariosObj();

        $historicos = $historicosObj->GetAllHistoricos($id);
        
        $usuario = $usuariosObj->UserByID($id);
    
        $titulo = "Notificaciones del tutor ".$usuario->nombre;

        $html .= '<div class="table-responsive" id="tablaDetVentaRenov">';
        $html .= '<table class="table table-condensed table-hover ">';//table-striped: clase
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th class="text-center">Alumno</th>';
        $html .= '<th class="text-center">Fecha</th>';
        $html .= '<th class="text-center">Hora</th>';
        $html .= '<th class="text-center">Tipo</th>';
        $html .= '<th class="text-center">Registrado</th>';
        $html .= '</tr>';
        $html .= '</thead>';
    
        $html .= '<tbody>';
    
        foreach ($historicos as $historico) {
            $alumno = $alumnosObj->obtAlumnoPorId($historico->alumnoId);
            switch($historico->tipo){
              case 1: $tipo = "Entrada";break;
              case 2: $tipo = "Salida";break;
  
          }
          $arrFecha = explode(" ",$historico->fecha);
          $hora = $arrFecha[1];
            $html .= '<tr>';
            $html .= $alumno->nombreAlumno;
            $html .= convertirFechaVista($historico->fecha);
            $html .= $hora;
            $html .= $tipo;
            $html .= convertirFechaVistaConHora($historico->fechaCreacion);
            $html .= '</tr>';
        }
    
        $html .= '</tbody>';
    
        $html .= '</table>';
        $html .= '</div>';
      break;

      default: 
      break;
    }
  
    
  
    $return_arr = array("success"=>true, "html"=>$html, "titulo"=>$titulo);
  
    echo $callback . '(' . json_encode($return_arr) . ');';
  }


  function cargaSelector($callback){
    $catJuiciosObj = new catJuiciosObj();
    $catJuzgadosObj = new catJuzgadosObj();
    $catAccionesObj = new catAccionesObj();

    $html = "";
    $arrConsulta = array();
    $opcTodos = (isset($_GET["opcTodos"]))?$_GET["opcTodos"]:false;
    $onchange = "";
    $tipochange = (isset($_GET["tipochange"]))?$_GET["tipochange"]:1;
    $dataText = "";
    $campoDT = "";
    $campoDT2 = "";
    $clases = "";//Jair 24/3/2022 Clases para el selector

    switch ($_GET["tabla"]) {
        

        case 'juicios':
          $arrConsulta = $catJuiciosObj->ObtJuicios("", $_GET["idOrigen"]);
          $idTabla = "idJuicio";
          $campoDT = "";
          $clases = "selectpicker";
      break;

      case 'juzgados':
        $arrConsulta = $catJuzgadosObj->ObtJuzgados("", $_GET["idOrigen"]);
        $idTabla = "idJuzgado";
        $campoDT = "";
        $clases = "selectpicker";
    break;

    case 'acciones':
      $arrConsulta = $catAccionesObj->ObtAcciones("", $_GET["idOrigen"]);
      $idTabla = "idAccion";
      $campoDT = "";
    break;

        default:
            # code...
            break;
    }

    switch ($tipochange) {
      case '1':
        $onchange = 'onchange="guardaValorTextoDeSelect(this, \'uMedidaId\');"';
      break;

      case '2':
        $onchange = 'onchange="cargaSelector(\'$_GET["idInputOrigen"]\', \'$_GET["idInputDestino"]\', \'$_GET["tabla"]\')"';
      break;

      default:
        # code...
        break;
    }
    // if(count($arrConsulta) > 0){
    $html .= '<select name="'.$_GET["idInputDestino"].'" id="'.$_GET["idInputDestino"].'" class="form-control required '.$clases.'" '.$onchange.'>';
    $html .= '<option value="">Seleccione...</option>';

    if($opcTodos){
        $html .= '<option value="0">Todos</option>';
    }

    foreach ($arrConsulta as $item) {
      $dataText = ($campoDT != "")?' data-texto="'.$item->$campoDT.'" ':'';
      $dataText2 = ($campoDT2 != "")?' data-texto2="'.$item->$campoDT2.'" ':'';
      $html .= '<option value="'.$item->$idTabla.'" '.$dataText.' '.$dataText2.'>'.$item->nombre.'</option>';
    }
    $html .= '</select>';
    // }


    $arr = array("success" => true, "html" => $html);
    echo $callback . '(' . json_encode($arr) . ');'; 
}

function enviarMesanjeClientesMultiple(){
  $callback = (isset($_GET['callback']) !="")?$_GET['callback']:'';
  $idUsuarios = (isset($_GET['idUsuarios']) !="")?$_GET['idUsuarios']:'';
  $titulo = (isset($_GET['titulo']) !="")?$_GET['titulo']:'';
  $contenido = (isset($_GET['contenido']) !="")?$_GET['contenido']:'';
  $tipo = (isset($_GET['tipo']) !="")?$_GET['tipo']:1;
  $creador = (isset($_GET['creador']) !="")?$_GET['creador']:0;
  $caducidad = (isset($_GET['caducidad']) !="")?$_GET['caducidad']:0;

  // $notificacionObj = new catNotificacionesObj();
  // $emailObj = new EmailFunctions();
  $usuarioObj = new usuariosObj();
  $enviados = 0;
  $ids = explode(",", $idUsuarios);
  // $usuarioObj->UserByID($id);

  // echo "<pre>";
  // print_r($ids);
  // print_r($_GET);
  // echo "</pre>";
  // var_dump($ids);
  // die();
  $arrRes = array();
  foreach ($ids as $idUser) {
    $res = enviarPushACliente($idUser, $titulo, $contenido, $tipo, $creador, 0, $caducidad);    
    $arrRes[] = $res;
    if ($res>0) {
      $enviados++;
    }
  }

  if($enviados>0){
    $return_arr = array("success"=>true, "res"=>$enviados, "arrRes"=>$res);
  }else{
    $return_arr = array("success"=>false, "arrRes"=>$res);
  }
  
  echo $callback . '(' . json_encode($return_arr) . ');';
}

//Metodo general para enviar Envia push 
function enviarPushACliente($idUsuario, $titulo, $mensajeCompleto, $tipo, $creador, $idRpt=0, $caducidad = ''){
  $mensajesObj = new mensajesObj();
  // $regDispObj = new registroDispositivo();
  $resPush="";

  if($idUsuario>0){
    //>>>Logica para mandar un push al cliente<<<
                        
    //consultar el id del dispositivo 
    // $regDispObj->usuarioId = $idUsuario;
    // $colRegDisp = $regDispObj->obtTodosRegDispositivoPorIdUsr();                   
    $regid = "";
    $plataforma = "";
    // if(count($colRegDisp)>0){
    //   foreach ($colRegDisp as $elemRegDisp){
    //     $regid = $elemRegDisp->idRegDispositivo;
    //     $plataforma = $elemRegDisp->plataforma;
    //   }
    // }
    //Si existe id del dispositivo continuar
    // if($regid!=""){
      //echo $idUsuario." - ".$regid.'<br/>';

      //Salvar mensaje
      $titulo = ($titulo!="")?$titulo:"Nuevo mensaje";

      //Setar datos
      $mensajesObj = new mensajesObj();
      $mensajesObj->usuarioId = $idUsuario;
      $mensajesObj->leido = 0;
      $mensajesObj->mostrar = 1;
      $mensajesObj->titulo = $titulo;
      $mensajesObj->contenido = str_replace("?","&quest;", $mensajeCompleto);
      $mensajesObj->tipo = 6;//comunicados
      $mensajesObj->idRegistro = 0;
      $mensajesObj->usuarioIdAlta = $creador;
      $mensajesObj->GuardarMensajesObj();

      //Comprobar si se salvo para despues enviar el mensaje push
      $mensajePush = "";
      if($mensajesObj->idMensaje>0){ 
        if($caducidad != ''){
          $mensajesObj->ActualizarMensaje("fechaCaducidad", conversionFechas($caducidad).' 23:59:59', $mensajesObj->idMensaje);
        }
        return 1;                             
        //Envio a android
        // if($plataforma == 0){
        //     $resPush = $regDispObj->gcmSend($regid, $titulo."| MS:".$mensajesObj->idMensaje, $titulo);
        //     $objPush = json_decode($resPush);              //echo "<pre>";print_r($resPush); echo "</pre>";
        //     if($objPush->success==1){
        //       return 1;
        //       // echo "Android: El mensaje push se envio al dispositivo.<br/>";
        //     }else{
        //       return 0;
        //       // echo "Android: El mensaje push no fue posible enviarlo.<br/>";
        //     }
        // }
        // //Envio a ios
        // if($plataforma == 1){
        //     // echo "reg: ".$regid."<br/>";
        //     // $resPush = $regDispObj->apnsSend($regid, $titulo."| MS:".$mensajesObj->idMensaje);
        //     $resPush = $regDispObj->gcmSend($regid, $titulo."| MS:".$mensajesObj->idMensaje, $titulo, 'ios');
        //     $objPush = json_decode($resPush);              //echo "<pre>";print_r($resPush); echo "</pre>";
        //     if(isset($objPush->success) && $objPush->success==1){
        //       return 1;
        //       // echo "Android: El mensaje push se envio al dispositivo.<br/>";
        //     }else{
        //       return 0;
        //       // echo "Android: El mensaje push no fue posible enviarlo.<br/>";
        //     }
        //     // echo $resPush.'<br/>';
        // }
      }   else{
        return 0;
      }     
    // }
  //>>>fin logica para mandar un push al cliente<<<

  }
}


function revisaArchivo(){
  $callback = $_GET['callback'];
  $url = $_GET['url'];
  $dirname = dirname(__DIR__);
  include  $dirname.'/common/config.php';
  // $fullUrl = $siteURL.$url;
  $fullUrl = $url;
  $return_arr = array("success"=>false);
  $myfile = fopen($fullUrl, "r");// or die("Unable to open file!");
  if ($myfile == false) {
    // echo "No tiene nada";
  } else {
    // echo "Tiene algo";
    $return_arr = array("success"=>true);
  }
  fclose($myfile);

  
  echo $callback . '(' . json_encode($return_arr) . ');';
}


function subirArchivo($name_input, $save_folder){    
    $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
    $dateTime = $dateByZone->format('Y-m-d'); //fecha Actual
    $date=explode("-",$dateTime);
        
    //Obtener la extrension
    $extension = obtenerExtension($_FILES[$name_input]['name']);
    //Cambiar nombre a la imagen 
    $nuevaImg = generarPassword(10, TRUE, TRUE, FALSE, FALSE).".".$extension;
    $destino = $save_folder.$date[0]."/".$date[1]."/".$nuevaImg;

    if(!file_exists($save_folder.$date[0]."/".$date[1])){
      mkdir($save_folder.$date[0]."/".$date[1], 0777, true);
    }

    if(move_uploaded_file($_FILES[$name_input]['tmp_name'], $destino)){
       return $date[0]."/".$date[1]."/".$nuevaImg;
    }
    else{
        return "";
    }
}

// >>>>>>
// >>>>>> Funciones especificas para el sistema
// >>>>>>

// Crear contacto
function crearContacto(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);
  // $motivosPropuestaObj = new motivosPropuestaObj();

  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $catContactosObj = new catContactosObj();


  // Setear datos
  $contactoId = (isset($_GET['contactoId']) && $_GET['contactoId']!="")?$_GET['contactoId']:0;
  $catContactosObj->casoId = (isset($_GET['c_casoid']) && $_GET['c_casoid']!="")?$_GET['c_casoid']:0;
  $catContactosObj->nombre = (isset($_GET['c_nombre']) && $_GET['c_nombre']!="")?$_GET['c_nombre']:"";
  $catContactosObj->telefono = (isset($_GET['c_telefono']) && $_GET['c_telefono']!="")?$_GET['c_telefono']:"";
  $catContactosObj->email = (isset($_GET['c_email']) && $_GET['c_email']!="")?$_GET['c_email']:"";
  $catContactosObj->domicilio = (isset($_GET['c_domicilio']) && $_GET['c_domicilio']!="")?$_GET['c_domicilio']:"";
  $catContactosObj->notas = (isset($_GET['c_notas']) && $_GET['c_notas']!="")?$_GET['c_notas']:"";
  // $clientesObj->direccion = (isset($_GET['pc_dir']) && $_GET['pc_dir']!="")?$_GET['pc_dir']:"";
  // $clientesObj->empresa = (isset($_GET['pc_empresa']) && $_GET['pc_empresa']!="")?$_GET['pc_empresa']:"";
  // $clientesObj->aka = (isset($_GET['pc_aka']) && $_GET['pc_aka']!="")?$_GET['pc_aka']:"";
  // $clientesObj->fechaAct = $tz->fechaHora;
  // $clientesObj->fechaCreacion = $tz->fechaHora;
  if($contactoId == 0) {
    $catContactosObj->GuardarContacto();
  }else{
    $catContactosObj->idContacto = $contactoId;
    $res = $catContactosObj->EditarContacto();
  }

  if($catContactosObj->idContacto){
    $arr = array("success"=>true, 
    "idContacto"=>$catContactosObj->idContacto,
   
  );
  }

  echo $callback . '(' . json_encode($arr) . ');';
}

// Crear documento
function crearDocumento(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);
  // $motivosPropuestaObj = new motivosPropuestaObj();

  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $catDocumentosObj = new catDocumentosObj();


  // Setear datos
  $catDocumentosObj->casoId = (isset($_GET['d_casoid']) && $_GET['d_casoid']!="")?$_GET['d_casoid']:0;
  $catDocumentosObj->nombre = (isset($_GET['d_nombre']) && $_GET['d_nombre']!="")?$_GET['d_nombre']:"";
  $catDocumentosObj->condiciones = (isset($_GET['d_condiciones']) && $_GET['d_condiciones']!="")?$_GET['d_condiciones']:"";
  $catDocumentosObj->fechaRecepcion = (isset($_GET['d_frecepcion']) && $_GET['d_frecepcion']!="")?'\''.conversionFechas($_GET['d_frecepcion']).'\'':"NULL";
  $catDocumentosObj->fechaRetorno = (isset($_GET['d_fretorno']) && $_GET['d_fretorno']!="")?'\''.conversionFechas($_GET['d_fretorno']).'\'':"NULL";
 
  $catDocumentosObj->CrearDocumento();

  if($catDocumentosObj->idDocumento){
    $arr = array("success"=>true, 
    "idDocumento"=>$catDocumentosObj->idDocumento,
   
  );
  }

  echo $callback . '(' . json_encode($arr) . ');';
}

function crearDigital($post){
  $dirname = dirname(__DIR__);
  include  $dirname.'/common/config.php';

  $digitalesObj = new digitalesObj();
  $casosObj = new casosObj();
  $add = 0;
  $res = 0;
  $res1 = 0;
  $res2 = 0;
  $res3 = 0; // LDAH 18/08/2022 IMP para nuevo campo de descripcion de archivo
  $opc = "";
  $folder = obtFolderDigital($post["digi_tipo"]);
  $save_folder = '../upload/'.$folder.'/';
  // $imgComunicado = (isset($_FILES))?subirArchivo('digi_file', $save_folder):"";
  $imgComunicado = $_POST["subido"];

  $digitalesObj->casoId = $post["digi_casoid"];
  $digitalesObj->tipo = $post["digi_tipo"];
  $digitalesObj->nombre = $post["digi_nombre"];
  $digitalesObj->usuarioId = $post["digi_usuarioId"];
  $digitalesObj->descripcion = $post["digi_descrip"]; // LDAH 16/08/2022 IMP para nuevo campo de descripcion de archivo
  
  if($post["digi_id"] == 0){
      $digitalesObj->url = $imgComunicado;
      $digitalesObj->CrearDigital();
      $add = $digitalesObj->idDocumento;
      $opc = "add";
      $digitalesObj->ActCampoDigital("usuarioId", $post["digi_usuarioId"], $add);
      if($digitalesObj->tipo == 2){
        $casosObj->ActCampoCaso("generado", 0, $post["digi_casoid"]);// 18/3/2022 MArcar como que no se ha generado el pdf merge
      }
  }else{
      $digitalAnt = $digitalesObj->DigitalesPorId($post["digi_id"]);
      $folderAnt = obtFolderDigital($digitalAnt->tipo);
      $fileLinkAnt = '../upload/'.$folderAnt.'/'.$digitalAnt->url;
      if($imgComunicado != ""){
        if(file_exists($fileLinkAnt)){
            //unlink($fileLinkAnt); // LDAH 18/08/2022 IMP para perdida del archivo cuando se cambia el nombre 
        }
        $res2 = $digitalesObj->ActCampoDigital("url", $imgComunicado, $post["digi_id"]);
      }
      $digitalesObj->url = $imgComunicado;
      $res1 = $digitalesObj->ActCampoDigital("nombre", $post["digi_nombre"], $post["digi_id"]);
      $res3 = $digitalesObj->ActCampoDigital("descripcion", $post["digi_descrip"], $post["digi_id"]);// LDAH 18/08/2022 IMP para descripcion del archivo
      
      if($res1 > 0 || $res2 > 0 || $res3 > 0){// LDAH 18/08/2022 IMP para descripcion del archivo
          $res = 1;
          if($digitalesObj->tipo == 2){
            $casosObj->ActCampoCaso("generado", 0, $post["digi_casoid"]);// 18/3/2022 MArcar como que no se ha generado el pdf merge
          }
      }
      $opc = "upd";
  }

  $return_arr = array("success"=>true, "post"=>$post, "add"=>$add, "res"=>$res, "opc"=>$opc, "files"=>$_FILES, "imgComunicado"=>$imgComunicado);

  echo json_encode($return_arr);
}

function muestraEditarCliente(){
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $idCliente = (isset($_GET['idCliente']) && $_GET['idCliente']!="")?$_GET['idCliente']:'';

  $clientesObj = new clientesObj();
  $cliente = $clientesObj->ClientePorId($idCliente);
  $arrCliente = json_decode(json_encode($cliente), true);
  // $cliente = object_to_array($cliente);
  // echo "<pre>";print_r($cliente);echo "</pre>";
  $arr = array("success"=>true, "cliente"=>$arrCliente);

  echo $callback . '(' . json_encode($arr) . ');';
}

// Crear cliente
function crearCliente(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);
  // $motivosPropuestaObj = new motivosPropuestaObj();
  header('Content-Type: text/html; charset=utf-8');
  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  //clienteEditId
  $clienteEditId = (isset($_GET['clienteEditId']) && $_GET['clienteEditId']!="")?$_GET['clienteEditId']:0;
  $clientesObj = new clientesObj();

   /*echo "<pre>";
   print_r($_GET);
   echo "</pre>";
   exit();*/

  // Setear datos
  $clientesObj->nombre = (isset($_GET['pc_cliente']) && $_GET['pc_cliente']!="")?$_GET['pc_cliente']:"";
  $clientesObj->telefono = (isset($_GET['pc_tel']) && $_GET['pc_tel']!="")?$_GET['pc_tel']:"";
  $clientesObj->email = (isset($_GET['pc_email']) && $_GET['pc_email']!="")?$_GET['pc_email']:"";
  $clientesObj->direccion = (isset($_GET['pc_dir']) && $_GET['pc_dir']!="")?$_GET['pc_dir']:"";
  $clientesObj->empresa = (isset($_GET['pc_empresa']) && $_GET['pc_empresa']!="")?$_GET['pc_empresa']:"";
  $clientesObj->aka = (isset($_GET['pc_aka']) && $_GET['pc_aka']!="")?$_GET['pc_aka']:"";
  $clientesObj->fechaAct = $tz->fechaHora;
  $clientesObj->fechaCreacion = $tz->fechaHora;



  if($clienteEditId == 0){
    $clientesObj->CrearCliente();
  }else{
    $clientesObj->idCliente = $clienteEditId;
    $clientesObj->EditarCliente();
  }

  if($clientesObj->idCliente){
    $arr = array("success"=>true, 
    "idCliente"=>$clientesObj->idCliente,
    "nombre"=>$clientesObj->nombre,
    "telefono"=>$clientesObj->telefono,
    "email"=>$clientesObj->email,
    "direccion"=>$clientesObj->direccion,
    "empresa"=>$clientesObj->empresa,
    "aka"=>$clientesObj->aka,
  );
  }

  echo $callback . '(' . json_encode($arr) . ');';
  
}

// Crear tipo
function crearTipo(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $catTipoCasosObj = new catTipoCasosObj();

  // Setear datos
  $catTipoCasosObj->nombre = (isset($_GET['pc_tipo']) && $_GET['pc_tipo']!="")?$_GET['pc_tipo']:"";
  $catTipoCasosObj->activo = 1;
  $catTipoCasosObj->CrearTipoCaso();

  if($catTipoCasosObj->idTipo){
    $opcion = array("id"=>$catTipoCasosObj->idTipo, "val"=>trim($catTipoCasosObj->nombre));
    $arr = array("success"=>true, "opcion"=>$opcion);
  }

  echo $callback . '(' . json_encode($arr) . ');';
  // echo "<pre>";
  // print_r($_GET);
  // echo "</pre>";
  // exit();
}

function guardarPago(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $arr = array("success"=>false);

  $save_folder = '../upload/recibos/';
  $reciboPago = (isset($_FILES))?subirArchivo('reciboPago', $save_folder):"";
  $idPago = 0;
  $metodoId = (isset($_POST['metodoId']) && $_POST['metodoId']!="")?$_POST['metodoId']:0;
  $bancoId = (isset($_POST['bancoId']) && $_POST['bancoId']!="")?$_POST['bancoId']:0;
  $cuentaId = (isset($_POST['cuentaId']) && $_POST['cuentaId']!="")?$_POST['cuentaId']:0;
  $montoPago = (isset($_POST['montoPago']) && $_POST['montoPago']!="")?removerCaracteres($_POST['montoPago']):0;
  $fechaPago = (isset($_POST['fechaPago']) && $_POST['fechaPago']!="")?conversionFechas($_POST['fechaPago']):'';
  $comentariosPago = (isset($_POST['comentariosPago']) && $_POST['comentariosPago']!="")?$_POST['comentariosPago']:'';
  $tipoPago = (isset($_POST['tipoPago']) && $_POST['tipoPago']!="")?$_POST['tipoPago']:1;

  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";

  $cuentasObj = new cuentasObj();
  $pagosObj = new pagosObj();
  $cuenta = $cuentasObj->CuentaPorId($cuentaId);
  $saldo = $cuenta->saldo;
  $pagosObj->cuentaId = $cuentaId;
  $pagosObj->metodoId = $metodoId;
  $pagosObj->bancoId = ($bancoId !== "")?$bancoId:0;
  $pagosObj->monto = $montoPago;
  $pagosObj->fechaPago = $fechaPago;
  $pagosObj->comentarios = $comentariosPago;
  $pagosObj->recibo = $reciboPago;
  $pagosObj->tipo = $tipoPago;

  $arrUpd = array();
  $arrIdsReg = array();
  
// echo $casosObj->descripcion." ";
  if($idPago>0){
    $resPlan = $cuentasObj->ActCampoCuenta("planPagos", $planPagos, $idCuenta);
    $resMonto = $cuentasObj->ActCampoCuenta("monto", $monto, $idCuenta);
    $resComentarios = $cuentasObj->ActCampoCuenta("comentarios", $comentarios, $idCuenta);
    
    // $casosObj->idCuenta = $idCuenta;
    // $resp = $cuentasObj->EditarCuenta();
  }else{
    $pagosObj->CrearPago();
    $idPago = $pagosObj->idPago;

    if($idPago > 0){
      // $saldo = $saldo - $montoPago;
      // $cuentasObj->ActCampoCuenta("saldo", $saldo, $cuentaId);
    }
  }

  if($idPago){

    $arr = array("success"=>true, "id"=>$idPago, "saldo"=>"$ ".number_format($saldo,2));
  }

  if($callback != ''){
    echo $callback . '(' . json_encode($arr) . ');';
  }else{
    echo json_encode($arr);
  }
  // echo "<pre>";
  // print_r($_GET);
  // echo "</pre>";
  // exit();
}

/**
 * Guardar casos asociados
 */
function guardarCasosAsociadosCta(){
  $tz = obtDateTimeZone();
  unset($_POST['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_POST);
  $cuentasObj = new cuentasObj();

  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";  

  $idCuenta = (isset($_POST['idCuenta']) && $_POST['idCuenta']!="")?$_POST['idCuenta']:0;
  $casosArr = (isset($_POST['casoAsoc']) && $_POST['casoAsoc']!="")?$_POST['casoAsoc']:array();

  // echo "<pre>";
  // print_r($_POST);//idCuenta
  // echo "</pre>";
  // exit();

  if($idCuenta > 0){
    $casosExsitArrObj = $cuentasObj->obtCasosDecuenta($idCuenta);

    $casosExistArr = array();

    //primero recorremos para saber cuales borrar
    foreach($casosExsitArrObj as $caso){
      //Si no esta en el nuevo arreglo lo borramos
      if(!in_array($caso->casoId, $casosArr))
      {
        $cuentasObj->EliminarCuentaCaso($idCuenta,$caso->casoId);
      }
      $casosExistArr[] = $caso->casoId;
    }

    //ahora recorremos para insertar evitando duplicados
    foreach($casosArr as $casoId){
       if(!in_array($casoId,$casosExistArr)){
        $cuentasObj->AsociarCuentaCaso($idCuenta,$casoId);
       }
    }

    $arr = array("success"=>true);

  }else{
    $arr = array("success"=>false);
  }
  
  if($callback != ''){
    echo $callback . '(' . json_encode($arr) . ');';
  }else{
    echo json_encode($arr);
  }

  
}

function guardarCobros(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $arr = array("success"=>false);

  $cuentasObj = new cuentasObj();
  $idPago = 0;
  $res = 0;
  $cuentaId = (isset($_POST['cuentaId']) && $_POST['cuentaId']!="")?$_POST['cuentaId']:0;
  $cont = (isset($_POST['cont']) && $_POST['cont']!="")?$_POST['cont']:0;
  $planPagos = (isset($_POST['planPagos']) && $_POST['planPagos']!="")?$_POST['planPagos']:0;
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
 
  $cuenta = $cuentasObj->CuentaPorId($cuentaId);
  $arrCobros = array();
  for ($i=1; $i <= $cont ; $i++) { 
    if(isset($_POST['cobro_'.$i]) && isset($_POST['fechacobro_'.$i])){
      $cobro = $_POST['cobro_'.$i];
      $modoCobro = $_POST['modoCobro_'.$i];
      $fechacobro = $_POST['fechacobro_'.$i];
      $label = $_POST["label_".$i];
      $arrCobros[] = array("cobro"=>$cobro, "fechacobro"=>$fechacobro, "modoCobro"=>$modoCobro, "label"=>$label);
    }
  }

  if(count($arrCobros)>0){
    if($planPagos==2){
      $meseNuevos=$cont-1;
      $cuentasObj->EditarCuentaMeses($cuentaId, $meseNuevos);
    }
    $res = $cuentasObj->ActCampoCuenta("cobrosJson", json_encode($arrCobros), $cuentaId);
  }

  if($res){

    $arr = array("success"=>true, "res"=>$res, "post"=>$_POST);
  }

  if($callback != ''){
    echo $callback . '(' . json_encode($arr) . ');';
  }else{
    echo json_encode($arr);
  }
  // echo "<pre>";
  // print_r($_GET);
  // echo "</pre>";
  // exit();
}


function guardarCuenta(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $arr = array("success"=>false);

  $idCuenta = (isset($_POST['idCuenta']) && $_POST['idCuenta']!="")?$_POST['idCuenta']:0;
  $casoId = (isset($_POST['casoId']) && $_POST['casoId']!="")?$_POST['casoId']:0;
  $clienteId = (isset($_POST['clienteId']) && $_POST['clienteId']!="")?$_POST['clienteId']:0;
  $tipoCobro = (isset($_POST['tipoCobro']) && $_POST['tipoCobro']!="")?$_POST['tipoCobro']:0;
  $planPagos = (isset($_POST['planPagos']) && $_POST['planPagos']!="")?$_POST['planPagos']:0;
  $avance = (isset($_POST['avance']) && $_POST['avance']!="")?$_POST['avance']:'';
  $monto = (isset($_POST['monto']) && $_POST['monto']!="")?removerCaracteres($_POST['monto']):0;
  $comentarios = (isset($_POST['comentarios']) && $_POST['comentarios']!="")?$_POST['comentarios']:'';
  $montoAux = (isset($_POST['montoAux']) && $_POST['montoAux']!="")?removerCaracteres($_POST['montoAux']):0;
  $numMeses = (isset($_POST['numMeses']) && $_POST['numMeses']!="")?$_POST['numMeses']:0;
  $diaCobro = (isset($_POST['diaCobro']) && $_POST['diaCobro']!="")?$_POST['diaCobro']:0;
  
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  
  $casosObj = new casosObj();
  $cuentasObj = new cuentasObj();
  $cuentasObj->idCuenta = $idCuenta;
  $cuentasObj->casoId = $casoId;
  $cuentasObj->clienteId = $clienteId;
  $cuentasObj->tipoCobro = $tipoCobro;
  $cuentasObj->planPagos = $planPagos;
  $cuentasObj->monto = $monto;
  $cuentasObj->montoAux = $montoAux;
  $cuentasObj->numMeses = $numMeses;
  $cuentasObj->diaCobro = $diaCobro;
  $cuentasObj->avance = $avance;
  $cuentasObj->comentarios = $comentarios;
  
  $arrUpd = array();
  $arrIdsReg = array();
  $cuenta = $cuentasObj->CuentaPorId($idCuenta);
  
// echo $casosObj->descripcion." ";
  if($idCuenta>0){
    $resPlan = $cuentasObj->ActCampoCuenta("planPagos", $planPagos, $idCuenta);
    $resMonto = $cuentasObj->ActCampoCuenta("monto", $monto, $idCuenta);
    $resComentarios = $cuentasObj->ActCampoCuenta("comentarios", $comentarios, $idCuenta);
    $resAvance = $cuentasObj->ActCampoCuenta("avance", $avance, $idCuenta);

    $resMontoAux = $cuentasObj->ActCampoCuenta("montoAux", $montoAux, $idCuenta);
    $resNumMeses = $cuentasObj->ActCampoCuenta("numMeses", $numMeses, $idCuenta);
    $resDiaCobro = $cuentasObj->ActCampoCuenta("diaCobro", $diaCobro, $idCuenta);
    $resTipoCobro = $cuentasObj->ActCampoCuenta("tipoCobro", $tipoCobro, $idCuenta);
    
  }else{
    $cuentasObj->saldo = $monto;
    $cuentasObj->CrearCuenta();
    $idCuenta = $cuentasObj->idCuenta;

  }

  if($cuentasObj->idCuenta){
    $casosObj->ActCampoCaso("cobro", $tipoCobro, $casoId);
    $arr = array("success"=>true, "id"=>$cuentasObj->idCuenta, "arrIdsReg"=>$arrIdsReg, "arrUpd"=>$arrUpd, "casoId"=>$cuentasObj->casoId);
  }

  if($callback != ''){
    echo $callback . '(' . json_encode($arr) . ');';
  }else{
    echo json_encode($arr);
  }
  // echo "<pre>";
  // print_r($_GET);
  // echo "</pre>";
  // exit();
}

function guardaComentarioExpediente(){
  //   co_usuarioId
  // co_comentarios
  // co_casoid
  // co_idaccion
    $tz = obtDateTimeZone();
    unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
    base64DecodeSubmit(0, $_GET);
  
    $arr = array("success"=>false);
    $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
    $casoId = (isset($_POST['co_casoid']) && $_POST['co_casoid']!="")?$_POST['co_casoid']:0;
    $comentarios = (isset($_POST['co_comentarios_hd']) && $_POST['co_comentarios_hd']!="")?convertirTextoEnriquecido(base64_decode($_POST['co_comentarios_hd'])):"";
    $usuarioId = (isset($_POST['co_usuarioId']) && $_POST['co_usuarioId']!="")?$_POST['co_usuarioId']:"";//El usuario que comenta siempre sera el mismo usuario de la sesion

    $comentariosObj = new comentariosObj();
    $comentariosObj->casoId = $casoId;
    $comentariosObj->comentario = $comentarios;
    $comentariosObj->usuarioId = $usuarioId;

    $comentariosObj->GuardarComentario();
    $idComentario = $comentariosObj->idComentario;

    if($idComentario > 0){
      $casosObj = new casosObj();
      $caso = $casosObj->CasoPorId($casoId);
      //crear notificacion
      $arrUsuariosMsj = array();
      if($caso->responsableId > 0){
        $arrUsuariosMsj[] = $caso->responsableId;
      }
      if($caso->titularId2 > 0){
        $arrUsuariosMsj[] = $caso->titularId2;
      }

      foreach ($arrUsuariosMsj as $usrMsjId) {
        // if($usuarioId != $usrMsjId){//Solo se crea la notificacion si el usuario no es el mismo de la sesion
          $mensajesObj = new mensajesObj();
          $mensajesObj->usuarioId = $usrMsjId;
          $mensajesObj->leido = 0;
          $mensajesObj->mostrar = 1;
          $mensajesObj->titulo = "Nuevo comentario";
          $mensajesObj->contenido = "Nuevo comentario en el expediente ".$caso->numExpediente."";
          $mensajesObj->tipo = 7;//comentario expediente
          $mensajesObj->idRegistro = $idComentario;
          $mensajesObj->usuarioIdAlta = $usuarioId;
          $mensajesObj->GuardarMensajesObj();
        // }
      }
    }
  
    // if($resp){
      $arr = array("success"=>true, "idComentario"=>$idComentario);
      //Actualizar la fecha de ultima actualizacion
      // $casosObj->ActCampoCaso("ultimaActividad", $tz->fechaHora, trim($_POST['co_casoid']));
    // }
  
    if($callback != ''){
      echo $callback . '(' . json_encode($arr) . ');';
    }else{
      echo json_encode($arr);
    }
    // echo "<pre>";
    // print_r($_GET);
    // echo "</pre>";
    // exit();
  }

// Crear tipo
function crearCaso(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $idCaso = (isset($_POST['c_id']) && $_POST['c_id']!="")?$_POST['c_id']:0;
  $casosObj = new casosObj();
  $clientesObj = new clientesObj();
  $usuariosObj = new usuariosObj();
  $emailObj = new EmailFunctions();
  $mensajesObj = new mensajesObj();
  $arrUpd = array();
  $arrIdsReg = array();

  $datosCaso = $casosObj->CasoPorId($idCaso);

  $responsableId = (isset($_POST['c_idtitular']) && $_POST['c_idtitular']!="")?$_POST['c_idtitular']:0;
  $titularId = (isset($_POST['titularId2']) && $_POST['titularId2']!="")?$_POST['titularId2']:0;
  $tipoId = (isset($_POST['c_tipo']) && $_POST['c_tipo']!="")?$_POST['c_tipo']:"";
  $comentariosTitular = (isset($_POST['comentariosTitularHd']) && $_POST['comentariosTitularHd']!="")?convertirTextoEnriquecido(base64_decode($_POST['comentariosTitularHd'])):"";
  $akaAsunto = (isset($_POST['akaAsunto']) && $_POST['akaAsunto']!="")?$_POST['akaAsunto']:"";

  $correonot = (isset($_POST['correonot']) && $_POST['correonot']!="")?$_POST['correonot']:"";
  $otro = (isset($_POST['otro']) && $_POST['otro']!="")?$_POST['otro']:"";
  $correonot = ($correonot == '' && $otro != '')?$otro:$correonot;
  

  $usuarioIdSesion = (isset($_POST['usuarioIdSesion']) && $_POST['usuarioIdSesion']!="")?$_POST['usuarioIdSesion']:0;//Usuario de la sesion
  $usuarioAltaId = (isset($_POST['hd_usuarioAltaId']) && $_POST['hd_usuarioAltaId']!="")?$_POST['hd_usuarioAltaId']:"";//Usuario que dio de alta el expediente, puede no ser el mismo de la sesion

  // Setear datos
  $casosObj->clienteId = (isset($_POST['c_idcliente']) && $_POST['c_idcliente']!="")?$_POST['c_idcliente']:"";
  $casosObj->representado = (isset($_POST['representado']) && $_POST['representado']!="")?$_POST['representado']:"";
  $casosObj->tipoId = $tipoId;
  $casosObj->responsableId = $responsableId;
  $casosObj->autorizadosIds = (isset($_POST['c_idsautorizados']) && $_POST['c_idsautorizados']!="")?",".$_POST['c_idsautorizados'].",":""; //Se le agrega una "," para facilitar la busqueda en sql
  $casosObj->autorizadosJuzgados = (isset($_POST['c_idsautorizadosj']) && $_POST['c_idsautorizadosj']!="")?",".$_POST['c_idsautorizadosj'].",":""; 
  $casosObj->fechaAlta = (isset($_POST['c_falta']) && $_POST['c_falta']!="")?conversionFechas($_POST['c_falta']):"";
  $casosObj->descripcion = (isset($_POST['descripcionHd']) && $_POST['descripcionHd']!="")?convertirTextoEnriquecido(base64_decode($_POST['descripcionHd'])):"";
  $casosObj->internos = (isset($_POST['internosHd']) && $_POST['internosHd']!="")?convertirTextoEnriquecido(base64_decode($_POST['internosHd'])):"";
  
  $estatusId = (isset($_POST['estatusId']) && $_POST['estatusId']!="")?$_POST['estatusId']:1;
  $velocidad = (isset($_POST['velocidad']) && $_POST['velocidad']!="")?$_POST['velocidad']:0;
  $saludExpediente = (isset($_POST['saludExpediente']) && $_POST['saludExpediente']!="")?$_POST['saludExpediente']:0;
  $numExpediente = (isset($_POST['numExpediente']) && $_POST['numExpediente']!="")?$_POST['numExpediente']:generaNumExpediente();
  $contundencia = (isset($_POST['contundencia']) && $_POST['contundencia']!="")?$_POST['contundencia']:0;
  $cobro = (isset($_POST['cobro']) && $_POST['cobro']!="")?$_POST['cobro']:0;
  
  $casosObj->usuarioAltaId = $usuarioAltaId;
  $casosObj->numExpediente = $numExpediente;
  $casosObj->numExpedienteJuzgado = (isset($_POST['numExpedienteJuzgado']) && $_POST['numExpedienteJuzgado']!="")?$_POST['numExpedienteJuzgado']:"";
  $casosObj->saludExpediente = $saludExpediente;
  $casosObj->titularId2 = $titularId;
  $casosObj->velocidad = $velocidad;
  $casosObj->contrario = (isset($_POST['contrario']) && $_POST['contrario']!="")?$_POST['contrario']:"";

  $casosObj->estatusId = $estatusId;
  $casosObj->parteId = (isset($_POST['parteId']) && $_POST['parteId']!="")?$_POST['parteId']:0;
  $casosObj->materiaId = (isset($_POST['materiaId']) && $_POST['materiaId']!="")?$_POST['materiaId']:0;
  $casosObj->juicioId = (isset($_POST['juicioId']) && $_POST['juicioId']!="")?$_POST['juicioId']:0;
  $casosObj->accionId = (isset($_POST['accionId']) && $_POST['accionId']!="")?$_POST['accionId']:0;
  $casosObj->distritoId = (isset($_POST['distritoId']) && $_POST['distritoId']!="")?$_POST['distritoId']:0;
  $casosObj->juzgadoId = (isset($_POST['juzgadoId']) && $_POST['juzgadoId']!="")?$_POST['juzgadoId']:0;
  $casosObj->domicilioEmplazar = (isset($_POST['domicilioEmplazar']) && $_POST['domicilioEmplazar']!="")?$_POST['domicilioEmplazar']:"";
  $casosObj->nombreJuez = (isset($_POST['nombreJuez']) && $_POST ['nombreJuez']!="")?$_POST['nombreJuez']:""; // LDAH IMP 17/08/2022 para nuevos campos de juez
  $casosObj->nombreSecretaria = (isset($_POST['nombreSecretaria']) && $_POST ['nombreSecretaria']!="")?$_POST['nombreSecretaria']:""; // LDAH IMP 18/08/2022 para nuevos campos de juez

  $casosObj->procesalId = (isset($_POST['procesalId']) && $_POST['procesalId']!="")?$_POST['procesalId']:0;
  $casosObj->contundencia = $contundencia;
  $casosObj->cobro = $cobro;
  $casosObj->correonot = $correonot;

// echo $casosObj->descripcion." ";
  if($idCaso>0){
    $expedienteAnt = $casosObj->CasoPorId($idCaso);
    $resEstatus = $casosObj->ActCampoCaso("estatusId", $estatusId, $idCaso);
    $resSaludExpediente = $casosObj->ActCampoCaso("saludExpediente", $saludExpediente, $idCaso);
    $resVelocidad = $casosObj->ActCampoCaso("velocidad", $velocidad, $idCaso);
    $resContundencia = $casosObj->ActCampoCaso("contundencia", $contundencia, $idCaso);
    $resTipo = $casosObj->ActCampoCaso("tipoId", $tipoId, $idCaso);
    $resResponsable = $casosObj->ActCampoCaso("responsableId", $responsableId, $idCaso);//Jair 24/2/2022 
    $resJuez = $casosObj->ActCampoCaso("nombreJuez", $casosObj->nombreJuez, $idCaso);
    $resSecret = $casosObj->ActCampoCaso("nombreSecretaria", $casosObj->nombreSecretaria, $idCaso);
    //Jair 24/2/2022 Actualizar responsable en actividades si se actualizo el responsable
    if($resResponsable > 0){
      $casoAccionesObj = new casoAccionesObj();
      $actividades = $casoAccionesObj->ObtCasoAcciones($idCaso, 0, '1,2,3', '', '', '', '', $expedienteAnt->responsableId);//Jair 3/3/2022 Obtener actividades del caso donde el responsable sea el responsable anterior
      
      foreach ($actividades as $actividad) {
          $resAc = $casoAccionesObj->ActCampoAccion("responsableId", $responsableId, $actividad->idAccion);
      }
    }

    if($resEstatus > 0 || $resSaludExpediente > 0 || $resVelocidad > 0 || $resContundencia > 0 || $resTipo > 0 || $resResponsable > 0 || $resJuez  > 0 || $resSecret > 0){
      $arrMensajeId =  array();
      $arrMensajes = array();

      if($responsableId > 0){
        $arrMensajeId[] = $responsableId;
      }
      if($titularId > 0){
        $arrMensajeId[] = $titularId;
      }

      $arrIdsReg[] = "responsable: ".$responsableId;
      $arrIdsReg[] = "titular: ".$titularId;
      $arrIdsReg[] = "sesion: ".$usuarioIdSesion;

      if($resEstatus >0){
        $estatus = obtEstatusCaso($estatusId);
        $estatusAnt = obtEstatusCaso($datosCaso->estatusId);
        $arrMensajes[] = array(
          "titulo" => "Estatus del expediente ".$numExpediente." modificado",
          "mensaje" => "Se ha modificado el estatus del expediente ".$numExpediente." de ".$estatusAnt." a ".$estatus
        );
        $arrUpd[] = "estatus";
      }

      if($resSaludExpediente >0){
        $salud = obtSaludCaso($saludExpediente);
        $saludAnt = obtSaludCaso($datosCaso->saludExpediente);
        $arrMensajes[] = array(
          "titulo" => "Salud del expediente ".$numExpediente." modificado",
          "mensaje" => "Se ha modificado la salud del expediente ".$numExpediente." de ".$saludAnt." a ".$salud
        );
        $arrUpd[] = "salud";
      }

      if($resVelocidad >0){
        $velocidadTxt = obtVelocidadCaso($velocidad);
        $velocidadAnt = obtVelocidadCaso($datosCaso->velocidad);
        $arrMensajes[] = array(
          "titulo" => "Velocidad del expediente ".$numExpediente." modificada",
          "mensaje" => "Se ha modificado la velocidad del expediente ".$numExpediente." de ".$velocidadAnt." a ".$velocidadTxt
        );
        $arrUpd[] = "velocidad";
      }

      if($resContundencia >0){
        $contundenciaTxt = obtContundenciaCaso($contundencia);
        $contundenciaAnt = obtContundenciaCaso($datosCaso->contundencia);
        $arrMensajes[] = array(
          "titulo" => "Contundencia del expediente ".$numExpediente." modificada",
          "mensaje" => "Se ha modificado la contundencia del expediente ".$numExpediente." de ".$contundenciaAnt." a ".$contundenciaTxt
        );
        $arrUpd[] = "contundencia";
      }

      if($resTipo >0){
        $catTipoCasosObj = new catTipoCasosObj();
        $tipo = $catTipoCasosObj->TipoCasoPorId($tipoId);
        $arrMensajes[] = array(
          "titulo" => "Tipo de cliente del expediente ".$numExpediente." modificado",
          "mensaje" => "Se ha modificado el tipo de cliente del expediente ".$numExpediente." a ".$tipo->nombre
        );
        $arrUpd[] = "tipo";
      }

      if($resResponsable >0){
        $responsable = $usuariosObj->UserByID($responsableId);
        $responsableAnt = $usuariosObj->UserByID($datosCaso->responsableId);
        $arrMensajes[] = array(
          "titulo" => "Cambio responsable al expediente ".$numExpediente."",
          "mensaje" => "Se ha asignado como nuevo responsable al expediente ".$numExpediente." de ".$responsableAnt->nombre." a ".$responsable->nombre
        );
        $arrUpd[] = "tipo";

        //25/3/2022 Incluir al coordinador en esta notificacion
        $titular = $usuariosObj->UserByID($titularId);
        if($titular->coordinadorId > 0){
          $arrMensajeId[] = $titular->coordinadorId;
        }
      }

      //Jair 3/3/2022 Cambie el codigo por funcion
      enviarNotificacion($arrMensajes, $arrMensajeId, 1, $idCaso, $usuarioIdSesion);

      //Jair 3/3/2022 Enviar notificacion al ex responsable
      if($resResponsable >0){
        $arrMensajes = array();
        $arrMensajes[] = array(
          "titulo" => "Fuiste removido como responsable ",
          "mensaje" => "Has sido removido como respomsable del expediente ".$numExpediente.""
        );
        $arrMensajeId =  array();
        $arrMensajeId[] = $expedienteAnt->responsableId;

        enviarNotificacion($arrMensajes, $arrMensajeId, -1, $idCaso, $usuarioIdSesion);
      }

    }

    $casosObj->idCaso = $idCaso;
    $resp = $casosObj->EditarCaso();
  }else{
    $casosObj->CrearCaso();
    $idCaso = $casosObj->idCaso;
    // Obtener el nombre del cliente
    $datosCliente = $clientesObj->ClientePorId(trim($casosObj->clienteId));

    //verificar si ya existe para no duplicar usuarios y si existe solo mandar correo con datos de acceso
    $datosUsuario = $usuariosObj->UserByEmail($datosCliente->email);
    // if($datosUsuario->idUsuario>0){
    //   //Solo mandar correo con datos de acceso
    //   // $emailObj->EnviarDatosDeAcceso("carlos.ramirez@framelova.com", $datosUsuario->nombre, $datosUsuario->password);
    //   $emailObj->EnviarDatosDeAcceso($datosUsuario->email, $datosUsuario->nombre, $datosUsuario->password);
    // }else{
    //   $password = generarPassword(5, true, true, false, false);
    //   $usuariosObj->idRol = 3;
    //   $usuariosObj->nombre = $datosCliente->nombre;
    //   $usuariosObj->email = $datosCliente->email;
    //   $usuariosObj->password = $password;
    //   $usuariosObj->activo = 1;
    //   $usuariosObj->GuardarUsuario();

    //   if($usuariosObj->idUsuario>0){
    //     //Agregar id del cliente en la tabla de usuarios
    //     $usuariosObj->ActualizarUsuario("clienteId", trim($_GET['c_idcliente']), $usuariosObj->idUsuario);

    //     //Mandar correo
    //     // $emailObj->EnviarDatosDeAcceso("carlos.ramirez@framelova.com", $datosCliente->nombre, $password);
    //     $emailObj->EnviarDatosDeAcceso($datosCliente->email, $datosCliente->nombre, $password);
    //   }
    // }

    if($idCaso){
      //notificaciones
      $arrMensajeId =  array();
      $arrMensajes = array();
      $titular = $usuariosObj->UserByID($titularId);
      $coordinador = $usuariosObj->UserByID($titular->coordinadorId);

      if($responsableId > 0){
        $arrMensajeId[] = $responsableId;
      }
      if($titularId > 0){
        // $arrMensajeId[] = $titularId;
      }
      //Jair 3/3/2022 Enviar notificacion de nuevo expediente a coordinador
      if($coordinador->idUsuario > 0){
        $arrMensajeId[] = $coordinador->idUsuario;
      }

      $arrIdsReg[] = "responsable: ".$responsableId;
      $arrIdsReg[] = "titular: ".$titularId;
      $arrIdsReg[] = "sesion: ".$usuarioIdSesion;

      if($responsableId >0){
        $arrMensajes[] = array(
          "titulo" => "Se ha creado un nuevo expediente: ",
          "mensaje" => "Nuevo expediente con el id: ".$numExpediente.""
        );
      }

      //Jair 3/3/2022 Cambie el codigo por funcion
      enviarNotificacion($arrMensajes, $arrMensajeId, 1, $idCaso, $usuarioIdSesion);

      //fin notificaciones
    }
  }

  if($casosObj->idCaso){
    if($comentariosTitular != ''){
      $resComentariosTitular = $casosObj->ActCampoCaso("comentariosTitular", $comentariosTitular, $idCaso);
      if($resComentariosTitular > 0){
        $arrUpd[] = "comentarios titular";
      }
    }

    if($akaAsunto != ''){
      $resAkaAsunto = $casosObj->ActCampoCaso("akaAsunto", $akaAsunto, $idCaso);
    }

    

    $arr = array("success"=>true, "id"=>$casosObj->idCaso, "arrIdsReg"=>$arrIdsReg, "arrUpd"=>$arrUpd);
  }

  if($callback != ''){
    echo $callback . '(' . json_encode($arr) . ');';
  }else{
    echo json_encode($arr);
  }
  // echo "<pre>";
  // print_r($_GET);
  // echo "</pre>";
  // exit();
}

// Crear y editar accion (actividades)
function creaEditaAccion(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $idaccion = (isset($_POST['pa_idaccion']) && $_POST['pa_idaccion']!="")?$_POST['pa_idaccion']:0;
  $casoAccionesObj = new casoAccionesObj();
  $casosObj = new casosObj();

  $usuarioIdSesion = (isset($_POST['pa_usuarioIdSesion']) && $_POST['pa_usuarioIdSesion']!="")?$_POST['pa_usuarioIdSesion']:0;//Usuario sesion para validar notificacion
  $usuarioId = (isset($_POST['pa_usuarioId']) && $_POST['pa_usuarioId']!="")?$_POST['pa_usuarioId']:"";//Usuario que creo la actividad, puede no ser el de la sesion

  $casoId = (isset($_POST['pa_casoid']) && $_POST['pa_casoid']!="")?$_POST['pa_casoid']:0;
  $estatusId = (isset($_POST['estatusId']) && $_POST['estatusId']!="")?$_POST['estatusId']:0;
  $responsableId = (isset($_POST['responsableId']) && $_POST['responsableId']!="")?$_POST['responsableId']:0;

  // Setear datos
  $casoAccionesObj->casoId = $casoId;
  $casoAccionesObj->nombre = (isset($_POST['pa_accion']) && $_POST['pa_accion']!="")?$_POST['pa_accion']:"";
  $casoAccionesObj->tipo = (isset($_POST['tipoactividad']) && $_POST['tipoactividad']!="")?$_POST['tipoactividad']:"";
  $casoAccionesObj->importancia = (isset($_POST['importanciaactividad']) && $_POST['importanciaactividad']!="")?$_POST['importanciaactividad']:"";
  // echo "aqui";die();

  $casoAccionesObj->comentarios = (isset($_POST['pa_comentario_hd']) && $_POST['pa_comentario_hd']!="")?convertirTextoEnriquecido(base64_decode($_POST['pa_comentario_hd'])):"";
  $casoAccionesObj->internos = (isset($_POST['pa_internos_hd']) && $_POST['pa_internos_hd']!="")?convertirTextoEnriquecido(base64_decode($_POST['pa_internos_hd'])):"";
  $casoAccionesObj->reporte = (isset($_POST['pa_reporte_hd']) && $_POST['pa_reporte_hd']!="")?convertirTextoEnriquecido(base64_decode($_POST['pa_reporte_hd'])):"";

  $casoAccionesObj->fechaAlta = (isset($_POST['pa_fechaaccion']) && $_POST['pa_fechaaccion']!="")?conversionFechas($_POST['pa_fechaaccion']):"";
  $casoAccionesObj->padreAccionId = 0;
  $casoAccionesObj->usuarioId = $usuarioId;

  $casoAccionesObj->estatusId = $estatusId;
  $casoAccionesObj->fechaCompromiso = (isset($_POST['fechaCompromiso']) && $_POST['fechaCompromiso']!="")?'\''.conversionFechaF3($_POST['fechaCompromiso']).'\'':"NULL";
  $casoAccionesObj->fechaRealizado = (isset($_POST['fechaRealizado']) && $_POST['fechaRealizado']!="")?'\''.conversionFechaF3($_POST['fechaRealizado']).'\'':"NULL";

  $casoAccionesObj->responsableId = $responsableId;
  $casoAccionesObj->avanzo = (isset($_POST['avanzo']) && $_POST['avanzo']!="")?$_POST['avanzo']:0;

  $casosObj = new casosObj();
  $caso = $casosObj->CasoPorId($casoId);

  $resp = 0;
  $opc = "";
  if($idaccion>0){
    $resEstatus = $casoAccionesObj->ActCampoAccion("estatusId", $estatusId, $idaccion);
    $resResponsable = $casoAccionesObj->ActCampoAccion("responsableId", $responsableId, $idaccion);
    $casoAccionesObj->idAccion = $idaccion;
    $resp = $casoAccionesObj->EditarCasoAccion();
    $opc = "upd";

    //notificacion
    if($resEstatus > 0 || $resResponsable > 0){
      $arrUsuariosMsj = array();
      $arrMensajes = array();

      if($caso->responsableId > 0){
        $arrUsuariosMsj[] = $caso->responsableId;
      }
      if($caso->titularId2 > 0){
        $arrUsuariosMsj[] = $caso->titularId2;
      }

      if($resResponsable >0){
        $arrMensajes[] = array(
          "titulo" => "Cambio responsable a la actividad ".$idaccion."",
          "mensaje" => "Se ha asignado nuevo respomsable a la actividad ".$idaccion."",
          "tipo"=>"responsable"
        );
        $arrUpd[] = "responsable";
      }

      if($resEstatus >0){
        $arrMensajes[] = array(
          "titulo" => "Cambio de estatus en actividad",
          "mensaje" => "La actividad \"".$casoAccionesObj->nombre."\"  en el expediente interno \"".$caso->numExpediente."\", ha cambiado su estatus",
          "tipo"=>"estatus"
        );
        $arrUpd[] = "estatus";
      }

      foreach ($arrMensajes as $mensajeItem) {
        foreach ($arrUsuariosMsj as $usrMsjId) {
          // if($usrMsjId != $usuarioIdSesion){//Se crea la notificacion solo para los usuarios distintos al usuario de la sesion
            $mensajesObj = new mensajesObj();
            $mensajesObj->usuarioId = $usrMsjId;
            $mensajesObj->leido = 0;
            $mensajesObj->mostrar = 1;
            $mensajesObj->titulo = $mensajeItem["titulo"];
            $mensajesObj->contenido = $mensajeItem["mensaje"];
            $mensajesObj->tipo = 2;//actividad
            $mensajesObj->idRegistro = $idaccion;
            $mensajesObj->usuarioIdAlta = $usuarioIdSesion;
            $mensajesObj->GuardarMensajesObj();
            $idMensaje = $mensajesObj->idMensaje;

            if($mensajeItem["tipo"] == "estatus"){
              $mensajesObj->ActualizarMensaje("campo", "estatusId", $idMensaje);
              $mensajesObj->ActualizarMensaje("cambioId", $estatusId, $idMensaje);
            }
          // }
        }
      }
    }
  }else{
    $casoAccionesObj->CrearCasoAccion();
    $idaccion = $casoAccionesObj->idAccion;

    //crear notificacion
    $arrUsuariosMsj = array();
    $arrMensajes = array();

    if($caso->responsableId > 0){
      $arrUsuariosMsj[] = $caso->responsableId;
    }
    if($caso->titularId2 > 0){
      $arrUsuariosMsj[] = $caso->titularId2;
    }

    if($responsableId >0){
      $arrMensajes[] = array(
        "titulo" => "Nueva actividad ",
        "mensaje" => "Nueva actividad \"".$casoAccionesObj->nombre."\"  en el expediente interno \"".$caso->numExpediente."\""
      );
    }

    foreach ($arrMensajes as $mensajeItem) {
      foreach ($arrUsuariosMsj as $usrMsjId) {
        // if($usrMsjId != $usuarioIdSesion){//Se crea la notificacion solo para los usuarios distintos al usuario de la sesion
          $mensajesObj = new mensajesObj();
          $mensajesObj->usuarioId = $usrMsjId;
          $mensajesObj->leido = 0;
          $mensajesObj->mostrar = 1;
          $mensajesObj->titulo = $mensajeItem["titulo"];
          $mensajesObj->contenido = $mensajeItem["mensaje"];
          $mensajesObj->tipo = 2;//actividad
          $mensajesObj->idRegistro = $idaccion;
          $mensajesObj->usuarioIdAlta = $usuarioIdSesion;
          $mensajesObj->GuardarMensajesObj();
          $idMensaje = $mensajesObj->idMensaje;

          $mensajesObj->ActualizarMensaje("campo", "estatusId", $idMensaje);
          $mensajesObj->ActualizarMensaje("cambioId", $estatusId, $idMensaje);
        // }
      }
    }
    $opc = "crear";
  }

  // if($resp){
    $arr = array("success"=>true, "idaccion"=>$idaccion, "opc"=>$opc, "resp"=>$resp);
    //Actualizar la fecha de ultima actualizacion
    $casosObj->ActCampoCaso("ultimaActividad", $tz->fechaHora, trim($_POST['pa_casoid']));
  // }

  if($callback != ''){
    echo $callback . '(' . json_encode($arr) . ');';
  }else{
    echo json_encode($arr);
  }
  // echo "<pre>";
  // print_r($_GET);
  // echo "</pre>";
  // exit();
}

//Comentarios de actividades (usan la misma tabla de actividades)
function creaEditaAccion2(){
//   co_usuarioId
// co_comentarios
// co_casoid
// co_idaccion
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $idaccion = (isset($_POST['pa_idaccion']) && $_POST['pa_idaccion']!="")?$_POST['pa_idaccion']:0;
  $casoAccionesObj = new casoAccionesObj();
  $casosObj = new casosObj();

  $usuarioId = (isset($_POST['co_usuarioId']) && $_POST['co_usuarioId']!="")?$_POST['co_usuarioId']:"";//El usuario que comenta siempre sera el mismo usuario de la sesion

  $casoId = (isset($_POST['co_casoid']) && $_POST['co_casoid']!="")?$_POST['co_casoid']:0;
  $padreAccionId = (isset($_POST['co_idaccion']) && $_POST['co_idaccion']!="")?$_POST['co_idaccion']:0;
  $estatusId = (isset($_POST['co_estatusId']) && $_POST['co_estatusId']!="")?$_POST['co_estatusId']:0;
  $comentarios = (isset($_POST['co_comentarios_hd']) && $_POST['co_comentarios_hd']!="")?convertirTextoEnriquecido(base64_decode($_POST['co_comentarios_hd'])):"";
  // Setear datos
  $casoAccionesObj->casoId = $casoId;
  $casoAccionesObj->nombre = "";
  $casoAccionesObj->tipo = 0;
  $casoAccionesObj->importancia = 0;
  // echo "aqui";die();

  $casoAccionesObj->comentarios = $comentarios;//RECUERDA REGRESAR EL BASE 64 DESPUES
  $casoAccionesObj->internos = "";
  $casoAccionesObj->reporte = (isset($_POST['co_reporte_hd']) && $_POST['co_reporte_hd']!="")?convertirTextoEnriquecido(base64_decode($_POST['co_reporte_hd'])):"";

  $casoAccionesObj->fechaAlta = (isset($_POST['co_fechaaccion']) && $_POST['co_fechaaccion']!="")?conversionFechas($_POST['co_fechaaccion']):"";
  $casoAccionesObj->padreAccionId = $padreAccionId;
  $casoAccionesObj->usuarioId = $usuarioId;

  $casoAccionesObj->estatusId = $estatusId;
  $casoAccionesObj->fechaCompromiso = (isset($_POST['co_fechaCompromiso']) && $_POST['co_fechaCompromiso']!="")?'\''.conversionFechas($_POST['fechaCompromiso']).'\'':"NULL";
  $casoAccionesObj->fechaRealizado = (isset($_POST['co_fechaRealizado']) && $_POST['co_fechaRealizado']!="")?'\''.conversionFechas($_POST['fechaRealizado']).'\'':"NULL";

  $casoAccionesObj->responsableId = 0;
  $casoAccionesObj->avanzo = 0;

  $casosObj = new casosObj();
  $caso = $casosObj->CasoPorId($casoId);
  $actividad = $casoAccionesObj->CasoAccionesPorId($padreAccionId);

  $resp = 0;
  $resEst = 0;
  if($idaccion>0){
    $casoAccionesObj->idAccion = $idaccion;
    $resp = $casoAccionesObj->EditarCasoAccion();
  }else{
    $casoAccionesObj->CrearCasoAccion();
    $idaccion = $casoAccionesObj->idAccion;
    if($idaccion > 0){
      $resp = 1;
      if($estatusId > 0){//Si el estatus es mayor a 0, se actualiza el estatus de la actividad
        $resEst = $casoAccionesObj->ActCampoAccion("estatusId", $estatusId, $padreAccionId);
        if($estatusId == 4){
          $resRep = $casoAccionesObj->ActCampoAccion("reporte", $comentarios, $padreAccionId);
        }
      }
    }

    //crear notificacion
    $arrUsuariosMsj = array();
    if($caso->responsableId > 0){
      $arrUsuariosMsj[] = $caso->responsableId;
    }
    if($caso->titularId2 > 0){
      $arrUsuariosMsj[] = $caso->titularId2;
    }

    foreach ($arrUsuariosMsj as $usrMsjId) {
      // if($usuarioId != $usrMsjId){//Solo se crea la notificacion si el usuario no es el mismo de la sesion
        $mensajesObj = new mensajesObj();
        $mensajesObj->usuarioId = $usrMsjId;
        $mensajesObj->leido = 0;
        $mensajesObj->mostrar = 1;
        $mensajesObj->titulo = "Nuevo comentario";
        $mensajesObj->contenido = "Nuevo comentario en la actividad \"".$actividad->nombre."\"  del expediente \"".$caso->numExpediente."\"";
        $mensajesObj->tipo = 3;//comentario
        $mensajesObj->idRegistro = $idaccion;
        $mensajesObj->usuarioIdAlta = $usuarioId;
        $mensajesObj->GuardarMensajesObj();
      // }
    }

    if($resEst > 0){
      //notificacion cambio de estatus
      foreach ($arrUsuariosMsj as $usrMsjId) {
        // if($usrMsjId != $usuarioIdSesion){//Se crea la notificacion solo para los usuarios distintos al usuario de la sesion
          $mensajesObj = new mensajesObj();
          $mensajesObj->usuarioId = $usrMsjId;
          $mensajesObj->leido = 0;
          $mensajesObj->mostrar = 1;
          $mensajesObj->titulo = "Cambio de estatus en actividad";
          $mensajesObj->contenido = "La actividad \"".$actividad->nombre."\"  en el expediente interno \"".$caso->numExpediente."\", ha cambiado su estatus";
          $mensajesObj->tipo = 2;//actividad
          $mensajesObj->idRegistro = $padreAccionId;
          $mensajesObj->usuarioIdAlta = $usuarioId;
          $mensajesObj->GuardarMensajesObj();
          $idMensaje = $mensajesObj->idMensaje;

          $mensajesObj->ActualizarMensaje("campo", "estatusId", $idMensaje);
          $mensajesObj->ActualizarMensaje("cambioId", $estatusId, $idMensaje);
        // }
      }
    
    }
  }

  // if($resp){
    $arr = array("success"=>true, "idaccion"=>$idaccion);
    //Actualizar la fecha de ultima actualizacion
    // $casosObj->ActCampoCaso("ultimaActividad", $tz->fechaHora, trim($_POST['co_casoid']));
  // }

  if($callback != ''){
    echo $callback . '(' . json_encode($arr) . ');';
  }else{
    echo json_encode($arr);
  }
  // echo "<pre>";
  // print_r($_GET);
  // echo "</pre>";
  // exit();
}

// Crear y editar tarea
function creaEditaTarea(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $idtarea = (isset($_POST['pa_idtarea']) && $_POST['pa_idtarea']!="")?$_POST['pa_idtarea']:0;
  $tareasObj = new tareasObj();
  

  $usuarioIdSesion = (isset($_POST['pa_usuarioIdSesion']) && $_POST['pa_usuarioIdSesion']!="")?$_POST['pa_usuarioIdSesion']:0;//Usuario sesion para validar notificacion
  $usuarioId = (isset($_POST['pa_usuarioId']) && $_POST['pa_usuarioId']!="")?$_POST['pa_usuarioId']:"";//Usuario que creo la actividad, puede no ser el de la sesion
  $responsableId = (isset($_POST['responsableId']) && $_POST['responsableId']!="")?$_POST['responsableId']:0;
  
  $estatusId = (isset($_POST['estatusId']) && $_POST['estatusId']!="")?$_POST['estatusId']:0;
  // Setear datos
  
  $tareasObj->nombre = (isset($_POST['pa_tarea']) && $_POST['pa_tarea']!="")?$_POST['pa_tarea']:"";
  $tareasObj->tipo = (isset($_POST['tipotarea']) && $_POST['tipotarea']!="")?$_POST['tipotarea']:"";
  $tareasObj->importancia = (isset($_POST['importanciatarea']) && $_POST['importanciatarea']!="")?$_POST['importanciatarea']:"";
  // echo "aqui";die();

  $tareasObj->comentarios = (isset($_POST['pa_comentario_hd']) && $_POST['pa_comentario_hd']!="")?convertirTextoEnriquecido(base64_decode($_POST['pa_comentario_hd'])):"";
  $tareasObj->internos = (isset($_POST['pa_internos_hd']) && $_POST['pa_internos_hd']!="")?convertirTextoEnriquecido(base64_decode($_POST['pa_internos_hd'])):"";
  $tareasObj->reporte = (isset($_POST['pa_reporte_hd']) && $_POST['pa_reporte_hd']!="")?convertirTextoEnriquecido(base64_decode($_POST['pa_reporte_hd'])):"";

  $tareasObj->fechaAlta = (isset($_POST['pa_fechatarea']) && $_POST['pa_fechatarea']!="")?conversionFechas($_POST['pa_fechatarea']):"";
  $tareasObj->padreTareaId = 0;
  $tareasObj->usuarioId = $usuarioId;

  $tareasObj->estatusId = $estatusId;
  $tareasObj->fechaCompromiso = (isset($_POST['fechaCompromiso']) && $_POST['fechaCompromiso']!="")?'\''.conversionFechas($_POST['fechaCompromiso']).'\'':"NULL";
  $tareasObj->fechaRealizado = (isset($_POST['fechaRealizado']) && $_POST['fechaRealizado']!="")?'\''.conversionFechas($_POST['fechaRealizado']).'\'':"NULL";

  $tareasObj->responsableId = $responsableId;

  

  $resp = 0;
  $opc = "";
  if($idtarea>0){
    $resEstatus = $tareasObj->ActCampoTarea("estatusId", $estatusId, $idtarea);
    $resResponsable = $tareasObj->ActCampoTarea("responsableId", $responsableId, $idtarea);
    $tareasObj->idTarea = $idtarea;
    $resp = $tareasObj->EditarTarea();
    $opc = "upd";

    //notificacion
    if($resEstatus > 0 || $resResponsable > 0){
      $arrUsuariosMsj = array();
      $arrMensajes = array();

      if($tareasObj->responsableId > 0){
        $arrUsuariosMsj[] = $tareasObj->responsableId;
      }
      if($usuarioId > 0 && $usuarioId != $tareasObj->responsableId){
        $arrUsuariosMsj[] = $usuarioId;
      }

      if($resResponsable >0){
        $arrMensajes[] = array(
          "titulo" => "Cambio responsable a la tarea ".$idtarea."",
          "mensaje" => "Se ha asignado nuevo respomsable a la tarea ".$idtarea."",
          "tipo"=>"responsable"
        );
        $arrUpd[] = "responsable";
      }

      if($resEstatus >0){
        $arrMensajes[] = array(
          "titulo" => "Cambio de estatus en tarea",
          "mensaje" => "La tarea \"".$tareasObj->nombre."\", ha cambiado su estatus",
          "tipo"=>"estatus"
        );
        $arrUpd[] = "estatus";
      }
      if($_POST["tipotarea"] < 100){//Si el tipo no es asignacion
        foreach ($arrMensajes as $mensajeItem) {
          foreach ($arrUsuariosMsj as $usrMsjId) {
            // if($usrMsjId != $usuarioIdSesion){//Se crea la notificacion solo para los usuarios distintos al usuario de la sesion
              $mensajesObj = new mensajesObj();
              $mensajesObj->usuarioId = $usrMsjId;
              $mensajesObj->leido = 0;
              $mensajesObj->mostrar = 1;
              $mensajesObj->titulo = $mensajeItem["titulo"];
              $mensajesObj->contenido = $mensajeItem["mensaje"];
              $mensajesObj->tipo = 4;//tarea
              $mensajesObj->idRegistro = $idtarea;
              $mensajesObj->usuarioIdAlta = $usuarioIdSesion;
              $mensajesObj->GuardarMensajesObj();
              $idMensaje = $mensajesObj->idMensaje;
    
              if($mensajeItem["tipo"] == "estatus"){
                $mensajesObj->ActualizarMensaje("campo", "estatusId", $idMensaje);
                $mensajesObj->ActualizarMensaje("cambioId", $estatusId, $idMensaje);
              }
            // }
          }
        }
      }
    }
  }else{
    $tareasObj->CrearTarea();
    $idtarea = $tareasObj->idTarea;

    //crear notificacion
    $arrUsuariosMsj = array();
    if($tareasObj->responsableId > 0){
      $arrUsuariosMsj[] = $tareasObj->responsableId;
    }
    if($usuarioId > 0 && $usuarioId != $tareasObj->responsableId){
      $arrUsuariosMsj[] = $usuarioId;
    }

    if($_POST["tipotarea"] < 100){//Si el tipo no es asignacion
      foreach ($arrUsuariosMsj as $usrMsjId) {
        // if($usrMsjId != $usuarioIdSesion){//Se crea la notificacion solo para los usuarios distintos al usuario de la sesion
          $mensajesObj = new mensajesObj();
          $mensajesObj->usuarioId = $usrMsjId;
          $mensajesObj->leido = 0;
          $mensajesObj->mostrar = 1;
          $mensajesObj->titulo = "Nueva tarea";
          $mensajesObj->contenido = "Nueva tarea \"".$tareasObj->nombre."\"";
          $mensajesObj->tipo = 4;//tarea
          $mensajesObj->idRegistro = $idtarea;
          $mensajesObj->usuarioIdAlta = $usuarioIdSesion;
          $mensajesObj->GuardarMensajesObj();
        // }
      }
    }
    $opc = "crear";
  }

  // if($resp){
    $arr = array("success"=>true, "idtarea"=>$idtarea, "opc"=>$opc, "resp"=>$resp);
    
  // }

  if($callback != ''){
    echo $callback . '(' . json_encode($arr) . ');';
  }else{
    echo json_encode($arr);
  }

}

function creaComentarioTarea(){
    $tz = obtDateTimeZone();
    unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
    base64DecodeSubmit(0, $_GET);
  
    $arr = array("success"=>false);
    $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
    $idtarea = (isset($_POST['pa_idtarea']) && $_POST['pa_idtarea']!="")?$_POST['pa_idtarea']:0;
    $tareasObj = new tareasObj();
    
  
    $usuarioId = (isset($_POST['co_usuarioId']) && $_POST['co_usuarioId']!="")?$_POST['co_usuarioId']:"";//El usuario que comenta siempre sera el mismo usuario de la sesion
  
   
    $padreTareaId = (isset($_POST['co_idtarea']) && $_POST['co_idtarea']!="")?$_POST['co_idtarea']:0;
    $estatusId = (isset($_POST['co_estatusId']) && $_POST['co_estatusId']!="")?$_POST['co_estatusId']:0;
    $comentarios = (isset($_POST['co_comentarios_hd']) && $_POST['co_comentarios_hd']!="")?convertirTextoEnriquecido(base64_decode($_POST['co_comentarios_hd'])):"";
    // Setear datos
    
    $tareasObj->nombre = "";
    $tareasObj->tipo = 0;
    $tareasObj->importancia = 0;
    // echo "aqui";die();
  
    $tareasObj->comentarios = $comentarios;//RECUERDA REGRESAR EL BASE 64 DESPUES
    $tareasObj->internos = "";
    $tareasObj->reporte = (isset($_POST['co_reporte_hd']) && $_POST['co_reporte_hd']!="")?convertirTextoEnriquecido(base64_decode($_POST['co_reporte_hd'])):"";
  
    $tareasObj->fechaAlta = (isset($_POST['co_fechatarea']) && $_POST['co_fechatarea']!="")?conversionFechas($_POST['co_fechatarea']):"";
    $tareasObj->padreTareaId = $padreTareaId;
    $tareasObj->usuarioId = $usuarioId;
  
    $tareasObj->estatusId = $estatusId;
    $tareasObj->fechaCompromiso = (isset($_POST['co_fechaCompromiso']) && $_POST['co_fechaCompromiso']!="")?'\''.conversionFechas($_POST['fechaCompromiso']).'\'':"NULL";
    $tareasObj->fechaRealizado = (isset($_POST['co_fechaRealizado']) && $_POST['co_fechaRealizado']!="")?'\''.conversionFechas($_POST['fechaRealizado']).'\'':"NULL";
  
    $tareasObj->responsableId = 0;
  

    $tarea = $tareasObj->TareasPorId($padreTareaId);
  
    $resp = 0;
    $resEst = 0;
    if($idtarea>0){
      $tareasObj->idTarea = $idtarea;
      $resp = $tareasObj->EditarTarea();
    }else{
      $tareasObj->CrearTarea();
      $idtarea = $tareasObj->idTarea;
      if($idtarea > 0){
        $resp = 1;
        if($estatusId > 0){//Si el estatus es mayor a 0, se actualiza el estatus de la actividad
          $resEst = $tareasObj->ActCampoTarea("estatusId", $estatusId, $padreTareaId);
          if($estatusId == 4){
            $resRep = $tareasObj->ActCampoTarea("reporte", $comentarios, $padreTareaId);
          }
        }
      }
  
      //crear notificacion
      $arrUsuariosMsj = array();
      if($tarea->responsableId > 0){
        $arrUsuariosMsj[] = $tarea->responsableId;
      }
      if($usuarioId > 0 && $usuarioId != $tarea->responsableId){
        $arrUsuariosMsj[] = $usuarioId;
      }
  
      foreach ($arrUsuariosMsj as $usrMsjId) {
        // if($usuarioId != $usrMsjId){//Solo se crea la notificacion si el usuario no es el mismo de la sesion
          $mensajesObj = new mensajesObj();
          $mensajesObj->usuarioId = $usrMsjId;
          $mensajesObj->leido = 0;
          $mensajesObj->mostrar = 1;
          $mensajesObj->titulo = "Nuevo comentario de Tarea";
          $mensajesObj->contenido = "Nuevo comentario en la tarea \"".$tarea->nombre."\"";
          $mensajesObj->tipo = 5;//comentario tarea
          $mensajesObj->idRegistro = $idtarea;
          $mensajesObj->usuarioIdAlta = $usuarioId;
          $mensajesObj->GuardarMensajesObj();
        // }
      }
  
      if($resEst > 0){
        //notificacion cambio de estatus
        foreach ($arrUsuariosMsj as $usrMsjId) {
          // if($usrMsjId != $usuarioIdSesion){//Se crea la notificacion solo para los usuarios distintos al usuario de la sesion
            $mensajesObj = new mensajesObj();
            $mensajesObj->usuarioId = $usrMsjId;
            $mensajesObj->leido = 0;
            $mensajesObj->mostrar = 1;
            $mensajesObj->titulo = "Cambio de estatus en tarea";
            $mensajesObj->contenido = "La tarea \"".$tarea->nombre."\", ha cambiado su estatus";
            $mensajesObj->tipo = 4;//tarea
            $mensajesObj->idRegistro = $padreTareaId;
            $mensajesObj->usuarioIdAlta = $usuarioId;
            $mensajesObj->GuardarMensajesObj();
          // }
        }
      
      }
    }
  
    // if($resp){
      $arr = array("success"=>true, "idtarea"=>$idtarea);
    // }
  
    if($callback != ''){
      echo $callback . '(' . json_encode($arr) . ');';
    }else{
      echo json_encode($arr);
    }

  }

// Obtener datos de la accion
function obtDatosAccion(){
  $tz = obtDateTimeZone();
  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $idAccion = (isset($_GET['idAccion']) && $_GET['idAccion']!="")?$_GET['idAccion']:0;
  $casoAccionesObj = new casoAccionesObj();

  $datosAccion = $casoAccionesObj->CasoAccionesPorId($idAccion);
  if($datosAccion->idAccion>0){
    $arr = array("success"=>true, "datos"=>$datosAccion);
  }

  echo $callback . '(' . json_encode($arr) . ');';
}

// Eliminar accion
function eliminarAccion(){
  $tz = obtDateTimeZone();
  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $idAccion = (isset($_GET['idAccion']) && $_GET['idAccion']!="")?$_GET['idAccion']:0;
  $casoAccionesObj = new casoAccionesObj();
  $accionGastosObj = new accionGastosObj();

  // Eliminar la accion
  $resp = $casoAccionesObj->Eliminar($idAccion);
  if($resp){
    // Elimina todos los gastos asociados a la accion
    $resp2 = $accionGastosObj->Eliminar(0, $idAccion);
    $arr = array("success"=>true);
  }

  echo $callback . '(' . json_encode($arr) . ');';
}

// Obtener la lista de gastos
function tblListaGastos(){
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $idTabla = (isset($_GET['idTabla']) && $_GET['idTabla']!="")?$_GET['idTabla']:"";
  $idAccion = (isset($_GET['idAccion']) && $_GET['idAccion']!="")?$_GET['idAccion']:"";
  $colAccion = (isset($_GET['colAccion']) && $_GET['colAccion']!="")?$_GET['colAccion']:0;
  // $idUsuario = (isset($_GET['idUsuario']) !="")?$_GET['idUsuario']:"";

  $accionGastosObj = new accionGastosObj();
  $colRes = $accionGastosObj->ObtAccionGastos($idAccion);
  $arr = array("success"=>false);

  // echo "<pre>";
  // print_r($colRes);
  // echo "</pre>";
  // exit();
  // if(count($colRes)>0){
    // table-striped
    $html = '
        <table id="'.$idTabla.'" class="table table-bordered table-condensed dataTable no-footer dt-responsive " role="grid" cellspacing="0" width="100%" >
            <thead>
                <tr>
                    <th>ID <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>Fecha <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>Concepto <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    <th>Monto <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                    ';
                    if($colAccion>0){
                      $html .= '<th>Acciones <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>';
                    }
                  $html .= '
                </tr>
            </thead>
            <tbody>
            ';
              foreach($colRes as $item){
                // if($idUsuario!=$item->idUsuario && $item->idRol!=4){
                  $html .= '
                  <tr style="cursor:initial !important;">
                      <td>'.$item->idGasto.'</td>
                      <td>'.$item->fechaAlta2.'</td>
                      <td>'.$item->concepto.'</td>
                      <td>'.$item->monto2.'</td>
                      ';
                      if($colAccion>0){
                      $html .= '<td>
                        <a href="javascript:void(0);" onclick="popupCreaEditaGasto('.$item->idGasto.', '.$item->casoId.', '.$item->accionId.', \''.$item->concepto.'\')" title="Editar gasto"><img width="16px" src="../images/iconos/iconos_grid/editar.png"></a>
                        <!-- &nbsp;<a href="javascript:void(0);" onclick="editargasto('.$item->idGasto.')" title="Editar gasto"><img width="16px" src="../images/iconos/iconos_grid/editar.png"></a>-->
                        <a href="javascript:void(0);" onclick="eliminargasto('.$item->idGasto.')" title="Eliminar gasto"><img width="16px" src="../images/iconos/iconos_grid/eliminar.png"></a>
                      </td>';
                      }
                    $html .= '
                  </tr>
                  ';
                // }
              }
        $html .= '
            </tbody>
        </table>
    ';

    $arr = array("success"=>true, "tblListaGastos"=>$html);
  // }

  echo $callback . '(' . json_encode($arr) . ');';
}

// Crea y edita un gasto
function creaEditaGasto(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $idGasto = (isset($_GET['pg_idgasto']) && $_GET['pg_idgasto']!="")?$_GET['pg_idgasto']:"";
  $accionGastosObj = new accionGastosObj();
  $casosObj = new casosObj();

  // Setear datos
  $accionGastosObj->casoId = (isset($_GET['pg_casoid']) && $_GET['pg_casoid']!="")?$_GET['pg_casoid']:"";;
  $accionGastosObj->accionId = (isset($_GET['pg_idaccion']) && $_GET['pg_idaccion']!="")?$_GET['pg_idaccion']:"";
  $accionGastosObj->conceptoId = (isset($_GET['pg_idconcepto']) && $_GET['pg_idconcepto']!="")?$_GET['pg_idconcepto']:"";
  $accionGastosObj->monto = (isset($_GET['pg_monto']) && $_GET['pg_monto']!="")?removerCaracteres($_GET['pg_monto']):0;
  $accionGastosObj->fechaAlta = (isset($_GET['pg_fechagasto']) && $_GET['pg_fechagasto']!="")?conversionFechas($_GET['pg_fechagasto']):"";

  if($idGasto>0){
    $accionGastosObj->idGasto = $idGasto;
    $resp = $accionGastosObj->EditarAccionGasto();
  }else{
    $accionGastosObj->CrearAccionGasto();
    $resp = $accionGastosObj->idGasto;
  }

  if($resp){
    $arr = array("success"=>true);
    //Actualizar la fecha de ultima actualizacion
    $casosObj->ActCampoCaso("fechaAct", $tz->fechaHora, trim($_GET['pg_casoid']));
  }

  echo $callback . '(' . json_encode($arr) . ');';
}

// Obtener datos del gasto
function obtDatosGasto(){
  $tz = obtDateTimeZone();
  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $idGasto = (isset($_GET['idGasto']) && $_GET['idGasto']!="")?$_GET['idGasto']:0;
  $accionGastosObj = new accionGastosObj();

  $datosGasto = $accionGastosObj->AccionGastosPorId($idGasto);
  if($datosGasto->idGasto>0){
    $arr = array("success"=>true, "datos"=>$datosGasto);
  }

  echo $callback . '(' . json_encode($arr) . ');';
}

// Eliminar gasto
function eliminarGasto(){
  $tz = obtDateTimeZone();
  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $idGasto = (isset($_GET['idGasto']) && $_GET['idGasto']!="")?$_GET['idGasto']:0;
  $accionGastosObj = new accionGastosObj();

  $resp = $accionGastosObj->Eliminar($idGasto);
  if($resp){
    $arr = array("success"=>true);
  }

  echo $callback . '(' . json_encode($arr) . ');';
}

// Obtener el total general de los gastos
function obtTotalGastos(){
  $tz = obtDateTimeZone();
  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $idCaso = (isset($_GET['idCaso']) && $_GET['idCaso']!="")?$_GET['idCaso']:0;
  $accionGastosObj = new accionGastosObj();

  $colGastos = $accionGastosObj->ObtAccionGastos(0, $idCaso);
  $tGastos = 0;
  foreach($colGastos as $elem){
    $tGastos += $elem->monto;
  }

  if($tGastos>0){
    $tGastos = formatoMoneda($tGastos);
    $arr = array("success"=>true, "tGastos"=>$tGastos);
  }

  echo $callback . '(' . json_encode($arr) . ');';
}

// Imp. 08/01/21
// Obtener eventos de google calendar
function obtEventosGoogleCal(){
  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $ical = new iCalEasyReader();
  $idCal = "q2n3jg9a0luaih80cahghksgu4@group.calendar.google.com"; //Identificador del calendario de google (Cambiar por el indicado)
  $arrEvents = $ical->getEventsCols($idCal); //Coleccion de eventos

  if(count($arrEvents)>0){
    $arr = array("success"=>true, "eventos"=>$arrEvents);
  }

  echo $callback . '(' . json_encode($arr) . ');';
}

// Crea y edita un gasto
function salvarEventos(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $eventosSel = (isset($_GET['eventosSel']) && $_GET['eventosSel']!="")?$_GET['eventosSel']:array();
  $idCaso = (isset($_GET['idCaso']) && $_GET['idCaso']!="")?$_GET['idCaso']:"";
  $idCliente = (isset($_GET['idCliente']) && $_GET['idCliente']!="")?$_GET['idCliente']:"";

  $arr = array("success"=>false);
  $casoAccionesObj = new casoAccionesObj();
  $casosObj = new casosObj();
  $arrSalvados = array();

  // echo "<pre>";
  // print_r($_GET);
  // echo "</pre>";
  // exit;

  foreach($eventosSel as $elem){
    $fecha = htmlentities(utf8_encode(base64_decode($elem["fecha"])), ENT_QUOTES);
    $fechaTmp = explode("T", $fecha);
    $fecha = $fechaTmp[0];
    $evento = htmlentities(utf8_encode(base64_decode($elem["evento"])), ENT_QUOTES);
    $comentario = htmlentities(utf8_encode(base64_decode($elem["comentario"])), ENT_QUOTES);

    // echo $fecha." - ".$evento." - ".$comentario."<br/>";

    // Setear datos
    $casoAccionesObj->casoId = $idCaso;
    $casoAccionesObj->nombre = $evento;
    $casoAccionesObj->comentarios = $comentario;
    $casoAccionesObj->fechaAlta = $fecha;
    $casoAccionesObj->CrearCasoAccion();

    if($casoAccionesObj->idAccion){
      $arrSalvados[] = $casoAccionesObj->idAccion;
    }
  }

  if(count($arrSalvados)>0){
    $arr = array("success"=>true);
    //Actualizar la fecha de ultima actualizacion
    $casosObj->ActCampoCaso("fechaAct", $tz->fechaHora, trim($idCaso));
  }

  echo $callback . '(' . json_encode($arr) . ');';
}


//

function verTablaConceptos(){
  $callback = (isset($_GET['callback']) !="")?$_GET['callback']:"";
  $idUsuario = (isset($_GET['idUsuario']) !="")?$_GET['idUsuario']:"";
  $desde = (isset($_GET['desde']) !="")?$_GET['desde']:"";
  $hasta = (isset($_GET['hasta']) !="")?$_GET['hasta']:"";
  $conceptosObj = new conceptosObj();
  $usuariosObj = new usuariosObj();
  $catConceptosObj = new catConceptosObj();
  $conceptos = $conceptosObj->ObtTodosConceptos($idUsuario, "", $desde, $hasta, "ORDER BY fecha DESC");

  //array fechas dias
  $fechaItem = '';
  $arrFechas = array();
  $arrFechas[] = $hasta;
  $fechaAnt = $hasta;

  while($fechaItem != $desde){
    $fechaItem = diasPrevPos(1, $fechaAnt, "prev", 3);
    $arrFechas[] = $fechaItem;
    $fechaAnt = $fechaItem;
  }

  $usuario = $usuariosObj->UserByID($idUsuario);
  $html = '';
  
  $html.= '
  <h4>'.$usuario->numAbogado.' - '.$usuario->nombre.'</h4>
  <div class="row">
      <div class="col-xs-6 col-md-3">
          <div class="alert alert-info uno">
              <div class="row text-center"><label>Total Entradas Periodo</label></div>
              <div class="row text-right"><span id="spanTotalAbonosAbg"></span></div>
          </div>
      </div>
      <div class="col-xs-6 col-md-3">
          <div class="alert alert-info dos">
              <div class="row text-center"><label>Total Salidas Periodo</label></div>
              <div class="row text-right"><span id="spanTotalCargosAbg"></span></div>
          </div>
      </div>
      <!-- <div class="col-xs-6 col-md-3">
          <div class="alert alert-info tres">
              <div class="row text-center"><label>Saldo periodo</label></div>
              <div class="row text-right"><label><span id="spanSaldoTotalAbg"></span></label></div>
          </div>
      </div> -->
      <div class="col-xs-6 col-md-3">
          <div class="alert alert-info cuatro">
              <div class="row text-center"><label>Saldo total actual al dia</label></div>
              <div class="row text-right"><span id="spanSaldoHistoricoAbg"></span></div>
          </div>
      </div>
  </div>
  <div class="tab_fancy">
  <table class="table table-hover">
  <thead>
  <tr>
  <th>Fecha</th>
  <th>F. Captura</th>
  <th>Entrada</th>
  <th>Salida</th>
  <th>Saldo Dia</th>
  <th>Saldo Sem Acum</th>
  <th>Tipo</th>
  <th>Concepto</th>
  <th>Acciones</th>
  </tr>
  </thead>
  <tbody>
      ';
    $arrFechas = array_reverse($arrFechas);  
    $arrConceptos = array();
    foreach($arrFechas as $fItem){
      $conceptosDia = $conceptosObj->ObtTodosConceptos($idUsuario, "", $fItem, $fItem, "ORDER BY fecha ASC");
      $conceptosSem = $conceptosObj->ObtTodosConceptos($idUsuario, "", $desde, $fItem, "ORDER BY fecha ASC");
      $entradasSem = $salidasSem = 0;
      $entradaSem = $salidaSem = '';
      
      foreach($conceptosSem as $itemConcepSem){
          if($itemConcepSem->tipoId == 1){
              $salidasSem += $itemConcepSem->monto;
              $salidaSem = '$ -'.number_format($itemConcepSem->monto, 2);
            }else{
              $entradasSem += $itemConcepSem->monto;
              $entradaSem = '$ '.number_format($itemConcepSem->monto, 2);
            }
      }
      
      if(count($conceptosDia) > 0){
          $entradasDia = 0;
          $salidasDia = 0;
          $saldoDia = 0;
          $htmlDia = '';
          $htmlConceptosDia = '';

          foreach ($conceptosDia as $concepto) {
            $catConcepto = $catConceptosObj->ConceptoPorId($concepto->catConceptoId);
            $entrada = $salida = '';
            if($concepto->tipoId == 1){
              $salidasDia += $concepto->monto;
              $salida = '$ -'.number_format($concepto->monto, 2);
            }else{
              $entradasDia += $concepto->monto;
              $entrada = '$ '.number_format($concepto->monto, 2);
            }
           
            $boton = ($concepto->comprobante != '')?'<a class="grid_download" href="../upload/comprobantes/'.$concepto->comprobante.'" download title="Descargar comprobante"></a>':'';

            $boton .= '
            <a class="grid_swap" onclick="creaMovimientoContrario('.$concepto->tipoId.', '.$concepto->monto.', '.$idUsuario.')" title="Movimiento contrario para correcci&oacute;n"></a>
            ';

              $htmlConceptosDia .= '
              <tr>
                <td></td>
                <td class="text-right">'.convertirFechaVistaConHora($concepto->fechaCreacion).'</td>
                <td class="text-right">'.$entrada.'</td>
                <td class="text-right">'.$salida.'</td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td>'.$catConcepto->nombre.'</td>
                <td>'.$concepto->descripcion.'</td>
                <td>'.$boton.'</td>
              </tr>
              ';
          }

          $saldoDia = $entradasDia - $salidasDia;
          $saldoDiaAcum = $entradasSem - $salidasSem;

          $htmlDia .= '
          <tr class="rowDia">
          <th>'.convertirAFechaCompleta3($fItem).'</th>
          <td></td>
           
            <th class="text-right">$ '.number_format($entradasDia,2).'</th>
            <th class="text-right">$ -'.number_format($salidasDia,2).'</th>
            <th class="text-right">$ '.number_format($saldoDia,2).'</th>
            <th class="text-right">$ '.number_format($saldoDiaAcum,2).'</th>
            <th></th>
            <th></th>
            <th></th>
          </tr>
          ';

          $html .= $htmlDia.$htmlConceptosDia;
      }
    }



  $html.= '
  </tbody>
  </table>
  </div>';

  $return_arr = array("success"=>true, "html"=>$html, "arrFechas"=>$arrFechas, "arrConceptos"=>$arrConceptos);

  echo $callback . '(' . json_encode($return_arr) . ')';
}

function guardarConcepto(){
  $conceptosObj = new conceptosObj();
  // echo "<pre>";print_r($_POST);echo "</pre>";die();
  $prefijo = $_POST["prefijo"];
  
  $conceptosObj->tipoId = $_POST["tipoId".$prefijo];
  $conceptosObj->usuarioId = $_POST["usuarioId".$prefijo];
  $conceptosObj->catConceptoId = $_POST["conceptoId".$prefijo];
  $conceptosObj->descripcion = $_POST["descripcion".$prefijo];
  $conceptosObj->monto = removerCaracteres($_POST["monto".$prefijo]);
  $conceptosObj->fecha = conversionFechas($_POST["fecha".$prefijo]);
  
  $res = $upd = 0;
  // if($_POST["idConcepto"] == 0){
    if($_POST["tipoId".$prefijo] == 1){
      $save_folder = '../upload/comprobantes/';
      $comprobante = (isset($_FILES['comprobante'.$prefijo]))?subirArchivo('comprobante'.$prefijo, $save_folder):"";
      $conceptosObj->comprobante = $comprobante;
      $res = $conceptosObj->crearCargo();//die();
      
    }
    if($_POST["tipoId".$prefijo] == 2){
      $res = $conceptosObj->crearAbono();
    }
  // }else{
  //   $catConceptosObj->idConcepto = $_POST["idConcepto"];
  //   $upd = $catConceptosObj->EditarConcepto();
  // }

  $arr = array("success"=>true, "res"=>$res["res"], "upd"=>$upd);

  echo json_encode($arr);
}

function marcarComoLeido(){
  $callback = (isset($_GET['callback']) !="")?$_GET['callback']:"";
  $idMensajeRecibido = (isset($_GET['idMensaje']) !="")?$_GET['idMensaje']:0;
  
  $mensajesObj = new mensajesObj();

  $arrIds = explode(",", $idMensajeRecibido);
  $cont = 0;
  foreach ($arrIds as $idMensaje) {
    $res = $mensajesObj->ActualizarMensaje("leido", 1, $idMensaje);
    if($res > 0){
      $cont++;
    }
  }
  $return_arr = array("success"=>true, "res"=>$res, "cont"=>$cont);

  echo $callback . '(' . json_encode($return_arr) . ')';
}

function marcarLeidoComentario(){
  $callback = (isset($_GET['callback']) !="")?$_GET['callback']:"";
  $idRegistroRecibido = (isset($_GET['idRegistro']) !="")?$_GET['idRegistro']:0;
  $tipo = (isset($_GET['tipo']) !="")?$_GET['tipo']:0;

  switch ($tipo) {
    case 'actividad':
      $objeto = new casoAccionesObj();
      $metodo = 'ActCampoAccion';
      $campo = 'leido';
    break;
    
    default:
      # code...
      break;
  }

  $arrIds = explode(",", $idRegistroRecibido);
  $cont = 0;
  foreach ($arrIds as $idRegistro) {
    $res = $objeto->{$metodo}($campo, 1, $idRegistro);
    if($res > 0){
      $cont++;
    }
  }
  $return_arr = array("success"=>true, "res"=>$res, "cont"=>$cont);

  echo $callback . '(' . json_encode($return_arr) . ')';
}

function unirPdf(){
  $callback = (isset($_GET['callback']) !="")?$_GET['callback']:"";
  $expedienteId = (isset($_GET['idCaso']) !="")?$_GET['idCaso']:0;
  
  $digitalesObj = new digitalesObj();
  $casosObj = new casosObj();
  
  $expediente = $casosObj->CasoPorId($expedienteId);
  $digitales = $digitalesObj->ObtDigitales($expedienteId, 2, '', '', '', '', 'orden ASC');
  
  $datadir = "../descargas/";
  $outputName = $datadir."merged_".$expedienteId.".pdf";
  $contNoCompatibles = 0;

  //Si no se ha generado, generar y actualizar el campo en bd
  if($expediente->generado == 0){
    $fileArray = array();
    foreach ($digitales as $digital) {
        $fileArray[] = "../upload/expedientes/".$digital->url;
    }
    
    
    $pdf = new \Clegginabox\PDFMerger\PDFMerger;
    foreach ($digitales as $digital) {
      $srcfile = "../upload/expedientes/".$digital->url;
      $filepdf = fopen($srcfile,"r");
      if($filepdf) {
        $line_first = fgets($filepdf);
        fclose($filepdf);
      }
      else{
        echo "error opening the file.";
      }
      // extract number such as 1.4,1.5 from first read line of pdf file
      preg_match_all('!\d+!', $line_first, $matches);
              
      // save that number in a variable
      $pdfversion = implode('.', $matches[0]);

      // compare that number from 1.4(if greater than proceed with ghostscript)
      // https://infoconic.com/blog/trick-for-fpdi-pdf-parser-that-supports-pdf-version-above-1-4/
      if($pdfversion > "1.4"){
        $contNoCompatibles++;
      }else{
        $pdf->addPDF($srcfile, 'all');
      }
    }
    
    $pdf->merge('file', $outputName, 'P');
    $casosObj->ActCampoCaso("generado", 1, $expedienteId);
  }

  $return_arr = array("success"=>true, "outputName"=>$outputName, "contNoCompatibles"=>$contNoCompatibles);

  echo $callback . '(' . json_encode($return_arr) . ')';
}

function unirPdf2(){
  set_time_limit(0);
  ini_set('memory_limit', '-1');
  
  $callback = (isset($_GET['callback']) !="")?$_GET['callback']:"";
  $expedienteId = (isset($_GET['idCaso']) !="")?$_GET['idCaso']:0;
  
  $digitalesObj = new digitalesObj();
  $casosObj = new casosObj();
    
  $expediente = $casosObj->CasoPorId($expedienteId);
  $digitales = $digitalesObj->ObtDigitales($expedienteId, 2, '', '', '', '', 'orden ASC');
 
  $nombreArch = str_replace("/","_",$expediente->numExpediente);

  $datadir = dirname(__DIR__).'/descargas/';
  $outputName = $datadir."expCompleto".$nombreArch.".pdf";
  $RetoutputName = '/descargas/'."expCompleto".$nombreArch.".pdf";
  $contNoCompatibles = 0;

  //Si no se ha generado, generar y actualizar el campo en bd
  //if($expediente->generado == 0){
    
    $fileArray = array();
    foreach ($digitales as $digital) {
        $fileArray[] = "../upload/expedientes/".$digital->url;
    }
    
    
    $pdf = new PDFMerger;
    foreach ($digitales as $digital) {
      $srcfile = "../upload/expedientes/".$digital->url;
      $filepdf = fopen($srcfile,"r");
echo $digital->url . ': ' . filesize($srcfile) . ' bytes';      
      if($filepdf) {
        $line_first = fgets($filepdf);
        fclose($filepdf);
      }
      else{
        echo "error opening the file.";
      }
      // extract number such as 1.4,1.5 from first read line of pdf file
      //preg_match_all('!\d+!', $line_first, $matches);
              
      // save that number in a variable
      //$pdfversion = implode('.', $matches[0]);

      //// compare that number from 1.4(if greater than proceed with ghostscript)
      //// https://infoconic.com/blog/trick-for-fpdi-pdf-parser-that-supports-pdf-version-above-1-4/
      //if($pdfversion > "1.4"){
      //  $contNoCompatibles++;
      //}else{
        $pdf->addPDF($srcfile, 'all');
      //}
    //}
    ob_clean();
    $ret = $pdf->merge('file', $outputName, 'F');
    //$pdf->merge('download', $outputName);
    $casosObj->ActCampoCaso("generado", 1, $expedienteId);
  }

  $return_arr = array("success"=>true, "outputName"=>$RetoutputName, "contNoCompatibles"=>$contNoCompatibles);

  echo $callback . '(' . json_encode($return_arr) . ')';
}

function ordenarDocs(){
  $callback = (isset($_GET['callback']) !="")?$_GET['callback']:"";
  $json = (isset($_GET['json']) !="")?$_GET['json']:0;
  $expedienteId = (isset($_GET['idCaso']) !="")?$_GET['idCaso']:0;

  $digitalesObj = new digitalesObj();
  $casosObj = new casosObj();
  
  $arrDigitales = json_decode($json);
  $cont = 0;
  foreach ($arrDigitales as $itemDig) {
    $res = $digitalesObj->ActCampoDigital("orden", $itemDig->order, $itemDig->idDocumento);
    if($res > 0){
      $cont++;
    }
  }

  if($cont > 0){
    $casosObj->ActCampoCaso("generado", 0, $expedienteId);
  }
  $return_arr = array("success"=>true, "cont"=>$cont);

  echo $callback . '(' . json_encode($return_arr) . ')';
}


// Crear Cita Simple
function crearCitaSimple(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);
  // $motivosPropuestaObj = new motivosPropuestaObj();

  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";

  $citaSimpleEditId = (isset($_GET['citaSimpleEditId']) && $_GET['citaSimpleEditId']!="")?$_GET['citaSimpleEditId']:0;
  $usrId = (isset($_GET['citaSimpleUsrId']) && $_GET['citaSimpleUsrId']!="")?$_GET['citaSimpleUsrId']:0;
  $respUsrId = (isset($_GET['citaSimpleRespUsrId']) && $_GET['citaSimpleRespUsrId']!="")?$_GET['citaSimpleRespUsrId']:0;
  $casoAccionObj = new casoAccionesObj();

  if($respUsrId > 0){
    $usrId = $respUsrId;
  }

  // Setear datos
  $casoAccionObj->nombre = (isset($_GET['pc_nombreCita']) && $_GET['pc_nombreCita']!="")?$_GET['pc_nombreCita']:"";
  $casoAccionObj->fechaCompromiso = (isset($_GET['pc_fechaCompromiso']) && $_GET['pc_fechaCompromiso']!="")?'\''.conversionFechaF3($_GET['pc_fechaCompromiso']).'\'':"";
  $casoAccionObj->responsableId = (isset($_GET['abogadosSel']) && $_GET['abogadosSel']!="")?$_GET['abogadosSel']:1;
  $casoAccionObj->comentarios = (isset($_GET['pc_comentarios']) && $_GET['pc_comentarios']!="")?$_GET['pc_comentarios']:"";
  $casoAccionObj->tipo = 6; //cita simple 
  $casoAccionObj->estatusId = 4; //nace terminado
  $casoAccionObj->fechaRealizado = $casoAccionObj->fechaCompromiso;
  $casoAccionObj->usuarioId = $usrId;
 
  

  if($citaSimpleEditId == 0){
    $casoAccionObj->fechaAlta = $tz->fechaHora;
    $casoAccionObj->fechaCreacion = $tz->fechaHora;
    $casoAccionObj->CrearCasoAccion();
  }else{
    $casoAccionObj->idAccion = $citaSimpleEditId;
    $casoAccionObj->EditarCasoAccion();
  }

  if($casoAccionObj->idAccion){
    $arr = array("success"=>true, 
      "idAccion"=>$casoAccionObj->idAccion
    );
  }

  echo $callback . '(' . json_encode($arr) . ');';
}


function crearCitaSocial(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);
  // $motivosPropuestaObj = new motivosPropuestaObj();

  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";

  $citaSocialEditId = (isset($_GET['citaSocialEditId']) && $_GET['citaSocialEditId']!="")?$_GET['citaSocialEditId']:0;
  $usrId = (isset($_GET['citaSocialUsrId']) && $_GET['citaSocialUsrId']!="")?$_GET['citaSocialUsrId']:0;
  $respUsrId = (isset($_GET['citaSocialRespUsrId']) && $_GET['citaSocialRespUsrId']!="")?$_GET['citaSocialRespUsrId']:0;
  $casoAccionObj = new casoAccionesObj();

  if($respUsrId > 0){
    $usrId = $respUsrId;
  }

  // Setear datos
  $casoAccionObj->nombre = (isset($_GET['pc_nombreCitaSocial']) && $_GET['pc_nombreCitaSocial']!="")?$_GET['pc_nombreCitaSocial']:"";
  $casoAccionObj->fechaCompromiso = (isset($_GET['pc_fechaCompromisoCS']) && $_GET['pc_fechaCompromisoCS']!="")?'\''.conversionFechaF3($_GET['pc_fechaCompromisoCS']).'\'':"";
  $casoAccionObj->comentarios = (isset($_GET['pc_comentariosCS']) && $_GET['pc_comentariosCS']!="")?$_GET['pc_comentariosCS']:"";
  $casoAccionObj->tipo = 10; //cita social
  $casoAccionObj->estatusId = 4; //nace terminado
  $casoAccionObj->fechaRealizado = $casoAccionObj->fechaCompromiso;
  $casoAccionObj->usuarioId = $usrId;
  $casoAccionObj->responsableId = $usrId;
 
  if($citaSocialEditId == 0){
    $casoAccionObj->fechaAlta = $tz->fechaHora;
    $casoAccionObj->fechaCreacion = $tz->fechaHora;
    $casoAccionObj->CrearCasoAccion();
  }else{
    $casoAccionObj->idAccion = $citaSocialEditId;
    $casoAccionObj->EditarCasoAccion();
  }

  if($casoAccionObj->idAccion){
    $arr = array("success"=>true, 
      "idAccion"=>$casoAccionObj->idAccion
    );
  }

  echo $callback . '(' . json_encode($arr) . ');';
}


function eliminarCitaSimple(){
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  //base64DecodeSubmit(0, $_GET);
  
  $arr = array("success"=>true);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $casoAccionObj = new casoAccionesObj();
  $idAccion = (isset($_GET['idAccion']) && $_GET['idAccion']!="")?$_GET['idAccion']:0;

  $casoAccionObj->idAccion = $idAccion;
  $casoAccionObj->EliminarCitaSimpleObj($idAccion);
  //var_dump($idAccion);
  echo $callback . '(' . json_encode($arr) . ');';
}


function eliminarCliente(){
  unset($_GET['funct']); 
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $clienteObj = new clientesObj();
  $idCliente = (isset($_GET['idCliente']) && $_GET['idCliente']!="")?$_GET['idCliente']:0;
  //verificar si no tiene un casos asociados
  /*$casosObject = new casosObj();
  $casosObject->idCliente = $idCliente;
  $resp=$casosObject->verificarCasos($idCliente);*/
  //if($resp=="0"||$resp==0){
    $clienteObj->idCliente = $idCliente;
    $clienteObj->EliminarClienteObj($idCliente);
    $arr = array("success"=>true);
  /*}else{
    $arr = array("success"=>false, "data"=>001);
    
  }*/
  echo $callback . '(' . json_encode($arr) . ');';
}

function eliminarPagoSerie(){
  unset($_GET['funct']); 

  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $casoAccionObj = new casoAccionesObj();
  $idAccion = (isset($_GET['idAccion']) && $_GET['idAccion']!="")?$_GET['idAccion']:0;

  $casoAccionObj->idAccion = $idAccion;
  $id=$casoAccionObj->obtenerIdPadre($idAccion);
  $padreAccionId=$id->padreAccionId;
  $casoAccionObj->EliminarCitaSimpleSerieObj($padreAccionId);
  $arr = array("success"=>true);
  
  echo $callback . '(' . json_encode($arr) . ');';
}

function crearPagoAgenda(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);
  
  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  
  //$pagoEditId = (isset($_GET['pagoEditId']) && $_GET['pagoEditId']!="")?$_GET['pagoEditId']:0;
  $usrId = (isset($_GET['pagoUsrId']) && $_GET['pagoUsrId']!="")?$_GET['pagoUsrId']:0;
  $respUsrId = (isset($_GET['pagoRespUsrId']) && $_GET['pagoRespUsrId']!="")?$_GET['pagoRespUsrId']:0;
  

  if($respUsrId > 0){
    $usrId = $respUsrId;
  }
  //obtener datos para hacer operaciones de tiempo
  $repetir = (isset($_GET['pc_repeti']) && $_GET['pc_repeti']!="")?$_GET['pc_repeti']:"";
  $periodo = (isset($_GET['pc_cadaTime']) && $_GET['pc_cadaTime']!="")?$_GET['pc_cadaTime']:"";
  $fechaCompromiso = (isset($_GET['pc_fechaPago']) && $_GET['pc_fechaPago']!="")?$_GET['pc_fechaPago']:"";
  $fecha_obj = DateTime::createFromFormat('d/m/Y H:i', $fechaCompromiso);
  $fecha = $fecha_obj->format('Y-m-d H:i:s');


  $arrayFechas = array();
  //array_push($arrayFechas,$fecha);
  switch ($periodo) {
    case 1:// caso 1 = dias
      for ($i=1; $i<=$repetir; $i++) {
        array_push($arrayFechas,$fecha);
        $fecha = date('Y-m-d H:i:s', strtotime($fecha . ' +1 day'));// se aumenta un dia en cada pasada del siclo
      }
      break;
    case 2://caso 2 = semanas
      for ($i=1; $i<=$repetir; $i++) {
        array_push($arrayFechas,$fecha);
        $fecha = date('Y-m-d H:i:s', strtotime($fecha . ' +1 week'));//se aumenta una semana en cada pasada del siclo
      }
      
      break;
    case 3:// caso 3 = meses
      $ultimo_dia_mes = date('t', strtotime($fecha)); // Último día del mes correspondiente a la fecha
      $dia_fecha = date('d', strtotime($fecha)); // Día de la fecha a verificar
      array_push($arrayFechas,$fecha);
      if ($dia_fecha == $ultimo_dia_mes) {$tipoDia='t';} else {$tipoDia='d';}//verifica si es el ultimo dia
      for ($i=1; $i<=$repetir-1; $i++) {
        //array_push($arrayFechas,$fecha);
        $fecha_nueva = date('Y-m-'.$tipoDia.' H:i:s', strtotime($fecha . ' +'.$i.' month'));//se aumenta $i meses en cada pasada del siclo
        array_push($arrayFechas,$fecha_nueva);
      }
      
      
        break;
    case 4:// caso 4 = años
      for ($i=1; $i<=$repetir; $i++) {
        array_push($arrayFechas,$fecha);
        $fecha = date('Y-m-d H:i:s', strtotime($fecha . ' +1 year'));//se aumenta un año en cada pasada del siclo
      }
      break;
  }
  //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>recorremos el arreglo de fechas para guardar los datos en la bd
  /*$casoAccionObj = new casoAccionesObj();
  foreach ($arrayFechas as $key => $fecha) {
    $casoAccionObj->nombre = (isset($_GET['pc_nombrePago']) && $_GET['pc_nombrePago']!="")?$_GET['pc_nombrePago']:"";
    $casoAccionObj->fechaCompromiso = "'".$fecha."'";
    $casoAccionObj->comentarios = (isset($_GET['pc_comentariosPago']) && $_GET['pc_comentariosPago']!="")?$_GET['pc_comentariosPago']:"";
    $casoAccionObj->tipo = 8; //pagos
    $casoAccionObj->estatusId = 4; 
    $casoAccionObj->fechaRealizado =$casoAccionObj->fechaCompromiso;
    $casoAccionObj->usuarioId = $usrId;
    $casoAccionObj->responsableId = $usrId;
    $casoAccionObj->fechaAlta = $tz->fechaHora;
    $casoAccionObj->fechaCreacion = $tz->fechaHora;
    $casoAccionObj->CrearCasoAccion();
    $idAcciones[$key] = $casoAccionObj->idAccion;
  }*/
  $casoAccionObj = new casoAccionesObj();
  $primerIdAccion=null;
  foreach ($arrayFechas as $key => $fecha) {
      $casoAccionObj->nombre = (isset($_GET['pc_nombrePago']) && $_GET['pc_nombrePago']!="")?$_GET['pc_nombrePago']:"";
      $casoAccionObj->fechaCompromiso = "'".$fecha."'";
      $casoAccionObj->comentarios = (isset($_GET['pc_comentariosPago']) && $_GET['pc_comentariosPago']!="")?$_GET['pc_comentariosPago']:"";
      $casoAccionObj->tipo = 8; //pagos
      $casoAccionObj->estatusId = 4; 
      $casoAccionObj->fechaRealizado = $casoAccionObj->fechaCompromiso;
      $casoAccionObj->usuarioId = $usrId;
      $casoAccionObj->responsableId = $usrId;
      $casoAccionObj->fechaAlta = $tz->fechaHora;
      $casoAccionObj->fechaCreacion = $tz->fechaHora;
      if ($key != 0) {
        $casoAccionObj->padreAccionId =$primerIdAccion;
      } 
      $casoAccionObj->CrearCasoAccion(); 
      if ($key === 0) {
        $primerIdAccion=$casoAccionObj->idAccion;
        $casoAccionObj->idAccion = $primerIdAccion;
        $casoAccionObj->padreAccionId =$primerIdAccion;
        $casoAccionObj->EditarCasoAccion();
      } else {
          $casoAccionObj->padreAccionId =$primerIdAccion;
      } 
  }

  if($casoAccionObj->idAccion){
    $arr = array("success"=>true, 
      "idAccion"=>$casoAccionObj->idAccion
    );
  }
  echo $callback . '(' . json_encode($arr) . ');';
}

function crearCobroAgenda(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);
  
  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  
  //$pagoEditId = (isset($_GET['pagoEditId']) && $_GET['pagoEditId']!="")?$_GET['pagoEditId']:0;
  $usrId = (isset($_GET['cobroUsrId']) && $_GET['cobroUsrId']!="")?$_GET['cobroUsrId']:0;
  $respUsrId = (isset($_GET['cobroRespUsrId']) && $_GET['cobroRespUsrId']!="")?$_GET['cobroRespUsrId']:0;
  

  if($respUsrId > 0){
    $usrId = $respUsrId;
  }
  //obtener datos para hacer operaciones de tiempo
  $repetir = (isset($_GET['pc_repetiCobro']) && $_GET['pc_repetiCobro']!="")?$_GET['pc_repetiCobro']:"";
  $periodo = (isset($_GET['pc_cadaTimeCobro']) && $_GET['pc_cadaTimeCobro']!="")?$_GET['pc_cadaTimeCobro']:"";
  $fechaCompromiso = (isset($_GET['pc_fechaCobro']) && $_GET['pc_fechaCobro']!="")?$_GET['pc_fechaCobro']:"";
  $fecha_obj = DateTime::createFromFormat('d/m/Y H:i', $fechaCompromiso);
  $fecha = $fecha_obj->format('Y-m-d H:i:s');


  $arrayFechas = array();
  //array_push($arrayFechas,$fecha);
  switch ($periodo) {
    case 1:// caso 1 = dias
      for ($i=1; $i<=$repetir; $i++) {
        array_push($arrayFechas,$fecha);
        $fecha = date('Y-m-d H:i:s', strtotime($fecha . ' +1 day'));// se aumenta un dia en cada pasada del siclo
      }
      break;
    case 2://caso 2 = semanas
      for ($i=1; $i<=$repetir; $i++) {
        array_push($arrayFechas,$fecha);
        $fecha = date('Y-m-d H:i:s', strtotime($fecha . ' +1 week'));//se aumenta una semana en cada pasada del siclo
      }
      
      break;
    case 3:// caso 3 = meses
      $ultimo_dia_mes = date('t', strtotime($fecha)); // Último día del mes correspondiente a la fecha
      $dia_fecha = date('d', strtotime($fecha)); // Día de la fecha a verificar
      array_push($arrayFechas,$fecha);
      if ($dia_fecha == $ultimo_dia_mes) {$tipoDia='t';} else {$tipoDia='d';}//verifica si es el ultimo dia
      for ($i=1; $i<=$repetir-1; $i++) {
        //array_push($arrayFechas,$fecha);
        $fecha_nueva = date('Y-m-'.$tipoDia.' H:i:s', strtotime($fecha . ' +'.$i.' month'));//se aumenta $i meses en cada pasada del siclo
        array_push($arrayFechas,$fecha_nueva);
      }
      
      
        break;
    case 4:// caso 4 = años
      for ($i=1; $i<=$repetir; $i++) {
        array_push($arrayFechas,$fecha);
        $fecha = date('Y-m-d H:i:s', strtotime($fecha . ' +1 year'));//se aumenta un año en cada pasada del siclo
      }
      break;
  }
  $casoAccionObj = new casoAccionesObj();
  $primerIdAccion=null;
  foreach ($arrayFechas as $key => $fecha) {
      $casoAccionObj->nombre = (isset($_GET['pc_nombreCobro']) && $_GET['pc_nombreCobro']!="")?$_GET['pc_nombreCobro']:"";
      $casoAccionObj->fechaCompromiso = "'".$fecha."'";
      $casoAccionObj->comentarios = (isset($_GET['pc_comentariosCobro']) && $_GET['pc_comentariosCobro']!="")?$_GET['pc_comentariosCobro']:"";
      $casoAccionObj->tipo = 9; //cobros
      $casoAccionObj->estatusId = 4; 
      $casoAccionObj->fechaRealizado = $casoAccionObj->fechaCompromiso;
      $casoAccionObj->usuarioId = $usrId;
      $casoAccionObj->responsableId = $usrId;
      $casoAccionObj->fechaAlta = $tz->fechaHora;
      $casoAccionObj->fechaCreacion = $tz->fechaHora;
      if ($key != 0) {
        $casoAccionObj->padreAccionId =$primerIdAccion;
      } 
      $casoAccionObj->CrearCasoAccion(); 
      if ($key === 0) {
        $primerIdAccion=$casoAccionObj->idAccion;
        $casoAccionObj->idAccion = $primerIdAccion;
        $casoAccionObj->padreAccionId =$primerIdAccion;
        $casoAccionObj->EditarCasoAccion();
      } else {
          $casoAccionObj->padreAccionId =$primerIdAccion;
      } 
  }

  if($casoAccionObj->idAccion){
    $arr = array("success"=>true, 
      "idAccion"=>$casoAccionObj->idAccion
    );
  }
  echo $callback . '(' . json_encode($arr) . ');';
}

function obtProxEventosCaso(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);
  // $motivosPropuestaObj = new motivosPropuestaObj();

  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";

  $idCaso = (isset($_GET['idCaso']) && $_GET['idCaso']!="")?$_GET['idCaso']:0;
  
  $casosObj = new casoAccionesObj();

  $result = $casosObj->ObtProxEventosCasoId($idCaso);

  $html = '';

  if(count($result)>0){
    $html .= '<table class="table">';
    $html .= '<th>Tipo</th>';
    $html .= '<th>Fecha Compromiso</th>';
    $html .= '<th>Descipción</th>';
    $html .= '<th></th>';

    foreach ($result as $key=>$elem) {
      $html .= '<tr>';
      $html .= '<td>'.$elem->TipoTxt.'</td>'; 
      $html .= '<td>'.$elem->fechaEvento.'</td>';
      $html .= '<td>'.$elem->comentarios.'</td>';
      $html .= '<td><a class="grid_edit" target="_blank" href="actividad.php?expId='.$idCaso.'&amp;actId='.$elem->idAccion.'" title="Ver detalle evento"></a></td>';
      $html .= '</tr>';
    }

    $html .= '</table>';
  }

  $arr = array("success"=>true, 
      "html"=>$html
  );

  echo $callback . '(' . json_encode($arr) . ');';
}

function obtArbolCaso(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);
  
  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";

  //$idCaso = (isset($_GET['idCaso']) && $_GET['idCaso']!="")?$_GET['idCaso']:0; // aqui esta trayendo la raiz pero la trae desde el form
  $idCasoClic = (isset($_GET['idCasoClic']) && $_GET['idCasoClic']!="")?$_GET['idCasoClic']:0;
  
  $casosObj = new casosObj();
  
  $idCaso= $casosObj->GetPadreCasoArbol($idCasoClic);
  //var_dump($idCasoClic);
  //var_dump($idCaso[0]->idPadreMain);
  //exit();
  
  $arrayArbol = $casosObj->arbolPorIdCaso($idCaso[0]->idPadreMain);
  /*echo '<pre>';  
  print_r($arrayArbol);
  echo '</pre>';
  exit();*/
  $html = '';

  if(count($arrayArbol)>0){

    foreach ($arrayArbol as $key=>$elem) {
      if($elem["caso"] == $idCasoClic && $elem["datosCaso"]["estatusId"] == 1){
        $html .= '<div id="'.$elem["id"].'" class="window windowSource hidden"';
      }else if($elem["caso"] == $idCasoClic && $elem["datosCaso"]["estatusId"] == 2){
        $html .= '<div id="'.$elem["id"].'" class="historicoEstilo windowSource hidden"';
      }else if($elem["caso"] == $idCasoClic && $elem["datosCaso"]["estatusId"] == 3){
        $html .= '<div id="'.$elem["id"].'" class="historicoEstilo windowSource hidden"';
      }else if($elem["caso"] == $idCasoClic && $elem["datosCaso"]["estatusId"] == 4){
        $html .= '<div id="'.$elem["id"].'" class="historicoEstilo windowSource hidden"';
      }else if($elem["datosCaso"]["estatusId"] == 2){
        $html .= '<div id="'.$elem["id"].'" class="historicoEstilo hidden"';
      }else if($elem["datosCaso"]["estatusId"] == 1){
        $html .= '<div id="'.$elem["id"].'" class="window hidden"';
      }else if($elem["datosCaso"]["estatusId"] == 3){
        $html .= '<div id="'.$elem["id"].'" class="bajaEstilo hidden"';
      }else if($elem["datosCaso"]["estatusId"] == 4){
        $html .= '<div id="'.$elem["id"].'" class="window terminadoEstilo hidden"';
      }
      
      $html .= '  data-id="'.$elem["data-id"].'"';

      if(isset($elem["data-parent"])){
        $html .= '  data-parent="'.$elem["data-parent"].'"';
      }else{
        $html .= '  data-parent="0"';
      }
  

      if($elem["data-id"] == 0){
        $html .= '  data-first-child="1"';
      }
      else if(isset($elem["data-first-child"])){
        $html .= '  data-first-child="'.$elem["data-first-child"].'"';
      }else{
        $html .= '  data-first-child=""';
      }
      
      if(isset($elem["data-next-sibling"])){
        $html .= '  data-next-sibling="'.$elem["data-next-sibling"].'">';
      }else{
        $html .= '  data-next-sibling="">';
      }
      
      $html .= '<a href="expedientes.php?caso='.$elem["caso"].'&estatus=1,5,2,3,4'.'" target="_blank"><strong>ID:<strong/> '.$elem["caso"].'</a>';
      $html .= '<br/><strong>Representado:<strong/> '.$elem["datosCaso"]["representado"];
      $html .= '<br/><strong>Parte:<strong/> '.$elem["datosCaso"]["parte"];
      $html .= '<br/><strong>Juicio:<strong/> '.$elem["datosCaso"]["juicio"];
      $html .= '<br/><strong>Exp Juzgado:<strong/> '.$elem["datosCaso"]["numExpedienteJuzgado"];
      $html .= '<br/><strong>Materia:<strong/> '.$elem["datosCaso"]["materia"];
      $html .= '<br/><strong>Juzgado:<strong/> '.$elem["datosCaso"]["juzgado"];
      if($elem["datosCaso"]["estatusId"]==1){
        $estatusCaso= "Activo";
        $html .= '<br/><strong>Estatus:<strong/> '.$estatusCaso;
      }else if($elem["datosCaso"]["estatusId"]==2){
        $estatusCaso= "Suspendido";
        $html .= '<br/><strong>Estatus:<strong/> '.$estatusCaso;
      }else if($elem["datosCaso"]["estatusId"]==3){
        $estatusCaso= "Baja";
        $html .= '<br/><strong>Estatus:<strong/> '.$estatusCaso;
      }else if($elem["datosCaso"]["estatusId"]==4){
        $estatusCaso= "Terminado";
        $html .= '<br/><strong>Estatus:<strong/> '.$estatusCaso;
      }


      $html .= '</div>';
       
      
    }

  }
  //exit();
  $arr = array("success"=>true, 
      "html"=>$html
  );

  //print_r($arr);

   echo $callback . '(' . json_encode($arr) . ');';
   

}



function salvarPadreId(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);
  
  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";

  $idCaso = (isset($_GET['idCaso']) && $_GET['idCaso']!="")?$_GET['idCaso']:0;
  $idPadre = (isset($_GET['idPadre']) && $_GET['idPadre']!="")?$_GET['idPadre']:0;
  
  $casosObj = new casosObj();
  $casoPadre = $casosObj->CasoPorId($idPadre);
  $casoHijo = $casosObj->CasoPorId($idCaso);
  $casosHijos = $casosObj->CasosHijos($idCaso);

  //TODO: Borrar padres, debemos tomar en cuenta 
  //si es el unico hijo también quitar del padre el main
  if($casoPadre->idPadreMain == 0){
    //es su primer hijo y el padre es la raiz
    //el caso padre es su propia raiz
    $casosObj->ActCampoCaso("idPadreMain",$casoPadre->idCaso,$casoPadre->idCaso);
    $casosObj->ActCampoCaso("idPadreMain",$casoPadre->idCaso,$casoHijo->idCaso);
    $casosObj->ActCampoCaso("idPadre",$casoPadre->idCaso,$casoHijo->idCaso);
  }else{
    //Ya hay un arbol
    $casosObj->ActCampoCaso("idPadreMain",$casoPadre->idPadreMain,$casoHijo->idCaso);
    $casosObj->ActCampoCaso("idPadre",$casoPadre->idCaso,$casoHijo->idCaso);
    if(count($casosHijos)>0){
      foreach ($casosHijos as $key => $value) {
        $idCaso2=$value->idCaso;
        $casosObj->ActCampoCasoNull("idPadreMain",NULL,$idCaso2);
        $casosObj->ActCampoCasoNull("idPadre",NULL,$idCaso2);
      }
   }
 
  }
  
  $arr = array("success"=>true);

  echo $callback . '(' . json_encode($arr) . ');';
}
//>>>>CMPB, 03/02/2023, cambios para eliminar nodos con hijos o sin hijos
function reasignarPadreId(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);
  
  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";

  $idCaso = (isset($_GET['idCaso']) && $_GET['idCaso']!="")?$_GET['idCaso']:0;
  $idPadre = (isset($_GET['idPadre']) && $_GET['idPadre']!="")?$_GET['idPadre']:0;
  
  $casosObj = new casosObj();
  $casoPadre = $casosObj->CasoPorId($idPadre);
  $casoHijo = $casosObj->CasoPorId($idCaso);
  $casosHijos = $casosObj->CasosHijos($idCaso);
 
  if($casoPadre->idPadre==null || $casoPadre->idPadre==0){
    $entrada=" if";
    $casosObj->ActCampoCaso("idPadreMain",$casoPadre->idCaso,$casoPadre->idCaso);
    $casosObj->ActCampoCaso("idPadreMain",$casoPadre->idCaso,$casoHijo->idCaso);
    $casosObj->ActCampoCaso("idPadre",$casoPadre->idCaso,$casoHijo->idCaso);
    if(count($casosHijos)>0){
      foreach ($casosHijos as $key => $value) {
        $idCaso2=$value->idCaso;
        $casosObj->ActCampoCaso("idPadreMain",$casoPadre->idPadreMain,$idCaso2);
      }
    }
  }else{
    //UPDATE casos SET ?='?' WHERE idCaso=?
    $entrada="else";
    $casosObj->ActCampoCaso("idPadreMain",$casoPadre->idPadreMain,$casoHijo->idCaso);
    $casosObj->ActCampoCaso("idPadre",$casoPadre->idCaso,$casoHijo->idCaso);
    if(count($casosHijos)>0){
      foreach ($casosHijos as $key => $value) {
        $idCaso2=$value->idCaso;
        $casosObj->ActCampoCaso("idPadreMain",$casoPadre->idPadreMain,$idCaso2);
      }
    }
  }
  $arr = array("success"=>true);

  echo $callback . '(' . json_encode($arr) . ');';
}

function eliminarDelPadreId(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);
  
  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";

  $idCaso = (isset($_GET['idCaso']) && $_GET['idCaso']!="")?$_GET['idCaso']:0;
  $idPadre = (isset($_GET['idPadre']) && $_GET['idPadre']!="")?$_GET['idPadre']:0;
  
  $casosObj = new casosObj();
  $casoPadre = $casosObj->CasoPorId($idPadre);
  $casoHijo = $casosObj->CasoPorId($idCaso);
  $casosHijos = $casosObj->CasosHijos($idCaso);

  $array = json_decode($casoPadre->idPadreMain, true);
 
  if($casoPadre->idPadreMain==$casoPadre->idCaso){
    $casosObj->ActCampoCasoNull("idPadreMain",NULL,$casoPadre->idCaso);
  }

  if(count($casosHijos)>0){//para los hijos
   // $array = json_decode($casosHijos, true);
   $casosObj->ActCampoCasoNull("idPadre",NULL,$casoHijo->idCaso);
   $casosObj->ActCampoCasoNull("idPadreMain",NULL,$casoHijo->idCaso);
    
    foreach ($casosHijos as $key => $value) {
      $idCaso2=$value->idCaso;
      $casosObj->ActCampoCasoNull("idPadreMain",NULL,$idCaso2);
      $casosObj->ActCampoCasoNull("idPadre",NULL,$idCaso2);
    }
  }else{
    $casosObj->ActCampoCasoNull("idPadre",NULL,$casoHijo->idCaso);
    $casosObj->ActCampoCasoNull("idPadreMain",NULL,$casoHijo->idCaso);
  }

  $arr = array("success"=>true);

  echo $callback . '(' . json_encode($arr) . ');';
}

function guardarAudio() {
  $tz = obtDateTimeZone();
  unset($_GET['funct']);
  $idCaso = (isset($_POST['idCaso']) && $_POST['idCaso'] != "") ? $_POST['idCaso'] : 0;
  $idUsario = (isset($_POST['idUsario']) && $_POST['idUsario'] != "") ? $_POST['idUsario'] : 0;
  $audioBlob = $_FILES['audio'];
  $descripcion = (isset($_POST['descripcion']) && $_POST['descripcion'] != "") ? $_POST['descripcion'] : 0;
  $audienciaTipo = (isset($_POST['audienciaTipo']) && $_POST['audienciaTipo'] != "") ? $_POST['audienciaTipo'] : 0;
  $callback = (isset($_GET['callback']) && $_GET['callback'] != "") ? $_GET['callback'] : "";
  
  $unico = uniqid();
  $dir = '../upload/audio/caso' . $idCaso;

  if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
  }

  move_uploaded_file($audioBlob['tmp_name'], $dir . '/' . $unico . $audioBlob['name']);

  $url = '../upload/audio/caso' . $idCaso . '/' . $unico . $audioBlob['name'];

  $audiosObj = new audiosObj();
  $audiosObj->idUsuario = $idUsario;
  $audiosObj->idCaso = $idCaso;
  $audiosObj->url = $url;
  $audiosObj->descripcion = $descripcion;
  $audiosObj->audienciaTipo = $audienciaTipo;
  $audiosObj->fechaCreacion = $tz->fechaHora;

  $audiosObj->CrearAudio();

  $arr = array("success" => true);
  if ($callback != '') {
    echo $callback . '(' . json_encode($arr) . ');';
  } else {
    echo json_encode($arr);
  }
}

function eliminarNota()
{
  unset($_GET['funct']);
  $callback = (isset($_GET['callback']) && $_GET['callback'] != "") ? $_GET['callback'] : "";
  $audiosObj = new audiosObj();
  $idNota = (isset($_GET['idNota']) && $_GET['idNota'] != "") ? $_GET['idNota'] : 0;
  $archivo = (isset($_GET['url']) && $_GET['url'] != "") ? $_GET['url'] : 0;
  if (unlink($archivo)) {
    $audiosObj->idNota = $idNota;
    $audiosObj->EliminarNotaObj($idNota);
    $arr = array("success" => true);
  } else {
    $arr = array("success" => false);
  }
  
  echo $callback . '(' . json_encode($arr) . ');';
}

function eliminarCobro(){
  $tz = obtDateTimeZone();
  $arr = array("success"=>false);
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  $id = (isset($_GET['id']) && $_GET['id']!="")?$_GET['id']:0;
  $idCuenta = (isset($_GET['idCuenta']) && $_GET['idCuenta']!="")?$_GET['idCuenta']:0;
  $planPagos = (isset($_GET['planPagos']) && $_GET['planPagos']!="")?$_GET['planPagos']:0;
  $cuentasObj = new cuentasObj();
 
  switch ($planPagos) {
	  case "2":
      $infoCuente = $cuentasObj->CuentaPorId($idCuenta);
      $numeroMeses =$infoCuente->numMeses-1;
      $cuentasObj->EditarCuentaMeses($idCuenta, $numeroMeses);
      
      break;
    default:
  }

  $resp = $cuentasObj->EliminarCobros($id, $idCuenta);

  if($resp){
    $arr = array("success"=>true);
  }

  echo $callback . '(' . json_encode($arr) . ');';
}

function cambiarTipo2a1(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $arr = array("success"=>false);

  $idCuenta = (isset($_POST['idCuenta']) && $_POST['idCuenta']!="")?$_POST['idCuenta']:0;
  $casoId = (isset($_POST['casoId']) && $_POST['casoId']!="")?$_POST['casoId']:0;
  $clienteId = (isset($_POST['clienteId']) && $_POST['clienteId']!="")?$_POST['clienteId']:0;
  $tipoCobro = (isset($_POST['tipoCobro']) && $_POST['tipoCobro']!="")?$_POST['tipoCobro']:0;
  $planPagos = (isset($_POST['planPagos']) && $_POST['planPagos']!="")?$_POST['planPagos']:0;
  $cobrosJson = (isset($_POST['cobrosJson']) && $_POST['cobrosJson']!="")?$_POST['cobrosJson']:0;
  $totalJson = (isset($_POST['totalJson']) && $_POST['totalJson']!="")?$_POST['totalJson']:0;
  $monto = (isset($_POST['monto']) && $_POST['monto']!="")?removerCaracteres($_POST['monto']):0;
  $comentarios = (isset($_POST['comentarios']) && $_POST['comentarios']!="")?$_POST['comentarios']:'';

  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  
  $casosObj = new casosObj();
  $cuentasObj = new cuentasObj();
  
  $arrUpd = array();
  $arrIdsReg = array();
  $cuenta = $cuentasObj->CuentaPorId($idCuenta);
  

  $resPlan = $cuentasObj->ActCampoCuenta("planPagos", $planPagos, $idCuenta);
  $resMonto = $cuentasObj->ActCampoCuenta("monto", $totalJson, $idCuenta);
  $resComentarios = $cuentasObj->ActCampoCuenta("comentarios", $comentarios, $idCuenta);
  $resAvance = $cuentasObj->ActCampoCuenta("avance", 0, $idCuenta);
  $resMontoAux = $cuentasObj->ActCampoCuenta("montoAux", 0, $idCuenta);
  $resNumMeses = $cuentasObj->ActCampoCuenta("numMeses", 0, $idCuenta);
  $resDiaCobro = $cuentasObj->ActCampoCuenta("diaCobro", 0, $idCuenta);
  $resTipoCobro = $cuentasObj->ActCampoCuenta("tipoCobro", $tipoCobro, $idCuenta);
  $rescobrosJson = $cuentasObj->ActCampoCuentaJson("cobrosJson", $cobrosJson, $idCuenta);


  $arr = array("success"=>true, "id"=>$cuentasObj->idCuenta, "arrIdsReg"=>$arrIdsReg, "arrUpd"=>$arrUpd, "casoId"=>$casoId);
  echo json_encode($arr);
  
}

function cambiarTipo2a3(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $arr = array("success"=>false);

  $idCuenta = (isset($_POST['idCuenta']) && $_POST['idCuenta']!="")?$_POST['idCuenta']:0;
  $casoId = (isset($_POST['casoId']) && $_POST['casoId']!="")?$_POST['casoId']:0;
  $tipoCobro = (isset($_POST['tipoCobro']) && $_POST['tipoCobro']!="")?$_POST['tipoCobro']:0;
  $planPagos = (isset($_POST['planPagos']) && $_POST['planPagos']!="")?$_POST['planPagos']:0;
  $cobrosJson = (isset($_POST['cobrosJson']) && $_POST['cobrosJson']!="")?$_POST['cobrosJson']:0;
  $totalJson = (isset($_POST['totalJson']) && $_POST['totalJson']!="")?$_POST['totalJson']:0;
  $monto = (isset($_POST['monto']) && $_POST['monto']!="")?($_POST['monto']):0;
  $comentarios = (isset($_POST['comentarios']) && $_POST['comentarios']!="")?$_POST['comentarios']:'';

  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  
  $casosObj = new casosObj();
  $cuentasObj = new cuentasObj();

  $arrUpd = array();
  $arrIdsReg = array();
  $cuenta = $cuentasObj->CuentaPorId($idCuenta);
  $cobroJson = $cuenta->cobrosJson;

  $data = json_decode($cobroJson, true);
  $firstElement = $data[0];
  $firstElement['cobro'] = $totalJson;
  $firstElement['modoCobro'] = 3;
  $newJsonData = json_encode([$firstElement]);

  $resPlan = $cuentasObj->ActCampoCuenta("planPagos", $planPagos, $idCuenta);
  $resMonto = $cuentasObj->ActCampoCuenta("monto", $totalJson, $idCuenta);
  $resComentarios = $cuentasObj->ActCampoCuenta("comentarios", $comentarios, $idCuenta);
  $resAvance = $cuentasObj->ActCampoCuenta("avance", 0, $idCuenta);
  $resMontoAux = $cuentasObj->ActCampoCuenta("montoAux", 0, $idCuenta);
  $resNumMeses = $cuentasObj->ActCampoCuenta("numMeses", 0, $idCuenta);
  $resDiaCobro = $cuentasObj->ActCampoCuenta("diaCobro", 0, $idCuenta);
  $resTipoCobro = $cuentasObj->ActCampoCuenta("tipoCobro", $tipoCobro, $idCuenta);
  $rescobrosJson= $cuentasObj->ActCampoCuenta("cobrosJson", $newJsonData, $idCuenta);

  $arr = array("success"=>true, "id"=>$cuentasObj->idCuenta, "arrIdsReg"=>$arrIdsReg, "arrUpd"=>$arrUpd, "casoId"=>$casoId);
  echo json_encode($arr);
 
}

function cambiarTipo2a4(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $arr = array("success"=>false);

  $idCuenta = (isset($_POST['idCuenta']) && $_POST['idCuenta']!="")?$_POST['idCuenta']:0;
  $casoId = (isset($_POST['casoId']) && $_POST['casoId']!="")?$_POST['casoId']:0;
  $tipoCobro = (isset($_POST['tipoCobro']) && $_POST['tipoCobro']!="")?$_POST['tipoCobro']:0;
  $planPagos = (isset($_POST['planPagos']) && $_POST['planPagos']!="")?$_POST['planPagos']:0;
  $cobrosJson = (isset($_POST['cobrosJson']) && $_POST['cobrosJson']!="")?$_POST['cobrosJson']:0;
  $totalJson = (isset($_POST['totalJson']) && $_POST['totalJson']!="")?$_POST['totalJson']:0;
  $monto = (isset($_POST['monto']) && $_POST['monto']!="")?($_POST['monto']):0;
  $comentarios = (isset($_POST['comentarios']) && $_POST['comentarios']!="")?$_POST['comentarios']:'';

  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  
  $casosObj = new casosObj();
  $cuentasObj = new cuentasObj();

  $arrUpd = array();
  $arrIdsReg = array();
  $cuenta = $cuentasObj->CuentaPorId($idCuenta);
  $cobroJson = null;

  $resPlan = $cuentasObj->ActCampoCuenta("planPagos", $planPagos, $idCuenta);
  $resMonto = $cuentasObj->ActCampoCuenta("monto", $totalJson, $idCuenta);
  $resSaldo = $cuentasObj->ActCampoCuenta("saldo", $totalJson, $idCuenta);
  $resComentarios = $cuentasObj->ActCampoCuenta("comentarios", $comentarios, $idCuenta);
  $resAvance = $cuentasObj->ActCampoCuenta("avance", 0, $idCuenta);
  $resMontoAux = $cuentasObj->ActCampoCuenta("montoAux", 0, $idCuenta);
  $resNumMeses = $cuentasObj->ActCampoCuenta("numMeses", 0, $idCuenta);
  $resDiaCobro = $cuentasObj->ActCampoCuenta("diaCobro", 0, $idCuenta);
  $resTipoCobro = $cuentasObj->ActCampoCuenta("tipoCobro", $tipoCobro, $idCuenta);
  $rescobrosJson= $cuentasObj->ActCampoCuentaJson("cobrosJson", $cobroJson, $idCuenta);

  $arr = array("success"=>true, "id"=>$cuentasObj->idCuenta, "arrIdsReg"=>$arrIdsReg, "arrUpd"=>$arrUpd, "casoId"=>$casoId);
  echo json_encode($arr);
 
}

function cambiarTipo3a1(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $arr = array("success"=>false);

  $idCuenta = (isset($_POST['idCuenta']) && $_POST['idCuenta']!="")?$_POST['idCuenta']:0;
  $casoId = (isset($_POST['casoId']) && $_POST['casoId']!="")?$_POST['casoId']:0;
  $tipoCobro = (isset($_POST['tipoCobro']) && $_POST['tipoCobro']!="")?$_POST['tipoCobro']:0;
  $planPagos = (isset($_POST['planPagos']) && $_POST['planPagos']!="")?$_POST['planPagos']:0;
  $cobrosJson = (isset($_POST['cobrosJson']) && $_POST['cobrosJson']!="")?$_POST['cobrosJson']:0;
  //$totalJson = (isset($_POST['totalJson']) && $_POST['totalJson']!="")?$_POST['totalJson']:0;
  $totalJson = (isset($_POST['totalJson']) && $_POST['totalJson'] != "") ? floatval($_POST['totalJson']) : 0;

  $monto = (isset($_POST['monto']) && $_POST['monto']!="")?($_POST['monto']):0;
  $montoFijo = floatval(str_replace("$", "", $monto));

  $comentarios = (isset($_POST['comentarios']) && $_POST['comentarios']!="")?$_POST['comentarios']:'';

  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  
  $casosObj = new casosObj();
  $cuentasObj = new cuentasObj();

  $arrUpd = array();
  $arrIdsReg = array();
  $cobroJson = null;

  $MontoSaldo =  $totalJson + $montoFijo;

  $resPlan = $cuentasObj->ActCampoCuenta("planPagos", 1, $idCuenta);
  $resMonto = $cuentasObj->ActCampoCuenta("monto", $MontoSaldo, $idCuenta);
  $resSaldo = $cuentasObj->ActCampoCuenta("saldo", $MontoSaldo, $idCuenta);
  $resComentarios = $cuentasObj->ActCampoCuenta("comentarios", $comentarios, $idCuenta);
  $resMontoAux = $cuentasObj->ActCampoCuenta("montoAux", 0, $idCuenta);
  $rescobrosJson= $cuentasObj->ActCampoCuentaJson("cobrosJson", $cobroJson, $idCuenta);

  $arr = array("success"=>true, "id"=>$cuentasObj->idCuenta, "arrIdsReg"=>$arrIdsReg, "arrUpd"=>$arrUpd, "casoId"=>$casoId);
  echo json_encode($arr);
 
}

function cambiarTipo3a2(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $arr = array("success"=>false);

  $idCuenta = (isset($_POST['idCuenta']) && $_POST['idCuenta']!="")?$_POST['idCuenta']:0;
  $casoId = (isset($_POST['casoId']) && $_POST['casoId']!="")?$_POST['casoId']:0;
  $tipoCobro = (isset($_POST['tipoCobro']) && $_POST['tipoCobro']!="")?$_POST['tipoCobro']:0;
  $totalJson = (isset($_POST['totalJson']) && $_POST['totalJson'] != "") ? floatval($_POST['totalJson']) : 0;
  $monto = (isset($_POST['monto']) && $_POST['monto']!="")?($_POST['monto']):0;
  $comentarios = (isset($_POST['comentarios']) && $_POST['comentarios']!="")?$_POST['comentarios']:'';
  $montoAux = (isset($_POST['montoAux']) && $_POST['montoAux']!="")?removerCaracteres($_POST['montoAux']):0;
  $numMeses = (isset($_POST['numMeses']) && $_POST['numMeses']!="")?$_POST['numMeses']:0;
  $diaCobro = (isset($_POST['diaCobro']) && $_POST['diaCobro']!="")?$_POST['diaCobro']:0;
  
  $montoFijo = floatval(str_replace("$", "", $monto));
  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  
  $casosObj = new casosObj();
  $cuentasObj = new cuentasObj();

  $arrUpd = array();
  $arrIdsReg = array();

  $MontoSaldo =  $totalJson + $montoFijo;

  $resPlan = $cuentasObj->ActCampoCuenta("planPagos", 2, $idCuenta);
  $resMonto = $cuentasObj->ActCampoCuenta("monto", $MontoSaldo, $idCuenta);
  $resSaldo = $cuentasObj->ActCampoCuenta("saldo", $MontoSaldo, $idCuenta);
  $resComentarios = $cuentasObj->ActCampoCuenta("comentarios", $comentarios, $idCuenta);
  $rescobrosJson= $cuentasObj->ActCampoCuentaJson("cobrosJson", null, $idCuenta);
  $resMontoAux = $cuentasObj->ActCampoCuenta("montoAux", $montoAux, $idCuenta);
  $resNumMeses = $cuentasObj->ActCampoCuenta("numMeses", $numMeses, $idCuenta);
  $resDiaCobro = $cuentasObj->ActCampoCuenta("diaCobro", $diaCobro, $idCuenta);
  $resTipoCobro = $cuentasObj->ActCampoCuenta("tipoCobro", $tipoCobro, $idCuenta);
  
  $arr = array("success"=>true, "id"=>$cuentasObj->idCuenta, "arrIdsReg"=>$arrIdsReg, "arrUpd"=>$arrUpd, "casoId"=>$casoId);
  echo json_encode($arr);
}

function cambiarTipo3a4(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $arr = array("success"=>false);

  $idCuenta = (isset($_POST['idCuenta']) && $_POST['idCuenta']!="")?$_POST['idCuenta']:0;
  $casoId = (isset($_POST['casoId']) && $_POST['casoId']!="")?$_POST['casoId']:0;
  $tipoCobro = (isset($_POST['tipoCobro']) && $_POST['tipoCobro']!="")?$_POST['tipoCobro']:0;
  $planPagos = (isset($_POST['planPagos']) && $_POST['planPagos']!="")?$_POST['planPagos']:0;
  $cobrosJson = (isset($_POST['cobrosJson']) && $_POST['cobrosJson']!="")?$_POST['cobrosJson']:0;
  //$totalJson = (isset($_POST['totalJson']) && $_POST['totalJson']!="")?$_POST['totalJson']:0;
  $totalJson = (isset($_POST['totalJson']) && $_POST['totalJson'] != "") ? floatval($_POST['totalJson']) : 0;

  $monto = (isset($_POST['monto']) && $_POST['monto']!="")?($_POST['monto']):0;
  $montoFijo = floatval(str_replace("$", "", $monto));

  $comentarios = (isset($_POST['comentarios']) && $_POST['comentarios']!="")?$_POST['comentarios']:'';

  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  
  $casosObj = new casosObj();
  $cuentasObj = new cuentasObj();

  $arrUpd = array();
  $arrIdsReg = array();
  $cobroJson = null;

  $MontoSaldo =  $totalJson + $montoFijo;

  $resPlan = $cuentasObj->ActCampoCuenta("planPagos", 4, $idCuenta);
  $resMonto = $cuentasObj->ActCampoCuenta("monto", $MontoSaldo, $idCuenta);
  $resSaldo = $cuentasObj->ActCampoCuenta("saldo", $MontoSaldo, $idCuenta);
  $resComentarios = $cuentasObj->ActCampoCuenta("comentarios", $comentarios, $idCuenta);
  $resMontoAux = $cuentasObj->ActCampoCuenta("montoAux", 0, $idCuenta);
  $rescobrosJson= $cuentasObj->ActCampoCuentaJson("cobrosJson", $cobroJson, $idCuenta);

  $arr = array("success"=>true, "id"=>$cuentasObj->idCuenta, "arrIdsReg"=>$arrIdsReg, "arrUpd"=>$arrUpd, "casoId"=>$casoId);
  echo json_encode($arr);
 
}

function cambiarTipo4a1(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $arr = array("success"=>false);

  $idCuenta = (isset($_POST['idCuenta']) && $_POST['idCuenta']!="")?$_POST['idCuenta']:0;
  $casoId = (isset($_POST['casoId']) && $_POST['casoId']!="")?$_POST['casoId']:0;
  $tipoCobro = (isset($_POST['tipoCobro']) && $_POST['tipoCobro']!="")?$_POST['tipoCobro']:0;
  $planPagos = (isset($_POST['planPagos']) && $_POST['planPagos']!="")?$_POST['planPagos']:0;
  $cobrosJson = (isset($_POST['cobrosJson']) && $_POST['cobrosJson']!="")?$_POST['cobrosJson']:0;
  //$totalJson = (isset($_POST['totalJson']) && $_POST['totalJson']!="")?$_POST['totalJson']:0;
  $totalJson = (isset($_POST['totalJson']) && $_POST['totalJson'] != "") ? floatval($_POST['totalJson']) : 0;

  $monto = (isset($_POST['monto']) && $_POST['monto']!="")?($_POST['monto']):0;
  $montoFijo = floatval(str_replace("$", "", $monto));

  $comentarios = (isset($_POST['comentarios']) && $_POST['comentarios']!="")?$_POST['comentarios']:'';

  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  
  $casosObj = new casosObj();
  $cuentasObj = new cuentasObj();

  $arrUpd = array();
  $arrIdsReg = array();
  $cobroJson = null;

  $MontoSaldo =  $totalJson + $montoFijo;

  $resPlan = $cuentasObj->ActCampoCuenta("planPagos", 1, $idCuenta);
  $resMonto = $cuentasObj->ActCampoCuenta("monto", $MontoSaldo, $idCuenta);
  $resSaldo = $cuentasObj->ActCampoCuenta("saldo", $MontoSaldo, $idCuenta);
  $resComentarios = $cuentasObj->ActCampoCuenta("comentarios", $comentarios, $idCuenta);
  $resMontoAux = $cuentasObj->ActCampoCuenta("montoAux", 0, $idCuenta);
  $rescobrosJson= $cuentasObj->ActCampoCuentaJson("cobrosJson", $cobroJson, $idCuenta);

  $arr = array("success"=>true, "id"=>$cuentasObj->idCuenta, "arrIdsReg"=>$arrIdsReg, "arrUpd"=>$arrUpd, "casoId"=>$casoId);
  echo json_encode($arr);
}

function cambiarTipo4a2(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $arr = array("success"=>false);

  $idCuenta = (isset($_POST['idCuenta']) && $_POST['idCuenta']!="")?$_POST['idCuenta']:0;
  $casoId = (isset($_POST['casoId']) && $_POST['casoId']!="")?$_POST['casoId']:0;
  $tipoCobro = (isset($_POST['tipoCobro']) && $_POST['tipoCobro']!="")?$_POST['tipoCobro']:0;
  $planPagos = (isset($_POST['planPagos']) && $_POST['planPagos']!="")?$_POST['planPagos']:0;
  $cobrosJson = (isset($_POST['cobrosJson']) && $_POST['cobrosJson']!="")?$_POST['cobrosJson']:0;
  //$totalJson = (isset($_POST['totalJson']) && $_POST['totalJson']!="")?$_POST['totalJson']:0;
  $totalJson = (isset($_POST['totalJson']) && $_POST['totalJson'] != "") ? floatval($_POST['totalJson']) : 0;
  $montoAux = (isset($_POST['montoAux']) && $_POST['montoAux']!="")?removerCaracteres($_POST['montoAux']):0;
  $numMeses = (isset($_POST['numMeses']) && $_POST['numMeses']!="")?$_POST['numMeses']:0;
  $diaCobro = (isset($_POST['diaCobro']) && $_POST['diaCobro']!="")?$_POST['diaCobro']:0;

  $monto = (isset($_POST['monto']) && $_POST['monto']!="")?($_POST['monto']):0;
  $montoFijo = floatval(str_replace("$", "", $monto));

  $comentarios = (isset($_POST['comentarios']) && $_POST['comentarios']!="")?$_POST['comentarios']:'';

  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  
  $casosObj = new casosObj();
  $cuentasObj = new cuentasObj();

  $arrUpd = array();
  $arrIdsReg = array();
  $cobroJson = null;

  $MontoSaldo =  $totalJson + $montoFijo;

  $resPlan = $cuentasObj->ActCampoCuenta("planPagos", 2, $idCuenta);
  $resMonto = $cuentasObj->ActCampoCuenta("monto", $MontoSaldo, $idCuenta);
  $resSaldo = $cuentasObj->ActCampoCuenta("saldo", $MontoSaldo, $idCuenta);
  $resComentarios = $cuentasObj->ActCampoCuenta("comentarios", $comentarios, $idCuenta);
  $resMontoAux = $cuentasObj->ActCampoCuenta("montoAux", 0, $idCuenta);
  $rescobrosJson= $cuentasObj->ActCampoCuentaJson("cobrosJson", $cobroJson, $idCuenta);
  $resMontoAux = $cuentasObj->ActCampoCuenta("montoAux", $montoAux, $idCuenta);
  $resNumMeses = $cuentasObj->ActCampoCuenta("numMeses", $numMeses, $idCuenta);
  $resDiaCobro = $cuentasObj->ActCampoCuenta("diaCobro", $diaCobro, $idCuenta);
  $resTipoCobro = $cuentasObj->ActCampoCuenta("tipoCobro", $tipoCobro, $idCuenta);

  $arr = array("success"=>true, "id"=>$cuentasObj->idCuenta, "arrIdsReg"=>$arrIdsReg, "arrUpd"=>$arrUpd, "casoId"=>$casoId);
  echo json_encode($arr);
}

function cambiarTipo4a3(){
  $tz = obtDateTimeZone();
  unset($_GET['funct']); //remover el nombre de la funcion para evitar errores
  base64DecodeSubmit(0, $_GET);

  $arr = array("success"=>false);

  $idCuenta = (isset($_POST['idCuenta']) && $_POST['idCuenta']!="")?$_POST['idCuenta']:0;
  $casoId = (isset($_POST['casoId']) && $_POST['casoId']!="")?$_POST['casoId']:0;
  $tipoCobro = (isset($_POST['tipoCobro']) && $_POST['tipoCobro']!="")?$_POST['tipoCobro']:0;
  $planPagos = (isset($_POST['planPagos']) && $_POST['planPagos']!="")?$_POST['planPagos']:0;
  $cobrosJson = (isset($_POST['cobrosJson']) && $_POST['cobrosJson']!="")?$_POST['cobrosJson']:0;
  //$totalJson = (isset($_POST['totalJson']) && $_POST['totalJson']!="")?$_POST['totalJson']:0;
  $totalJson = (isset($_POST['totalJson']) && $_POST['totalJson'] != "") ? floatval($_POST['totalJson']) : 0;

  $monto = (isset($_POST['monto']) && $_POST['monto']!="")?($_POST['monto']):0;
  $montoFijo = floatval(str_replace("$", "", $monto));

  $comentarios = (isset($_POST['comentarios']) && $_POST['comentarios']!="")?$_POST['comentarios']:'';

  $callback = (isset($_GET['callback']) && $_GET['callback']!="")?$_GET['callback']:"";
  
  $casosObj = new casosObj();
  $cuentasObj = new cuentasObj();

  $arrUpd = array();
  $arrIdsReg = array();
  $cobroJson = null;

  $MontoSaldo =  $totalJson + $montoFijo;

  $resPlan = $cuentasObj->ActCampoCuenta("planPagos", 3, $idCuenta);
  $resMonto = $cuentasObj->ActCampoCuenta("monto", $MontoSaldo, $idCuenta);
  $resSaldo = $cuentasObj->ActCampoCuenta("saldo", $MontoSaldo, $idCuenta);
  $resComentarios = $cuentasObj->ActCampoCuenta("comentarios", $comentarios, $idCuenta);
  $resMontoAux = $cuentasObj->ActCampoCuenta("montoAux", 0, $idCuenta);
  $rescobrosJson= $cuentasObj->ActCampoCuentaJson("cobrosJson", $cobroJson, $idCuenta);

  $arr = array("success"=>true, "id"=>$cuentasObj->idCuenta, "arrIdsReg"=>$arrIdsReg, "arrUpd"=>$arrUpd, "casoId"=>$casoId);
  echo json_encode($arr);
}