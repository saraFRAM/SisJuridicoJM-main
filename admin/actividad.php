<?php
session_start();
$idRol = $_SESSION['idRol'];
switch ($idRol) {
    case 1: $rol = true; break;
	case 2: $rol = true; break;
    case 4: $rol = true; break;
    default: $rol = false; break;
}
if($_SESSION['status']!= "ok" || $rol!=true)
        header("location: logout.php");

        $dirname = dirname(__DIR__);
include_once '../common/screenPortions.php';
include_once '../brules/KoolControls/KoolGrid/koolgrid.php';
require_once '../brules/KoolControls/KoolAjax/koolajax.php';
require_once '../brules/KoolControls/KoolCalendar/koolcalendar.php';
include_once '../brules/KoolControls/KoolGrid/ext/datasources/MySQLiDataSource.php';
$localization = "../brules/KoolControls/KoolCalendar/localization/es.xml";
include_once $dirname.'/brules/casosObj.php';
include_once $dirname.'/brules/casoAccionesObj.php';
include_once $dirname.'/brules/usuariosObj.php';
include_once $dirname.'/brules/utilsObj.php';

# Load Fakers own autoloader
require_once '../libs/faker/autoload.php';

$casosObj = new casosObj();
$casoAccionesObj = new casoAccionesObj();
$usuariosObj = new usuariosObj();

$dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
$dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
$fechaHoy = $dateByZone->format('d/m/Y'); //fecha Actual
$year = $dateByZone->format('Y');

//establecer la zona horaria
$tz = obtDateTimeZone();

$faker = Faker\Factory::create();


$expId = (isset($_GET['expId']))?$_GET['expId']:0;//echo $expId;
$actId = (isset($_GET['actId']))?$_GET['actId']:0;

$notificacionId = (isset($_GET['notificacionId']))?$_GET['notificacionId']:0;// Jair 17/2/2022 Saber si se viene de una notificacion
$idRegistro = (isset($_GET['idRegistro']))?$_GET['idRegistro']:0;

$datosCaso = $casosObj->CasoPorId($expId);
$dtDetCaso = $casosObj->ObtCasoInfoPorId($expId);
$actividad = $casoAccionesObj->CasoAccionesPorId($actId);
$usuarioId = ($actId > 0)?$actividad->usuarioId:$_SESSION["idUsuario"];
$comentarios = $casoAccionesObj->ObtCasoAcciones($expId, $actId);
$usuario = $usuariosObj->UserByID($actividad->usuarioId);

//Campo responsable de actividad, agregar los ids de responsable y titular del expediente
//Tambien agregamos autorizados
$arrIdsAutorizados = preg_split("/[,]+/", $datosCaso->autorizadosIds,NULL,PREG_SPLIT_NO_EMPTY);
$arrIds = array($datosCaso->responsableId, $datosCaso->titularId2);

$arrIds = array_merge($arrIds,$arrIdsAutorizados); 

$arrResponsable = array();
$arrResponsable[] = array("valor"=>"", "texto"=>"Seleccione...");

foreach ($arrIds as $idItem) {
    $userItem = $usuariosObj->UserByID($idItem);
    $arrResponsable[] = array("valor"=>$idItem, "texto"=>$userItem->numAbogado." - ".$userItem->nombre);    
}

$responsableId = ($actividad->responsableId > 0)?$actividad->responsableId:$datosCaso->responsableId;//JGP por default muestro el nombre del abogado responsable
$claseResponsable = ($_SESSION["idRol"] == 4)?"likeDisaled":"";// 20/1/2022 Jair Solo lectura para abogados

$arrCampoResponsable = array(
    array("nameid"=>"responsableId", "type"=>"select", "class"=>"form-control required ".$claseResponsable, "readonly"=>false, "label"=>"Responsable:", "datos"=>$arrResponsable, "value"=>$responsableId),
);

// 4/2/2022 Campo Avanzo
$arrAvanzo = array();
$arrAvanzo[] = array("valor"=>"", "texto"=>"Seleccione...");
$arrAvanzo[] = array("valor"=>"0", "texto"=>"No");
$arrAvanzo[] = array("valor"=>"1", "texto"=>"Si");
$avanzo = ($actividad->idAccion > 0)?$actividad->avanzo:"1";
$ClaseRowAvanzo = ($actividad->estatusId == 4)?'':'oculto';
$arrCampoAvanzo = array(
    array("nameid"=>"avanzo", "type"=>"select", "class"=>"form-control required", "readonly"=>false, "label"=>"Avanz&oacute;:", "datos"=>$arrAvanzo, "value"=>$avanzo, "claseRow"=>"rowAvanzo ".$ClaseRowAvanzo),
);

if($actividad->idAccion==null){
  $isHidden='hidden';
}else{
  $isHidden='';
}

/*echo '<pre>';
print_r($actividad);
echo '</pre>';*/
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Actividad</title>
    <?php echo estilosPagina(true); ?>
    <?php echo scriptsPagina(true); ?>
</head>
<body>
    <?php echo getHeaderMain($_SESSION['myusername'], true);?>
    <?php $menu = getAdminMenu(); ?>

    <input type="hidden" name="vista" id="vista" value="inicio">
    <input type="hidden" name="notificacionId" id="notificacionId" value="<?php echo $notificacionId ?>">
    <input type="hidden" name="idRegistro" id="idRegistro" value="<?php echo $idRegistro ?>">

    <section class="section-internas">
        <div class="panel-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="colmenu col-md-2 menu_bg">
                        <?php echo getNav($menu); ?>
                    </div>
                    <div class="col-md-10" style="<?php echo ($datosCaso->estatusId == 2)?'background-color: #fa0606':''; ?>">
                        <h1 class="titulo">Actividad <span class="pull-right"><a id="btnAyudaweb" onclick="mostrarAyuda('web_inicio')" href="#fancyAyudaWeb"><img src="../images/icon_ayuda.png" width="20px"></a></span></h1>  


                        <ol class="breadcrumb">
                          <li><a href="index.php">Inicio</a></li>
                          <li><a href="frmExpedienteEdit.php?id=<?php echo $expId; ?>">Expediente</a></li>
                          <li class="active">Actividad</li>
                        </ol>
                        <!-- CMPB 03/03/23-->
                        <!--<h2>Expediente interno: <?php echo $datosCaso->numExpediente; ?></h2>-->
                        <h2>Num Exp Juzg: <?php echo $datosCaso->numExpedienteJuzgado; ?></h2>
                        

                        <div class="row" id="datos_expdiente">
                            <div class="col-xs-10">
                            <span><b>Exp Interno:</b> <?php echo $datosCaso->numExpediente; ?></span><br/>
                            <span><b>ID Exp:</b> <?php echo $datosCaso->idCaso; ?></span><br/>
                            <span><b>ID Actividad:</b> <?php echo $actId; ?></span><br/>
                            <!--<span><b>Num Exp Juzg:</b> <?php echo $datosCaso->numExpedienteJuzgado; ?></span><br/>-->
                            <span><b>Cliente:</b> <?php echo $dtDetCaso[0]->cliente; ?></span><br/>
                            <span><b>Materia:</b> <?php echo $dtDetCaso[0]->nombreMateria; ?></span><br/>
                            <span><b>Distrito:</b> <?php echo $dtDetCaso[0]->nombreDistrito; ?></span><br/>
                            <span><b>Juzgado:</b> <?php echo $dtDetCaso[0]->nombreJuzgado; ?></span><br/>
                            <p><br/><a class="btn btn-primary" role="button" target="_blank" href="frmExpedienteEdit.php?id=<?php echo $datosCaso->idCaso; ?>" title="Ver detalle expediente">Ver Expediente</a></p>
                            </div>
                        </div>

                        <div class="row button-fixed">
                          <div class="row">
                              <div class="col-xs-1">
                                <a class="btn btn-primary" onclick="window.close()">Cerrar</a>
                              </div>
                          </div><br>
                          <div class="row">
                              <div class="col-xs-1">
                                <a class="btn btn-primary" id="btnCrearTipo" onclick="btnCreaEditaAccion();">Aceptar</a>
                              </div>
                            </div><br>
                          <div class="row">
                              <div class="col-xs-1">
                                <a class="btn btn-primary" id="btnCrearTipoRelod" onclick="btnCreaEditaAccionRelod();">Guardar y nuevo</a> <!--LDAH 16/08/2022 IMP Boton guardar y nuevo -->
                              </div>
                          </div>
                        
                        </div>

                        <div class="row">
                            <div class="col-xs-10"></div>
                            <div class="col-xs-2">
                                <?php if($actId > 0){ ?>
                              <input type="checkbox" name="checkinterno" id="checkinterno" onchange="revisaCheckInternos()">
                              <label for="checkinterno">Ver internos</label>
                              <?php } ?>
                            </div>
                        </div>
                            <!--LDAH 16/08/2022 IMP Boton guardar y nuevo -->
                        <!-- <div class="row"> 
                            <div class="alert alert-warning divInfoInterno" style="visibility: hidden;">
                            <span class="material-icons">new_releases</span> Los comentarios internos est&aacute;n activados
                            </div>
                        </div> -->

                        

                        
                        <form role="form" id="formCrearAccion" name="formCrearAccion" method="post" action="" enctype="multipart/form-data">
                            <input type="hidden" name="pa_casoid" id="pa_casoid" value="<?php echo $expId;?>">
                            <input type="hidden" name="pa_idaccion" id="pa_idaccion" value="<?php echo $actId;?>">
                            <input type="hidden" name="pa_usuarioId" id="pa_usuarioId" value="<?php echo $usuarioId;?>">
                            <input type="hidden" name="pa_usuarioIdSesion" id="pa_usuarioIdSesion" value="<?php echo $_SESSION["idUsuario"];?>">
                            <input type="hidden" name="abrirotra" id="abrirotra" value="0">
                            
                    <br>
                    <div class="">

                    <!-- <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="panel panel-warning">
                                <div class="panel-heading"></div>
                                    <div class="panel-body"><br>
                                    
                                    </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <div class="panel panel-warning">
                                <div class="panel-heading"></div>
                                    <div class="panel-body"><br>
                                    </div>
                            </div>
                        </div>
                    </div> -->
                    
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="panel panel-warning">
                                <div class="panel-heading"></div>
                                    <div class="panel-body"><br>
                                    <!-- lDAH IMP 22/08/2022 -->
                                    <div class="row">
                                        <div class="col-md-4 text-right">
                                            <label>Actividad:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input class="form-control " type="text" name="pa_idaccion" id="pa_idaccion" value="<?php echo $actId;?>" style="width:90%;display:inline-block;" readonly>
                                        </div>
                                    </div>
                                    <!--  --------   --------  -->
                                    <div class="row">
                                        <div class="col-md-4 text-right">
                                            <label>Usuario:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input class="form-control " type="text" name="pa_usuario" id="pa_usuario" value="<?php echo ($actId == 0)?$_SESSION["myusername"]:$usuario->numAbogado." - ".$usuario->nombre;?>" style="width:90%;display:inline-block;" readonly>
                                        </div>
                                    </div>
                                    <?php 
                                        $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                                        echo generaHtmlForm($arrCampoResponsable, $cols); 
                                    ?>
                                    <div class="row">
                                        <div class="col-md-4 text-right">
                                            <label>Fecha creaci&oacute;n:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input class="form-control required" type="text" name="pa_fechaaccion" id="pa_fechaaccion" value="<?php echo ($actId == 0)?$tz->fechaF2:convertirFechaVista($actividad->fechaAlta);?>" style="width:90%;display:inline-block;" readonly>
                                        </div>
                                    </div>

                                    <?php
                                        $claseCompromiso = 'inputfechaGralHora';
                                        $styleCompromiso = 'width:90%;display:inline-block;';

                                        $claseRealizado = 'inputfechaGralHora';
                                        $stylRealizado = 'width:90%;display:inline-block;';
                                    
                                       if($actividad->estatusId == 4){ 
                                            $claseCompromiso = '';
                                            //$styleCompromiso = '';
    
                                            $claseRealizado = '';
                                            //$stylRealizado = '';
                                        }
                                    ?>

                                    <div class="row">
                                    <!-- <div class="row rowFechaCompromiso" style="display: <?php echo ($actId == 0)?'none':'block'; ?>;"> -->
                                        <div class="col-md-4 text-right">
                                            <label>Fecha compromiso:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input class="form-control <?php echo $claseCompromiso; ?> required" type="text" name="fechaCompromiso" id="fechaCompromiso" value="<?php echo ($actividad->fechaCompromiso != '')?convertirFechaVistaConHora($actividad->fechaCompromiso):"";?>" style="<?php echo $styleCompromiso; ?>"  readonly>
                                        </div>
                                    </div>

                                    <!-- <div class="row rowFechaRealizado" style="display: <?php echo ($actId == 0)?'none':'block'; ?>;"> -->
                                    <div class="row" style="visibility:hidden;">
                                        <div class="col-md-4 text-right">
                                            <label>Fecha realizado:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input class="form-control <?php echo $claseRealizado; ?>" type="text" name="fechaRealizado" id="fechaRealizado" value="<?php echo ($actividad->fechaRealizado!= '' )?convertirFechaVistaConHora($actividad->fechaRealizado):"";?>" style="<?php echo $stylRealizado; ?>" readonly>
                                        </div>
                                    </div>
                                    </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <div class="panel panel-warning">
                                <div class="panel-heading"></div>
                                    <div class="panel-body"><br>
                                    
                                    <div class="row">
                                        <div class="col-md-4 text-right">
                                        <label for="tipoactividad">Tipo:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-control required" name="tipoactividad" id="tipoactividad" onchange="cambiaTipoActividad(this.value);" >
                                            <option value="">Seleccione...</option>
                                            <!-- <option value="1" <?php echo ($actividad->tipo == 1)?"selected":""; ?>>De fondo</option> -->
                                            <option value="2" <?php echo ($actividad->tipo == 2)?"selected":""; ?>>Seguimiento</option>
                                            <option value="3" <?php echo ($actividad->tipo == 3)?"selected":""; ?>>Audiencias</option>
                                            <!-- <option value="4" <?php echo ($actividad->tipo == 4)?"selected":""; ?>>T&eacute;rmino</option> -->
                                            <option value="5" <?php echo ($actividad->tipo == 5)?"selected":""; ?>>Citaciones y emplazamientos</option>
                                            <option value="7" <?php echo ($actividad->tipo == 7)?"selected":""; ?>>Escritos de terminos</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 text-right">
                                        <label for="pa_accion">Actividad:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control revisamaxlength required" name="pa_accion" id="pa_accion" value="<?php echo $actividad->nombre; ?>" maxlength="300">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 text-right">
                                        <label for="importanciaactividad">Importancia:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-control required" name="importanciaactividad" id="importanciaactividad">
                                            <option value="">Seleccione...</option>
                                            <option value="1" <?php echo ($actividad->importancia == 1)?"selected":""; ?>>Normal</option>
                                            <option value="2" <?php echo ($actividad->importancia == 2)?"selected":""; ?>>Media</option>
                                            <option value="3" <?php echo ($actividad->importancia == 3)?"selected":""; ?>>Alta</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!--CMPB, 10/02/2023 Ocultar select estatus cundo la actividad es nueva-->
                                    <div class="row" <?php  echo $isHidden ?>>
                                        <div class="col-md-4 text-right">
                                        <label for="estatusId">Estatus:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <!--<select class="form-control required" name="estatusId" id="estatusId" onchange="cambiaEstatusActividad(this.value)">--> <!-- JGP comento temporalmente, para permitir salvar  -->
                                            <select class="form-control required" name="estatusId" id="estatusId" onchange="cambiaEstatusActividad(this.value)">
                                            <option value="">Seleccione...</option>
                                            <option value="1" <?php echo ($actividad->estatusId == 1)?"selected":""; ?>>Por realizar</option>
                                            <!--<option value="2" <?php echo ($actividad->estatusId == 2)?"selected":""; ?>>En proceso</option>--> <!-- JGP Ocultamos por ahora  -->
                                            <option value="4" <?php echo ($actividad->estatusId == 4)?"selected":""; ?>>Terminado</option>
                                            <option value="3" <?php echo ($actividad->estatusId == 3)?"selected":""; ?>>Espero instrucciones</option>
                                            </select>
                                        </div>
                                    </div>
                                    <?php 
                                        $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                                        echo generaHtmlForm($arrCampoAvanzo, $cols); 
                                    ?>
                                    </div>
                            </div>
                        </div>
                    </div><!-- fin fila nuevos campos -->

                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading">Descripci&oacute;n</div>
                                    <div class="panel-body"><br>
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <textarea class="form-control" name="pa_comentario" id="pa_comentario" rows="6">
                                                  <?php if($actividad->comentarios!=null){echo $actividad->comentarios;}else{?>
                                                  <p><strong>Reporte:</strong></p>
                                                  <p></p>
                                                  <p><strong>Actividad:</strong></p>
                                                  <p></p>
                                                  <?php }?>
                                                </textarea>
                                                <input type="hidden" name="pa_comentario_hd" id="pa_comentario_hd" value="">
                                            </div>
                                            <div class="col-md-1"></div>
                                        </div>
                                    </div>
                            </div>
                      </div>
                    </div>

                    <div class="row divInfoInterno" style="display:none;">
                        <div class="col-xs-12 col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading">Comentarios internos:</div>
                                    <div class="panel-body"><br>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-10">
                                            <div class="alert alert-warning">
                                            <span class="material-icons">new_releases</span> Los comentarios internos est&aacute;n activados
                                            </div>
                                        </div>
                                        <div class="col-md-1"></div>
                                    </div>

                                    <div class="row ">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="pa_internos" id="pa_internos" rows="6"><?php echo $actividad->internos; ?></textarea>
                                            <input type="hidden" name="pa_internos_hd" id="pa_internos_hd" value="">
                                        </div>
                                        <div class="col-md-1"></div>
                                    </div>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="display:none;">
                        <div class="col-xs-12 col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading">Reporte</div>
                                    <div class="panel-body"><br>
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <textarea class="form-control" name="pa_reporte" id="pa_reporte" rows="6"><?php echo $actividad->reporte; ?></textarea>
                                                <input type="hidden" name="pa_reporte_hd" id="pa_reporte_hd" value="">
                                            </div>
                                            <div class="col-md-1"></div>
                                        </div>
                                    </div>
                            </div>
                        </div>


                    </div>
                        
                    </div>
                    
                      
                    

                    <div class="col-md-offset-1 col-md-10" id="cont_gastos" style="visibility:hidden;">
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Gastos:</label>
                            </div>
                        </div>
                        <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                            <div id="cont_listagastos"></div>
                        </div>
                    </div>

                    <div class="col-md-12"><br/></div>
                  </form>

                  <?php if($actividad->estatusId == 4 && ($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2) ){ 
                      $gridAcciones = $casoAccionesObj->ObtAccionesGrid($actividad->casoId, $_SESSION["idUsuario"], $actividad->fechaRealizado);
                      ?>
                      <div class="row rowReporte">
                        <div class="col-xs-12 col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading">Siguientes actividades detectadas</div>
                                    <div class="panel-body"><br>
                                        <div class="row">
                                            <!-- <div class="col-md-1"></div> -->
                                            <div class="col-md-12">
                                            <form name="grids" method="post">
                                                <?php
                                                echo $koolajax->Render();
                                                if($gridAcciones != null){
                                                    echo $gridAcciones->Render();
                                                }
                                                ?>
                                            </form>
                                            </div>
                                            <!-- <div class="col-md-1"></div> -->
                                        </div>
                                    </div>
                            </div>
                        </div>


                    </div>
                        
                  <?php } ?>
                  <?php if($actId > 0){ ?>
                  <div class="row">
                      <div class="col-xs-12">
                          <div class="panel panel-warning">
                              <div class="panel-heading">Comentarios</div>
                              <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-8">
                                    <div id="contLoading"><div id="contForLoading"></div></div>
                                    </div>
                                    <div class="col-xs-4">
                                        <!-- <a onclick="agregarComentarioActividad()" class="btn btn-primary">Agregar comentario prueba</a> -->
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_comentario" onclick="preparaComentario();">Comentar</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <ul class="media-list lista-comentarios">
                                        <?php
                                        foreach ($comentarios as $comentario) {
                                            $usrComentario = $usuariosObj->UserByID($comentario->usuarioId);
                                            $btnLeido = ($comentario->leido == 0)?'<a class="btn-notification pull-right" onclick="marcarLeidoComentario('.$comentario->idAccion.', \'actividad\')" href="javascript:void(0)" id="btn_comentario_'.$comentario->idAccion.'"><span class="material-icons">check_circle</span></a>':'<i class="pull-right">Leido</i>';
                                            $icono = obtenerIconoComentario($usrComentario->idRol);
                                            
                                            
                                            echo '
                                            <li class="media">
                                              <a class="pull-left" href="#">
                                                <span class="material-icons">
                                                '.$icono.'
                                                </span>
                                              </a>
                                              <div class="media-body">
                                                <h4 class="media-heading">'.$usrComentario->nombre.'
                                                <small>'.convertirAFechaCompleta4($comentario->fechaCreacion).' (Id: '.$comentario->idAccion.')</small></h4>
                                                '.$comentario->comentarios.'
                                                '.$btnLeido.'
                                              </div>
                                            </li>
                                          ';
                                        }
                                         ?>
                                         </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <?php } ?>

                    </div>

                    
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modal_comentario" role="dialog" style="display:none;">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Comentario</h4>
              </div>
              <form role="form" id="formComentario" name="formComentario" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="co_usuarioId" id="co_usuarioId" value="<?php echo $_SESSION["idUsuario"];?>">
                    <!-- <input type="hidden" name="co_comentarios" id="co_comentarios" value="<?php echo $faker->text; ?>"> -->
                    <input type="hidden" name="co_casoid" id="co_casoid" value="<?php echo $expId;?>">
                    <input type="hidden" name="co_idaccion" id="co_idaccion" value="<?php echo $actId;?>">
                    <input class="" type="hidden" name="co_fechaaccion" id="co_fechaaccion" value="<?php echo $tz->fechaF2?>" style="width:50%;display:inline-block;" >
                    <input type="hidden" name="co_comentarios_hd" id="co_comentarios_hd" value="">
                    <input type="hidden" name="co_usuarioIdSesion" id="co_usuarioIdSesion" value="<?php echo $_SESSION["idUsuario"];?>">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-4 text-right">
                            <label for="estatusId" title="Si se cambia el estatus de la actividad a terminado, este comentario se considerara reporte">Cambiar Estatus Actividad:</label>
                            </div>
                            <div class="col-md-8">
                                <!--<select class="form-control required" name="co_estatusId" id="co_estatusId" onchange="cambiaEstatusActividad(this.value)"> JGP quito temporalmente esto para que puedan avanzar -->
                                    <select class="form-control" name="co_estatusId" id="co_estatusId" >
                                <option value="">Seleccione...</option>
                                <!-- <option value="1" <?php echo ($actividad->estatusId == 1)?"selected":""; ?>>Por realizar</option> -->
                                <!-- <option value="2" <?php echo ($actividad->estatusId == 2)?"selected":""; ?>>En proceso</option> -->
                                <option value="4" >Terminado</option>
                                <option value="3" >Espero instrucciones</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <textarea class="form-control" name="co_comentarios" id="co_comentarios" cols="30" rows="5"></textarea>
                        </div>

                       
                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnCerrarModalDeuda">Cerrar</button>
                            </div>
                            <div class="col-md-3">
                            <a class="btn btn-primary" data-dismiss="modal" onclick="guardaComentarioActividad()" id="btnGuardarComentario">Aceptar</a>
                            </div>
                            <div class="col-md-1"></div>
                        </div><br><br>
                    </div>
                    <div class="col-md-1"></div>
                </div>
          </form>
            </div>
        </div>
    </div>


    <!-- <div class="modal fade" id="popup_vacio" role="dialog" style="display:none;">
        <div class="modal-dialog">
          <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Deuda</h4>
              </div>
              <form role="form" id="formDeuda" name="formDeuda" method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="idDeuda" id="idDeuda" value="0">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnCerrarModalDeuda">Cerrar</button>
                            </div>
                            <div class="col-md-3">
                                <a class="btn btn-primary" id="btnGuardarDeuda" onclick="guardarDeuda()">Guardar</a>
                            </div>
                            <div class="col-md-1"></div>
                        </div><br><br>
                    </div>
                    <div class="col-md-1"></div>
                </div>
                </form>
            </div>
        </div>
    </div> -->

    <footer>
        <div class="panel-footer">
            <?php echo getPiePag(true); ?>
        </div>
    </footer>
    <!-- <link href="../css/jquery-ui.css" rel="stylesheet" type="text/css" /> -->
    <link rel="stylesheet" media="all" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
    <!-- <link href="../css/jquery.signature.css" rel="stylesheet" type="text/css" /> -->
    <link href="../css/jquery.timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/jquery.timepicker.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../js/jquery.timepicker.min.js?upd='.$upd.'"></script>
    <script type="text/javascript" src="../js/jquery-ui-timepicker.js?upd='.$upd.'"></script>
    <script type="text/javascript" src="../js/jquery-ui-sliderAccess.js?upd='.$upd.'"></script>
    <script type="text/javascript" src="../js/spanish_datapicker.js?upd='.$upd.'"></script>
    <script>
        $(document).ready(function(){

            var params = {selector:"#pa_comentario", height:"230", btnImg:true};
            opcionesTinymce(params);

            var params = {selector:"#pa_internos", height:"230", btnImg:true};
            opcionesTinymce(params);

            var params = {selector:"#pa_reporte", height:"230", btnImg:true};
            opcionesTinymce(params);

            <?php if($actId == 0){ ?>
                $("#tipoactividad").val(2);
                $("#tipoactividad").trigger("change");
            <?php }else{ ?>
                cambiaTipoActividad($("#tipoactividad").val(),true);
                console.log("en else"+ $("#tipoactividad").val());
            <?php } ?>    
            // var data = {id: 1, message: 'Mensaje para el padre'};
            // window.opener.ProcessChildMessage(data);
        });

    </script>
</body>
</html>
