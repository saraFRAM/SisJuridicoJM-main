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

include_once '../common/screenPortions.php';
include_once '../brules/KoolControls/KoolGrid/koolgrid.php';
require_once '../brules/KoolControls/KoolAjax/koolajax.php';
require_once '../brules/KoolControls/KoolCalendar/koolcalendar.php';
include_once '../brules/KoolControls/KoolGrid/ext/datasources/MySQLiDataSource.php';
$localization = "../brules/KoolControls/KoolCalendar/localization/es.xml";
include_once '../brules/usuariosObj.php';
include_once '../brules/conceptosObj.php';
include_once '../brules/catConceptosObj.php';
include_once '../brules/utilsObj.php';
//establecer la zona horaria
$dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
$dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
$fechaHoy = $dateByZone->format('d/m/Y'); //fecha Actual

$usuariosObj =  new usuariosObj();
$catConceptosObj = new catConceptosObj();
$conceptosObj = new conceptosObj();

$abogadoId = (isset($_GET['abogado']))?$_GET['abogado']:0;
$imprimir = (isset($_GET['imprimir']))?$_GET['imprimir']:0;
$desde = (isset($_GET['from']) && $_GET['from'] != '')?conversionFechas($_GET['from']):0;
$hasta = (isset($_GET['to']) && $_GET['to'] != '')?conversionFechas($_GET['to']):0;

// echo $abogadoId." ".$desde." ".$hasta;
$result = $usuariosObj->obtGastosUsuario("4", $abogadoId, $desde, $hasta);
// echo "<pre>";print_r($result);echo "</pre>";
$htmlForm  = '';

$htmlForm .= '
<form role="form" id="formImprimir_'.$abogadoId.'" name="formImprimir_'.$abogadoId.'" method="post" action="conceptos.php?idUsuario='.$abogadoId.'&vista=2&imprimir='.$imprimir.'" >
<input type="hidden" name="totalAbonos_'.$abogadoId.'" id="totalAbonos_'.$abogadoId.'" value="'.$result[0]->totalAbonos.'">
<input type="hidden" name="totalCargos_'.$abogadoId.'" id="totalCargos_'.$abogadoId.'" value="'.$result[0]->totalCargos.'">
<input type="hidden" name="saldoPeriodo_'.$abogadoId.'" id="saldoPeriodo_'.$abogadoId.'" value="'.$result[0]->saldoPeriodo.'">
<input type="hidden" name="saldo_'.$abogadoId.'" id="saldo_'.$abogadoId.'" value="'.$result[0]->saldo.'">
<input type="hidden" name="desde_'.$abogadoId.'" id="desde_'.$abogadoId.'" value="'.$desde.'">
<input type="hidden" name="hasta_'.$abogadoId.'" id="hasta_'.$abogadoId.'" value="'.$hasta.'">
<input type="hidden" name="desde" id="desde" value="'.$desde.'">
<input type="hidden" name="hasta" id="hasta" value="'.$hasta.'">
<a class="grid_print" onclick="imprimirGastos('.$abogadoId.', 2);" title="Imprimir" id="btnImprimir"></a>
</form>
';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>En proceso</title>
    <?php echo estilosPagina(true); ?>
    <?php echo scriptsPagina(true); ?>
</head>
<body>
<!-- <body onload="window.print()"> -->
    <img class="logo-img" src="../images/logo.png" class="">
    <button onclick="window.print()">Imprimir</button>
    <?php echo getHeaderMain($_SESSION['myusername'], true);?>
    <?php $menu = getAdminMenu(); ?>

    <input type="hidden" name="vista" id="vista" value="inicio">

    <section class="section-internas">
        <div class="panel-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="colmenu col-md-2 menu_bg">
                        <?php echo getNav($menu); ?>
                    </div>
                    <div class="col-md-10">
                        <h1 class="titulo">En proceso <span class="pull-right"><a id="btnAyudaweb" onclick="mostrarAyuda('web_inicio')" href="#fancyAyudaWeb"><img src="../images/icon_ayuda.png" width="20px"></a></span></h1>  


                        <ol class="breadcrumb">
                          <li><a href="index.php">Inicio</a></li>
                          <li class="active">Reporte Gastos</li>
                        </ol>

                        <?php echo $htmlForm ?>
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
        </div>
    </footer>
    <script>
        $( document ).ready(function() {
            
            $("#btnImprimir").trigger("click");
        });
    </script>
</body>
</html>
