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
include_once $dirname.'/brules/tareasObj.php';
include_once $dirname.'/brules/usuariosObj.php';
include_once $dirname.'/brules/utilsObj.php';

# Load Fakers own autoloader
require_once '../libs/faker/autoload.php';

$casosObj = new casosObj();
$tareasObj = new tareasObj();
$usuariosObj = new usuariosObj();

$dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
$dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
$fechaHoy = $dateByZone->format('d/m/Y'); //fecha Actual
$year = $dateByZone->format('Y');

//establecer la zona horaria
$tz = obtDateTimeZone();

$faker = Faker\Factory::create();


// $expId = (isset($_GET['expId']))?$_GET['expId']:0;//echo $expId;
$tareaId = (isset($_GET['tareaId']))?$_GET['tareaId']:0;
$tipo2 = (isset($_GET['tipo2']))?$_GET['tipo2']:1;



$tarea = $tareasObj->TareasPorId($tareaId);
$usuarioId = ($tareaId > 0)?$tarea->usuarioId:$_SESSION["idUsuario"];
$tipo2 = ($tareaId > 0)?$tarea->tipo2:$tipo2;
$comentarios = $tareasObj->ObtTareas('', $tareaId);
$usuario = $usuariosObj->UserByID($tarea->usuarioId);

//Campo responsable de tarea, agregar los ids de responsable y titular del expediente
$colAbogados = $usuariosObj->obtTodosUsuarios(true, "4,2,1", "", " numAbogado ASC ");
$arrResponsable = array();
$arrResponsable[] = array("valor"=>"", "texto"=>"Seleccione...");

foreach ($colAbogados as $itemAb) {
    if($itemAb->activo == 1){
        $arrResponsable[] = array("valor"=>$itemAb->idUsuario, "texto"=>$itemAb->numAbogado." - ".$itemAb->nombre);    
    }
}
if($idRol > 2){
    $responsableId = ($tarea->responsableId > 0)?$tarea->responsableId:$_SESSION["idUsuario"];//JGP por default muestro el nombre del abogado responsable
}
else{
    $responsableId = ($tarea->responsableId > 0)?$tarea->responsableId:'';//JGP Admin y titulares deben seleccionar
}


$claseResponsable = ($_SESSION["idRol"] == 4)?"likeDisaled":"";// 20/1/2022 Jair Solo lectura para abogados

$arrCampoResponsable = array(
    array("nameid"=>"responsableId", "type"=>"select", "class"=>"form-control required ".$claseResponsable, "readonly"=>false, "label"=>"Responsable:", "datos"=>$arrResponsable, "value"=>$responsableId),
);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Tarea</title>
    <?php echo estilosPagina(true); ?>
    <?php echo scriptsPagina(true); ?>
</head>
<body>
    <?php echo getHeaderMain($_SESSION['myusername'], true);?>
    <?php $menu = getAdminMenu(); ?>

    <input type="hidden" name="vista" id="vista" value="tareas">

    <section class="section-internas">
        <div class="panel-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="colmenu col-md-2 menu_bg">
                        <?php echo getNav($menu); ?>
                    </div>
                    <div class="col-md-10">
                        <h1 class="titulo">Tarea <span class="pull-right"><a id="btnAyudaweb" onclick="mostrarAyuda('web_tarea')" href="#fancyAyudaWeb"><img src="../images/icon_ayuda.png" width="20px"></a></span></h1>  


                        <ol class="breadcrumb">
                          <li><a href="index.php">Inicio</a></li>
                          <li><a href="tareas.php">Tareas</a></li>
                          <li class="active">Tarea</li>
                        </ol>

                        

                        <div class="row">
                            <div class="col-xs-10"></div>
                            <div class="col-xs-2">
                                <?php if($tareaId > 0){ ?>
                              <input type="checkbox" name="checkinterno" id="checkinterno" onchange="revisaCheckInternos()">
                              <label for="checkinterno">Ver internos</label>
                              <?php } ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="alert alert-warning divInfoInterno" style="display: none;">
                            <span class="material-icons">new_releases</span> Los comentarios internos est&aacute;n activados
                            </div>
                        </div>

                        
                        <form role="form" id="formCrearTarea" name="formCrearTarea" method="post" action="" enctype="multipart/form-data">
                            
                            <input type="hidden" name="pa_idtarea" id="pa_idtarea" value="<?php echo $tareaId;?>">
                            <input type="hidden" name="pa_usuarioId" id="pa_usuarioId" value="<?php echo $usuarioId;?>">
                            <input type="hidden" name="pa_usuarioIdSesion" id="pa_usuarioIdSesion" value="<?php echo $_SESSION["idUsuario"];?>">
                            
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
                                    <div class="row">
                                        <div class="col-md-4 text-right">
                                            <label>Usuario:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input class="form-control " type="text" name="pa_usuario" id="pa_usuario" value="<?php echo ($tareaId == 0)?$_SESSION["myusername"]:$usuario->numAbogado." - ".$usuario->nombre;?>" style="width:90%;display:inline-block;" readonly>
                                        </div>
                                    </div>
                                    <?php 
                                        $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                                        echo generaHtmlForm($arrCampoResponsable, $cols); 
                                    ?>
                                    <div class="row">
                                        <div class="col-md-4 text-right">
                                            <label>Fecha:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input class="form-control inputfechaGral required" type="text" name="pa_fechatarea" id="pa_fechatarea" value="<?php echo ($tareaId == 0)?$tz->fechaF2:convertirFechaVista($tarea->fechaAlta);?>" style="width:90%;display:inline-block;" readonly>
                                        </div>
                                    </div>

                                    <?php
                                        $claseCompromiso = 'inputfechaGral';
                                        $styleCompromiso = 'width:90%;display:inline-block;';

                                        $claseRealizado = 'inputfechaGral';
                                        $stylRealizado = 'width:90%;display:inline-block;';
                                    
                                        if($tarea->estatusId == 4){ 
                                            $claseCompromiso = '';
                                            $styleCompromiso = '';
    
                                            $claseRealizado = '';
                                            $stylRealizado = '';
                                        }
                                    ?>

                                    <div class="row">
                                    <!-- <div class="row rowFechaCompromiso" style="display: <?php echo ($tareaId == 0)?'none':'block'; ?>;"> -->
                                        <div class="col-md-4 text-right">
                                            <label>Fecha compromiso:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input class="form-control <?php echo $claseCompromiso; ?> required" type="text" name="fechaCompromiso" id="fechaCompromiso" value="<?php echo ($tarea->fechaCompromiso != '')?convertirFechaVista($tarea->fechaCompromiso):"";?>" style="<?php echo $styleCompromiso; ?>"  readonly>
                                        </div>
                                    </div>

                                    <!-- <div class="row rowFechaRealizado" style="display: <?php echo ($tareaId == 0)?'none':'block'; ?>;"> -->
                                    <div class="row">
                                        <div class="col-md-4 text-right">
                                            <label>Fecha realizado:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input class="form-control <?php echo $claseRealizado; ?>" type="text" name="fechaRealizado" id="fechaRealizado" value="<?php echo ($tarea->fechaRealizado!= '' )?convertirFechaVista($tarea->fechaRealizado):"";?>" style="<?php echo $stylRealizado; ?>" readonly>
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
                                        <label for="idTarea">Id:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control required" name="idTarea" id="idTarea" value="<?php echo $tarea->idTarea; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 text-right">
                                        <label for="pa_tarea">Tarea:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control revisamaxlength required" name="pa_tarea" id="pa_tarea" value="<?php echo $tarea->nombre; ?>" maxlength="300">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 text-right">
                                        <label for="tipotarea">Tipo:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-control required" name="tipotarea" id="tipotarea">
                                            <option value="">Seleccione...</option>
                                            <option value="1" <?php echo ($tarea->tipo == 1)?"selected":""; ?>>Administrativa</option>
                                            <option value="2" <?php echo ($tarea->tipo == 2)?"selected":""; ?>>Otros</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row" style="visibility:hidden;">
                                        <div class="col-md-4 text-right">
                                        <label for="tipotarea2">Tipo 2:</label>
                                        </div>
                                        <div class="col-md-8" >
                                            <select class="form-control required" name="tipotarea2" id="tipotarea2" onchange="cambiaTipoActividad2(this.value)">
                                            <option value="">Seleccione...</option>
                                            <option value="1" <?php echo ($tarea->tipo == 1 || $tipo2 == 1)?"selected":""; ?>>Tarea</option>
                                            <option value="2" <?php echo ($tarea->tipo == 2 || $tipo2 == 2)?"selected":""; ?>>Reporte</option>
                                            
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 text-right">
                                        <label for="importanciatarea">Importancia:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-control required" name="importanciatarea" id="importanciatarea">
                                            <option value="">Seleccione...</option>
                                            <option value="1" <?php echo ($tarea->importancia == 1)?"selected":""; ?>>Normal</option>
                                            <option value="2" <?php echo ($tarea->importancia == 2)?"selected":""; ?>>Media</option>
                                            <option value="3" <?php echo ($tarea->importancia == 3)?"selected":""; ?>>Alta</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 text-right">
                                        <label for="estatusId">Estatus:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <!--<select class="form-control required" name="estatusId" id="estatusId" onchange="cambiaEstatusActividad(this.value)">--> <!-- JGP comento temporalmente, para permitir salvar  -->
                                            <select class="form-control required" name="estatusId" id="estatusId" onchange="cambiaEstatusActividad(this.value)">
                                            <option value="">Seleccione...</option>
                                            <option value="1" <?php echo ($tarea->estatusId == 1)?"selected":""; ?>>Por realizar</option>
                                            <!--<option value="2" <?php echo ($tarea->estatusId == 2)?"selected":""; ?>>En proceso</option>--> <!-- JGP Ocultamos por ahora  -->
                                            <option value="4" <?php echo ($tarea->estatusId == 4)?"selected":""; ?>>Terminado</option>
                                            <option value="3" <?php echo ($tarea->estatusId == 3)?"selected":""; ?>>Espero instrucciones</option>
                                            </select>
                                        </div>
                                    </div>
                                    </div>
                            </div>
                        </div>
                    </div><!-- fin fila nuevos campos -->

                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading">Descripci&oacute;n tarea</div>
                                    <div class="panel-body"><br>
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <textarea class="form-control" name="pa_comentario" id="pa_comentario" rows="6"><?php echo $tarea->comentarios; ?></textarea>
                                                <input type="hidden" name="pa_comentario_hd" id="pa_comentario_hd" value="">
                                            </div>
                                            <div class="col-md-1"></div>
                                        </div>
                                    </div>
                            </div>
                        </div>


                    </div>

                    <div class="row divInfoInterno" style="display: <?php echo ($tareaId == 0)?'block':'none'; ?>;">
                        <div class="col-xs-12 col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading">Descripci&oacute;n interna:</div>
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
                                            <textarea class="form-control" name="pa_internos" id="pa_internos" rows="6"><?php echo $tarea->internos; ?></textarea>
                                            <input type="hidden" name="pa_internos_hd" id="pa_internos_hd" value="">
                                        </div>
                                        <div class="col-md-1"></div>
                                    </div>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <div class="row rowReporte">
                        <div class="col-xs-12 col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading">Reporte tarea</div>
                                    <div class="panel-body"><br>
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <textarea class="form-control" name="pa_reporte" id="pa_reporte" rows="6"><?php echo $tarea->reporte; ?></textarea>
                                                <input type="hidden" name="pa_reporte_hd" id="pa_reporte_hd" value="">
                                            </div>
                                            <div class="col-md-1"></div>
                                        </div>
                                    </div>
                            </div>
                        </div>


                    </div>
                        
                    </div>
                    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                      <div class="row">
                        <div class="col-md-offset-6 col-md-3 text-right">
                          <button type="button" class="btn btn-primary" onclick="window.close()">Cerrar</button>
                        </div>
                        <div class="col-md-3 text-right">
                          <a class="btn btn-primary" id="btnCrearTarea" onclick="creaEditaTarea();">Aceptar</a>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-offset-1 col-md-10" id="cont_gastos" style="display:none;">
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

                  
                  <?php if($tareaId > 0){ ?>
                  <div class="row">
                      <div class="col-xs-12">
                          <div class="panel panel-warning">
                              <div class="panel-heading">Comentarios</div>
                              <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-8"></div>
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
                                                <small>'.convertirAFechaCompleta3($comentario->fechaCreacion).' (Id: '.$comentario->idTarea.')</small></h4>
                                                '.$comentario->comentarios.'
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
                    
                    <input type="hidden" name="co_idtarea" id="co_idtarea" value="<?php echo $tareaId;?>">
                    <input class="" type="hidden" name="co_fechatarea" id="co_fechatarea" value="<?php echo $tz->fechaF2?>" style="width:50%;display:inline-block;" >
                    <input type="hidden" name="co_comentarios_hd" id="co_comentarios_hd" value="">
                    <input type="hidden" name="co_usuarioIdSesion" id="co_usuarioIdSesion" value="<?php echo $_SESSION["idUsuario"];?>">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-4 text-right">
                            <label for="estatusId" title="Si se cambia el estatus de la tarea a terminado, este comentario se considerara reporte">Cambiar Estatus Tarea:</label>
                            </div>
                            <div class="col-md-8">
                                <!--<select class="form-control required" name="co_estatusId" id="co_estatusId" onchange="cambiaEstatusActividad(this.value)"> JGP quito temporalmente esto para que puedan avanzar -->
                                    <select class="form-control" name="co_estatusId" id="co_estatusId" >
                                <option value="">Seleccione...</option>
                                <!-- <option value="1" <?php echo ($tarea->estatusId == 1)?"selected":""; ?>>Por realizar</option> -->
                                <!-- <option value="2" <?php echo ($tarea->estatusId == 2)?"selected":""; ?>>En proceso</option> -->
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
                            <a class="btn btn-primary" data-dismiss="modal" onclick="guardaComentarioTarea()">Aceptar</a>
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
    <link href="../css/jquery-ui.css" rel="stylesheet" type="text/css" />
    <!-- <link href="../css/jquery.signature.css" rel="stylesheet" type="text/css" /> -->
    <link href="../css/jquery.timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/jquery.timepicker.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../js/jquery.timepicker.min.js?upd='.$upd.'"></script>
    <script type="text/javascript" src="../js/jquery-ui-timepicker.js?upd='.$upd.'"></script>
    <script type="text/javascript" src="../js/spanish_datapicker.js?upd='.$upd.'"></script>
    <script>
        $(document).ready(function(){
            var idActividad = $("#pa_idtarea").val();

            setTimeout(function(){
                var params = {selector:"#pa_comentario", height:"230", btnImg:true};
                opcionesTinymce(params);
                
                var params = {selector:"#pa_internos", height:"230", btnImg:true};
                opcionesTinymce(params);
                
                var params = {selector:"#pa_reporte", height:"230", btnImg:true};
                opcionesTinymce(params);
            }, 500);

            
            if(idActividad == 0){
                setTimeout(function(){
                    $("#tipotarea2").trigger("change");
                }, 1000);
            }

            

        });
    </script>
</body>
</html>
