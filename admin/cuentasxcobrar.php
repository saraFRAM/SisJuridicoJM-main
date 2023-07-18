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

        $dirname = dirname(__DIR__);
include_once '../common/screenPortions.php';
include_once '../brules/KoolControls/KoolGrid/koolgrid.php';
require_once '../brules/KoolControls/KoolAjax/koolajax.php';
require_once '../brules/KoolControls/KoolCalendar/koolcalendar.php';
include_once '../brules/KoolControls/KoolGrid/ext/datasources/MySQLiDataSource.php';
$localization = "../brules/KoolControls/KoolCalendar/localization/es.xml";
include_once $dirname.'/brules/utilsObj.php';
include_once $dirname.'/brules/casosObj.php';
include_once $dirname.'/brules/cuentasObj.php';
include_once $dirname.'/brules/pagosObj.php';
include_once $dirname.'/brules/catBancosObj.php';
include_once $dirname.'/brules/catMetodosObj.php';
include_once $dirname.'/brules/arraysObj.php';
//establecer la zona horaria
$dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
$dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
$fechaHoy = $dateByZone->format('d/m/Y'); //fecha Actual

$casosObj = new casosObj();
$cuentasObj = new cuentasObj();
$pagosObj = new pagosObj();
$catBancosObj = new catBancosObj();
$catMetodosObj = new catMetodosObj();

$expedienteId = (isset($_GET['expedienteId']))?$_GET['expedienteId']:0;
$montoAuxBruto =0;
$numMeses =0;
$monto =0;
$datosCaso = $casosObj->CasoPorId($expedienteId);
$dtDetCaso = $casosObj->ObtCasoInfoPorId($expedienteId);
$cuenta = $cuentasObj->CuentaPorCasoId($expedienteId);
$pagos = $pagosObj->ObtPagos($cuenta->idCuenta);
$pagosAdicionales = $pagosObj->ObtPagos($cuenta->idCuenta, 2);

$casosAsociadosArr = $cuentasObj->obtCasosDecuenta($cuenta->idCuenta);
$casosSinCuentaArr = $cuentasObj->ObtCasosPorIdClienteSinCta($datosCaso->clienteId);

//print_r($casosSinCuentaArr);

$cobroJson = json_encode($cuenta->cobrosJson);
//var_dump($cobroJson);
$saldo=$cuenta->monto;
$planPagos= $cuenta->planPagos;
$total = 0;
if($planPagos==2 || $planPagos==3){
    $cobroJson2 = json_decode($cuenta->cobrosJson, true);
    if($cobroJson2!=null){
        foreach ($cobroJson2 as $item) {
            $cobro = str_replace("$", "", $item['cobro']);
            $cobro = str_replace(",", "", $cobro);
            $total += floatval($cobro);
        }
    }
    $montoInicial=0;
}else{
    $montoInicial=$cuenta->montoAux;
}

$pagado = 0;
$pagadoAdicionales = 0;
$cobrado = 0;
$vencido = 0;
$porCobrar = 0;
//var_dump($pagos);
foreach ($pagos as $pago) {
    $pagado += $pago->monto;
    //var_dump($pagado);
}
//var_dump($pagado);
foreach ($pagosAdicionales as $pagoAd) {
    $pagadoAdicionales += $pagoAd->monto;
}

$totalPagado = $pagado + $pagadoAdicionales +$montoInicial;

$fechasCobros = array();
if($cuenta->diaCobro > 0){
    $fechaAnt = getFechaMes($cuenta->diaCobro);
    $primeraFecha = diasPrevPos(0, $dateTime, "prev", 3);
    // $primeraFecha = $fechaHoy;
    $fechasCobros[] = convertirFechaVista($primeraFecha);
    for($i=1;$i<=$cuenta->numMeses;$i++){
        $fechaMes = diasPorMesPrevPos(1, $fechaAnt, "pos", 3);
        $fechasCobros[] = convertirFechaVista($fechaMes);
        $fechaAnt = $fechaMes;
    }
}

//Preparamos string con casos asociados
$casosAsociados = '';
foreach($casosAsociadosArr as $casoAsoc){
    $casosAsociados .= $casoAsoc->casoId.', ';
}



$msgGral = ($cuenta->idCuenta == 0)?"Para visualizar la seccion de cobros y pagos, es necesario guardar la seccion de Info cobro":"Si cambia el modo de cobro, al recargar la pagina se borraran los cobros y se sugerira el primer cobro de acuerdo al modo seleccionado";

$msgCobros = ($cuenta->idCuenta == 0)?"":"
<ol>
    <li>Si no ha guardado cobros, se sugerira uno o mas cobros dependiendo el modo de cobro seleccionado</li>
    <li>Debera guardar la seccion de cobros, para visualizar los cambios en saldos</li>
    <li>Si los cobros, no se guardan con fecha, solo se sumaran a saldo proyectado, ya que no se tendria forma de calcular para saldo vencido</li>
</ol>";

    $arrCampoCuentaId = array(
    array("nameid"=>"idCuenta", "type"=>"hidden", "class"=>"form-control", "readonly"=>false, "label"=>"idCuenta:", "datos"=>array(), "value"=>$cuenta->idCuenta),
  );

  $arrCampoCasoId = array(
    array("nameid"=>"casoId", "type"=>"hidden", "class"=>"form-control", "readonly"=>false, "label"=>"Expediente:", "datos"=>array(), "value"=>$expedienteId),
  );

  $arrCampoClienteId = array(
    array("nameid"=>"clienteId", "type"=>"hidden", "class"=>"form-control", "readonly"=>false, "label"=>"clienteId:", "datos"=>array(), "value"=>$datosCaso->clienteId),
  );

  $arrTipoCobro = obtTipoCobro();
  $tipoCobro = ($cuenta->idCuenta == 0)?$datosCaso->cobro:$cuenta->tipoCobro;
  $arrCampoCobro = array(
    array("nameid"=>"tipoCobro", "type"=>"select", "class"=>"form-control required", "readonly"=>false, "label"=>"Tipo cobro:", "datos"=>$arrTipoCobro, "value"=>$tipoCobro),
  );

  $arrPlanes = obtPlanPagos();
  $dataPlan = array(
    "onchange"=>"cambiaPlanPagos(this.value)",
);
  $arrCampoPlan = array(
    array("nameid"=>"planPagos", "type"=>"select", "class"=>"form-control required", "readonly"=>false, "label"=>"Modo de cobro:", "datos"=>$arrPlanes, "value"=>$cuenta->planPagos, "dataInput"=>$dataPlan),
  );

  $claseRowAvance = ($cuenta->planPagos == 3)?"oculto":"oculto";
  $requiredAvance = ($cuenta->planPagos == 3)?"required":"";
  $arrCampoAvance = array(
    array("nameid"=>"avance", "type"=>"text", "class"=>"form-control text-right ".$requiredAvance, "readonly"=>false, "label"=>"Avance:", "value"=>$cuenta->avance, "claseRow"=>"rowAvance ".$claseRowAvance),
  );
//------------------------------------------Nombrar campo de monto deacuerdo al plan de pagos
  switch ($cuenta->planPagos) {
    case 1: $label = "Monto fijo"; break;
    case 2: $label = "Monto inicial"; break;
    case 3: $label = "Monto inicial"; break;
    case 4: $label = "Monto fijo"; break;
    default: $label = "Monto"; break;
  }
  $monto = ($cuenta->monto == 0) ? "" : "$ ".$cuenta->monto;
  $arrCampoMonto = array(
    //array("nameid"=>"monto", "type"=>"text", "class"=>"form-control text-right required", "readonly"=>false, "label"=>"<span id=\"spanLabelMonto\">".$label.":</span>", "datos"=>array(), "value"=>"$ ".number_format($cuenta->monto,2)),
    array("nameid"=>"monto", "type"=>"text", "class"=>"form-control text-right required", "readonly"=>false, "label"=>"<span id=\"spanLabelMonto\">".$label.":</span>", "datos"=>array(), "value"=>"".$monto),
  );
  ///--------------------Ocualta imputs deacuerdo al plan de pagos
  switch ($cuenta->planPagos) {
    case 1: $labelAux = "Monto aux"; $claseRowMontoAux ="oculto"; $claseRowAux = "oculto";break;
    case 2: $labelAux = "Monto mensualidades"; $claseRowMontoAux =""; $claseRowAux = ""; $montoAuxBruto = $cuenta->montoAux; $numMeses = $cuenta->numMeses; $monto = $cuenta->monto; break;
    case 3: $labelAux = "Monto aux"; $claseRowMontoAux ="oculto"; $claseRowAux = "oculto";break;
    case 4: $labelAux = "Monto inicial"; $claseRowMontoAux ="oculto"; $claseRowAux = "oculto";break;
    default: $labelAux = "Monto aux"; $claseRowMontoAux ="oculto"; $claseRowAux = "oculto";break;
  }
  $montoAux = ($cuenta->montoAux == 0) ? "" : "$ ".$cuenta->montoAux;
  

  $arrCampoMontoAux = array(
    //array("nameid"=>"montoAux", "type"=>"text", "class"=>"form-control text-right", "readonly"=>false, "label"=>"<span id=\"spanLabelMontoAux\">".$labelAux.":</span>", "datos"=>array(), "value"=>"$ ".number_format($cuenta->montoAux,2), "claseRow"=>"rowMontoAuxMain rowMontoAux ".$claseRowMontoAux),
        array("nameid"=>"montoAux", "type"=>"text", "class"=>"form-control text-right", "readonly"=>false, "label"=>"<span id=\"spanLabelMontoAux\">".$labelAux.":</span>", "datos"=>array(), "value"=>"".$montoAux, "claseRow"=>"rowMontoAuxMain rowMontoAux ".$claseRowMontoAux),
    );

  
    $mesesNum = ($cuenta->numMeses == 0) ? "" : $cuenta->numMeses;
    $cobroDia = ($cuenta->diaCobro == 0) ? "" : $cuenta->diaCobro;
    $arrCampoNumMeses = array(
        array("nameid"=>"numMeses", "type"=>"text", "class"=>"form-control text-right", "readonly"=>false, "label"=>"Numero de meses", "datos"=>array(), 
        "value"=>"".$mesesNum, "claseRow"=>"rowMontoAux ".$claseRowAux),
    );

    $arrCampoDiaCobro = array(
        array("nameid"=>"diaCobro", "type"=>"text", "class"=>"form-control text-right", "readonly"=>false, "label"=>"Dia cobro", "datos"=>array(), "value"=>"".$cobroDia, "claseRow"=>"rowMontoAux ".$claseRowAux),
    );

  $arrCampoComentarios = array(
    array("nameid"=>"comentarios", "type"=>"textarea", "class"=>"form-control", "readonly"=>false, "label"=>"Comentarios:", "datos"=>array(), "value"=>$cuenta->comentarios),
  );


  // Pagos
  $result = $pagosObj->ObtPagosGrid($cuenta->idCuenta);
  $resultAdicionales = $pagosObj->ObtPagosGrid($cuenta->idCuenta, 2);
  $metodos = $catMetodosObj->ObtMetodos(1, "nombre");
  $bancos = $catBancosObj->ObtBancos(1, "nombre");
  $arrMetodos = convertirObjetoAArraySelect($metodos, "idMetodo", "nombre", "requiereBanco");
  $arrBancos = convertirObjetoAArraySelect($bancos, "idBanco", "nombre");
// echo "<pre>";print_r($metodos);echo "</pre>";

  $dataMetodo = array(
    "onchange"=>"cambiaMetodo(this.id)",
);
  $arrCampoMetodo = array(
    array("nameid"=>"metodoId", "type"=>"select", "class"=>"form-control required", "readonly"=>false, "label"=>"Metodo:", "datos"=>$arrMetodos, "value"=>"", "dataInput"=>$dataMetodo),
  );

  $arrCampoBanco = array(
    array("nameid"=>"bancoId", "type"=>"select", "class"=>"form-control", "readonly"=>false, "label"=>"Banco:", "datos"=>$arrBancos, "value"=>""),
  );

  $arrCampoCuentaIdPago = array(
    array("nameid"=>"cuentaId", "type"=>"hidden", "class"=>"form-control", "readonly"=>false, "label"=>"idCuenta:", "datos"=>array(), "value"=>$cuenta->idCuenta),
  );
    
  $arrCampoMontoPago = array(
    array("nameid"=>"montoPago", "type"=>"text", "class"=>"form-control text-right required", "readonly"=>false, "label"=>"Monto:", "datos"=>array(), "value"=>""),
  );

  $arrCampoReciboPago = array(
    array("nameid"=>"reciboPago", "type"=>"file", "class"=>"form-control", "readonly"=>false, "label"=>"Recibo:", "datos"=>array(), "value"=>""),
  );

  $atributosFP = array(
    array("atributo"=>"style", "valor"=>"width:90%;display:inline-block;"),
);
  $arrCampoFechaPago = array(
    array("nameid"=>"fechaPago", "type"=>"text", "class"=>"form-control required inputfechaGral", "readonly"=>true, "label"=>"Fecha:", "datos"=>array(), "value"=>$dateByZone->format('d/m/Y'), "atributos"=>$atributosFP),
  );

  $arrCampoComentariosPago = array(
    array("nameid"=>"comentariosPago", "type"=>"textarea", "class"=>"form-control", "readonly"=>false, "label"=>'<span id="spanComentarios">Comentarios:</span>', "datos"=>array(), "value"=>""),
  );

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Cuentas por cobrar</title>
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
                        <h1 class="titulo">Cuentas por cobrar <span class="pull-right"><a id="btnAyudaweb" onclick="mostrarAyuda('web_cuentas')" href="#fancyAyudaWeb"><img src="../images/icon_ayuda.png" width="20px"></a></span></h1>  

                        <input type="hidden" name="fechaHoy" id="fechaHoy" value="<?php echo $dateByZone->format('d/m/Y') ?>">


                        <ol class="breadcrumb">
                          <li><a href="index.php">Inicio</a></li>
                          <li><a href="frmExpedienteEdit.php?id=<?php echo $expedienteId ?>">Expediente</a></li>
                          <li class="active">Cuentas por cobrar</li>
                        </ol>

                        <div class="row">
                            <div class="col-xs-6 col-md-5"></div>
                            <div class="col-xs-6 col-md-3">
                                <a data-toggle="modal" data-target="#asociar_casos_cta" class="btn btn-primary" role="button">Asociar Expedientes</a>
                            </div>    
                            <div class="col-xs-6 col-md-2">
                                <a onclick="window.close()" class="btn btn-primary" role="button">Cerrar</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="panel panel-warning">
                                    <div class="panel-heading">Expediente</div>
                                        <div class="panel-body"><br>
                                        <div class="row" id="datos_expdiente">
                                            <div class="col-xs-1"></div>
                                            <div class="col-xs-10">
                                            <span><b>ID Exp:</b> <?php echo $datosCaso->idCaso; ?></span><br/>
                                            <span><b>ID Exp Asociados:</b> <?php echo $casosAsociados; ?></span><br/>
                                            <span><b>Exp interno:</b> <?php echo $datosCaso->numExpediente; ?></span><br/>
                                            <span><b>Num Exp Juzg:</b> <?php echo $datosCaso->numExpedienteJuzgado; ?></span><br/>
                                            <span><b>Materia:</b> <?php echo $dtDetCaso[0]->nombreMateria; ?></span><br/>
                                            <span><b>Distrito:</b> <?php echo $dtDetCaso[0]->nombreDistrito; ?></span><br/>
                                            <span><b>Juzgado:</b> <?php echo $dtDetCaso[0]->nombreJuzgado; ?></span><br/>
                                            
                                                <span><b>Cliente:</b> <?php echo $dtDetCaso[0]->cliente; ?></span><br/>
                                                <span><b>Tipo cobro:</b> <?php echo obtTipoCobro($datosCaso->cobro); ?></span><br/><br>
                                                <?php if($cuenta->idCuenta > 0){
                                                   
                                                        
                                                   if($cuenta->planPagos == 2 || $cuenta->planPagos == 3){
                                                    $arrRows = array(
                                                        "spanMontoFijo"=>"Monto total Proyectado:",
                                                        "spanRestante"=>"Monto restante del monto fijo:",
                                                        "spanSaldoVencido"=>"Saldo vencido:",
                                                        "spanSaldoProyectado"=>"Saldo proyectado:",
                                                        "spanCobrado"=>"Cobrado Agendado:",
                                                        "spanAdicionales"=>"Cobros adicionales:",
                                                        "spanTotalPagado"=>"Total cobrado:",
                                                    );

                                                    $arrTextos = array(
                                                        "spanMontoFijo"=>"$ ",
                                                        "spanRestante"=>"",
                                                        "spanSaldoVencido"=>"",
                                                        "spanSaldoProyectado"=>"",
                                                        "spanCobrado"=>"",
                                                        "spanAdicionales"=>"$ ".number_format($pagadoAdicionales, 2),
                                                        "spanTotalPagado"=>"$ ".number_format($totalPagado, 2),

                                                    );
                                                }else{
                                                    $arrRows = array(
                                                        "spanMontoFijo"=>"Monto fijo:",
                                                        "spanRestante"=>"Monto restante del monto fijo:",
                                                        "spanSaldoVencido"=>"Saldo vencido:",
                                                        "spanSaldoProyectado"=>"Saldo proyectado:",
                                                        "spanCobrado"=>"Cobrado:",
                                                        "spanAdicionales"=>"Cobros adicionales:",
                                                        "spanTotalPagado"=>"Total cobrado:",
                                                    );

                                                    $arrTextos = array(
                                                        "spanMontoFijo"=>"$ ".number_format($saldo, 2),
                                                        "spanRestante"=>"",
                                                        "spanSaldoVencido"=>"",
                                                        "spanSaldoProyectado"=>"",
                                                        "spanCobrado"=>"",
                                                        "spanAdicionales"=>"$ ".number_format($pagadoAdicionales, 2),
                                                        "spanTotalPagado"=>"$ ".number_format($totalPagado, 2),

                                                    );
                                                }
                                                    

                                                    foreach ($arrRows as $key => $value) {
                                                        echo '
                                                        <div class="row">
                                                            <div class="col-xs-6">
                                                                <label>'.$value.'</label>
                                                            </div> 
                                                            <div class="col-xs-6 text-right">
                                                                <span id="'.$key.'">'.$arrTextos[$key].'</span>
                                                            </div>
                                                        </div>';
                                                        
                                                    }
                                                    
                                                    ?>
                                                
                                                <?php } ?>
                                            </div>

                                        </div>
                                           
                                        </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-6">
                                <div class="panel panel-warning">
                                    <div class="panel-heading">Info cobro</div>
                                    <?php 
                                    if($msgGral != ''){
                                        echo '
                                        <div class="row">
                                            <div class="col-xs-1"></div>
                                            <div class="col-xs-10">
                                                <div class="alert alert-info">
                                                    <strong>Recuerde:</strong>
                                                    <span class="material-icons cursorPointer" onclick="expanderDiv(\'divMsgGRal\')">expand_more</span>
                                                    <div id="divMsgGRal" style="display:none;">'.$msgGral.'</div>
                                                </div>
                                            </div>
                                            <div class="col-xs-1"></div>
                                        </div>
                                        ';
                                    }

                                         ?>
                                        <div class="panel-body"><br>
                                        <form role="form" id="formCuenta" name="formCuenta" method="post" action="" enctype="multipart/form-data">
                                            
                                        <?php
                                            $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                                            echo generaHtmlForm($arrCampoCuentaId, $cols);
                                            echo generaHtmlForm($arrCampoCasoId, $cols);
                                            echo generaHtmlForm($arrCampoClienteId, $cols);
                                            echo generaHtmlForm($arrCampoCobro, $cols);
                                            echo generaHtmlForm($arrCampoPlan, $cols);
                                            echo generaHtmlForm($arrCampoAvance, $cols);
                                            echo generaHtmlForm($arrCampoMonto, $cols);
                                            echo generaHtmlForm($arrCampoMontoAux, $cols);
                                            echo generaHtmlForm($arrCampoNumMeses, $cols);
                                            echo generaHtmlForm($arrCampoDiaCobro, $cols);
                                            echo generaHtmlForm($arrCampoComentarios, $cols);
                                        ?>

                                        <div class="row">
                                            <div class="col-xs-6"></div>
                                            <div class="col-xs-6">
                                                <a class="btn btn-promary" onclick="guardarCuenta(<?php echo $planPagos; ?>, <?php echo $total; ?>)" id="btnGuardarCuenta">Guardar</a>
                                            </div>
                                        </div>
                                        </form>
                                           
                                        </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            

                            <?php if($cuenta->idCuenta > 0){ ?>

                            <div class="col-xs-12 col-sm-12">
                                <div class="panel panel-warning">
                                    <div class="panel-heading">Cobros</div>
                                    <?php 
                                    if($msgCobros != ''){
                                        echo '
                                        <div class="row">
                                            <div class="col-xs-1"></div>
                                            <div class="col-xs-10">
                                                <div class="alert alert-info">
                                                    <strong>Recuerde:</strong> 
                                                    <span class="material-icons cursorPointer" onclick="expanderDiv(\'divMsgCobros\')">expand_more</span>
                                                    <div id="divMsgCobros" style="display:none;">'.$msgCobros.'</div>
                                                </div>
                                            </div>
                                            <div class="col-xs-1"></div>
                                        </div>
                                        ';
                                    }

                                         ?>
                                         <div class="row" id="rowAvisoCobros1">
                                            <div class="col-xs-1"></div>
                                            <div class="col-xs-10">
                                                <div class="alert alert-info">
                                                        <p><b>¡IMPORTANTE!</b>, guardar saldos sugeridos.</p>
                                                        <p>Cobro(s) sugeridos, pendientes de guardar para calcular saldos</p>
                                                    </div>
                                                </div>
                                                <div class="col-xs-1"></div>
                                            </div>
                                    
                                        <div class="panel-body"><br>
                                        <div class="col-xs-7"></div>
                                        <div class="col-xs-5">
                                            <span>&nbsp;<a  onclick="agregarCobro(<?php echo $cuenta->idCuenta ?>)" class="btn btn-primary agregarCobro" title="Agregar cobro" id="btnAgregarCobro">Agregar cobro<img width="16px" src="../images/iconos/iconos_grid/agregar.png"></a></span>
                                        </div><br><br>
                                        <form role="form" id="formCobro" name="formCobro" method="post" action="" enctype="multipart/form-data">
                                        <?php 
                                        //Campo restante (se oculto)
                                        //print_r($cuenta);
                                        $claseRowRestante = ($cuenta->planPagos == 4)?"":"oculto";
                                        $arrCampoRestante = array(
                                            array("nameid"=>"restante", "type"=>"text", "class"=>"form-control text-right", "readonly"=>true, "label"=>"Monto restante a registrar de cobros:", "datos"=>array(), "value"=>$cuenta->monto, "claseRow"=>$claseRowRestante),
                                        );
                                        $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                                            echo generaHtmlForm($arrCampoRestante, $cols);

                                        //Campo numCobros
                                        $dataNumCobros = array(
                                            "onchange"=>"cambiaNumCobros(this.value)",
                                        );
                                        $arrCampoNumCobros = array(
                                            array("nameid"=>"numcobros", "type"=>"text", "class"=>"form-control text-right", "readonly"=>false, "label"=>"Num cobros:", "datos"=>array(), "value"=>"", "claseRow"=>"rowNumCobros oculto", "dataInput"=>$dataNumCobros),
                                        );
                                        $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                                            // echo generaHtmlForm($arrCampoNumCobros, $cols);

                                         ?>
                                        <div id="divCobros">
                                        <?php
                                        //print_r($cuenta->cobrosJson);
                                        if($cuenta->cobrosJson != ''  || $cuenta->cobrosJson != 0){
                                            $cobros = json_decode($cuenta->cobrosJson);
                                            $cont = 1;
                                            foreach ($cobros as $key => $cobro) {
                                                $modoCobro = $cobro->modoCobro;//Jair 20/5/2022 Se toma el modo de cobro de manera individual por cada cobro
                                                $htmlCampo = '';
                                                $htmlCampo2 = '';
                                                if($modoCobro == 3){//pago sobre avance
                                                //     //Como no hay porcentaje de avance (solo texto avance) todo siempre se va a saldo proyectado
                                                //     // if($cuenta->avance >= $cobro->fechacobro){
                                                //     //     $vencido += removerCaracteres($cobro->cobro);
                                                //     // }else{
                                                //        $porCobrar += removerCaracteres($cobro->cobro);
                                                //     // }
                                                    $htmlCampo2 = '<input type="text" class="form-control text-right inputCobro avance" id="avance_'.$cont.'" name="avance_'.$cont.'" value="" placeholder="avance">';
                                                }
                                                //else{//Los demas planes
                                                    if($cobro->fechacobro == ''){
                                                        $porCobrar += removerCaracteres($cobro->cobro);
                                                    }else{
                                                        $diferencia = obtenerDiferenciaPorFechaHoy(conversionFechas($cobro->fechacobro));
                                                        if($diferencia > -1){
                                                            $vencido += removerCaracteres($cobro->cobro);
                                                        }else{
                                                            $porCobrar += removerCaracteres($cobro->cobro);
                                                        }
                                                    }
                                                    // echo "otro";
                                                    $htmlCampo = '<input type="text" class="form-control inputCobro inputfechaGral" id="fechacobro_'.$cont.'" name="fechacobro_'.$cont.'" value="'.$cobro->fechacobro.'" readonly="" style="width:87%;display:inline-block;">';
                                                // }
                                                $planPagoItem = obtPlanPagos($cobro->modoCobro);
                                                echo '<div class="row rowCobro" id="rowCobro_'.$cont.'">
                                                <input type="hidden" name="modoCobro_'.$cont.'" id="modoCobro_'.$cont.'" value="'.$cobro->modoCobro.'">
                                                    <div class="col-md-2 text-right">
                                                    <input type="text" readonly="" class="form-control" name="label_'.$cont.'" id="label_'.$cont.'" value="'.$cobro->label.'">
                                                    </div>
                                                    <div class="col-md-3">
                                                            <input type="text" class="form-control inputCobro cobro text-right required" id="cobro_'.$cont.'" name="cobro_'.$cont.'" placeholder="Cobro '.$cont.'" value="'.$cobro->cobro.'" onchange="actualizaRestante('.$cont.', '.$cuenta->planPagos.')">
                                                    </div>
                                                    <div class="col-md-3">
                                                        '.$htmlCampo.'
                                                    </div>
                                                    <div class="col-md-3">
                                                    '.$htmlCampo2.'
                                                    </div>
                                                    <div class="col-md-1">
                                                            <a onclick="eliminarCobro('.$cont.', '.$cuenta->idCuenta.', '.$cuenta->planPagos.')" class="eliminarCobro cursorPointer" title="Eliminar cobro">
                                                            <span class="material-icons" style="font-size:12px;">clear</span>
                                                            </a>
                                                        </div>
                                                    </div>';
                                                    $cont++;
                                                }
                                                $vencidoOriginal = $vencido;
                                                $cobrado = $pagado;
                                                $vencido -= $pagado;
                                                $pagado = ($pagado >= $vencidoOriginal)?$pagado-$vencidoOriginal:0;
                                                
                                                
                                                $porCobrar -= $pagado;
                                            }
                                            ?>
                                        </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-6"></div>
                                            <div class="col-xs-6">
                                                <!--a class="btn btn-promary" onclick="guardarCobros('.$cuenta->idCuenta.', '.$cuenta->planPagos.')" id="btnGuardarCobros">Guardar</a-->
                                                <a class="btn btn-promary" onclick="guardarCobros('<?php echo $cuenta->planPagos; ?>')" id="btnGuardarCobros">Guardar</a>
                                            </div>
                                        </div>
                                        </form>
                                </div>
                            </div>
                            <?php } ?>
                        </div>

                        <div class="row">
                        <?php if($cuenta->idCuenta > 0){ ?>
                            <div class="col-xs-12 col-sm-12">
                                <div class="panel panel-warning">
                                    <div class="panel-heading">Pagos</div>
                                        <div class="panel-body"><br>
                                        <div class="col-xs-8"></div>
                                        <div class="col-xs-4">
                                            <span>&nbsp;<a id="btnAgregarPagoCuentas" href="#" data-toggle="modal" data-target="#popup_modalPago" class="btn btn-primary agregarPago" title="Agregar pago" onclick="inicializaAgregarPAgo(1)">Agregar pago<img width="16px" src="../images/iconos/iconos_grid/agregar.png"></a></span>
                                        </div>
                                        <form name="gridPagos" method="post">
                                        <?php
                                        echo $koolajax->Render();
                                        if($result != null){
                                            echo $result->Render();
                                        }
                                        ?>
                                    </form>
                                           
                                        </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12">
                                <div class="panel panel-warning">
                                    <div class="panel-heading">Pagos adicionales</div>
                                        <div class="panel-body"><br>
                                        <div class="col-xs-8"></div>
                                        <div class="col-xs-4">
                                            <span>&nbsp;<a href="#" data-toggle="modal" data-target="#popup_modalPago" class="btn btn-primary agregarPago" title="Agregar pago"  onclick="inicializaAgregarPAgo(2)">Agregar pago adicional<img width="16px" src="../images/iconos/iconos_grid/agregar.png"></a></span>
                                        </div>
                                        <form name="gridPagos" method="post">
                                        <?php
                                        echo $koolajax->Render();
                                        if($resultAdicionales != null){
                                            echo $resultAdicionales->Render();
                                        }
                                        ?>
                                    </form>
                                           
                                        </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="popup_modalPago" role="dialog" data-backdrop="static" data-keyboard="false" style="display:none;">
          <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Pago</h4>
                </div>
                <div class="row">

                  <form role="form" id="formPago" name="formPago" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="tipoPago" id="tipoPago" value="1">
                    <br>
                    <div class="col-xs-1"></div>
                    <div class="col-xs-10">
                        <?php
                            $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                            echo generaHtmlForm($arrCampoCuentaIdPago, $cols);
                            echo generaHtmlForm($arrCampoMetodo, $cols);
                            echo generaHtmlForm($arrCampoBanco, $cols);
                            echo generaHtmlForm($arrCampoMontoPago, $cols);
                            echo generaHtmlForm($arrCampoReciboPago, $cols);
                            echo generaHtmlForm($arrCampoFechaPago, $cols);
                            echo generaHtmlForm($arrCampoComentariosPago, $cols);

                        ?>
                    </div>
                    <div class="col-xs-1"></div>
                   
                    

                    <div class="col-md-offset-1 col-md-9 col-md-offset-1">
                      <div class="row">
                        <div class="col-md-offset-5 col-md-3 text-right">
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="col-md-3 text-right">
                          <a class="btn btn-primary" id="btnGuardarPago" onclick="guardarPago();">Guardar</a>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12"><br/></div>
                  </form>

                </div>
            </div>
          </div>
        </div>
    
    <!-- modal de asociar expedientes a cuenta -->
    <div class="modal fade" id="asociar_casos_cta" role="dialog" style="display:none;">
        <div class="modal-dialog">
          <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Asociar Expedientes del Cliente</h4>
              </div>
              <form role="form" id="formCasosAsociadosCta" name="formCasosAsociadosCta" method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="idCuenta" id="idCuenta" value="<?php echo $cuenta->idCuenta; ?>">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <h4>Expedientes Asociados</h4>
                        <?php foreach($casosAsociadosArr as $casoAsoc){ 
                            $idCasoAsoc = $casoAsoc->casoId;
                            ?>
                            <input type="checkbox" name="casoAsoc[]" id="<?php echo $idCasoAsoc; ?>" value="<?php echo $idCasoAsoc; ?>" checked><label for="<?php echo $idCasoAsoc; ?>"><?php echo 'ID Exp: '. $casoAsoc->casoId .' | Num Exp: '. $casoAsoc->numExpediente . ' | Exp Juzgado: '. $casoAsoc->numExpedienteJuzgado ?></label><br/>
                        <?php } ?>
                            <!-- $casosSinCuentaArr -->
                        <h4>Expedientes Sin Cuenta</h4>
                        <?php foreach($casosSinCuentaArr as $casoAsoc){ 
                            $idCasoAsoc = $casoAsoc->idCaso;
                            ?>
                            <input type="checkbox" name="casoAsoc[]" id="<?php echo $idCasoAsoc; ?>" value="<?php echo $idCasoAsoc; ?>"><label for="<?php echo $idCasoAsoc; ?>"><?php echo 'ID Exp: '. $casoAsoc->idCaso .' | Num Exp: '. $casoAsoc->numExpediente . ' | Exp Juzgado: '. $casoAsoc->numExpedienteJuzgado ?></label><br/>
                        <?php } ?>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                            </div>
                            <div class="col-md-3">
                                <a class="btn btn-primary" id="btnGuardarCasosAsociados" onclick="guardarCasosAsociados()">Guardar</a>
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
    <link href="../css/jquery-ui.css" rel="stylesheet" type="text/css" />
    <!-- <link href="../css/jquery.signature.css" rel="stylesheet" type="text/css" /> -->
    <link href="../css/jquery.timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/jquery.timepicker.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../js/jquery.timepicker.min.js?upd='.$upd.'"></script>
    <script type="text/javascript" src="../js/jquery-ui-timepicker.js?upd='.$upd.'"></script>
    <script type="text/javascript" src="../js/spanish_datapicker.js?upd='.$upd.'"></script>
    <script>
        let vencido = <?php echo $vencido; ?>;
        let porCobrar = <?php echo $porCobrar; ?>;
        let cobrado = <?php echo $pagado; ?>;
        if(cobrado==0){
            cobrado = <?php echo $cobrado; ?>;
        }
        let saldo = <?php echo $saldo; ?>;
        let montoIncial = <?php echo $saldo; ?>;
        let planPagos = <?php echo  $planPagos ?>; 
        let restante = saldo - cobrado
        let fechasCobros = "<?php echo implode(",", $fechasCobros); ?>";
        let cobrosJson=<?php echo $cobroJson; ?>;
        let data=0;
        let total = 0;
        console.log(montoIncial);
        vencido = (vencido < 0) ? 0 : vencido;
        porCobrar = (porCobrar < 0) ? 0 : porCobrar;
        console.log(planPagos);
        $(document).ready(function(){
            const btnAgregarCobro = document.getElementById('btnAgregarCobro');
            const montoInput = document.getElementById("monto");
            console.log(montoInput);
            const montoAuxInput = document.getElementById("montoAux");
            switch (planPagos) {
                case 1:
                    btnAgregarCobro.removeAttribute('onclick');
                    btnAgregarCobro.setAttribute('disabled', 'disabled');
                    break;
                case 2:
                    //suma del monto fijo
                    data = JSON.parse(cobrosJson);
                    if(data != null){
                        for (let i = 0; i < data.length; i++) {
                            let cobro = data[i].cobro.replace("$", "").replace(",", "");
                            total += parseFloat(cobro);
                        }
                        $("#spanMontoFijo").text(accounting.formatMoney(total, "$"));
                    }                
                    //fin suma monto fijo
                    let montoAuxBruto = <?php echo $montoAuxBruto; ?>;
                    let numMeses = <?php echo $numMeses; ?>;
                    let monto = parseInt("<?php echo $monto; ?>".replace("$", ""));
                    cobrado = <?php echo $pagado; ?>;
                    if(cobrado==0){
                        cobrado = <?php echo $cobrado; ?>;
                    }
                    restante = total-cobrado;
                    break;
                case 3:
                    //suma del monto fijo:
                     data = JSON.parse(cobrosJson);
                    if(data != null){
                        for (let i = 0; i < data.length; i++) {
                            let cobro = data[i].cobro.replace("$", "").replace(",", "");
                            total += parseFloat(cobro);
                        }
                        $("#spanMontoFijo").text(accounting.formatMoney(total, "$"));
                    }                
                    //fin suma monto fijo
                    cobrado = <?php echo $pagado; ?>;
                    if(cobrado==0){
                        cobrado = <?php echo $cobrado; ?>;
                    }
                    restante = total-cobrado;
                    break;
                case 4:
                    data = JSON.parse(cobrosJson);
                    break
                default:
                    console.log("Opción no válida");
                    // Acciones por defecto cuando la opción no coincide con ningún case
            }
            
            montoInput.addEventListener("blur", function() {
                let cantidad = montoInput.value;
                if(cantidad==""){
                    cantidad=0
                }
      
                if (!isNaN(cantidad)) {
                    let cantidad_formateada = parseFloat(cantidad).toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD"
                    })
                    montoInput.value = cantidad_formateada;
                }
            });
            montoAuxInput.addEventListener("blur", function() {
                let cantidad = montoAuxInput.value;
                if(cantidad==""){
                    cantidad=0
                }
                if (!isNaN(cantidad)) {
                    let cantidad_formateada = parseFloat(cantidad).toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD"
                    })
                    montoAuxInput.value = cantidad_formateada;
                }
            });
            const btnAgregarPagoCuentas = document.getElementById('btnAgregarPagoCuentas');
            if(restante==0){
                btnAgregarPagoCuentas.disabled = true;
                btnAgregarPagoCuentas.setAttribute('disabled', 'disabled');
            }else{
                btnAgregarPagoCuentas.disabled = false;
                btnAgregarPagoCuentas.removeAttribute('disabled');
            }
            
            if($(".rowCobro").length > 0){
                actualizaRestante(1,  planPagos);
            }

            if($(".rowCobro").length == 0){
                $("#rowAvisoCobros1").show();
                preparaPlanPagos();
            }

            $("#spanSaldoVencido").text(accounting.formatMoney(vencido, "$"));
            $("#spanSaldoProyectado").text(accounting.formatMoney(porCobrar, "$"));
            $("#spanCobrado").text(accounting.formatMoney(cobrado, "$"));
            if(restante===0){
                $("#spanRestante").text(accounting.formatMoney("$ 0.00"));
            }else{
                $("#spanRestante").text(accounting.formatMoney(restante, "$"))
            }
        });
    </script>

    <footer>
        <div class="panel-footer">
            <?php echo getPiePag(true); ?>
        </div>
    </footer>
</body>
</html>
