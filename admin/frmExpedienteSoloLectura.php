<?php
session_start();
$idRol = $_SESSION['idRol'];
$rol = true;
switch ($idRol) {
    case 1: case 2: case 4:  $rol = true; break;
    default: $rol = false; break;
}
if($_SESSION['status']!= "ok" || $rol!=true)
        header("location: logout.php");

$dirname = dirname(__DIR__);
include_once '../common/screenPortions.php';
include_once '../brules/utilsObj.php';
require_once '../brules/KoolControls/KoolAjax/koolajax.php';
libreriasKool();
include_once $dirname.'/brules/casosObj.php';
include_once $dirname.'/brules/catTipoCasosObj.php';
include_once $dirname.'/brules/usuariosObj.php';
include_once $dirname.'/brules/clientesObj.php';
include_once $dirname.'/brules/casoAccionesObj.php';
include_once $dirname.'/brules/accionGastosObj.php';
include_once $dirname.'/brules/catConceptosObj.php';
$casosObj = new casosObj();
$catTipoCasosObj = new catTipoCasosObj();
$usuariosObj = new usuariosObj();
$clientesObj = new clientesObj();
// $casoAccionesObj = new casoAccionesObj();
// $accionGastosObj = new accionGastosObj();
$catConceptosObj = new catConceptosObj();

//establecer la zona horaria
$tz = obtDateTimeZone();
$fecha = $tz->fecha;
$msjResponse = "";
$msjResponseE = "";
$id = (isset($_GET['id']))?$_GET['id']:0;

$colTipos = $catTipoCasosObj->ObtCatTipoCasos();
$colAbogados = $usuariosObj->obtTodosUsuarios(true, 4, "", " numAbogado ASC ");
$colConceptos = $catConceptosObj->ObtCatConceptos();

$datosCaso = $casosObj->CasoPorId($id);
$datosCliente = $clientesObj->ClientePorId($id);
//Obtener datos del titular
$idtitular = (isset($datosCaso->titularId))?$datosCaso->titularId:0;
$datosTitular = $usuariosObj->UserByID($idtitular);

$clienteId = (isset($datosCaso->clienteId))?$datosCaso->clienteId:0;
$idTipo = (isset($datosCaso->tipoId))?$datosCaso->tipoId:0;
$cliente = (isset($datosCliente->nombre))?$datosCliente->nombre:"";
// $idtitular = (isset($datosTitular->idUsuario))?$datosTitular->idUsuario:0;
$titular = (isset($datosTitular->nombre))?$datosTitular->nombre:"";
$fechaAlta = (isset($datosCaso->fechaAlta))?conversionFechaF2($datosCaso->fechaAlta):"";
$fechaAct = (isset($datosCaso->fechaAct))?conversionFechaF6($datosCaso->fechaAct):"";
//Obtener total de gastos
$colGastos = $accionGastosObj->ObtAccionGastos(0, $id);
$tGastos = 0;
foreach($colGastos as $elem){
    $tGastos += $elem->monto;
}
$tGastos = formatoMoneda($tGastos);
$autorizadosIds = (isset($datosCaso->autorizadosIds))?$datosCaso->autorizadosIds:"";
$arrIdsAutorizados = explode(",", $autorizadosIds);
// Obtener grid de acciones
$gridAcciones = $casoAccionesObj->ObtAccionesGrid($id,1);

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Ver Caso</title>
    <?php echo estilosPagina(true); ?>
</head>

<body>
	<?php echo getHeaderMain($_SESSION['myusername'], true);?>
    <?php $menu = getAdminMenu(); ?>

    <section class="section-internas">
    	<div class="panel-body">
    		<div class="container-fluid">
    			<div class="row">
    				<div class="colmenu col-md-2 menu_bg">
                        <?php echo getNav($menu); ?>
                    </div>

                    <div class="col-md-10">
                        <h1 class="titulo">Ver Caso<span class="pull-right"><a id="btnAyudaweb" onclick="mostrarAyuda('web_prospectos')" href="#fancyAyudaWeb"><img src="../images/icon_ayuda.png" width="20px"></a></span></h1>
                        <ol class="breadcrumb">
                            <li><a href="expedientes.php">Mis casos</a></li>
                            <li class="active">Ver caso</li>
                        </ol>

                        <!--Mostrar en caso de presionar el boton de guardar-->
                        <?php if ($msjResponse != "") { ?>
                            <div class="new_line alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?php echo $msjResponse; ?>
                            </div>
                        <?php } ?>

                        <div class="row">
                            <div class="col-md-offset-8 col-md-2">
                            </div>
                            <div class="col-md-2">
                                <a href="expedientes.php" class="btn btn-danger" role="button">Regresar</a>
                            </div>
                        </div>
                        <br>

                        <form role="form" id="formCaso" name="formCaso" method="post" action="">
                            <input type="hidden" name="form_caso">
                            <input type="hidden" name="c_id" id="c_id" value="<?php echo $id;?>">
                            <input type="hidden" name="c_colaccion" id="c_colaccion" value="0">

                            <div class="content_wrapper">
                                <div class="row">
                                    <!-- columna 1 -->
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-3 text-right">
                                                <label>ID:</label>
                                            </div>
                                            <div class="col-md-7">
                                                <input class="form-control" type="text" name="c_id" id="c_id" value="<?php echo $id;?>" style="width:50%;display:inline-block;" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 text-right">
                                                <label>Cliente:</label>
                                            </div>
                                            <div class="col-md-7 form-group">
                                                <input type="hidden" id="c_idcliente" name="c_idcliente" value="<?php echo $clienteId; ?>"/>
                                                <input type="text" id="c_cliente" name="c_cliente" value="<?php echo $cliente;?>" class="form-control required" readonly style="width:72%;display:inline-block;"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 text-right">
                                                <label>Tipo:</label>
                                            </div>
                                            <div class="col-md-7">
                                                <select id="c_tipo" name="c_tipo" class="form-control required sel_sololectura" style="width:90%;display:inline-block;">
                                                    <option value="">---Seleccionar---</option>
                                                        <?php
                                                            foreach ($colTipos as $elem) {
                                                                $sel = ($idTipo==$elem->idTipo)?"selected":"";
                                                                echo '<option '.$sel.' value="'.$elem->idTipo.'">'.$elem->nombre.'</option>';
                                                            }
                                                        ?>
                                                </select>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-md-3 text-right">
                                                <label>Responsable:</label>
                                            </div>
                                            <div class="col-md-7 form-group">
                                                <input type="hidden" id="c_idtitular" name="c_idtitular" value="<?php echo $idtitular;?>"/>
                                                <input type="text" id="c_titular" name="c_titular" value="<?php echo $titular;?>" class="form-control required" readonly style="width:80%;display:inline-block;"/>
                                                <!-- <button type="button" class="btn btn-primary" role="button" title="Buscar" id="busca_titular" value="Buscar" onclick="obtListaTitulares();">
                                                    <span class="glyphicon glyphicon-search"></span>
                                                </button> -->
                                            </div>
                                        </div>

                                        <!-- <div class="row">
                                            <div class="col-md-3 text-right">
                                                <label>Autorizados:</label>
                                            </div>
                                            <div class="col-md-9 form-group" style="margin-left:25%; width:135% !important;">
                                                <select id="c_autorizados" name="c_autorizados" multiple="multiple" class="duallb">
                                                    <?php
                                                    foreach ($colAbogados as $elem) {
                                                        if(in_array($elem->idUsuario, $arrIdsAutorizados)){
                                                            $sel = 'selected';
                                                        }else{
                                                            $sel = '';
                                                        }
                                                        echo '<option '.$sel.' value="'.$elem->idUsuario.'">'.$elem->nombre.'</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <input type="hidden" id="c_idsautorizados" name="c_idsautorizados" value="<?php echo $autorizadosIds;?>" />
                                            </div>
                                        </div> -->
                                    </div>

                                    <!-- columna 2 -->
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-3 text-right">
                                                <label>Fecha alta:</label>
                                            </div>
                                            <div class="col-md-7">
                                                <input class="form-control" type="text" name="c_falta" id="c_falta" value="<?php echo $fechaAlta;?>" style="width:50%;display:inline-block;" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 text-right">
                                                <label>Ult. Act.:</label>
                                            </div>
                                            <div class="col-md-7">
                                                <input class="form-control" type="text" name="c_fact" id="c_fact" value="<?php echo $fechaAct;?>" style="width:50%;display:inline-block;" readonly>
                                            </div>
                                        </div>
                                        <!--<div class="row">
                                            <div class="col-md-3 text-right">
                                                <label>Total Gastos:</label>
                                            </div>
                                            <div class="col-md-7">
                                                <input class="form-control text-right" type="text" name="c_tgastos" id="c_tgastos" value="<?php echo $tGastos;?>" style="width:50%;display:inline-block;" readonly>
                                            </div>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- <div class="row">
                            <div class="text-right col-md-12">
                                <a href="javascript:void(0);" onclick="popupCreaEditaAccion(<?php echo $id; ?>, 0)" title="Agregar acci&oacute;n" class="btn btn-primary"><img width="16px" src="../images/iconos/iconos_grid/agregar.png"> Agregar</a>
                            </div>
                        </div>
                        <br> -->
                        <form name="grids" method="post">
                            <?php
                            echo $koolajax->Render();
                            if($gridAcciones != null){
                                echo $gridAcciones->Render();
                            }
                            ?>
                        </form>
                    </div>
    			</div>
    		</div>
        </div>

        <!-- Modal lista de clientes -->
        <div class="modal fade" id="modalListaClientes" role="dialog" data-backdrop="static" data-keyboard="false" style="display:none;">
          <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Lista de clientes</h4>
                </div>

                <div class="row">
                    <div id="textoSeleccionado"></div>
                </div>

                <div class="row">

                  <form role="form" id="formListaClientes" name="formListaClientes" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="id_sel_cliente" id="id_sel_cliente" value="0">

                    <br>
                    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                        <div id="cont_listaclientes"></div>
                    </div>

                    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                      <div class="row">
                        <div class="col-md-offset-6 col-md-6 text-right">
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                          <a class="btn btn-primary" id="btnObt" onclick="btnObtIdCliente()">Aceptar</a>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12"><br/></div>
                  </form>

                </div>
            </div>
          </div>
        </div>

        <!-- Modal lista de clientes -->
        <div class="modal fade" id="modalListaTitulares" role="dialog" data-backdrop="static" data-keyboard="false" style="display:none;">
          <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Lista de Titulares</h4>
                </div>

                <div class="row">
                    <div id="textoSeleccionadoTit"></div>
                </div>

                <div class="row">

                  <form role="form" id="formListatitulares" name="formListatitulares" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="id_sel_titular" id="id_sel_titular" value="0">

                    <br>
                    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                        <div id="cont_listatitulares"></div>
                    </div>

                    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                      <div class="row">
                        <div class="col-md-offset-6 col-md-6 text-right">
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                          <a class="btn btn-primary" id="btnObt" onclick="btnObtIdTitular()">Aceptar</a>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12"><br/></div>
                  </form>

                </div>
            </div>
          </div>
        </div>

        <!-- Modal crear cliente -->
        <div class="modal fade" id="popup_modalCrearCliente" role="dialog" data-backdrop="static" data-keyboard="false" style="display:none;">
          <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Crear Cliente</h4>
                </div>
                <div class="row">

                  <form role="form" id="formCrearCliente" name="formCrearCliente" method="post" action="" enctype="multipart/form-data">
                    <!-- <input type="hidden" name="id_sel_titular" id="id_sel_titular" value="0"> -->
                    <br>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                              <label for="pc_cliente">Cliente:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control required" name="pc_cliente" id="pc_cliente">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                              <label for="pc_tel">Tel&eacute;fono:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control digits" name="pc_tel" id="pc_tel">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                              <label for="pc_email">Correo:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control email" name="pc_email" id="pc_email">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                              <label for="pc_dir">Direcci&oacute;n:</label>
                            </div>
                            <div class="col-md-8">
                                <!-- <textarea class="form-control" name="pc_dir" id="pc_dir" rows="6"></textarea> -->
                                <input type="text" class="form-control" name="pc_dir" id="pc_dir">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                      <div class="row">
                        <div class="col-md-offset-6 col-md-3 text-right">
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="col-md-3 text-right">
                          <a class="btn btn-primary" id="btnCrearCliente" onclick="btnCrearCliente();">Aceptar</a>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12"><br/></div>
                  </form>

                </div>
            </div>
          </div>
        </div>

        <!-- Modal crear tipo -->
        <div class="modal fade" id="popup_modalCrearTipo" role="dialog" data-backdrop="static" data-keyboard="false" style="display:none;">
          <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Crear Tipo</h4>
                </div>
                <div class="row">

                  <form role="form" id="formCrearTipo" name="formCrearTipo" method="post" action="" enctype="multipart/form-data">
                    <br>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                              <label for="pc_tipo">Tipo:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control required" name="pc_tipo" id="pc_tipo">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                      <div class="row">
                        <div class="col-md-offset-6 col-md-3 text-right">
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="col-md-3 text-right">
                          <a class="btn btn-primary" id="btnCrearTipo" onclick="btnCrearTipo();">Aceptar</a>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12"><br/></div>
                  </form>

                </div>
            </div>
          </div>
        </div>

        <!-- Modal crear accion -->
        <div class="modal fade" id="popup_modalCrearAccion" role="dialog" data-backdrop="static" data-keyboard="false" style="display:none;">
          <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Detalle Acci&oacute;n</h4>
                </div>
                <div class="row">
                  <form role="form" id="formCrearAccion" name="formCrearAccion" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="pa_casoid" id="pa_casoid" value="<?php echo $id;?>">
                    <input type="hidden" name="pa_idaccion" id="pa_idaccion" value="0">
                    <br>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Fecha:</label>
                            </div>
                            <div class="col-md-7">
                                <input class="form-control required" type="text" name="pa_fechaaccion" id="pa_fechaaccion" value="<?php echo $tz->fechaF2;?>" style="width:50%;display:inline-block;" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                              <label for="pa_accion">Acci&oacute;n:</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control required" name="pa_accion" id="pa_accion" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                              <label for="pa_comentario">Comentario:</label>
                            </div>
                            <div class="col-md-7">
                                <textarea class="form-control" name="pa_comentario" id="pa_comentario" rows="6" readonly></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                      <div class="row">
                        <div class="col-md-offset-6 col-md-3 text-right">
                          <!-- <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button> -->
                        </div>
                        <div class="col-md-3 text-right">
                          <!-- <a class="btn btn-primary" id="btnCrearTipo" onclick="btnCreaEditaAccion();">Aceptar</a> -->
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
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
                </div>
            </div>
          </div>
        </div>

        <!-- Modal crear gasto -->
        <div class="modal fade" id="popup_modalCrearGasto" role="dialog" data-backdrop="static" data-keyboard="false" style="display:none;">
          <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Crea/Edita Gasto</h4>
                </div>
                <div class="row">
                  <form role="form" id="formCrearGasto" name="formCrearGasto" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="pg_casoid" id="pg_casoid" value="<?php echo $id;?>">
                    <input type="hidden" name="pg_idaccion" id="pg_idaccion" value="0">
                    <input type="hidden" name="pg_idgasto" id="pg_idgasto" value="0">

                    <br>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-3">
                              <label for="pg_accion">Acci&oacute;n:</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="pg_accion" id="pg_accion" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Fecha:</label>
                            </div>
                            <div class="col-md-7">
                                <input class="form-control inputfechaGral required" type="text" name="pg_fechagasto" id="pg_fechagasto" value="<?php echo $tz->fechaF2;?>" style="width:50%;display:inline-block;" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Concepto:</label>
                            </div>
                            <div class="col-md-7">
                                <select id="pg_idconcepto" name="pg_idconcepto" class="form-control required">
                                    <option value="">---Seleccionar---</option>
                                        <?php
                                            foreach ($colConceptos as $elem) {
                                                echo '<option value="'.$elem->idConcepto.'">'.$elem->nombre.'</option>';
                                            }
                                        ?>
                                </select>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-3">
                              <label for="pg_monto">Monto:</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control text-right required" name="pg_monto" id="pg_monto" value="$0.00">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                      <div class="row">
                        <div class="col-md-offset-6 col-md-3 text-right">
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="col-md-3 text-right">
                          <a class="btn btn-primary" id="btnCrearGasto" onclick="btnCreaEditaGasto();">Aceptar</a>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12"><br/></div>
                  </form>
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
    <?php echo scriptsPagina(true); ?>
    <script>
        $(document).ready(function(){
            <?php if( isset($res) && $res==1){ ?>
                alertify.success("Informaci&oacute;n guardada correctamente.");
                setTimeout(function(){
                    window.location.href = "frmUsuario.php?id="+'<?php echo $idUsuario?>';
                }, 500);
            <?php } ?>
        });
    </script>
</body>

</html>
