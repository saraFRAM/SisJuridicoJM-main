<?php
session_start();
$checkRol = ($_SESSION['idRol']==1 || $_SESSION['idRol']==2 || $_SESSION['idRol']==5) ?true :false;

if($_SESSION['status']!= "ok" || $checkRol!=true)
        header("location: logout.php");

include_once '../common/screenPortions.php';
include_once '../brules/utilsObj.php';
require_once '../brules/KoolControls/KoolAjax/koolajax.php';
libreriasKool();
include_once '../brules/casosObj.php';
include_once '../brules/casoAccionesObj.php';
include_once '../brules/catPartesObj.php';
include_once '../brules/catMateriasObj.php';
include_once '../brules/catJuiciosObj.php';
include_once '../brules/catJuzgadosObj.php';
include_once '../brules/clientesObj.php';
include_once '../brules/usuariosObj.php';
include_once '../brules/mensajesObj.php';

include_once 'filtrosExpGrid.php';

$casosObj = new casosObj();
$usuariosObj = new usuariosObj();
$casoAccionesObj = new casoAccionesObj();


$usuario = $usuariosObj->UserByID($_SESSION["idUsuario"]);

//Obtener el id del cliente
$idCliente = -1;
if($_SESSION['idRol']==3){
    $idCliente = $_SESSION['clienteId'];
}
//Obtener aquellos que pertenecen al abogado
$idAbogado = -1;
$titularId = "";
$filtroTitular = "";
if($_SESSION['idRol']==1){
    $titularId = $_SESSION['idUsuario'];
    if($usuario->titularTodos > 0){
        $titularId = "";
        $filtroTitular = $usuario->nombre;
    }
}
elseif($_SESSION['idRol']==5){
    $idAbogado = $_SESSION['idUsuario'];
}

$activar = 0;
if(isset($_POST["activar"])){
    $activar = $_POST["activar"];
}

$filSel = "";
$filTexto = "";
$filEstatus = "";

$responsables = (isset($_GET["responsables"]) && $_GET["responsables"] != 'null')?$_GET["responsables"]:'';
$clientes = (isset($_GET["clientes"]) && $_GET["clientes"] != 'null')?$_GET["clientes"]:'';
$estatus = (isset($_GET["estatus"]) && $_GET["estatus"] != 'null')?$_GET["estatus"]:'';
$juicios = (isset($_GET["juicios"]) && $_GET["juicios"] != 'null')?$_GET["juicios"]:'';
$juzgados = (isset($_GET["juzgados"]) && $_GET["juzgados"] != 'null')?$_GET["juzgados"]:'';

//$result = $casosObj->ObtListadoCasosGrid($idCliente, $idAbogado, $filSel, $filTexto, $filEstatus, $titularId, $filtroTitular,$pantalla=0);
//Jair 17/2/2022 Obtener el grid y los ids seleccionados
list($result,$selected_keys) = $casosObj->ObtListadoCasosGrid($idCliente, $idAbogado, $filSel, $filTexto, $filEstatus, $titularId, $filtroTitular,$pantalla=0, $activar, $responsables, $clientes, $estatus, $juicios, $juzgados);
//$selected_keys = $result->GetInstanceMasterTable()->SelectedKeys;
//echo 'tengo';print_r($selected_keys);
$mensaje = '';
$warning = '';
$contUpd = 0;
if(isset($_POST["boton"])){
    // echo "boton: ".$_POST["boton"]." ";
    $seleccionados = $_POST["seleccionados"];//Jair 25/2/2022 Obtener seleccionados de la forma nueva
    $arrSeleccionados = explode(",", $seleccionados);

    if (sizeof($arrSeleccionados)>0){
        if($_POST["boton"] == 'cambiarres'){
            $responsableId = $_POST["responsableId"];
            if($responsableId!= ''){
                // echo "You selected rows with <b>idCaso = [ ";
                for($i=0;$i<sizeof($arrSeleccionados);$i++){
                    // echo $selected_keys[$i]["idCaso"];
                    $expedienteId = $arrSeleccionados[$i];
                    $expediente = $casosObj->CasoPorId($expedienteId);
                    $res = $casosObj->ActCampoCaso("responsableId", $responsableId, $expedienteId);
                    $titularId = $expediente->titularId2;
                    $usuarioIdSesion = $_SESSION["idUsuario"];
                    $numExpediente = $expediente->numExpediente;
                    
                    if($res > 0){
                        $contUpd++;
                        $actividades = $casoAccionesObj->ObtCasoAcciones($expedienteId, 0, '1,2,3', '', '', '', '', $expediente->responsableId);//Obtener actividades del caso donde el responsable sea el responsable anterior
                        
                        foreach ($actividades as $actividad) {
                            $resAc = $casoAccionesObj->ActCampoAccion("responsableId", $responsableId, $actividad->idAccion);
                        }

                        //Notificacion
                        $arrMensajeId =  array();
                        $arrMensajes = array();

                        if($responsableId > 0){
                            $arrMensajeId[] = $responsableId;
                        }
                        if($titularId > 0){
                            $arrMensajeId[] = $titularId;
                        }

                        $arrIdsReg[] = "responsable: ".$responsableId;
                        $arrIdsReg[] = "titular: ".$titularId;
                        $arrIdsReg[] = "sesion: ".$usuarioIdSesion;

                        if($res >0){
                            $arrMensajes[] = array(
                            "titulo" => "Cambio responsable al expediente ".$numExpediente."",
                            "mensaje" => "Se ha asignado nuevo respomsable al expediente ".$numExpediente.""
                            );
                            $arrUpd[] = "tipo";
                        }

                        //Jair 3/3/2022 Cambie el codigo por funcion
                        enviarNotificacion($arrMensajes, $arrMensajeId, 1, $expedienteId, $usuarioIdSesion);

                        //Jair 3/3/2022 Enviar notificacion al ex responsable
                        if($resResponsable >0){
                            $arrMensajes = array();
                            $arrMensajes[] = array(
                            "titulo" => "Fuiste removido como responsable ",
                            "mensaje" => "Has sido removido como respomsable del expediente ".$numExpediente.""
                            );
                            $arrMensajeId =  array();
                            $arrMensajeId[] = $expediente->responsableId;

                            enviarNotificacion($arrMensajes, $arrMensajeId, -1, $expedienteId, $usuarioIdSesion);
                        }

                    }
                }
            }
                // echo " ]</b>";

                if($contUpd > 0){
                    // list($result,$selected_keys) = $casosObj->ObtListadoCasosGrid($idCliente, $idAbogado, $filSel, $filTexto, $filEstatus, $titularId, $filtroTitular,$pantalla=0, $activar, $responsables, $clientes, $estatus);
                }

        }else{
                $warning = 'Por favor seleccione un responsable';
        }
    }
	else{
        $warning = 'No ha seleccionado ningun expediente';
    }
}


$colRes = $usuariosObj->obtTodosUsuarios(true, 4, "", " numAbogado ASC ");
$arrResponsable = array();
$arrResponsable[] = array("valor"=>"", "texto"=>"Seleccione...");

foreach ($colRes as $itemRes) {
    $arrResponsable[] = array("valor"=>$itemRes->idUsuario, "texto"=>$itemRes->nombre);    
}

$claseResponsable = ($_SESSION["idRol"] == 4)?"likeDisaled":"";// 20/1/2022 Jair Solo lectura para abogados
$dataInput = array("required"=>"");
$arrCampoResponsable = array(
    array("nameid"=>"responsableId", "type"=>"select", "class"=>"form-control required ".$claseResponsable, "readonly"=>false, "label"=>"Responsable:", "datos"=>$arrResponsable, "value"=>"", "claseRow"=>"cambiaRes oculto", "required"=>"required"),
);
    //NO BORRAR ES PARA LA EXPORTACIÓN
    if(isset($_POST["IgnorePaging"])){
		$result->ExportSettings->IgnorePaging = true;
	}
 
	if(isset($_POST["ExportToExcel"])){
		ob_end_clean();
		$result->GetInstanceMasterTable()->ExportToExcel();
	}	
	if(isset($_POST["ExportToWord"])){
		ob_end_clean();
		$result->GetInstanceMasterTable()->ExportToWord();
	}	
	if(isset($_POST["ExportToCSV"])){
		ob_end_clean();
		$result->GetInstanceMasterTable()->ExportToCSV();
	}
	if(isset($_POST["ExportToPDF"])){
		ob_end_clean();
		$result->GetInstanceMasterTable()->ExportToPDF();
	}	
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Expedientes</title>
    <?php echo estilosPagina(true); ?>
</head>
<body>
    <?php echo getHeaderMain($_SESSION['myusername'], true, 'externo');?>
    <?php $menu = getAdminMenu(); ?>

    <input type="hidden" name="vista" id="vista" value="expedientes">

    <section class="section-internas">
        <div class="panel-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="colmenu col-md-2 menu_bg ">
                        <?php echo getNav($menu); ?>
                    </div>
                    <div class="col-md-10">
                        <h1 class="titulo">Expedientes<span class="pull-right"><a id="btnAyudaweb" onclick="mostrarAyuda('web_expedientes')" href="#fancyAyudaWeb"><img src="../images/icon_ayuda.png" width="20px"></a></span></h1>

                        <ol class="breadcrumb">
                            <li><a href="index.php">Inicio</a></li>
                            <li class="active">Expedientes</li>
                        </ol>

                        <div class="row">
                            <div class="col-md-2 text-right">
                                <a href="historico.php" class="btn btn-primary">Historico</a>
                            </div>   
                            <div class="col-xs-2">
                                <?php if(($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2) && $activar == 0){ ?>
                                    <form method="post" name="formActivar" id="formActivar" action="">
                                        <input type="hidden" name="activar" id="activar" value="1">
                                        <input class="btn btn-primary" type="submit" value="Activar Multi">
                                    </form>
                                <?php } ?>
                            </div> 
                            <div class="col-xs-2">
                                <?php if(($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2)  && $activar == 1){ ?>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle"
                                            data-toggle="dropdown">
                                    Acciones
                                    <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                    <li><a onclick="muestraDivsClase('cambiaRes')">Cambiar responsable</a></li>
                                    
                                    </ul>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="col-xs-offset-2 col-xs-2 text-right">
                                
                                <?php if($_SESSION['idRol']==1 || $_SESSION['idRol']==2 || $_SESSION['idRol']==4){ ?>
                                <a href="frmExpediente.php?id=0" class="btn btn-primary">Nuevo</a>
                                <?php } ?>
                            </div>
                            <div class="col-xs-2 text-right">
                                <a href="index.php"><input type="button" id="regresar" value="Regresar" class="btn"></a>
                            </div>
                        </div>
                        <br/>

                        <!-- <div class="filtro">
                            <form role="form" id="frmFilProcesos" name="frmFilProcesos" method="get" action="">
                                <div class="row">
                                    <div class="text-right form-group col-md-3 col-sm-3 col-xs-3 alinear">
                                        <input type="text" id="filTexto" name="filTexto" value="<?php echo $filTexto; ?>" class="">
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <select class="form-control" id="filSel" name="filSel">
                                            <option value="">--Seleccionar--</option>
                                            <option value="1" <?php echo ($filSel==1)?"selected":""; ?>>Nombre</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 col-sm-1 col-xs-1 form-group">
                                        Estatus
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <select class="form-control " id="filEstatus" name="filEstatus">
                                            <option value="">--Seleccionar--</option>
                                            ?php
                                            foreach ($colEstatus as $elem) { ?>
                                                <option value="?php echo $elem->idEstatus;?>">?php echo $elem->nombre; ?></option>
                                            ?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <div style="display:inline-block;">
                                            <button type="submit" class="btn btn-primary" role="button" title="Buscar" name="buscarProceso" id="buscarProceso" value="Buscar">
                                                <span class="glyphicon glyphicon-search"></span>
                                            </button>
                                        </div>
                                        <div style="display:inline-block;">
                                            <button type="button" class="btn btn-primary" role="button" value="Limpiar" title="Limpiar" onclick="limpiarBusqueda();">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div> -->

                        <div class="row">
                            <div class="col-xs-12">
                            <?php
                            if($contUpd > 0){
                                echo '<div class="alert alert-info">
                                Se ha cambiado el responsable en '.$contUpd.' expedientes.
                            </div>';
                            }

                            if($warning != ''){
                                echo '<div class="alert alert-warning">
                                '.$warning.'
                              </div>';
                            }

                            ?>
                            </div>  
                        </div>
                        <br>
                        <form name="grids" method="post" action="">
                            <?php
                            echo $koolajax->Render();
                            if($result != null){
                                ?>
                                <div class="row">
                                    <!--<div class="col-xs-8">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading">Filtrar</div>
                                            <div class="panel-body">
                                                <div class="col-xs-6">-->
                                                    <!-- <h4>Filtros</h4> -->
                                                <!--    <?php echo obtenerCampoResponsables($responsables); ?>
                                                    <?php echo obtenerCampoClientes($clientes); ?>
                                                    <?php echo obtenerCampoJuzgados($juzgados); ?>
                                                    
                                                </div>
                                                <div class="col-xs-6">
                                                    <?php echo obtenerCampoEstatus($estatus); ?>
                                                    <?php echo obtenerCampoJuicios($juicios); ?>
                                                    <a onclick="filtrarExpedientes()" class="btn btn-primary">Filtrar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>-->
                                    <div class="col-xs-4">
                                                <!--<div class="panel panel-warning">
                                            <div class="panel-heading">Exportar</div>
                                            <div class="panel-body">
                                                <div class="col-xs-12">
                                                    <div >
                                                        <input type="checkbox" id="IgnorePaging" name="IgnorePaging"/> <label for="IgnorePaging">Expotar todas las paginas</label>
                                                        <br/><br/>
                                                        <input class="btn btn-primary" type="submit" name="ExportToCSV" value = "Exportar a CSV" />
                                                        <input type="submit" name="ExportToExcel" value = "Exportar a Excel" />
                                                        <input class="btn btn-primary"type="submit" name="ExportToWord" value = "Exportar a Word" />
                                                        <input type="submit" name="ExportToPDF" value = "Exportar a PDF" />
                                                    </div>
                                                </div>
                                            </div>
                                                </div>-->
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <input type="hidden" name="seleccionados" id="seleccionados" value="">
                                    <?php 
                                    $cols = array("label_xs"=>"2", "label_md"=>"2", "input_xs"=>"4", "input_md"=>"4");
                                        echo generaHtmlForm($arrCampoResponsable, $cols); 
                                    ?>
                                    <div class="row"></div>
                                    <div class="row">
                                        <div class="col-xs-2"></div>
                                        <div class="col-xs-2">
                                            <button class="btn btn-primary cambiaRes" type="submit" name="boton" value="cambiarres" style="display: none;">Cambiar res</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row">
                                    <div class="cont_grid" id="cont_grid_expedientes">
                                    <?php
                                        echo $result->Render();
                                    ?>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal ver eventos sincronizados  -->
        <!-- <div class="modal fade" id="popup_vereventoscal" role="dialog" data-backdrop="static" data-keyboard="false" style="display:none;">
          <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Eventos Localizados</h4>
                </div>
                <div class="row">
                  <form role="form" id="formEventosCal" name="formEventosCal" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="evcal_casoid" id="evcal_casoid">
                    <input type="hidden" name="evecal_clienteid" id="evecal_clienteid">

                    <div class="col-md-offset-2 col-md-10 col-md-offset-1">
                        <div class="row">
                            <div class="col-md-3">
                              <label for="cliente_ev">Cliente:</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" id="cliente_ev" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-offset-1 col-md-10" id="verEventos">
                        <table class="table table-bordered table-condensed dataTable no-footer dt-responsive " role="grid" cellspacing="0" width="100%" >
                            <tbody id="cont_eventos">
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-offset-2 col-md-10 col-md-offset-1">
                      <div class="row">
                        <div class="col-md-offset-6 col-md-2 text-right">
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="col-md-3 text-right">
                          <a class="btn btn-primary" id="btnSalvarEventos" onclick="btnSalvarEventos();">Agregar seleccionados</a>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12"><br/></div>
                  </form>
                </div>
            </div>
          </div>
        </div> -->
    </section>
    <footer>
        <div class="panel-footer">
            <?php echo getPiePag(true); ?>
        </div>
    </footer>

    <?php echo scriptsPagina(true); ?>
    <script>
        $(document).ready(function(){
            let contUpd = <?php echo $contUpd?>;
            if(contUpd > 0){
                casos.refresh();
                casos.commit();
            }
        });
    </script>
</body>
</html>
