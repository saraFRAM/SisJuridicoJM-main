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
include_once '../brules/utilsObj.php';
include_once $dirname.'/brules/usuariosObj.php';
//establecer la zona horaria
$dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
$dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual

$usuariosObj = new usuariosObj();
$colAbogados = $usuariosObj->obtTodosUsuarios(true, 4, "", " numAbogado ASC ");
// echo "<pre>";print_r($colAbogados);echo "</pre>";

$arrEstatus = array();
// $arrEstatus[] = array("valor"=>"", "texto"=>"Seleccione...");
$arrEstatus[] = array("valor"=>1, "texto"=>"Por realizar");
$arrEstatus[] = array("valor"=>2, "texto"=>"En proceso");
$arrEstatus[] = array("valor"=>3, "texto"=>"Espero instrucciones");
$arrEstatus[] = array("valor"=>4, "texto"=>"Terminado");

$dataEstatus = array(
    "multiple"=>"multiple",
    // "data-live-search"=>"true",
);

$arrReportes = array();
$arrReportes[] = array("valor"=>"", "texto"=>"Seleccione...");
$arrReportes[] = array("valor"=>1, "texto"=>"Sabana actividades");
$arrReportes[] = array("valor"=>2, "texto"=>"Gastos");

$dataReporte = array(
    "onchange"=>"cambiaReporte(this.value)",
);

$arrAbogados = array();
$arrAbogados[] = array("valor"=>"", "texto"=>"Seleccione...");
foreach ($colAbogados as $abogadoItem) {
    $arrAbogados[] = array("valor"=>$abogadoItem->idUsuario, "texto"=>$abogadoItem->numAbogado." - ".$abogadoItem->nombre);
}

$dataAbogados = array(
    "data-live-search"=>"true",
    //"multiple"=>"multiple",
);

$arrCampoReporte = array(
    array("nameid"=>"reporte", "type"=>"select", "class"=>"form-control required", "readonly"=>false, "label"=>"Reporte:", "datos"=>$arrReportes, "value"=>"", "dataInput"=>$dataReporte),
);

$valorAbogado = ($_SESSION["idRol"] == 4)?$_SESSION["idUsuario"]:"";
$claseAbogado = ($_SESSION["idRol"] == 4)?"sololectura":"";

$arrCampoAbogado = array(
    //JGP 24/10/22 Remuevo selectpicker porque solo debe poder seleccionar uno
    array("nameid"=>"abogado", "type"=>"select", "class"=>"form-control $claseAbogado required", "readonly"=>true, "label"=>"Abogado:", "datos"=>$arrAbogados, "value"=>$valorAbogado, "dataInput"=>$dataAbogados, "claseRow"=>"cambiaRes"),
);

$arrCampoEstatus = array(
    array("nameid"=>"estatus", "type"=>"select", "class"=>"form-control selectpicker required", "readonly"=>true, "label"=>"Estatus:", "datos"=>$arrEstatus, "value"=>"", "dataInput"=>$dataEstatus, "claseRow"=>"rowEstatus"),
);


$arrFechas = array();
$arrFechas[] = array("valor"=>"", "texto"=>"Seleccione...");
$arrFechas[] = array("valor"=>"fechaCompromiso", "texto"=>"Fecha compromiso");
$arrFechas[] = array("valor"=>"fechaRealizado", "texto"=>"Fecha realizado");

$dataFecha = array(
    "style"=>"width:90%;display:inline-block;"
);

$arrCampoFecha = array(
    array("nameid"=>"fecha", "type"=>"select", "class"=>"form-control required", "readonly"=>true, "label"=>"Por Fecha:", "datos"=>$arrFechas, "value"=>"", "dataInput"=>array(), "claseRow"=>"rowFecha"),
);

$arrCampoDesde = array(
    array("nameid"=>"from", "type"=>"text", "class"=>"form-control required", "readonly"=>true, "label"=>"Desde:", "value"=>"", "dataInput"=>$dataFecha),
);

$arrCampoHasta = array(
    array("nameid"=>"to", "type"=>"text", "class"=>"form-control required", "readonly"=>true, "label"=>"Hasta:", "value"=>"", "dataInput"=>$dataFecha),
);

$arrMostrar = array();
//$arrMostrar[] = array("valor"=>"", "texto"=>"Seleccione...");
$arrMostrar[] = array("valor"=>"si", "texto"=>"Si");
$arrMostrar[] = array("valor"=>"no", "texto"=>"No");

$arrCampoMostrar = array(
    array("nameid"=>"mostrar", "type"=>"select", "class"=>"form-control required", "readonly"=>true, "label"=>"Mostrar comentarios:", "datos"=>$arrMostrar, "value"=>"", "dataInput"=>array(), "claseRow"=>"rowMostrar"),
);


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Reportes</title>
    <?php echo estilosPagina(true); ?>
    <?php echo scriptsPagina(true); ?>
</head>
<body>
    <?php echo getHeaderMain($_SESSION['myusername'], true);?>
    <?php $menu = getAdminMenu(); ?>

    <input type="hidden" name="vista" id="vista" value="reportes">

    <section class="section-internas">
        <div class="panel-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="colmenu col-md-2 menu_bg">
                        <?php echo getNav($menu); ?>
                    </div>
                    <div class="col-md-10">
                        <h1 class="titulo">Reportes <span class="pull-right"><a id="btnAyudaweb" onclick="mostrarAyuda('web_reportes')" href="#fancyAyudaWeb"><img src="../images/icon_ayuda.png" width="20px"></a></span></h1>  


                        <ol class="breadcrumb">
                          <li><a href="index.php">Inicio</a></li>
                          <li class="active">Reportes</li>
                        </ol>

                        <p>En proceso</p>
                        
                        <div class="col-xs-12 col-md-6">
                            <div class="panel panel-warning">
                                <div class="panel-heading"></div>
                                    <div class="panel-body"><br>
                                    <form role="form" id="formReporte" name="formReporte" method="post" action="">
                                    <?php
                                    $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                                    // echo generaHtmlForm($arrCampoSaludExp, $cols);
                                    // echo generaHtmlForm($arrCampoVelocidad, $cols);
                                    // echo generaHtmlForm($arrCampoEstatus, $cols);
                                    echo generaHtmlForm($arrCampoReporte, $cols);
                                    echo generaHtmlForm($arrCampoAbogado, $cols);
                                    echo generaHtmlForm($arrCampoEstatus, $cols);
                                    echo generaHtmlForm($arrCampoFecha, $cols);
                                    echo generaHtmlForm($arrCampoDesde, $cols);
                                    echo generaHtmlForm($arrCampoHasta, $cols);
                                    echo generaHtmlForm($arrCampoMostrar, $cols);
                                        ?>

                                        <div class="row">
                                            <div class="col-xs-offset-6 col-xs-2">
                                                <a onclick="imprimirReporte(0)" class="btn btn-primary" role="button" id="btnVer">Ver</a>
                                            </div>
                                            <div class="col-xs-2">
                                                <a onclick="imprimirReporte(1)" class="btn btn-primary" role="button" id="btnReporte">Imprimir</a>
                                            </div>
                                        </div>
                                    </form>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
            <link href="../css/jquery-ui.css" rel="stylesheet" type="text/css" />
            <!-- <link href="../css/jquery.signature.css" rel="stylesheet" type="text/css" /> -->
            <link href="../css/jquery.timepicker.min.css" rel="stylesheet" type="text/css" />
            <link href="../css/jquery.timepicker.css" rel="stylesheet" type="text/css" />
            <script type="text/javascript" src="../js/jquery.timepicker.min.js?upd='.$upd.'"></script>
            <script type="text/javascript" src="../js/jquery-ui-timepicker.js?upd='.$upd.'"></script>
            <script type="text/javascript" src="../js/spanish_datapicker.js?upd='.$upd.'"></script>
        </div>
    </footer>
</body>
</html>
