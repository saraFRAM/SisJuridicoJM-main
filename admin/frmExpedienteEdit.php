<?php
ini_set("session.cookie_lifetime","36000");
ini_set("session.gc_maxlifetime","36000");
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
include_once '../brules/arraysObj.php';
require_once '../brules/KoolControls/KoolAjax/koolajax.php';
libreriasKool();
include_once $dirname.'/brules/casosObj.php';
include_once $dirname.'/brules/catTipoCasosObj.php';
include_once $dirname.'/brules/usuariosObj.php';
include_once $dirname.'/brules/clientesObj.php';
include_once $dirname.'/brules/casoAccionesObj.php';
// include_once $dirname.'/brules/accionGastosObj.php';
include_once $dirname.'/brules/catConceptosObj.php';
include_once $dirname.'/brules/comentariosObj.php';

include_once $dirname.'/brules/catPartesObj.php';
include_once $dirname.'/brules/catMateriasObj.php';
include_once $dirname.'/brules/catJuiciosObj.php';
include_once $dirname.'/brules/catDistritosObj.php';
include_once $dirname.'/brules/catJuzgadosObj.php';
include_once $dirname.'/brules/catAccionesObj.php';
include_once $dirname.'/brules/digitalesObj.php';
include_once $dirname.'/brules/audiosObj.php';

$casosObj = new casosObj();
$catTipoCasosObj = new catTipoCasosObj();
$usuariosObj = new usuariosObj();
$clientesObj = new clientesObj();
$casoAccionesObj = new casoAccionesObj();
// $accionGastosObj = new accionGastosObj();
$catConceptosObj = new catConceptosObj();

$catPartesObj = new catPartesObj();
$catMateriasObj = new catMateriasObj();
$catJuiciosObj = new catJuiciosObj();
$catDistritosObj = new catDistritosObj();
$catJuzgadosObj = new catJuzgadosObj();
$catAccionesObj = new catAccionesObj();
$comentariosObj = new comentariosObj();
$digitalesObj = new digitalesObj();
$audiosObj = new audiosObj();

$dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
$dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
$fechaHoy = $dateByZone->format('d/m/Y'); //fecha Actual
$year = $dateByZone->format('Y');

//establecer la zona horaria
$tz = obtDateTimeZone();
$fecha = $tz->fecha;
$msjResponse = "";
$msjResponseE = "";
$id = (isset($_GET['id']))?$_GET['id']:0;

$colTipos = $catTipoCasosObj->ObtCatTipoCasos();
$colAbogados = $usuariosObj->obtTodosUsuarios(true, "1,2,4", "", " numAbogado ASC ", true);
$colExternos = $usuariosObj->obtTodosUsuarios(true, "5");
$colTitulares = $usuariosObj->obtTodosUsuarios(true, 1);
$colConceptos = $catConceptosObj->ObtCatConceptos();
$comentarios = $comentariosObj->ObtTodosComentarios("", $id);

$datosCaso = $casosObj->CasoPorId($id);
//Obtener datos del titular (jair idtitular = responsable)
$idtitular = (isset($datosCaso->responsableId))?$datosCaso->responsableId:0;
$datosTitular = $usuariosObj->UserByID($idtitular);
$usuarioAlta = $usuariosObj->UserByID($datosCaso->usuarioAltaId);

$clienteId = (isset($datosCaso->clienteId))?$datosCaso->clienteId:0;
$datosCliente = $clientesObj->ClientePorId($clienteId);
$idTipo = (isset($datosCaso->tipoId))?$datosCaso->tipoId:0;
$cliente = (isset($datosCliente->nombre))?$datosCliente->nombre:"";
// $idtitular = (isset($datosTitular->idUsuario))?$datosTitular->idUsuario:0;
$titular = (isset($datosTitular->nombre))?$datosTitular->nombre:"";
$fechaAlta = (isset($datosCaso->fechaAlta))?conversionFechaF2($datosCaso->fechaAlta):"";// conversionFechaF2 
$fechaAlta2 = (isset($datosCaso->fechaAlta))?convertirAFechaCompleta3($datosCaso->fechaAlta):"";// conversionFechaF2 
$fechaAct = (isset($datosCaso->fechaAct))?convertirAFechaCompleta3($datosCaso->fechaAct):"";// conversionFechaF6
//Obtener total de gastos
// $colGastos = $accionGastosObj->ObtAccionGastos(0, $id);
$tGastos = 0;
// foreach($colGastos as $elem){
//     $tGastos += $elem->monto;
// }
$tGastos = formatoMoneda($tGastos);
$autorizadosIds = (isset($datosCaso->autorizadosIds))?$datosCaso->autorizadosIds:"";
$autorizadosJuzgados = (isset($datosCaso->autorizadosJuzgados))?$datosCaso->autorizadosJuzgados:"";
$arrIdsAutorizados = explode(",", $autorizadosIds);
$arrIdsAutorizadosJ = explode(",", $autorizadosJuzgados);
// echo $autorizadosIds." ".$autorizadosJuzgados;
// Obtener grid de acciones
$gridAcciones = $casoAccionesObj->ObtAccionesGrid($id, $_SESSION["idUsuario"]);
//print_r($gridAcciones);
$gridDigitales = $digitalesObj->ObtDigitalesGrid($id);
$gridAudios = $audiosObj->ObtAudiosGrid($id);
$gridAudiosTitular = $audiosObj->ObtAudiosGrid($id, true);

$dataInput = array("data-live-search"=>"true");

$dataMateria = array(
    "data-live-search"=>"true",
    "onchange"=>"cargaSelector('materiaId', 'juicioId', 'juicios');cargaSelector('materiaId', 'accionId', 'acciones');muestraAccion(this.options[this.selectedIndex]);",
  );

$dataDistrito = array(
  "data-live-search"=>"true",
  "onchange"=>"cargaSelector('distritoId', 'juzgadoId', 'juzgados')"
);


$arrSalud = array();
$arrSalud[] = array("valor"=>"", "texto"=>"Seleccione...");
$arrSalud[] = array("valor"=>1, "texto"=>"Verde");
$arrSalud[] = array("valor"=>2, "texto"=>"Amarillo");
$arrSalud[] = array("valor"=>3, "texto"=>"Rojo");

$arrVelocidad = array();
$arrVelocidad[] = array("valor"=>"", "texto"=>"Seleccione...");
$arrVelocidad[] = array("valor"=>1, "texto"=>"Normal");
$arrVelocidad[] = array("valor"=>2, "texto"=>"Media");
$arrVelocidad[] = array("valor"=>3, "texto"=>"Alta");

$arrTitulares = array();
$arrTitulares[] = array("valor"=>"", "texto"=>"Seleccione...");
//CMPB 03/03/2023 QUE LOS TITULARES PUEDAN EDITAR AKA
$arrIdTitulares = array();

foreach ($colTitulares as $titularItem) {
    $arrTitulares[] = array("valor"=>$titularItem->idUsuario, "texto"=>$titularItem->nombre);
    array_push($arrIdTitulares, $titularItem->idUsuario);
}

$atributosNE = array(
    // array("atributo"=>"placeholder", "valor"=>"___/".$year),
    array("atributo"=>"placeholder", "valor"=>"____/20__"),
    // array("atributo"=>"data-slots", "valor"=>"_"),
    
);

$atributosNEJ = array(
  // array("atributo"=>"placeholder", "valor"=>"___/".$year),
  array("atributo"=>"maxlength", "valor"=>"50"),
  // array("atributo"=>"data-slots", "valor"=>"_"),
  
);

$valoresUsuario = array(
    "value" => $usuarioAlta->nombre,
    "hidden"=> $datosCaso->usuarioAltaId,
);


$arrEstatus = array();
$arrEstatus[] = array("valor"=>"", "texto"=>"Seleccione...");
$arrEstatus[] = array("valor"=>1, "texto"=>"Activo");
$arrEstatus[] = array("valor"=>2, "texto"=>"Suspendido");
$arrEstatus[] = array("valor"=>3, "texto"=>"Baja");
$arrEstatus[] = array("valor"=>4, "texto"=>"Terminado");
$arrEstatus[] = array("valor"=>5, "texto"=>"Prospecto");

$arrProcesal = array();
$arrProcesal[] = array("valor"=>"", "texto"=>"Seleccione...");
$arrProcesal[] = array("valor"=>1, "texto"=>"Antes de desahogo");
$arrProcesal[] = array("valor"=>2, "texto"=>"Presentación de demanda");
$arrProcesal[] = array("valor"=>3, "texto"=>"Admisión");
$arrProcesal[] = array("valor"=>4, "texto"=>"Vista demanda");
$arrProcesal[] = array("valor"=>5, "texto"=>"Contestación");

$arrContundencia = array();
$arrContundencia[] = array("valor"=>"", "texto"=>"Seleccione...");
$arrContundencia[] = array("valor"=>1, "texto"=>"Fuerte");
$arrContundencia[] = array("valor"=>2, "texto"=>"Muy fuerte");
$arrContundencia[] = array("valor"=>3, "texto"=>"Implacable");

$arrTipoCobro = obtTipoCobro();

//correonot juridicomartinez@yahoo.com.mx, juridicomartinezpuebla@gmail.com. lic.almarosaperez@gmail.com
$arrCorreoNot = array();
$arrCorreoNot[] = array("valor"=>"", "texto"=>"Seleccione...");
$arrCorreoNot[] = array("valor"=>"juridicomartinez@yahoo.com.mx", "texto"=>"juridicomartinez@yahoo.com.mx");
$arrCorreoNot[] = array("valor"=>"juridicomartinezpuebla@gmail.com", "texto"=>"juridicomartinezpuebla@gmail.com");
$arrCorreoNot[] = array("valor"=>"lic.almarosaperez@gmail.com", "texto"=>"lic.almarosaperez@gmail.com");
$arrCorreoNot[] = array("valor"=>"otro", "texto"=>"Otro");

$dataCorreoNot = array(
    "onchange"=>"cambiaCorreoNot(this.value)",
);
$arrBuscar = array("juridicomartinez@yahoo.com.mx","juridicomartinezpuebla@gmail.com","lic.almarosaperez@gmail.com");
$valorcorreonot = (in_array($datosCaso->correonot, $arrBuscar))?$datosCaso->correonot:'otro';
$valorotro = ($valorcorreonot == 'otro')?$datosCaso->correonot:'';

$readonlyotro = ($valorotro == '')?true:false;

//partes
$arrParte = array();
$arrParte[] = array("valor"=>"", "texto"=>"Seleccione...");
$partes = $catPartesObj->ObtPartes();
foreach ($partes as $itemParte) {
    $arrParte[] = array("valor"=>$itemParte->idParte, "texto"=>$itemParte->nombre);
}

//materias
$arrMateria = array();
$arrMateria[] = array("valor"=>"", "texto"=>"Seleccione...");
$materias = $catMateriasObj->ObtMaterias();
foreach ($materias as $itemMateria) {
    $atributosMat = array(
        array("atributo"=>"data-tieneacc", "valor"=>$itemMateria->tieneAcciones),
    );
    $arrMateria[] = array("valor"=>$itemMateria->idMateria, "texto"=>$itemMateria->nombre, "atributos"=>$atributosMat);
}

//juicios
$arrJuicio = array();
$arrJuicio[] = array("valor"=>0, "texto"=>"Seleccione...");

//acciones
$arrAccion = array();
$arrAccion[] = array("valor"=>0, "texto"=>"Seleccione...");
$juicios = $catJuiciosObj->ObtJuicios("", $datosCaso->materiaId);
foreach ($juicios as $itemJuicio) {
  $arrJuicio[] = array("valor"=>$itemJuicio->idJuicio, "texto"=>$itemJuicio->nombre);
}
$claseRowAccion = ($datosCaso->accionId > 0)?'':'oculto';
//distritos
$arrDistrito = array();
$arrDistrito[] = array("valor"=>"", "texto"=>"Seleccione...");
$distritos = $catDistritosObj->ObtDistritos();
foreach ($distritos as $itemDistrito) {
    $arrDistrito[] = array("valor"=>$itemDistrito->idDistrito, "texto"=>$itemDistrito->nombre);
}

//juzgados
$arrJuzgado = array();
$arrJuzgado[] = array("valor"=>0, "texto"=>"Seleccione...");
$juzgados = $catJuzgadosObj->ObtJuzgados("", $datosCaso->distritoId);
foreach ($juzgados as $itemJuzgado) {
  $arrJuzgado[] = array("valor"=>$itemJuzgado->idJuzgado, "texto"=>$itemJuzgado->nombre);
}

// inicio campos
$arrCampoUsuario = array(
  array("nameid"=>"usuarioAltaId", "type"=>"hidden_text", "class"=>"from-control", "readonly"=>true, "label"=>"Usuario alta:", "datos"=>array(), "value"=>"", "valores"=>$valoresUsuario),
);

$readonlyNumexpediente = ($datosCaso->numExpediente == '')?false:true;
$arrCampoNumExpediente = array(
  array("nameid"=>"numExpediente", "type"=>"text", "class"=>"form-control", "readonly"=>$readonlyNumexpediente, "label"=>"Expediente interno:", "datos"=>array(), "value"=>$datosCaso->numExpediente, "atributos"=>$atributosNE),
);

$arrCampoNumExpJuz = array(
  array("nameid"=>"numExpedienteJuzgado", "type"=>"text", "class"=>"form-control revisamaxlength", "readonly"=>false, "label"=>"Expediente juzgado:", "datos"=>array(), "value"=>$datosCaso->numExpedienteJuzgado, "atributos"=>$atributosNEJ),
);

$arrCampoSaludExp = array(
    array("nameid"=>"saludExpediente", "type"=>"select", "class"=>"form-control", "readonly"=>false, "label"=>"Salud expediente:", "datos"=>$arrSalud, "value"=>$datosCaso->saludExpediente),
);

$likeDisabled = ($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2)?"":"likeDisaled";
$arrCampoTitular = array(
    array("nameid"=>"titularId2", "type"=>"select", "class"=>"form-control required ".$likeDisabled, "label"=>"Titular:", "datos"=>$arrTitulares, "value"=>$datosCaso->titularId2),
);

//Cambiamos por un hidden JGP 18/11/22
/*$arrCampoVelocidad = array(
    array("nameid"=>"velocidad", "type"=>"select", "class"=>"form-control", "readonly"=>false, "label"=>"Velocidad:", "datos"=>$arrVelocidad, "value"=>$datosCaso->velocidad),
);*/
$arrCampoVelocidad = array(
  array("nameid"=>"velocidad", "type"=>"hidden", "value"=>"1"),
);

$arrCampoContrario = array(
    array("nameid"=>"contrario", "type"=>"text", "class"=>"form-control", "readonly"=>false, "label"=>"Contrario:", "datos"=>array(), "value"=>$datosCaso->contrario),
);

$arrCampoEstatus = array(
  array("nameid"=>"estatusId", "type"=>"select", "class"=>"form-control", "readonly"=>false, "label"=>"Estatus:", "datos"=>$arrEstatus, "value"=>$datosCaso->estatusId),
);

//Cambiamos por un hidden JGP 18/11/22
/*$arrCampoProcesal = array(
  array("nameid"=>"procesalId", "type"=>"select", "class"=>"form-control", "readonly"=>false, "label"=>"Estado procesal:", "datos"=>$arrProcesal, "value"=>$datosCaso->procesalId),
);*/
$arrCampoProcesal = array(
  array("nameid"=>"procesalId", "type"=>"hidden", "value"=>"1"),
);

//Cambiamos por un hidden JGP 18/11/22
/*$arrCampoContundencia = array(
  array("nameid"=>"contundencia", "type"=>"select", "class"=>"form-control", "readonly"=>false, "label"=>"Contundencia:", "datos"=>$arrContundencia, "value"=>$datosCaso->contundencia),
);*/
$arrCampoContundencia = array(
  array("nameid"=>"contundencia", "type"=>"hidden", "value"=>"1"),
);

$claseCobro = ($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2)?"":"oculto";
$arrCampoCobro = array(
    array("nameid"=>"cobro", "type"=>"select", "class"=>"form-control", "readonly"=>false, "label"=>"Tipo cobro:", "datos"=>$arrTipoCobro, "value"=>$datosCaso->cobro, "claseRow"=>$claseCobro),
);

$arrCampoCorreoNot = array(
  array("nameid"=>"correonot", "type"=>"select", "class"=>"form-control", "readonly"=>false, "label"=>"Correo notificaciones:", "datos"=>$arrCorreoNot, "value"=>$valorcorreonot, "dataInput"=>$dataCorreoNot),
);


$arrCampoOtro = array(
  array("nameid"=>"otro", "type"=>"text", "class"=>"form-control", "readonly"=>$readonlyotro, "label"=>"Otro:", "datos"=>array(), "value"=>$valorotro),
);
//$claseRowAkaAsunto = "oculto"; Cambi local para que no me de error en vista
//CMPB, 13/02/2023 mostrar akaAsunto a los roles 4 y solo editar si es el titular

/*if($_SESSION['idRol']==4 || $_SESSION['idRol'] ==1){
  $claseRowAkaAsunto = "";
}else{
  $claseRowAkaAsunto = "oculto";
}*/
$claseRowAkaAsunto = ($_SESSION["idRol"] == 4 || $_SESSION['idRol'] ==1)? "" : "oculto";

/*if($datosCaso->titularId2==$_SESSION['idUsuario']){
  $permisoEditarAkaAsunto= false;
}else{
  $permisoEditarAkaAsunto= true;
}*/
//echo $_SESSION['idUsuario'];
//CMPB 03/03/2023 QUE LOS TITULARES PUEDAN EDITAR AKA
$permisoEditarAkaAsunto = (in_array($_SESSION['idUsuario'],  $arrIdTitulares))? false : true;
$arrCampoAkaasunto = array(
  array("nameid"=>"akaAsunto", "type"=>"text", "class"=>"form-control", "readonly"=> $permisoEditarAkaAsunto, "label"=>"Aka asunto:", "datos"=>array(), "value"=>$datosCaso->akaAsunto, "claseRow"=>" ".$claseRowAkaAsunto),
);


$arrCampoParte = array(
  array("nameid"=>"parteId", "type"=>"select", "class"=>"form-control selectpicker", "readonly"=>false, "label"=>"Parte:", "datos"=>$arrParte, "value"=>$datosCaso->parteId, "dataInput"=>$dataInput),
);

$arrCampoMateria = array(
  array("nameid"=>"materiaId", "type"=>"select", "class"=>"form-control selectpicker", "readonly"=>false, "label"=>"Materia:", "datos"=>$arrMateria, "value"=>$datosCaso->materiaId, "dataInput"=>$dataMateria),
);

$arrCampoJuicio = array(
  array("nameid"=>"juicioId", "type"=>"select", "class"=>"form-control selectpicker", "readonly"=>false, "label"=>"Juicio:", "datos"=>$arrJuicio, "value"=>$datosCaso->juicioId, "dataInput"=>$dataInput),
);

  $arrCampoAccion = array(
    array("nameid"=>"accionId", "type"=>"select", "class"=>"form-control selectpicker", "readonly"=>false, "label"=>"Accion:", "datos"=>$arrAccion, "value"=>$datosCaso->accionId, "dataInput"=>$dataInput, "claseRow"=>"rowAccion ".$claseRowAccion),
  );
$arrCampoDistrito = array(
  array("nameid"=>"distritoId", "type"=>"select", "class"=>"form-control selectpicker", "readonly"=>false, "label"=>"Distrito:", "datos"=>$arrDistrito, "value"=>$datosCaso->distritoId, "dataInput"=>$dataDistrito),
);

$arrCampoJuzgado = array(
  array("nameid"=>"juzgadoId", "type"=>"select", "class"=>"form-control selectpicker", "readonly"=>false, "label"=>"Juzgado:", "datos"=>$arrJuzgado, "value"=>$datosCaso->juzgadoId, "dataInput"=>$dataInput),
);

$arrCampoDomicilio = array(
  array("nameid"=>"domicilioEmplazar", "type"=>"text", "class"=>"form-control", "readonly"=>false, "label"=>"Domicilio para emplazar:", "datos"=>array(), "value"=>$datosCaso->domicilioEmplazar),
);
$arrCampoRepresentado = array(
  array("nameid"=>"representado", "type"=>"text", "class"=>"form-control", "readonly"=>false, "label"=>"Representado:", "datos"=>array(), "value"=>$datosCaso->representado),
);
// LDAH IMP 17/08/2022 para nuevos campos de juez
$arrCampoNombreJuez = array(
  array("nameid"=>"nombreJuez", "type"=>"text", "class"=>"form-control", "readonly"=>false, "label"=>"Nombre de Juez:", "datos"=>array(), "value"=>$datosCaso->nombreJuez),
);
// LDAH IMP 17/08/2022 para nuevos campos de juez
$arrCampoNombreSecret = array(
  array("nameid"=>"nombreSecretaria", "type"=>"text", "class"=>"form-control", "readonly"=>false, "label"=>"Nombre de secretaria:", "datos"=>array(), "value"=>$datosCaso->nombreSecretaria),
);


// echo "<pre>";
// print_r($colTipos);
// print_r($colAbogados);
// print_r($datosCaso);
// print_r($datosCliente);
// print_r($datosTitular);
// print_r($arrIdsAutorizados);
// print_r($colConceptos);
// print_r($colGastos);
// echo "</pre>";


/* if(isset($_POST["idUsuario"])){
	$usuarioGObj = new usuariosObj();
    $idUsuario = $_POST["idUsuario"];
    $idRolG = $_POST["idRol"];

    $usuarioGObj->idUsuario = $idUsuario;
    $usuarioGObj->idRol = $idRolG;
    $usuarioGObj->nombre = $_POST["nombreU"];
    $usuarioGObj->email = $_POST["emailU"];
    $usuarioGObj->password = $_POST["passU"];
    $usuarioGObj->activo = $_POST["activoU"];

    $res = 0;
    if($idUsuario == 0){
    	$usuarioGObj->GuardarUsuario();
        $idUsuario = $usuarioGObj->idUsuario;
        if($idUsuario > 0){
            $res = 1;
        }
    }else{
    	$res = $usuarioGObj->EditarUsuario();
    }


    if($res > 0){
        $msjResponse .= "Cambios guardados";
        header("location: catalogos.php?catalog=usuarios");
    }
    else{
        $msjResponse .= "No hay cambios que guardar";
    }
} */
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Editar Expediente</title>
    <?php echo estilosPagina(true); ?>
</head>

<body>
	<?php echo getHeaderMain($_SESSION['myusername'], true);?>
    <?php $menu = getAdminMenu(); ?>
    
    <input type="hidden" name="vista" id="vista" value="expedientes">

    <section class="section-internas">
    	<div class="panel-body">
    		<div class="container-fluid">
    			<div class="row">
    				<div class="colmenu col-md-2 menu_bg">
                        <?php echo getNav($menu); ?>
                    </div>

                    <div class="col-md-10" style="<?php echo ($datosCaso->estatusId == 2)?'background-color: #fa0606':''; ?>" >
                        <h1 class="titulo">Editar Expediente<span class="pull-right"><a id="btnAyudaweb" onclick="mostrarAyuda('web_expediente')" href="#fancyAyudaWeb"><img src="../images/icon_ayuda.png" width="20px"></a></span></h1>
                        <ol class="breadcrumb">
                            <li><a href="expedientes.php">Mis expedientes</a></li>
                            <li class="active">Editar expediente</li>
                        </ol>

                        <!--Mostrar en caso de presionar el boton de guardar-->
                        <?php if ($msjResponse != "") { ?>
                            <div class="new_line alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?php echo $msjResponse; ?>
                            </div>
                        <?php } ?>

                        <div class="row">
                          
                            <div class="col-xs-4"></div>
                            <?php if($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2){ ?>
                              <div class="col-xs-2">
                                <input type="checkbox" name="checkcomtit" id="checkcomtit" onchange="revisaCheckComTit()">
                                <label for="checkcomtit">Ver Com. Tit</label>
                              </div>
                            <?php } ?>
                            <div class="col-xs-2" style="display: none">
                              <input type="checkbox" name="checkinterno" id="checkinterno" onchange="revisaCheckInternos()">
                              <label for="checkinterno">Ver internos</label>
                            </div>
                            <div class="col-xs-2">
                                <a href="expediente.php?id=<?php echo $id ?>&imprimir=0" class="btn btn-primary" role="button" id="btnVer" target="_blank">Ver</a>
                            </div>
                            <div class="col-xs-2">
                                <a href="expediente.php?id=<?php echo $id ?>&imprimir=1" class="btn btn-primary" role="button" id="btnImprimir" target="_blank">Imprimir</a>
                            </div>
                            <div class="col-xs-2">
                              <a class="btn btn-primary" role="button" href="expedientes.php?caso=<?php echo $id ?>" target="_blank" title="Ver caso en el grid" >Ver en el grid</a>
                            </div>
                        </div>
                        
                        <br>

                        <div class="row button-fixed">
                          <div class="row">
                          <div class="col-xs-1">

                                <a class="btns btn-primary" onclick="crearCaso()"  role="button" id="btnCrearCaso">Guardar</a>
                            </div>

                          </div><br>
                          <div class="row">

                            <div class="col-xs-1">
                                <a onclick="window.close()" class="btn btn-primary" role="button">Cerrar</a>
                              </div>

                          </div>
                        </div>

                        <form role="form" id="formCaso" name="formCaso" method="post" action="">
                            <input type="hidden" name="form_caso">
                            <input type="hidden" name="c_id" id="c_id" value="<?php echo $id;?>">
                            <input type="hidden" name="c_colaccion" id="c_colaccion" value="1">
                            <input type="hidden" name="usuarioIdSesion" id="usuarioIdSesion" value="<?php echo $_SESSION['idUsuario'];?>">
                            <!--
                            <input type="hidden" name="dp_ids_deptos" id="dp_ids_deptos" value="<?php //echo $idsDeptos; ?>">
                            <input type="hidden" id="dp_borrarP" value="<?php //echo $borrarP; ?>">
                            <input type="hidden" id="salvarSinCrearVer" name="salvarSinCrearVer" value="0">
                            <input type="hidden" id="check_soloLectura" value="<?php //echo $soloLectura;?>"> -->

                            <div class="content_wrapper">
                              <div class="row">
                                <div class="alert alert-warning divInfoInterno" style="display: none;">
                                <span class="material-icons">new_releases</span> Los comentarios internos est&aacute;n activados
                                </div>
                              </div>

                             <div class="comentTitu">
                              <div class="row">
                                <div class="alert alert-error divInfoComTit" style="display: none;">
                                <span class="material-icons">new_releases</span> Los comentarios de titular est&aacute;n activados
                                </div>
                              </div>
                             </div>

                              <div class="row" id="divCasoArbol">
                                <div class="col-xs-12 col-md-6">
                                            <div class="panel panel-warning">
                                                <div class="panel-heading"></div>
                                                    <div class="panel-body">
                                                    <br>
                                                    <?php
                                                    $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                                                    echo generaHtmlForm($arrCampoNumExpediente, $cols);
                                                     ?>
                                                    <div class="row">
                                                      <div class="col-md-4 text-right">
                                                          <label>Tipo cliente:</label>
                                                      </div>
                                                      <div class="col-xs-8 col-md-8">
                                                          <select id="c_tipo" name="c_tipo" class="form-control required" style="width:90%;display:inline-block;">
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
                                                  <br>
                                                  <?php
                                                    $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                                                    echo generaHtmlForm($arrCampoNumExpJuz, $cols);

                                                    $styleIdPadre = "";
                                                    $styleEditPadre = "";
                                                    $styleVerArbol = "";
                                                    $styleValidarIP = "";
                                                    
                                                    if($datosCaso->idPadreMain == 0){
                                                      $styleEditPadre = 'style="display:none;"';
                                                      $styleVerArbol = 'style="display:none;"';
                                                      
                                                    }else{
                                                      $styleIdPadre = "readonly";
                                                      $styleValidarIP = 'style="display:none;"';
                                                    }

                                                    ?>
                                                    <div class="row ">
                                                      <div class="col-xs-4 col-md-4 text-right"><label for="idPadre">Expediente Padre: </label></div>
                                                      <div class="col-xs-3 col-md-3">
                                                          <input class="form-control form-control" type="text" name="idPadre" id="idPadre" <?php echo $styleIdPadre; ?> value="<?php echo $datosCaso->idPadre != 0?$datosCaso->idPadre:''; ?>">
                                                          <input type="hidden" id="idPadreMain" name="idPadreMain" value="" />
                                                          <input type="hidden" id="validPadre" name="validPadre" value="false" />
                                                      </div>
                                                      <button type="button" class="btn btn-primary" role="button" title="validar" id="validar_padre" value="Validar" <?php echo $styleValidarIP; ?> onclick="validarPadre(<?php echo $datosCaso->idCaso; ?>);">
                                                        Validar
                                                      </button>
                                                      <button type="button" class="btn btn-primary" role="button" title="Reasignar sin sus hijos" id="reasignar_padre" value="Reasignar" style="display:none;" onclick="reasignarPadre(<?php echo $datosCaso->idCaso; ?>);">
                                                        Reasignar
                                                      </button>
                                                      <button type="button" class="btn btn-primary" role="button" title="Eliminar relacion con todo y sus hijos" id="eliminar_Padre" value="eliminarDelPadre" style="display:none;" onclick="eliminarDelPadre(<?php echo $datosCaso->idCaso; ?>);">
                                                        Eliminar
                                                      </button>
                                                      
                                                      <button type="button" class="btn btn-primary" role="button" title="Cancelar la accion" id="cancelar_eliminar" value="cancelar_eliminar" style="display:none; margin: 3px;" onclick="cancelarEliminar();">
                                                        Cancelar
                                                      </button>
                                                      <button type="button" class="btn btn-primary" role="button" title="Reasignar con sus hijos" id="editar_padre" value="Editar Padre" <?php echo $styleEditPadre; ?> onclick="editarPadre();">
                                                        <span class="glyphicon glyphicon-edit"></span>
                                                      </button>
                                                      <!-- CMPB, 10/02/2023 cambio la funcion de editar el arbol -->
                                                      <button type="button" class="btn btn-primary" role="button" title="Ver Arbol" id="ver_arbol" value="Ver Arbol" <?php echo $styleVerArbol; ?>  onclick="mostrarArbol(<?php echo $datosCaso->idPadreMain.','.$datosCaso->idCaso; ?>);">
                                                        <span class="glyphicon glyphicon-zoom-in"></span>
                                                      </button>
                                                      <button type="button" class="btn btn-primary" role="button" title="Eliminar Relacion" id="eliminarRelacionPadre" value="Eliminar Relacion" <?php echo $styleEditPadre; ?> onclick="eliminarRelacion();">
                                                        <span class="glyphicon glyphicon-trash"></span>
                                                      </button>
                                                  </div>                                                    
                                              </div>
                                            </div>
                                        </div>
    
                                        <div class="col-xs-12 col-md-6">
                                            <div class="panel panel-warning">
                                                <div class="panel-heading"></div>
                                                    <div class="panel-body"><br>
                                                    <div class="row">
                                                      <div class="col-md-4 text-right">
                                                          <label>Fecha alta:</label>
                                                      </div>
                                                    
                                                          <input class="form-control" type="hidden" name="c_falta" id="c_falta" value="<?php echo $fechaAlta;?>"  readonly>
                                                    
                                                      <div class="col-md-8">
                                                          <input class="form-control" type="text" name="c_falta2" id="c_falta2" value="<?php echo $fechaAlta2;?>"  readonly>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-4 text-right">
                                                          <label>Ult. Act.:</label>
                                                      </div>
                                                      <div class="col-md-8">
                                                          <input class="form-control" type="text" name="c_fact" id="c_fact" value="<?php echo $fechaAct;?>"  readonly>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-4 text-right">
                                                          <label>ID Expediente:</label>
                                                      </div>
                                                      <div class="col-md-8">
                                                          <input class="form-control" type="text" name="c_id" id="c_id" value="<?php echo $id;?>" style="width:50%;display:inline-block;" readonly>
                                                      </div>
                                                  </div>
                                                    <?php
                                                    $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                                                    echo generaHtmlForm($arrCampoUsuario, $cols);
                                                     ?>
                                                    </div>
                                            </div>
                                        </div>

                              </div><!-- fin row fila 1 datos basicos y datos automaticos -->
                              
                              <div class="row">

                                    <div class="col-xs-12 col-md-6">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading"></div>
                                                <div class="panel-body"><br>
                                                <?php
                                                $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                                                echo generaHtmlForm($arrCampoTitular, $cols);
                                                 ?>
                                                <div class="row">
                                                    <div class="col-md-4 text-right">
                                                        <label>Responsable:</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                      <input type="hidden" id="c_idtitular" name="c_idtitular" value="<?php echo $idtitular;?>"/>
                                                      <input type="text" id="c_titular" name="c_titular" value="<?php echo $titular;?>" class="form-control required" readonly style="width:80%;display:inline-block;"/>
                                                      <?php if($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2){ ?>
                                                      <button type="button" class="btn btn-primary" role="button" title="Buscar" id="busca_titular" value="Buscar" onclick="obtListaTitulares();">
                                                          <span class="glyphicon glyphicon-search"></span>
                                                      </button>
                                                      <?php } ?>
                                                  </div>
                                                </div>
                                                <?php
                                                $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                                                echo generaHtmlForm($arrCampoContrario, $cols);
                                                echo generaHtmlForm($arrCampoCorreoNot, $cols);
                                                echo generaHtmlForm($arrCampoOtro, $cols);
                                                echo generaHtmlForm($arrCampoAkaasunto, $cols);
                                                 ?>
                                                <div class="comentTitu">
                                                  <div class="row">
                                                    <div class="col-xs-12">
                                                      <div class="alert alert-error divInfoComTit" style="display: none;">
                                                        <span class="material-icons">new_releases</span> Los comentarios de titular est&aacute;n activados
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>

                                                </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-md-6">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading"></div>
                                                <div class="panel-body"><br>
                                                <?php
                                                $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                                                echo generaHtmlForm($arrCampoSaludExp, $cols);
                                                echo generaHtmlForm($arrCampoVelocidad, $cols);
                                                echo generaHtmlForm($arrCampoEstatus, $cols);
                                                echo generaHtmlForm($arrCampoProcesal, $cols);
                                                echo generaHtmlForm($arrCampoContundencia, $cols);
                                                echo generaHtmlForm($arrCampoCobro, $cols);

                                                if($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2){
                                                 ?>
                                                 <div class="row">
                                                 <div class="col-xs-6"></div>
                                                   <div class="col-xs-6">
                                                     <a class="btn btn-primary" href="cuentasxcobrar.php?expedienteId=<?php echo $id ?>" target="_blank">Cuentas por cobrar</a>
                                                   </div>
                                                 </div>
                                                </div>
                                                <?php } ?>
                                        </div>
                                    </div>
                                </div><!-- fin segunda fila de datos -->

                                <div class="row">

                                  <div class="col-xs-12 col-md-6">
                                      <div class="panel panel-warning">
                                          <div class="panel-heading"></div>
                                              <div class="panel-body"><br>
                                              <?php
                                              $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                                              echo generaHtmlForm($arrCampoParte, $cols);
                                              echo generaHtmlForm($arrCampoMateria, $cols);
                                              echo generaHtmlForm($arrCampoJuicio, $cols);
                                              echo generaHtmlForm($arrCampoAccion, $cols);
                                              ?>
                                              </div>
                                      </div>
                                  </div>

                                  <div class="col-xs-12 col-md-6">
                                      <div class="panel panel-warning">
                                          <div class="panel-heading"></div>
                                              <div class="panel-body"><br>
                                              <?php
                                              $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                                              echo generaHtmlForm($arrCampoDomicilio, $cols);
                                              echo generaHtmlForm($arrCampoDistrito, $cols);
                                              echo generaHtmlForm($arrCampoJuzgado, $cols);
                                              echo generaHtmlForm($arrCampoNombreJuez, $cols); // LDAH IMP 17/08/2022 para nuevos campos de juez
                                              echo generaHtmlForm($arrCampoNombreSecret, $cols); // LDAH IMP 18/08/2022 para nuevos campos de juez
                                              ?>
                                              </div>
                                      </div>
                                  </div>
                                </div><!-- fin fila nuevos campos -->

                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading">Cliente</div>
                                                <div class="panel-body"><br>
                                                <div class="row">
                                                    <div class="col-md-3 text-right">
                                                        <label>Seleccionar:</label>
                                                    </div>
                                                    <div class="col-md-7 form-group">
                                                      <input type="hidden" id="c_idcliente" name="c_idcliente" value="<?php echo $clienteId; ?>"/>
                                                      <input type="text" id="c_cliente" name="c_cliente" value="<?php echo $cliente;?>" class="form-control required" readonly style="width:72%;display:inline-block;"/>
                                                      <button type="button" class="btn btn-primary" role="button" title="Buscar" id="busca_clientes" value="Buscar" onclick="obtListaClientes();">
                                                            <span class="glyphicon glyphicon-search"></span>
                                                        </button>
                                                        <span>&nbsp;<a href="#" data-toggle="modal" data-target="#popup_modalCrearCliente" class="agregarCliente" title="Agregar cliente" onclick="reseteaFormulario('formCrearCliente');asignaValorCampo('clienteEditId',0)"><img width="16px" src="../images/iconos/iconos_grid/agregar.png"></a></span>
                                                    </div>
                                                    <div class="col-md-2">
                                                      <?php if($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2 || $_SESSION["idRol"] == 4){ ?>
                                                        <a class="cursorPointer" onclick="muestraEditarCliente()"><span class="material-icons">edit</span></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                  <div class="col-xs-12">
                                                    <div class="alert alert-warning divInfoInterno" style="display: none;">
                                                      <span class="material-icons">new_releases</span> Los comentarios internos est&aacute;n activados
                                                    </div>
                                                  </div>
                                                </div>
                                                <?php
                                                    $cols = array("label_xs"=>"3", "label_md"=>"3", "input_xs"=>"7", "input_md"=>"7");
                                                    echo generaHtmlForm($arrCampoRepresentado, $cols);
                                                ?>
                                                <div class="row">
                                                 
                                                  <div class="col-md-12" id="rowDivDatosCliente">
                                                  <div class="col-xs-12 col-md-12">
                                                  <div class="alert alert-info">
                                                    <strong>Datos cliente</strong>
                                                    <p><b>ID Cliente: </b><?php echo $datosCliente->idCliente ?> </p>
                                                    <p><b>Nombre: </b><?php echo $datosCliente->nombre ?> </p>
                                                    <p><b>Email: </b><?php echo $datosCliente->email ?> </p>
                                                    <p><b>Tel&eacute;fono: </b><?php echo $datosCliente->telefono ?> </p>
                                                    <p><b>Empresa: </b><?php echo $datosCliente->empresa ?> </p>
                                                    <p><b>Direcci&oacute;n </b><?php echo $datosCliente->direccion ?> </p>
                                                    <p><b>Aka: </b><?php echo $datosCliente->aka ?> </p>
                                                    <p class="divInfoInterno" style="display:none;"><b>Aka: </b><?php echo $datosCliente->aka ?> </p>
                                                  </div>
                                                  </div>
                                                  </div>
                                              </div>
                                                </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-md-6">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading">Contactos</div>
                                                <div class="panel-body"><br>
                                                    <div class="row">
                                                      <div class="col-xs-8"></div>
                                                      <div class="col-xs-4">
                                                      <span>&nbsp;<a href="#" data-toggle="modal" data-target="#popup_modalCrearContacto" class="agregarCliente" title="Agregar contacto" onclick="nuevoContacto();"><img width="16px" src="../images/iconos/iconos_grid/agregar.png"></a></span>
                                                      </div>
                                                    </div>
                                                    <div class="row">
                                                      <div class="col-xs-1"></div>
                                                      <div class="col-xs-10">
                                                        <div class="row">
                                                          <div class="col-xs-12">
                                                            <div id="">
                                                              <!-- <div id="busca_contactos"></div> -->
                                                            </div>
                                                          </div>
                                                        </div>
                                                        <div class="row">
                                                          <div class="col-xs-12">
                                                            <div id="divTablaContactos" class="tabla_overflow"></div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <div class="col-xs-1"></div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div> <!-- fin fila 3 cliente y contactos -->

                                <div class="row">

                                    <div class="col-xs-12 col-md-12">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading">Descripci&oacute;n del asunto</div>
                                                <div class="panel-body"><br>
                                                <div class="row">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-md-10">
                                                      <textarea class="form-control" name="descripcion" id="descripcion" rows="5"><?php echo $datosCaso->descripcion ?></textarea>
                                                      <input type="hidden" name="descripcionHd" id="descripcionHd" value="">
                                                    </div>
                                                    <div class="col-md-1"></div>
                                                </div>
                                                <br/>
                                                </div>
                                        </div>
                                    </div>
                                </div><!-- fin fila descripcion -->
                <div class="row"><!-- fila audio --->
                  <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning">
                      <div class="panel-heading">Notas de voz</div>
                      <div class="panel-body"><br>
                        <div class="row">
                          <div class="col-xs-4">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#popup_modalAudioNuevo" style="margin-left: 13px;" onclick="stateSoloVozTitular(false);">Nuevo Audio</button>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-1"></div>
                          <div class="col-xs-10">
                            <div class="row">
                              <div class="col-xs-12">
                                <div id="">
                                  <!-- <div id="busca_contactos"></div> -->
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-xs-12">
                                <!-- <div id="divTablaDigitales" class="tabla_overflow"></div> -->
                              </div>
                            </div>
                            <br>
                            <form name="grids" method="post">
                              <?php
                              echo $koolajax->Render();
                              if($gridAudios != null){
                                  echo $gridAudios->Render();
                              }
                              ?>
                            </form>
                            
                          </div>
                          <div class="col-xs-1"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <?php if($_SESSION['idRol']==1){ ?>

                  <div class="row"><!-- fila audio --->
                    <div class="col-xs-12 col-md-12">
                      <div class="panel panel-warning">
                        <div class="panel-heading">Notas de voz Titular</div>
                        <div class="panel-body"><br>
                          <div class="row">
                            <div class="col-xs-4">
                              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#popup_modalAudioNuevo" style="margin-left: 13px;" onclick="stateSoloVozTitular(true);">Nuevo Audio</button>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-1"></div>
                            <div class="col-xs-10">
                              <div class="row">
                                <div class="col-xs-12">
                                  <div id="">
                                    <!-- <div id="busca_contactos"></div> -->
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-xs-12">
                                  <!-- <div id="divTablaDigitales" class="tabla_overflow"></div> -->
                                </div>
                              </div>
                              <br>
                              <form name="grids" method="post">
                                <?php
                                echo $koolajax->Render();
                                if($gridAudiosTitular != null){
                                    echo $gridAudiosTitular->Render();
                                }
                                ?>
                              </form>
                              
                            </div>
                            <div class="col-xs-1"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                <?php } ?>              
                <div class="row" id="rowInternos" style="display: none;">
                  <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning">
                      <div class="panel-heading">Comentarios internos (Hechos, qu&eacute; quiere el cliente y c&oacute;mo vamos a ganar)</div>
                      <div class="panel-body"><br>
                        <div class="row">
                          <div class="col-xs-12">
                            <div class="alert alert-warning divInfoInterno" style="display: none;">
                              <span class="material-icons">new_releases</span> Los comentarios internos est&aacute;n activados
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-1"></div>
                          <div class="col-md-10">
                            <?php if ($idRol == 1 || $idRol == 2 || $idRol == 4) { //JGP 23/12/21 solicitan abogados si puedan editar 
                            ?>
                              <textarea class="form-control" name="internos" id="internos" rows="5"><?php echo $datosCaso->internos ?></textarea>
                              <input type="hidden" name="internosHd" id="internosHd" value="">
                            <?php } else { ?>
                              <?php echo $datosCaso->internos ?>
                              <input type="hidden" name="internos" id="internos" value="<?php echo $datosCaso->internos ?>">
                              <input type="hidden" name="internosHd" id="internosHd" value="">
                            <?php } ?>
                          </div>
                          <div class="col-md-1"></div>
                        </div>
                        <br />
                      </div>
                    </div>
                  </div>
                </div><!-- fin fila internos -->

                                <?php
                                // if($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2){
                                    ?>
                                  <div class="row" id="rowComTit" style="display: none;">

                                  <div class="col-xs-12 col-md-12">
                                    
                                      <div class="panel panel-warning">
                                          <div class="panel-heading">Comentarios titular</div>
                                              <div class="panel-body"><br>
                                              <div class="comentTituBg">

                                              <div class="comentTitu">
                                              <div class="row">
                                                <div class="col-xs-12">
                                                  <div class="alert alert-error divInfoComTit" style="display: none;">
                                                    <span class="material-icons">new_releases</span> Los comentarios de titular est&aacute;n activados
                                                  </div>
                                                </div>
                                              </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-1"></div>
                                                  <div class="col-md-10">
                                                    
                                                    <textarea class="form-control" name="comentariosTitular" id="comentariosTitular" rows="5"><?php echo $datosCaso->comentariosTitular ?></textarea>
                                                    <input type="hidden" name="comentariosTitularHd" id="comentariosTitularHd" value="">
                                                    
                                                  </div>
                                                  <div class="col-md-1"></div>
                                              </div>
                                              <br/>
                                              </div>

                                              </div>
                                      </div>
                                    
                                  </div>
                                  </div><!-- fin fila com titular -->
                                <?php
                                // }
                                ?>
                                <?php if($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2 ){ ?>
                                    <div class="col-xs-6 col-md-6">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading">Autorizaciones sistema</div>
                                                <div class="panel-body"><br>
                                                <div class="row">
                                                </div>
                                                    <div class="col-md-12 form-group" >
                                                        <select id="c_autorizados" name="c_autorizados" multiple="multiple" class="duallb">
                                                          <?php
                                                          $colAutorizados =  array_merge($colAbogados, $colExternos);
                                                          foreach ($colAutorizados as $elem) {
                                                              if(in_array($elem->idUsuario, $arrIdsAutorizados)){
                                                                  $sel = 'selected';
                                                                  
                                                              }else{
                                                                  $sel = '';
                                                              }
                                                              echo '<option '.$sel.' value="'.$elem->idUsuario.'">'.$elem->numAbogado.' - '.$elem->nombre.'</option>';
                                                            }
                                                            
                                                          ?>
                                                        </select>
                                                        <input type="hidden" id="c_idsautorizados" name="c_idsautorizados" value="<?php echo $autorizadosIds;?>" />
                                                    </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                                    <?php //end if rol 
                                                  }
                                              
                                              
                                                 ?>
                                

                                
                              
                                    <div class="col-xs-6 col-md-6" hidden>
                                        <div class="panel panel-warning">
                                            <div class="panel-heading">Autorizaciones en juzgados</div>
                                                <div class="panel-body"><br>
                                                <div class="row">
                                                    
                                                    <?php 
                                                    // if($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2){ 
                                                      ?>
                                                    <div class="col-md-12 form-group" >
                                                        <select id="c_autorizadosj" name="c_autorizadosj" multiple="multiple" class="duallb">
                                                          <?php
                                                          foreach ($colAbogados as $elem) {
                                                              if(in_array($elem->idUsuario, $arrIdsAutorizadosJ)){
                                                                  $sel = 'selected';
                                                              }else{
                                                                  $sel = '';
                                                              }
                                                              echo '<option '.$sel.' value="'.$elem->idUsuario.'">'.$elem->numAbogado.' - '.$elem->nombre.'</option>';
                                                          }
                                                          ?>
                                                        </select>
                                                        <input type="hidden" id="c_idsautorizadosj" name="c_idsautorizadosj" value="<?php echo $autorizadosIds;?>" />
                                                    </div>
                                                    <?php 
                                                    // }//end if rol 
                                                    // else{
                                                    //   foreach ($arrIdsAutorizadosJ as $autorizadoId) {
                                                    //     $autorizado = $usuariosObj->UserByID($autorizadoId);
                                                    //     echo '
                                                    //     <div class="row">
                                                    //       <div class="col-xs-1"></div>
                                                    //         <div class="col-xs-10">'.$autorizado->nombre.'</div>
                                                    //       <div class="col-xs-1"></div>
                                                    //     </div>';
                                                    //   }
                                                    // }
                                                    ?>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-1"></div>
                                                    <div class="col-xs-10">
                                                        <div class="alert alert-info">
                                                        <span class="material-icons">help_outline</span>
                                                        <strong>Recuerde:</strong> Este campo solo es informativo, no realiza ninguna acci&oacute;n dentro del sistema
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-1"></div>
                                                </div>
                                                </div>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="row" hidden>
                                  <div class="col-xs-12 col-md-6">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading">Documentos recibidos</div>
                                                <div class="panel-body"><br>
                                                    <div class="row">
                                                      <div class="col-xs-8"></div>
                                                      <div class="col-xs-4">
                                                      <span>&nbsp;<a href="#" data-toggle="modal" data-target="#popup_modalDocumentos" class="agregarCliente" title="Agregar documento"><img width="16px" src="../images/iconos/iconos_grid/agregar.png"></a></span>
                                                      </div>
                                                    </div>
                                                    <div class="row">
                                                      <div class="col-xs-1"></div>
                                                      <div class="col-xs-10">
                                                        <div class="row">
                                                          <div class="col-xs-12">
                                                            <div id="">
                                                              <!-- <div id="busca_contactos"></div> -->
                                                            </div>
                                                          </div>
                                                        </div>
                                                        <div class="row">
                                                          <div class="col-xs-12">
                                                            <div id="divTablaDocumentos" class="tabla_overflow"></div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <div class="col-xs-1"></div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-md-6">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading">Comentarios del expediente</div>
                                                <div class="panel-body"><br>
                                                    <div class="row">
                                                      <div class="col-xs-8"></div>
                                                      <div class="col-xs-4">
                                                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_comentario" onclick="preparaComentario();">Comentar</button>
                                                      </div>
                                                    </div>
                                                    <div class="row">
                                    <div class="col-xs-12">
                                        <ul class="media-list lista-comentarios">
                                        <?php
                                        foreach ($comentarios as $comentario) {
                                            $usrComentario = $usuariosObj->UserByID($comentario->usuarioId);
                                            $btnLeido = '';
                                            //$btnLeido = ($comentario->leido == 0)?'<a class="btn-notification pull-right" onclick="marcarLeidoComentario('.$comentario->idAccion.', \'actividad\')" href="javascript:void(0)" id="btn_comentario_'.$comentario->idAccion.'"><span class="material-icons">check_circle</span></a>':'<i class="pull-right">Leido</i>';
                                            $icono = obtenerIconoComentario($usrComentario->idRol);
                                            
                                            
                                            echo '
                                            <li class="media">
                                              <a class="pull-left" href="#">
                                                <span class="material-icons">
                                                '.$icono.'
                                                </span>
                                              </a>
                                              <div class="media-body">
                                                <h4 class="media-heading">'.$usrComentario->nombre.'
                                                <small>'.convertirAFechaCompleta4($comentario->fechaCreacion).' (Id: '.$comentario->idComentario.')</small></h4>
                                                '.$comentario->comentario.'
                                                '.$btnLeido.'
                                              </div>
                                            </li>
                                          ';
                                        }
                                         ?>
                                         </ul>
                                    </div>
                                </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>

                                

                                
                                

                            </div><!-- fin content_wrapper -->

                                
                            <!-- </div> -->
                        </form>

                        <div class="row">
                          <div class="col-xs-12 col-md-12">
                                <div class="panel panel-warning">
                                    <div class="panel-heading">Expediente digital</div>
                                        <div class="panel-body"><br>
                                            <div class="row">
                                              <div class="col-xs-2">
                                                <a class="btn btn-primary" href="digital.php?expedienteId=<?php echo $id; ?>&tipo=1" target="_blank">Ver escritos</a>
                                              </div>
                                              
                                              <div class="col-xs-2">
                                                <a class="btn btn-primary" href="../viewer/digital.php?expedienteId=<?php echo $id; ?>" target="_blank">Ver exp. Digital</a>
                                              </div>
                                              <div class="col-xs-2">
                                                <a class="btn btn-primary" href="digital.php?expedienteId=<?php echo $id; ?>&tipo=3" target="_blank">Ver audiencias</a>
                                              </div>
                                              <div class="col-xs-4">
                                              <span>&nbsp;<a href="#" data-toggle="modal" data-target="#popup_modalDigital" class="agregarCliente" title="Agregar documento digital" onclick="edicionDigital(0, '', '', '')">Agregar <img width="16px" src="../images/iconos/iconos_grid/agregar.png"></a></span> <!-- // LDAH 18/08/2022 IMP para descripcion del archivo -->
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-xs-1"></div>
                                              <div class="col-xs-10">
                                                <div class="row">
                                                  <div class="col-xs-12">
                                                    <div id="">
                                                      <!-- <div id="busca_contactos"></div> -->
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="row">
                                                  <div class="col-xs-12">
                                                    <!-- <div id="divTablaDigitales" class="tabla_overflow"></div> -->
                                                  </div>
                                                </div>
                                                <br>
                                                <form name="grids" method="post">
                                                  <?php
                                                  echo $koolajax->Render();
                                                  if($gridDigitales != null){
                                                      echo $gridDigitales->Render();
                                                  }
                                                  ?>
                                              </form>
                                              </div>
                                              <div class="col-xs-1"></div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <h3>Actividades</h3>

                            <div class="row">
                                <div class="col-xs-4">
                                </div>
                                <div class="col-xs-4">
                                <!-- <a href="actividad.php?expId=<?php echo $id ?>&tipo2=2"  title="Agregar reporte" class="btn btn-primary" target="_blank"><img width="16px" src="../images/iconos/iconos_grid/agregar.png"> Reporte</a> -->
                                </div>
                                <div class="col-xs-4">
                                    <?php //onclick="popupCreaEditaAccion(echo $id, 0)" ?>
                                    <a href="actividad.php?expId=<?php echo $id ?>"  title="Agregar acci&oacute;n" class="btn btn-primary" target="_blank"><img width="16px" src="../images/iconos/iconos_grid/agregar.png"> Agregar</a>
                                </div>
                            </div>
                            <br>
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
        </div>

        <!-- Modal lista de clientes -->
        <div class="modal fade" id="modalListaClientes" role="dialog" data-backdrop="static" data-keyboard="false" style="display:none;">
          <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Lista de clientes</h4>
                </div>

                <div class="row">

                  <form role="form" id="formListaClientes" name="formListaClientes" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="id_sel_cliente" id="id_sel_cliente" value="0">

                    <br>

                    <div class="row">
                        <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                            <div id="textoSeleccionado"></div>
                        </div>
                    </div>

                    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                        <div id="cont_listaclientes"></div>
                    </div>
                      
                    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                      <div class="row">
                        <div class="col-md-5 text-left">
                            <a href="#" data-toggle="modal" data-target="#popup_modalCrearCliente" class="btn btn-primary" title="Agregar cliente" onclick="reseteaFormulario('formCrearCliente');asignaValorCampo('clienteEditId',0);verificarExistenciaCliente()">Crear nuevo cliente</a>
                        </div>
                        </div>
                      </div>
                    

                    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                      <div class="row">
                        <div class="col-md-offset-5 col-md-6 text-right">
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

                  <form role="form" id="formListatitulares" name="formListatitulares" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="id_sel_titular" id="id_sel_titular" value="0">

                    <br>
                    <div class="row">
                        <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                            <div id="textoSeleccionadoTit"></div>
                        </div>
                    </div>
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
                    <input type="hidden" name="clienteEditId" id="clienteEditId" value="0">
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
                                <input type="text" class="form-control revisamaxlength" name="pc_dir" id="pc_dir" maxlength="300">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                              <label for="pc_aka">AKA:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="pc_aka" id="pc_aka">
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

        <div class="modal fade" id="popup_modalCrearContacto" role="dialog" data-backdrop="static" data-keyboard="false" style="display:none;">
          <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Crear/Editar Contacto</h4>
                </div>
                <div class="row">

                  <form role="form" id="formCrearContacto" name="formCrearContacto" method="post" action="" enctype="multipart/form-data">
                    <!-- <input type="hidden" name="id_sel_titular" id="id_sel_titular" value="0"> -->
                    <input type="hidden" name="c_casoid" id="c_casoid" value="<?php echo $id ?>">
                    <input type="hidden" name="contactoId" id="contactoId" value="0">
                    
                    <br>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                              <label for="c_nombre">Nombre:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control required" name="c_nombre" id="c_nombre">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                              <label for="c_telefono">Tel&eacute;fono:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control digits" name="c_telefono" id="c_telefono">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                              <label for="c_email">Correo:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control email" name="c_email" id="c_email">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                              <label for="c_domicilio">Domicilio:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="c_domicilio" id="c_domicilio">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                              <label for="c_notas">Notas</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="c_notas" id="c_notas">
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                              <label for="pc_dir">Direcci&oacute;n:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control revisamaxlength" name="pc_dir" id="pc_dir" maxlength="300">
                            </div>
                        </div>
                    </div> -->

                    <!-- <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                              <label for="pc_aka">AKA:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="pc_aka" id="pc_aka">
                            </div>
                        </div>
                    </div> -->

                    
                      <div class="row">
                        <div class="col-xs-3 text-right">
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="col-xs-3 text-right">
                          <a class="btn btn-primary" id="btnCrearContacto" onclick="crearContacto();">Aceptar</a>
                        </div>
                      </div>
                    

                    <div class="col-md-12"><br/></div>
                  </form>

                </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="popup_modalDocumentos" role="dialog" data-backdrop="static" data-keyboard="false" style="display:none;">
          <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Crear Documento</h4>
                </div>
                <div class="row">

                  <form role="form" id="formCrearDocumento" name="formCrearDocumento" method="post" action="" enctype="multipart/form-data">
                    <!-- <input type="hidden" name="id_sel_titular" id="id_sel_titular" value="0"> -->
                    <input type="hidden" name="d_casoid" id="d_casoid" value="<?php echo $id ?>">
                    <br>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                              <label for="d_nombre">Documento:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control required" name="d_nombre" id="d_nombre">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                              <label for="c_telefono">Condiciones de recepci&oacute;n:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="d_condiciones" id="d_condiciones">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Fecha recepci&oacute;n:</label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control inputfechaGral required" type="text" name="d_frecepcion" id="d_frecepcion" value="" style="width:90%;display:inline-block;" readonly>
                        </div>
                    </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Fecha retorno:</label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control inputfechaGral" type="text" name="d_fretorno" id="d_fretorno" value="" style="width:90%;display:inline-block;" readonly>
                        </div>
                    </div>
                    </div>

                    
                      <div class="row">
                        <div class="col-xs-3 text-right">
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="col-xs-3 text-right">
                          <a class="btn btn-primary" id="btnCrearDocumento" onclick="crearDocumento();">Aceptar</a>
                        </div>
                      </div>
                    

                    <div class="col-md-12"><br/></div>
                  </form>

                </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="popup_modalDigital" role="dialog" data-backdrop="static" data-keyboard="false" style="display:none;">
          <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Crear Documento Expediente Digital</h4>
                </div>
                <div class="row">

                  <form role="form" id="formCrearDigital" name="formCrearDigital" method="post" action="" enctype="multipart/form-data">
                    <!-- <input type="hidden" name="id_sel_titular" id="id_sel_titular" value="0"> -->
                    <input type="hidden" name="digi_casoid" id="digi_casoid" value="<?php echo $id ?>">
                    <input type="hidden" name="digi_id" id="digi_id" value="0">
                    <input type="hidden" name="digi_usuarioId" id="digi_usuarioId" value="<?php echo $_SESSION["idUsuario"] ?>">
                    <br>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                              <label for="digi_nombre">Nombre:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control required" name="digi_nombre" id="digi_nombre" onchange="cambiaNombreDigital(this.value)">
                            </div>
                        </div>
                    </div>
		    
		    
                    <!-- LDAH 16/08/2022 IMP para nuevo campo de descripcion de archivo -->
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                              <label for="digi_descrip">Descripcion:</label>
                            </div>
                            <div class="col-md-8">
                                <!-- <textarea name="comentarios" rows="10" cols="40" class="form-control required" name="digi_descrip" id="digi_descrip" onchange="cambiaDescripDigital(this.value)"></textarea> -->
                                
                                <input type="textarea" rows="10" cols="40" class="form-control required" name="digi_descrip" id="digi_descrip" onchange="cambiaDescripDigital(this.value)">
                                
                            </div>
                        </div>
                    </div>
                    <!-- -->


                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                              <label for="digi_tipo">Tipo:</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control required" name="digi_tipo" id="digi_tipo" onchange="cambiaTipoExp(this.value)">
                                  <option value="">Seleccione...</option>
                                  <option value="1">Escrito</option>
                                  <option value="2">Expediente</option>
                                  <option value="3">Audiencias</option>
                                  <option value="5">Audios</option>
                                  <option value="4">Otros</option>
                                </select>
                                <div class="row">
                      <div class="col-md-12" id="divArchivosAceptados"></div>
                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Archivo:</label>
                        </div>
                        <div class="col-md-8">
                            <!-- <input type="file" class="form-control" name="digi_file" id="digi_file"> -->
                            <div id="filelist"></div>
                            <br />

                            <div id="containerFile">
                                <a class="btn btn-primary" id="pickfiles" href="javascript:;" disabled="">Seleccionar Archivos</a> 
                                <a class="btn btn-primary" id="uploadfiles" href="javascript:;">Subir Archivos</a>
                            </div>

                            <div class="progress progress-striped">
                              <div id="barra_progreso" class="progress-bar progress-bar-info" role="progressbar"
                                  aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                <span class="sr-only">20% completado</span>
                              </div>
                            </div>

                            <pre id="console"></pre>

                        </div>
                    </div>
                    <div class="row">
                    <div class="col-md-4">
                            <label></label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="subido" id="subido" value="" readonly class="form-control required">

                        </div>
                    </div>
                    
                    </div>
                    

                    
                      <div class="row">
                        <div class="col-xs-3 text-right">
                          <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="actualizarGridDigitales()">Cerrar</button>
                        </div>
                        <div class="col-xs-3 text-right">
                          <a class="btn btn-primary" id="btnCrearDigital" onclick="crearDigital();">Aceptar</a>
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
                    <h4 class="modal-title">Crea Actividad</h4>
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
                                <input class="form-control inputfechaGral required" type="text" name="pa_fechaaccion" id="pa_fechaaccion" value="<?php echo $tz->fechaF2;?>" style="width:50%;display:inline-block;" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                              <label for="pa_accion">Actividad:</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control required" name="pa_accion" id="pa_accion">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                              <label for="pa_accion">Tipo:</label>
                            </div>
                            <div class="col-md-7">
                                <select class="form-control required" name="tipoactividad" id="tipoactividad">
                                  <option value="">Seleccione...</option>
                                  <option value="1">De fondo</option>
                                  <option value="2">Seguimiento</option>
                                  <option value="3">Audiencias</option>
                                  <option value="4">T&eacute;rmino</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                              <label for="pa_accion">Importancia:</label>
                            </div>
                            <div class="col-md-7">
                                <select class="form-control required" name="importanciaactividad" id="importanciaactividad">
                                  <option value="">Seleccione...</option>
                                  <option value="1">Media</option>
                                  <option value="2">Normal</option>
                                  <option value="3">Alta</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                              <label for="pa_comentario">Comentario:</label>
                            </div>
                            <div class="col-md-7">
                                <textarea class="form-control" name="pa_comentario" id="pa_comentario" rows="6"></textarea>
                                <input type="hidden" name="pa_comentario_hd" id="pa_comentario_hd" value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                              <label for="pa_internos">Comentarios internos:</label>
                            </div>
                            <div class="col-md-7">
                                <textarea class="form-control" name="pa_internos" id="pa_internos" rows="6"></textarea>
                                <input type="hidden" name="pa_internos_hd" id="pa_internos_hd" value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                      <div class="row">
                        <div class="col-md-offset-6 col-md-3 text-right">
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="col-md-3 text-right">
                          <a class="btn btn-primary" id="btnCrearTipo" onclick="btnCreaEditaAccion();">Aceptar</a>
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

        <div class="modal fade" id="modal_comentario" role="dialog" style="display:none;">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Comentario</h4>
              </div>
              <form role="form" id="formComentario" name="formComentario" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="co_usuarioId" id="co_usuarioId" value="<?php echo $_SESSION["idUsuario"];?>">
                    <!-- <input type="hidden" name="co_comentarios" id="co_comentarios" value="<?php echo $faker->text; ?>"> -->
                    <input type="hidden" name="co_casoid" id="co_casoid" value="<?php echo $id;?>">
                    
                    <input class="" type="hidden" name="co_fechaaccion" id="co_fechaaccion" value="<?php echo $tz->fechaF2?>" style="width:50%;display:inline-block;" >
                    <input type="hidden" name="co_comentarios_hd" id="co_comentarios_hd" value="">
                    <input type="hidden" name="co_usuarioIdSesion" id="co_usuarioIdSesion" value="<?php echo $_SESSION["idUsuario"];?>">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        
                        <div class="row">
                            <textarea class="form-control" name="co_comentarios" id="co_comentarios" cols="30" rows="5"></textarea>
                        </div>

                       
                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnCerrarModalDeuda">Cerrar</button>
                            </div>
                            <div class="col-md-3">
                            <a class="btn btn-primary" data-dismiss="modal" onclick="guardaComentarioExpediente()" id="btnGuardarComentario">Aceptar</a>
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
    <div class="modal" id="popup_modalAudioNuevo" role="dialog" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content" style="height: 400px;">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Nuevo Audio</h4>
          </div>
          <div id="row">
            <div style="margin-top: 42px;margin-left: 150px;">
            <div class="loadImg" id="redCircle" style="display:none;"><img src="../images/Red_circle.gif" height="45" style="margin-left: 138px;"/></div>   
            <p style="margin-left:120px">Tiempo: <span id="contador">0</span> segundos</p>
            <audio id="audioPreview" controls style="display: none"></audio>
              <div class="container">
                <label>Descripci&oacute;n</label>
                <br/>
                <textarea name="notaVozDescripcion" id="notaVozDescripcion" cols="42" rows="4" >

                </textarea>
                <br/>
                <label>
                <?php if($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2){ ?>  
                  <input type="checkbox" name="mostrarTitulares" id="mostrarTitulares" value="1">
                    Mostrar solo a titulares
                  </label>
                <?php }else{ ?>
                    <input type="hidden" name="mostrarTitulares" id="mostrarTitulares" value="1" />

                <?php } ?>
                <br/>
                <button id="startRecordingButton" class="btn btn-primary">Grabar</button>
                <button id="stopRecordingButton" class="btn btn-danger" disabled>Detener</button>
                <button id="playRecordingButton" class="btn btn-primary" disabled>Reproducir</button>
                <button id="saveRecordingButton" class="btn btn-primary" disabled>Guardar</button>
              </div>
            </div>


            <div class="container">
              <form role="form" id="formCrearAudio" name="formCrearAudio" method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="audio_casoid" id="audio_casoid" value="<?php echo $id ?>">
                <input type="hidden" name="audio_usuarioId" id="audio_usuarioId" value="<?php echo $_SESSION["idUsuario"] ?>">
                <div class="container">
                <div class="col-md-12"><br /></div>
              </form>
            </div>
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
    <script>
       var idRol = <?php echo $idRol ?>;
    </script>
    <?php echo scriptsPagina(true); ?>
    <link href="../css/jquery-ui.css" rel="stylesheet" type="text/css" />
    <!-- <link href="../css/jquery.signature.css" rel="stylesheet" type="text/css" /> -->
    <link href="../css/jquery.timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/jquery.timepicker.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../js/jquery.timepicker.min.js?upd='.$upd.'"></script>
    <script type="text/javascript" src="../js/jquery-ui-timepicker.js?upd='.$upd.'"></script>
    <script type="text/javascript" src="../js/spanish_datapicker.js?upd='.$upd.'"></script>
    <script type="text/javascript" src="../libs/jsPlumb.min.js"></script>
    <script type="text/javascript" src="../libs/jsplumb-tree.js"></script>
 
    <script>
        $(document).ready(function(){
            <?php if( isset($res) && $res==1){ ?>
                alertify.success("Informaci&oacute;n guardada correctamente.");
                setTimeout(function(){
                    window.location.href = "frmUsuario.php?id="+'<?php echo $idUsuario?>';
                }, 500);
            <?php } ?>

            obtListaContactos();

            obtListaDocumentos();

            // obtListaDigitales();
            digitales.refresh();
            digitales.commit();

           
            var params = {selector:"#descripcion", height:"230", btnImg:true};
            opcionesTinymce(params);

            var params = {selector:"#pa_comentario", height:"230", btnImg:true};
            opcionesTinymce(params);
            
            if(idRol == 1 || idRol == 2 || idRol == 4){
              var params = {selector:"#internos", height:"230", btnImg:true};
              opcionesTinymce(params);
            }

            if(idRol == 1 || idRol == 2){
                var params = {selector:"#comentariosTitular", height:"230", btnImg:true};
                opcionesTinymce(params);
            }

            var params = {selector:"#pa_internos", height:"230", btnImg:true};
            opcionesTinymce(params);

            searhColMaxLength();
        });
    </script>
</body>

</html>
