<?php
session_start();
$idRol = $_SESSION['idRol'];
switch ($idRol) {
    case 1: $rol = true; break;
	case 2: $rol = true; break;
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
//establecer la zona horaria
$dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
$dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual

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
                          <li class="active">Subir documentos</li>
                        </ol>

                        <p>En proceso</p>
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
</body>
</html>
