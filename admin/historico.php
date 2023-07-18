<?php
session_start();
$checkRol = ($_SESSION['idRol']==1 || $_SESSION['idRol']==2 || $_SESSION['idRol']==3 || $_SESSION['idRol']==4) ?true :false;

if($_SESSION['status']!= "ok" || $checkRol!=true)
        header("location: logout.php");

include_once '../common/screenPortions.php';
include_once '../brules/utilsObj.php';
include_once '../brules/arraysObj.php';
require_once '../brules/KoolControls/KoolAjax/koolajax.php';
libreriasKool();
include_once '../brules/casosObj.php';
include_once '../brules/casoAccionesObj.php';
include_once '../brules/catPartesObj.php';
include_once '../brules/catMateriasObj.php';
include_once '../brules/catJuiciosObj.php';
include_once '../brules/catJuzgadosObj.php';
include_once '../brules/catDistritosObj.php';
include_once '../brules/clientesObj.php';//LDAH IMP 23/08/2022 Filtros en historico
include_once '../brules/usuariosObj.php';
include_once '../brules/mensajesObj.php';
include_once '../brules/arraysObj.php';
include_once 'filtrosExpGrid.php'; //LDAH IMP 23/08/2022 Filtros en historico

$casosObj = new casosObj();
$usuariosObj = new usuariosObj();
$casoAccionesObj = new casoAccionesObj();

$usuario = $usuariosObj->UserByID($_SESSION["idUsuario"]);
$estadisticaArr = $casosObj->obtenerEstadisticaExpedientes();
$casosPorAbogadoArr = $casosObj->expedientesPorabogado();

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
elseif($_SESSION['idRol']==4){
    $idAbogado = $_SESSION['idUsuario'];
}

//LDAH IMP 23/08/2022 Filtros en historico
$activar = 0;
if(isset($_POST["activar"])){
    $activar = $_POST["activar"];
}

$filSel = "";
$filTexto = "";
$filEstatus = "";

$arrCampoRepresentado = array(
    array("nameid"=>"representado", "type"=>"text", "class"=>"form-control", "readonly"=>false, "label"=>"Representado:", "datos"=>array(), "value"=>""),
  );
$caso = (isset($_GET["caso"]) && $_GET["caso"] != 'null')?$_GET["caso"]:'';
//echo $caso;
$responsables = (isset($_GET["responsables"]) && $_GET["responsables"] != 'null')?$_GET["responsables"]:'';
$clientes = (isset($_GET["clientes"]) && $_GET["clientes"] != 'null')?$_GET["clientes"]:'';
//echo $clientes;
$estatus = (isset($_GET["estatus"]) && $_GET["estatus"] != 'null')?$_GET["estatus"]:'';
$juicios = (isset($_GET["juicios"]) && $_GET["juicios"] != 'null')?$_GET["juicios"]:'';
$materias = (isset($_GET["materias"]) && $_GET["materias"] != 'null')?$_GET["materias"]:'';
$juzgados = (isset($_GET["juzgados"]) && $_GET["juzgados"] != 'null')?$_GET["juzgados"]:'';
$camposIds = (isset($_GET["camposIds"]) && $_GET["camposIds"] != 'null')?$_GET["camposIds"]:'';
$mostrarCamposTitular = (isset($_GET["mostrarCamposTitular"]) && $_GET["mostrarCamposTitular"] != 'null')?$_GET["mostrarCamposTitular"]:'';
$mostrarCamposGrid = (isset($_GET["mostrarCamposGrid"]) && $_GET["mostrarCamposGrid"] != 'null')?$_GET["mostrarCamposGrid"]:$usuario->camposGridExp;
$distritos = (isset($_GET["distritos"]) && $_GET["distritos"] != 'null')?$_GET["distritos"]:'';


$clientesno = (isset($_GET["clientesno"]) && $_GET["clientesno"] != 'null')?$_GET["clientesno"]:'';

if($mostrarCamposGrid != ''){
    $usuariosObj->ActualizarUsuario("camposGridExp", $mostrarCamposGrid, $usuario->idUsuario);
}
//list($result,$selected_keys) = $casosObj->ObtListadoCasosGrid($idCliente, $idAbogado, $filSel, $filTexto, $filEstatus, $titularId, $filtroTitular,$pantalla=1);
//LDAH IMP 23/08/2022 Filtros en historico

list($result,$selected_keys) = $casosObj->ObtListadoCasosGrid($idCliente, $idAbogado, $mostrarCamposTitular, $filTexto, $filEstatus, $titularId, $filtroTitular,$pantalla=1, $activar, $responsables, $clientes, $estatus, $juicios, $juzgados, $materias, $camposIds, $mostrarCamposGrid, $clientesno, $caso, $distritos);
//$selected_keys = $result->GetInstanceMasterTable()->SelectedKeys;
//echo 'tengo';print_r($result->Render());
$mensaje = '';
$warning = '';
$contUpd = 0;
if(isset($_POST["boton"])){
    // echo "boton: ".$_POST["boton"]." ";
    $seleccionados = $_POST["seleccionados"];//Jair 25/2/2022 Obtener seleccionados de la forma nueva
    $arrSeleccionados = explode(",", $seleccionados);
    $fechaHoy = date("Y-m-d").' 00:00';//JGP 25/03/23 para incluir actividades futuras

    if (sizeof($arrSeleccionados)>0){
        $reload  = false;

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
                    
                    //ahora notificamos los cambios
                    if($res > 0){
                        $contUpd++;
                        $actividades = $casoAccionesObj->ObtCasoAcciones($expedienteId, 0, '1,2,3', '', '', '', '', '', $fechaHoy);//Obtener actividades del caso donde el responsable sea el responsable anterior
                        
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
            }else{
                $warning = 'Por favor seleccione un responsable';
            }

        }
        elseif($_POST["boton"] == 'cambiarEstatus'){
            $estatusId = $_POST["cambioEstatusId"];
            
            if($estatusId!= ''){
                for($i=0;$i<sizeof($arrSeleccionados);$i++){
                    $reload = true;
                    // echo $selected_keys[$i]["idCaso"];
                    $expedienteId = $arrSeleccionados[$i];
                    $expediente = $casosObj->CasoPorId($expedienteId);
                    $res = $casosObj->ActCampoCaso("estatusId", $estatusId, $expedienteId);
                }
            }else{
                $warning = 'Por favor seleccione el nuevo estatus';
            }

        }
        if($reload){
            header("Refresh:0");
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

//para select de cambio de estatus
$arrEstatus[] = array("valor"=>1, "texto"=>'Activo');
$arrEstatus[] = array("valor"=>5, "texto"=>'Prospecto');
$arrEstatus[] = array("valor"=>2, "texto"=>'Suspendido');
$arrEstatus[] = array("valor"=>3, "texto"=>'Baja');
$arrEstatus[] = array("valor"=>4, "texto"=>'Terminado');
 
//campo cambio estatus
$arrCampoEstatus = array(
    array("nameid"=>"cambioEstatusId", "type"=>"select", "class"=>"form-control required", "readonly"=>false, "label"=>"Estatus:", "datos"=>$arrEstatus, "value"=>0, "claseRow"=>"cambiarEstatus oculto"),
);



$claseResponsable = ($_SESSION["idRol"] == 4)?"likeDisaled":"";// 20/1/2022 Jair Solo lectura para abogados
$valueResponsable = ($_SESSION["idRol"] == 4)?$_SESSION["idUsuario"]:"";
$dataInput = array("required"=>"");
$arrCampoResponsable = array(
    array("nameid"=>"responsableId", "type"=>"select", "class"=>"form-control required ".$claseResponsable, "readonly"=>false, "label"=>"Responsable:", "datos"=>$arrResponsable, "value"=>$valueResponsable, "claseRow"=>"cambiaRes oculto", "required"=>"required"),
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
    <title>Historico Expedientes</title>
    <?php echo estilosPagina(true); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
</head>
<body>
    <?php echo getHeaderMain($_SESSION['myusername'], true);?>
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
                        <h1 class="titulo">Historico Expedientes<span class="pull-right"><a id="btnAyudaweb" onclick="mostrarAyuda('web_expedientes')" href="#fancyAyudaWeb"><img src="../images/icon_ayuda.png" width="20px"></a></span></h1>

                        <ol class="breadcrumb">
                            <li><a href="index.php">Inicio</a></li>
                            <li><a href="expedientes.php">Expedientes</a></li>
                            <li class="active">Historico Expedientes</li>
                        </ol>

                        <div class="row">
                            <div class="col-xs-4 col-sm-2 text-right">
                                <!---CMPB 30/03/2023 que los usuarios titulares lleven pre filtrado los campos de titular-->
                                 <?php if($_SESSION['idRol'] == 1){ ?>
                                    <a href="historico.php?mostrarCamposTitular=1&camposIds=3,4" class="btn btn-primary">Historico</a>
                                <?php } else{ ?>
                                        <a href="historico.php" class="btn btn-primary">Historico</a>
                                    <?php } ?>
                            </div>   
                            <div class="col-xs-4 col-sm-2">
                                <?php if(($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2) && $activar == 0){ ?>
                                    <form method="post" name="formActivar" id="formActivar" action="">
                                        <input type="hidden" name="activar" id="activar" value="1">
                                        <input class="btn btn-primary" type="submit" value="Activar Multi">
                                    </form>
                                <?php } ?>
                            </div> 
                            <div class="col-xs-4 col-sm-2">
                                <?php if(($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2)  && $activar == 1){ ?>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle"
                                            data-toggle="dropdown">
                                    Acciones
                                    <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a onclick="muestraDivsClase('cambiaRes')">Cambiar Responsable</a></li>
                                        <li><a onclick="muestraDivsClase('cambiarEstatus')">Cambiar Estatus</a></li>
                                    
                                    </ul>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="col-xs-4 col-sm-2">
                                <?php if(($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2)){ ?>
                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#popup_estadisticas" class="btn btn-primary">Ver Estadistica</a>
                                <?php } ?>
                            </div>
                            <div class="col-xs-4 col-sm-2 text-right">
                                
                                <?php if($_SESSION['idRol']==1 || $_SESSION['idRol']==2 || $_SESSION['idRol']==4){ ?>
                                <a href="frmExpediente.php?id=0" target="_blank" class="btn btn-primary">Nuevo</a>
                                <?php } ?>
                            </div>
                            <div class="col-xs-4 col-sm-2 text-right">
                                <a href="expedientes.php"><input type="button" id="regresar" value="Regresar" class="btn"></a>
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
                                    <div class="col-xs-12 col-sm-8">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading">Filtrar</div>
                                            <div class="panel-body">
                                                <div class="col-xs-6">
                                                    <!-- <h4>Filtros</h4> -->
                                                    <?php echo obtenerCampoResponsables($responsables); ?>
                                                    <?php echo obtenerCampoClientes($clientes); ?>
                                                    <?php echo obtenerCampoClientesExep($clientesno); ?>
                                                    <?php echo obtenerCampoJuzgados($juzgados); ?>
                                                    <?php echo obtenerCampoTitular($mostrarCamposTitular); ?>
                                                    <?php echo obtenerCamposGrid($mostrarCamposGrid); ?>
                                                    <?php
                                                    $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"7", "input_md"=>"7");
                                                    echo generaHtmlForm($arrCampoRepresentado, $cols);
                                                ?>
                                                </div>
                                                <div class="col-xs-6">
                                                    <?php echo obtenerCampoEstatus($estatus,true); ?>
                                                    <?php echo obtenerCampoMaterias($materias); ?>
                                                    <?php echo obtenerCampoJuicios($juicios); ?>
                                                    <?php echo obtenerCampoCampos($camposIds); ?>
                                                    <?php echo obtenerCampoDistritos($distritos); ?>
                                                    <a onclick="filtrarExpedientesHis()" class="btn btn-primary">Filtrar</a>  <!-- //LDAH IMP 23/08/2022 Filtros en historico -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading">Exportar</div>
                                            <div class="panel-body">
                                                <div class="col-xs-12">
                                                    <div >
                                                        <input type="checkbox" id="IgnorePaging" name="IgnorePaging"/> <label for="IgnorePaging">Exportar todas las paginas</label>
                                                        <br/><br/>
                                                        <input class="btn btn-primary" type="submit" name="ExportToCSV" value = "Exportar a CSV" />
                                                        <!--<input type="submit" name="ExportToExcel" value = "Exportar a Excel" />-->
                                                        <input class="btn btn-primary"type="submit" name="ExportToWord" value = "Exportar a Word" />
                                                        <!--<input type="submit" name="ExportToPDF" value = "Exportar a PDF" />-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
								<!-- Filtros fin-->
                                <div class="row"> <!-- nuevo -->
                                    <input type="hidden" name="seleccionados" id="seleccionados" value="">
                                    <?php 
                                    $cols = array("label_xs"=>"2", "label_md"=>"2", "input_xs"=>"4", "input_md"=>"4");
                                        echo generaHtmlForm($arrCampoResponsable, $cols); 
                                    ?>
                                    <div class="row"></div>
                                    <div class="row">
                                        <div class="col-xs-2"></div>
                                        <div class="col-xs-2">
                                            <button class="btn btn-primary cambiaRes" type="submit" name="boton" value="cambiarres" style="display: none;">Cambiar responsable</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php 
                                        $cols = array("label_xs"=>"2", "label_md"=>"2", "input_xs"=>"4", "input_md"=>"4");
                                        echo generaHtmlForm($arrCampoEstatus, $cols);
                                    ?>
                                    <div class="row"><br/></div>
                                    <div class="row">
                                        <div class="col-xs-2"></div>
                                        <div class="col-xs-2">
                                            <button class="btn btn-primary cambiarEstatus oculto" type="submit" name="boton" value="cambiarEstatus">Cambiar estatus</button>
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

        <!-- Modal ver proximos eventos -->
        <div class="modal fade" id="popup_modalVerEventos" role="dialog" data-backdrop="static" data-keyboard="false" >
          <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Proximos Eventos</h4>
                </div>
                <div id="row-proxEventos">

                </div>
            </div>
          </div>
        </div>

        <!-- Modal ver ArbolCasos -->
        <div class="modal fade" id="popup_modalArbolCasos" role="dialog" data-backdrop="static" data-keyboard="false" >
          <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Arbol Casos</h4>
                    <button id="print-modal-content" class="btn btn-primary" onclick="printModalContent()"style="margin-left: 410px; margin-top: 0px;">Imprimir</button>
                </div>
                <div id="row-ArbolCasos"  id="modal-contenido-arbol">
                    <div class="container_tree" id="imprimir-arbol">        
                        <div id="treemain">
                            
                        </div>        
                    </div>      
                </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="popup_estadisticas" role="dialog" data-backdrop="static" data-keyboard="false" >
          <div class="modal-dialog">
            <div class="modal-content">
                <?php if($_SESSION['idRol']==1 || $_SESSION['idRol']==2 ){ ?>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Estadisticas Expedientes</h4>
                    </div>
                    <div id="row-proxEventos"  id="modal-contenido-estadistica">
                        <div class="container-estadistica" id="estadistica-casos">        
                            <div style="margin-bottom: 50px;">
                                <strong>Total Expedientes: </strong><?php echo $estadisticaArr->totalCasos; ?><br/>
                                <strong>Total Clientes: </strong><?php echo $estadisticaArr->totalClientes; ?><br/>
                                <strong>Total Lic. Alma: </strong><?php echo $estadisticaArr->totalAlma; ?><br/>
                                <strong>Total Lic. Jesus: </strong><?php echo $estadisticaArr->totalJesus; ?><br/>
                            </div>
                              
                            <div>
                                <table class="table table-bordered table-condensed dataTable no-footer dt-responsive hover">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Casos</th>
                                    </tr>
                                <?php foreach($casosPorAbogadoArr as $casoAbogado){ ?>
                                    <tr>
                                        <td>
                                            <?php echo $casoAbogado->nombre; ?>
                                        </td>
                                        <td>
                                            <?php echo $casoAbogado->CasosTotal; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </table>
                            </div>      
                        </div>      
                    </div>
                <?php } ?>
            </div>
          </div>
        </div>

        <!-- Modal ventos sincronizados  -->
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
<a class="event" href="javascript:void(0);" title="Ver Arbol" style="display:inline-block" onclick="mostrarArbol(6);"></a>


    <footer>
        <div class="panel-footer">
            <?php echo getPiePag(true); ?>
        </div>
    </footer>

    <?php echo scriptsPagina(true); ?>

    <script type="text/javascript" src="../libs/jsPlumb.min.js"></script>
    <script type="text/javascript" src="../libs/jsplumb-tree.js"></script>

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
