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
$objFechas = $conceptosObj->fechasGastos();
//Obtener aquellos que pertenecen al abogado
$idAbogado = 0;
if($_SESSION['idRol']==4){
    $idAbogado = $_SESSION['idUsuario'];
    $abogados = $usuariosObj->obtTodosUsuarios(true, "", $idAbogado, " numAbogado ASC ");
}
else{
    $abogados = $usuariosObj->obtTodosUsuarios(true, "4", "", " numAbogado ASC ", true);
}
//, $objFechas->last_sat, $objFechas->next_sat
$result = $usuariosObj->GetGastosGrid("4", $idAbogado, $objFechas->last_sat, $objFechas->next_sat);
// echo $idAbogado." ";
//$abogados = $usuariosObj->obtTodosUsuarios(true, "4", $idAbogado, " numAbogado ASC ");
$conceptos = $catConceptosObj->ObtCatConceptos(1, 2);//Conceptos salidas
$conceptosEnt = $catConceptosObj->ObtCatConceptos(1, 1);//Conceptos entradas
$totalCargos = 0;
$totalAbonos = 0;
$saldoTotal = 0;
$saldoPeriodo = 0;

foreach ($abogados as $abogado) {
    $conceptosUsr = $conceptosObj->ObtTodosConceptos($abogado->idUsuario, "", $objFechas->last_sat, $objFechas->next_sat);
    $saldoTotal += $abogado->saldo;

    foreach ($conceptosUsr as $conceptoItem) {
        if($conceptoItem->tipoId == 1){
            $totalCargos += $conceptoItem->monto;
        }else{
            $totalAbonos += $conceptoItem->monto;
        }
    }
}

$saldoPeriodo = $totalAbonos - $totalCargos;


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Control de gastos</title>
    <?php echo estilosPagina(true); ?>
    <?php echo scriptsPagina(true); ?>
</head>
<body>
    <?php echo getHeaderMain($_SESSION['myusername'], true);?>
    <?php $menu = getAdminMenu(); ?>

    <input type="hidden" name="vista" id="vista" value="gastos">
    <input type="hidden" name="desde" id="desde" value="<?php echo $objFechas->last_sat ?>">
    <input type="hidden" name="hasta" id="hasta" value="<?php echo $objFechas->next_sat ?>">

    <section class="section-internas">
        <div class="panel-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="colmenu col-md-2 menu_bg">
                        <?php echo getNav($menu); ?>
                    </div>
                    <div class="col-md-10">
                        <h1 class="titulo">Control de gastos <span class="pull-right"><a id="btnAyudaweb" onclick="mostrarAyuda('web_gastos')" href="#fancyAyudaWeb"><img src="../images/icon_ayuda.png" width="20px"></a></span></h1>  


                        <ol class="breadcrumb">
                          <li><a href="index.php">Inicio</a></li>
                          <li class="active">Control de gastos</li>
                        </ol>

                        <h4><strong>PERIODO: </strong><?php echo 'De '.$objFechas->last_completa.' a '.$objFechas->next_completa; ?></h4>

                        <div class="row">
                            <div class="col-xs-6 col-md-3">
                                <div class="alert alert-info uno">
                                    <div class="row text-center"><label>Total Entradas Periodo</label></div>
                                    <div class="row text-right"><span id="spanTotalAbonos"><?php echo "$ ".number_format($totalAbonos, 2) ?></span></div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="alert alert-info dos">
                                    <div class="row text-center"><label>Total Salidas Periodo</label></div>
                                    <div class="row text-right"><span id="spanTotalCargos"><?php echo "$ ".number_format($totalCargos, 2) ?></span></div>
                                </div>
                            </div>
                            <!-- <div class="col-xs-6 col-md-3">
                                <div class="alert alert-info tres">
                                    <div class="row text-center"><label>Saldo periodo</label></div>
                                    <div class="row text-right"><label><span id="spanSaldoTotal"><?php echo "$ ".number_format($saldoPeriodo, 2) ?></span></label></div>
                                </div>
                            </div> -->
                            <div class="col-xs-6 col-md-3">
                                <div class="alert alert-info cuatro">
                                    <div class="row text-center"><label>Saldo total actual al d&iacute;a</label></div>
                                    <div class="row text-right"><span id="spanSaldoHistorico"><?php echo "$ ".number_format($saldoTotal, 2) ?></span></div>
                                </div>
                            </div>
                        </div>
                        

                        <div class="row">
                            <div class="col-xs-1 col-md-7"></div>
                            <div class="col-xs-5 col-md-2">
                                <a href="#fancyNuevoAbono" class="btn btn-primary btnFancy" onclick="reseteaFormulario('formAbono');seleccionaAbogado(<?php echo $idAbogado ?>, '_a');" id="btnEntrada">Entrada</a>
                            </div>
                            <div class="col-xs-5 col-md-2">
                                <a href="#fancyNuevoCargo" class="btn btn-primary btnFancy" onclick="reseteaFormulario('formCargo');seleccionaAbogado(<?php echo $idAbogado ?>, '_c');" id="btnSalida">Salida</a>
                            </div>
                        </div><br>
                        <!-- <form name="grids" method="post"> -->
                            <div class="row">
                            <?php
                            echo $koolajax->Render();
                            if($result != null){
                                echo $result->Render();
                            }
                            ?>
                            </div>
                        <!-- </form> -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="fancyNuevoCargo" style="display:none;width:950px;height:300px;">
        <div class="col-xs-12" >
        <h3>Agregar salida</h3>
        </div>
        <form role="form" id="formCargo" name="formCargo" method="post" enctype="multipart/form-data" action="">
                      <?php
                      $prefijo = "_c";
                      $arrAbogados = array();
                      $arrAbogados[] = array("valor"=>"", "texto"=>"Seleccione...");
                      foreach ($abogados as $abogado) {
                          if($abogado->activo == 1){
                              $arrAbogados[] = array("valor"=>$abogado->idUsuario, "texto"=>$abogado->numAbogado.' - '.$abogado->nombre);
                          }
                      }


                      $arrConceptos = array();
                      $arrConceptos[] = array("valor"=>"", "texto"=>"Seleccione...");
                      foreach ($conceptos as $concepto) {
                          $arrConceptos[] = array("valor"=>$concepto->idConcepto, "texto"=>$concepto->nombre);
                      }

                      $arrAtributos = array();
                      $arrAtributos[] = array("atributo"=>"style", "valor"=>"width:50%;display:inline");
                      $dataInput = array("data-live-search"=>"true");
                      $arrCamposConcepto = array(
                        // array("nameid"=>"", "type"=>"", "class"=>"", "readonly"=>false, "label"=>""),
                        array("nameid"=>"tipoId".$prefijo, "type"=>"hidden", "class"=>"", "readonly"=>true, "label"=>"Nombre:", "datos"=>array(), "value"=>1),
                        array("nameid"=>"usuarioId".$prefijo, "type"=>"select", "class"=>"required", "readonly"=>false, "label"=>"Nombre:", "datos"=>$arrAbogados),
                        array("nameid"=>"conceptoId".$prefijo, "type"=>"select", "class"=>"selectpicker required", "readonly"=>false, "label"=>"Concepto:", "datos"=>$arrConceptos, "dataInput"=>$dataInput),
                        array("nameid"=>"descripcion".$prefijo, "type"=>"text", "class"=>"", "readonly"=>false, "label"=>"Comentarios:", "datos"=>array()),
                        array("nameid"=>"monto".$prefijo, "type"=>"text", "class"=>"required text-right", "readonly"=>false, "label"=>"Monto:", "datos"=>array(), "atributos"=>$arrAtributos),
                        array("nameid"=>"fecha".$prefijo, "type"=>"text", "class"=>"required inputfechaGral", "readonly"=>true, "label"=>'Fecha: ', "datos"=>array(), "value"=>$fechaHoy, "atributos"=>$arrAtributos),
                        array("nameid"=>"comprobante".$prefijo, "type"=>"file", "class"=>" ", "readonly"=>false, "label"=>'Comprobante: ', "datos"=>array()),
                    );
                    
                    echo generaHtmlForm($arrCamposConcepto);
                       ?>
                       <div class="row">
                                <div class="col-xs-7"></div>
                                <div class="col-xs-4">
                                    <a onclick="guardarConcepto('formCargo', '_c')" class="btn btn-primary" id="btnGuardarConcepto_c">Guardar</a>
                                </div>
                            </div>
                      </form>
    </div>

    <div id="fancyNuevoAbono" style="display:none;width:950px;height:300px;">
        <div class="col-xs-12" >
        <h3>Agregar entrada</h3>
        </div>
        <form role="form" id="formAbono" name="formAbono" method="post" enctype="multipart/form-data" action="">
            <?php
            $prefijo = "_a";
            $arrAbogados = array();
            $arrAbogados[] = array("valor"=>"", "texto"=>"Seleccione...");
            foreach ($abogados as $abogado) {
                if($abogado->activo == 1){
                    $arrAbogados[] = array("valor"=>$abogado->idUsuario, "texto"=>$abogado->numAbogado.' - '.$abogado->nombre);
                }
            }

            $arrAtributos = array();
            $arrAtributos[] = array("atributo"=>"style", "valor"=>"width:50%;display:inline");
            $arrConceptos = array();
            $arrConceptos[] = array("valor"=>"", "texto"=>"Seleccione...");
            foreach ($conceptosEnt as $concepto) {
                $arrConceptos[] = array("valor"=>$concepto->idConcepto, "texto"=>$concepto->nombre);
            }
            $arrCamposConcepto = array(
            // array("nameid"=>"", "type"=>"", "class"=>"", "readonly"=>false, "label"=>""),
            array("nameid"=>"tipoId".$prefijo, "type"=>"hidden", "class"=>"", "readonly"=>true, "label"=>"Nombre:", "datos"=>array(), "value"=>2),
            array("nameid"=>"usuarioId".$prefijo, "type"=>"select", "class"=>"required", "readonly"=>false, "label"=>"Nombre:", "datos"=>$arrAbogados),
            array("nameid"=>"conceptoId".$prefijo, "type"=>"select", "class"=>"selectpicker required", "readonly"=>false, "label"=>"Concepto:", "datos"=>$arrConceptos, "dataInput"=>$dataInput),
            array("nameid"=>"descripcion".$prefijo, "type"=>"text", "class"=>"", "readonly"=>false, "label"=>"Comentarios:", "datos"=>array()),
            array("nameid"=>"monto".$prefijo, "type"=>"text", "class"=>"required text-right", "readonly"=>false, "label"=>"Monto:", "datos"=>array(), "atributos"=>$arrAtributos),
            array("nameid"=>"fecha".$prefijo, "type"=>"text", "class"=>"required inputfechaGral", "readonly"=>true, "label"=>'Fecha: ', "datos"=>array(), "value"=>$fechaHoy, "atributos"=>$arrAtributos),
        );
        
        echo generaHtmlForm($arrCamposConcepto);
            ?>
            <div class="row">
                    <div class="col-xs-7"></div>
                    <div class="col-xs-4">
                        <a onclick="guardarConcepto('formAbono', '_a')" class="btn btn-primary" id="btnGuardarConcepto_a">Guardar</a>
                    </div>
                </div>
            </form>
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
    <?php echo getFancyConcepto(); ?>
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
</body>
</html>
