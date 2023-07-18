<?php
session_start();
$idRol = $_SESSION['idRol'];
switch ($idRol) {
    case 1: case 2: $rol = true; break;
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

include_once '../brules/comunicadosObj.php';
include_once '../brules/utilsObj.php';
//establecer la zona horaria
$dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
$dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
$fechaHoy = $dateByZone->format('d/m/Y H:i:s'); //fecha Actual
// $apartadosObj = new apartadosObj();
$idComunicado = 0;
$comunicadosObj = new comunicadosObj();


if(isset($_GET["id"])){
    $idComunicado = $_GET["id"];
    // $comunicado = $comunicadosObj->obtenerComunicadosById($idComunicado);
    $comunicado = $comunicadosObj->obtComunicadoPorId($idComunicado);
}else{
    $comunicado = $comunicadosObj->obtComunicadoPorId(0);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Comunicado</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="../js/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
      <?php echo getCSSRot(); ?>
    </style>
    <link href="../css/style.css?upd=<?php echo time() ?>" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link href="../css/style-responsive.css?upd=<?php echo time() ?>" rel="stylesheet" type="text/css" />
    <link href="../css/styleupload.css" rel="stylesheet" type="text/css" />
    <link href="../css/alertify.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="../css/alertify.default.css" rel="stylesheet" type="text/css" /> -->
    <link rel="stylesheet" href="../css/themes/semantic.min.css"/>
    <!-- <script type="text/javascript" src="../js/blueupload.js"></script> -->
</head>
<body>
    <?php echo getHeaderMain($_SESSION['myusername'], true);?>
    <?php $menu = getAdminMenu(); ?>

    <input type="hidden" name="vista" id="vista" value="catalogos">

    <section class="section-internas">
        <div class="panel-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="colmenu col-md-2 menu_bg">
                      <?php echo getNav($menu); ?>
                    </div>
                    <div class="col-md-10">
                        <h1 class="titulo">Comunicado <span class="pull-right"><a id="btnAyudaweb" onclick="mostrarAyuda('web_comunicado')" href="#fancyAyudaWeb"><img src="../images/icon_ayuda.png" width="20px"></a></span></h1>


                        <ol class="breadcrumb">
                          <li><a href="index.php">Inicio</a></li>
                          <li><a href="catalogos.php?catalog=comunicados">Comunicados</a></li>
                          <li class="active">Comunicado</li>
                        </ol>

                        <form role="form" id="formComunicado" name="formComunicado" method="post" action="" enctype="multipart/form-data">
                            <input type="hidden" name="idComunicado" id="idComunicado" value="<?php echo $idComunicado ?>">
                            <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $_SESSION["idUsuario"] ?>">
                            <input type="hidden" name="fechaHoy" id="fechaHoy" value="<?php echo $fechaHoy ?>">

                            <div class="row">
                                <div class="col-md-2 text-right"><label>T&iacute;tulo:</label></div>
                                <div class="col-md-4">
                                    <input class="form-control required" type="text" name="titulo" id="titulo" value="<?php echo $comunicado->titulo; ?>" maxlength="100">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2 text-right"><label>Descripci&oacute;n corta:</label></div>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="descripcionCorta" id="descripcionCorta" rows="6"><?php echo $comunicado->descripcionCorta; ?></textarea>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-md-2 text-right"><label>Contenido:</label></div>
                                <div class="col-md-8">
                                    <textarea class="form-control required" name="contenido" id="contenido" rows="6"><?php echo $comunicado->contenido; ?></textarea>
                                    <input type="hidden" name="contenidoHd" id="contenidoHd" value="">
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-md-2 text-right"><label>Url Comunicado:</label></div>
                                <div class="col-md-4">
                                    <input class="form-control" type="text" name="urlComunicado" id="urlComunicado" value="<?php echo $comunicado->urlComunicado; ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2 text-right"><label>Url Video:</label></div>
                                <div class="col-md-4">
                                    <input class="form-control" type="text" name="urlVideo" id="urlVideo" value="<?php echo $comunicado->urlVideo; ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2 text-right"><label>Imagen:</label></div>
                                <div class="col-md-4">
                                    <input class="form-control " type="file" name="imgComunicado" id="imgComunicado">
                                </div>
                            </div>
                            <div class="row <?php echo ($comunicado->imgComunicado != "")?"":"oculto2" ?>" id="rowImgComunicado">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <img src="../upload/comunicados/<?php echo $comunicado->imgComunicado ?>" height="200px" id="showImgComunicado">
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-2 text-right"><label>Tipo:</label></div>
                                <div class="col-md-4">
                                    <select class="form-control required" name="opcTipo" id="opcTipo">
                                        <option value="">Seleccione...</option>
                                        <option value="0" <?php echo ($comunicado->opcTipo == 0)?"selected":""; ?>>Capsula</option>
                                        <!-- <option value="2" ?php echo ($comunicado->opcTipo == 2)?"selected":""; ?> >Bolet&iacute;n</option> -->
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2 text-right"><label>Activo:</label></div>
                                <div class="col-md-4">
                                    <select class="form-control required" name="activo" id="activo">
                                        <option value="1" <?php echo ($comunicado->activo == 1 || $idComunicado == 0)?"selected":""; ?>>Si</option>
                                        <option value="0" <?php echo ($comunicado->activo == 0 && $idComunicado > 0)?"selected":""; ?> >No</option>
                                    </select>
                                </div>
                            </div>

                            <br><br>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-2">
                                    <a href="catalogos.php?catalog=comunicados" class="btn btn-warning">Regresar</a>
                                </div>
                                <div class="col-md-2">
                                    <a href="javascript:void(0);" class="btn btn-primary" onclick="guardarComunicado()" id="btnGuardarComunicado">Guardar</a>
                                </div>
                            </div>
                        </form>
                         <br><br>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="panel-footer">
            <?php echo getPiePag(true); ?>
        </div>
    </footer>

    <?php echo scriptsPagina(true, 1); ?>
    <!-- <script type="text/javascript" src="../js/blueupload.js"></script> -->
    <script type="text/javascript">
            var params = {selector:"#contenido", height:"230", btnImg:true};
            opcionesTinymce(params);
            // var params = {selector:"#descripcionCorta", height:"230", btnImg:false};
            // opcionesTinymce(params);
    </script>

     <link rel="stylesheet" href="../css/jquery-ui.css">
    <link rel="stylesheet" href="../css/jquery.timepicker.min.css">
    <link rel="stylesheet" href="../css/jquery.timepicker.css">
    <script src="../js/jquery.timepicker.min.js"></script>
    <script src="../js/jquery-ui-timepicker.js"></script>
    <script src="../js/spanish_datapicker.js"></script>

     <!--Para crear el grid-->
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/accounting.min.js"></script>
</body>
</html>
