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
include_once '../brules/digitalesObj.php';
include_once '../brules/casosObj.php';
//establecer la zona horaria
$dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
$dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual

$digitalesObj = new digitalesObj();
$casosObj = new casosObj();
$expedienteId = (isset($_GET['expedienteId']))?$_GET['expedienteId']:0;
$digitalId = (isset($_GET['digitalId']))?$_GET['digitalId']:0;
$urlPdf = '';

$datosCaso = $casosObj->CasoPorId($expedienteId);
$dtDetCaso = $casosObj->ObtCasoInfoPorId($expedienteId);
$digitales = $digitalesObj->ObtDigitales($expedienteId, 2, '', '', '', '', 'orden ASC');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Expediente Digital</title>
    <?php echo estilosPagina(true); ?>
    <link rel="stylesheet" href="viewer.css?upd=1">
    <!-- This snippet is used in production (included from viewer.html) -->
    <link rel="resource" type="application/l10n" href="locale/locale.properties">
</head>
<body>
    <?php echo getHeaderMain($_SESSION['myusername'], true, 'viewer');?>
    <?php $menu = getAdminMenu(); ?>

    <input type="hidden" name="vista" id="vista" value="inicio">

    <section class="section-internas">
        <div class="panel-body">
            <div class="container-fluid">
                <div class="row">
                    <!-- <div class="colmenu col-md-2 menu_bg"> -->
                        <?php //echo getNav($menu); ?>
                    <!-- </div> -->
                    <div class="col-md-12">
                        <h1 class="titulo">Expediente Digital <span class="pull-right"><a id="btnAyudaweb" onclick="mostrarAyuda('web_inicio')" href="#fancyAyudaWeb"><img src="../images/icon_ayuda.png" width="20px"></a></span></h1>  


                        <!-- <ol class="breadcrumb">
                          <li><a href="index.php">Inicio</a></li>
                          <li><a href="expedientes.php">Expedientes</a></li>
                          <li><a href="frmExpedienteEdit.php?id=<?php echo $expedienteId ?>">Expediente</a></li>
                          <li class="active">Expediente Digital</li>
                        </ol> -->

                        <h2>Num Expediente: <?php echo $datosCaso->numExpediente; ?>
                        <span class="material-icons" onclick="expanderDiv('datos_expdiente')">expand_more</span>
                        </h2>
                        
                        <div class="row" id="datos_expdiente" style="display: none;">
                            <div class="col-xs-10">
                                <input type="hidden" name="idCaso" id="idCaso" value="<?php echo $datosCaso->idCaso; ?>">
                            <span><b>ID Exp:</b> <?php echo $datosCaso->idCaso; ?></span><br/>
                            <span><b>Num Exp:</b> <?php echo $datosCaso->numExpediente; ?></span><br/>
                            <span><b>Num Exp Juzg:</b> <?php echo $datosCaso->numExpedienteJuzgado; ?></span><br/>
                            <span><b>Cliente:</b> <?php echo $dtDetCaso[0]->cliente; ?></span><br/>
                            <span><b>Materia:</b> <?php echo $dtDetCaso[0]->nombreMateria; ?></span><br/>
                            <span><b>Distrito:</b> <?php echo $dtDetCaso[0]->nombreDistrito; ?></span><br/>
                            <span><b>Juzgado:</b> <?php echo $dtDetCaso[0]->nombreJuzgado; ?></span><br/>
                            <p class="text-right"><br/></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-2">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle"
                                            data-toggle="dropdown">
                                        Documento<span class="caret"></span>
                                    </button>

                                    <ul class="dropdown-menu" role="menu">
                                    <?php
                                        $cont = 1;
                                        foreach ($digitales as $digital) {
                                            $clase = '';
                                            if($digitalId == 0 && $cont == 1){
                                                $urlPdf = $digital->url;
                                            }elseif($digitalId == $digital->idDocumento){
                                                $urlPdf = $digital->url;
                                            }

                                            if($digitalId == $digital->idDocumento || ($digitalId == 0 && $cont == 1)){
                                                $clase = 'active';
                                            }else{
                                                $clase = '';
                                            }
                                            echo '
                                            <li>
                                                <a id="btnDigital_'.$digital->idDocumento.'" onclick="recargaViewer('.$digital->idDocumento.', '.$expedienteId.')" class="btnDigital '.$clase.'">'.$digital->nombre.'</a>

                                                <input type="hidden" name="url_'.$digital->idDocumento.'" id="url_'.$digital->idDocumento.'" value="'.$digital->url.'" class="url_pdf">
                                            </li>
                                            ';
                                            //<div class="flip-book-container solid-container container-flipbook" src="../upload\expedientes\\'.$digital->url.'"></div>';
                                            $cont++;
                                        }
                                    ?>
                                        <!-- <li class="divider"></li> -->
                                       
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xs-2">
                            <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle"
                                            data-toggle="dropdown" id="btnOrdenar">
                                        Ordenar<span class="caret"></span>
                                    </button>

                                    <ul class="dropdown-menu sortable" role="menu" id="sortable">
                                    <?php
                                        $cont = 1;
                                        foreach ($digitales as $digital) {
                                            echo '
                                            <li class="ui-state-default" id="digital_'.$digital->idDocumento.'"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'.$digital->nombre.'</li>
                                            ';
                                        }
                                    ?>
                                        
                                       
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xs-4"></div>
                            <div class="col-xs-2">
                                <a class="btn btn-primary" role="button" target="_blank" onclick="window.close()" title="Ver detalle expediente">Cerrar</a>
                            </div>
                            <div class="col-xs-2">
                            <a class="btn btn-primary" role="button" target="_blank" onclick="unirPdf(<?php echo $datosCaso->idCaso; ?>)" title="Descargar" id="btnDescargar">Descargar Todos Juntos</a>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-xs-12">
                                <input type="hidden" name="url_selected" id="url_selected" value="../upload/expedientes/<?php echo $urlPdf ?>" >
                                <?php
                                include 'only_viewer.html';
                                 ?>
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
        </div>
    </footer>
    <?php echo scriptsPagina(true, true); ?>
    
    <script src="../libs/pdfviewer/pdf.js"></script>
    <script src="viewer.js"></script>

    <link rel="stylesheet" href="../css/jquery-ui.css">
    <!-- <script type="text/javascript" src="../js/jquery-ui.js"></script> -->

    <script>
        $( document ).ready(function() {
            let idCaso = <?php echo $datosCaso->idCaso; ?>;
            $( "#sortable" ).sortable({
                update: function( event, ui ) {ordenarDocs(idCaso)}
            });
            // $( "#sortable" ).disableSelection();
            // $("#url_selected").val($(".url_pdf").first().val());
            // // webViewerLoad();
            // var _app = __webpack_require__(2);
        });
    </script>

</body>
</html>
