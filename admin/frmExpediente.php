<?php
ini_set("session.cookie_lifetime","36000");
ini_set("session.gc_maxlifetime","36000");
session_start();
$idRol = $_SESSION['idRol'];
$rol = true;
switch ($idRol) {
    case 1: case 2: case 4: $rol = true; break;
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

include_once $dirname.'/brules/catPartesObj.php';
include_once $dirname.'/brules/catMateriasObj.php';
include_once $dirname.'/brules/catJuiciosObj.php';
include_once $dirname.'/brules/catDistritosObj.php';
include_once $dirname.'/brules/catJuzgadosObj.php';
include_once $dirname.'/brules/catAccionesObj.php';


$casosObj = new casosObj();
$catTipoCasosObj = new catTipoCasosObj();
$usuariosObj = new usuariosObj();


$catPartesObj = new catPartesObj();
$catMateriasObj = new catMateriasObj();
$catJuiciosObj = new catJuiciosObj();
$catDistritosObj = new catDistritosObj();
$catJuzgadosObj = new catJuzgadosObj();
$catAccionesObj = new catAccionesObj();



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
$colAbogados = $usuariosObj->obtTodosUsuarios(true, "1,2,4", "", " numAbogado ASC ");
$colExternos = $usuariosObj->obtTodosUsuarios(true, "5");
$colTitulares = $usuariosObj->obtTodosUsuarios(true, 1);
$currentUsr = $usuariosObj->UserByID($_SESSION['idUsuario']);

$permisohistorico = $_SESSION['permisoHistorico'];

//echo 'tengo:';
//print_r($_permisohistorico);

// echo "<pre>";
// print_r($currentUsr);
// print_r($colAbogados);
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


$cliente = "";
$idTipo = 0;
$titular = "";
$fechaAlta = $tz->fechaF2;//echo $fechaAlta;
$fechaAlta2 = (isset($tz->fecha))?convertirAFechaCompleta3($tz->fecha):"";
$autorizadosIds = "";
$autorizadosJuzgados = "";
$arrIdsAutorizados = explode(",", $autorizadosIds);
$arrIdsAutorizadosJ = explode(",", $autorizadosJuzgados);
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
foreach ($colTitulares as $titularItem) {
    $atributosTit = array(
        array("atributo"=>"data-coordinador", "valor"=>$titularItem->coordinadorId),
    );
    $arrTitulares[] = array("valor"=>$titularItem->idUsuario, "texto"=>$titularItem->nombre, "atributos"=>$atributosTit);
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
    "value" => $_SESSION['myusername'],
    "hidden"=> $_SESSION['idUsuario'],
);

$arrEstatus = array();
$arrEstatus[] = array("valor"=>"", "texto"=>"Seleccione...");
$arrEstatus[] = array("valor"=>1, "texto"=>"Activo");
$arrEstatus[] = array("valor"=>2, "texto"=>"Suspendido");
$arrEstatus[] = array("valor"=>3, "texto"=>"Baja");
$arrEstatus[] = array("valor"=>4, "texto"=>"Terminado");

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

$dataTitular = array(
    "onchange"=>"asignaCoordinador(this)",
);

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

// INICIO CAMPOS

$arrCampoUsuario = array(
    array("nameid"=>"usuarioAltaId", "type"=>"hidden_text", "class"=>"from-control", "readonly"=>true, "label"=>"Usuario alta:", "datos"=>array(), "value"=>"", "valores"=>$valoresUsuario),
);

if($permisohistorico == 1){
    $arrCampoNumExpediente = array(
    array("nameid"=>"numExpediente", "type"=>"text", "class"=>"form-control", "readonly"=>false, "label"=>"Expediente interno:", "datos"=>array(), "value"=>"", "atributos"=>$atributosNE),);
}else{
    $arrCampoNumExpediente = array(
        array("nameid"=>"numExpediente", "type"=>"text", "class"=>"form-control", "readonly"=>true, "label"=>"Expediente interno:", "datos"=>array(), "value"=>"", "atributos"=>$atributosNE),);

}
$arrCampoNumExpJuz = array(
    array("nameid"=>"numExpedienteJuzgado", "type"=>"text", "class"=>"form-control revisamaxlength", "readonly"=>false, "label"=>"Expediente juzgado:", "datos"=>array(), "value"=>"", "atributos"=>$atributosNEJ),
);

$arrCampoSaludExp = array(
    array("nameid"=>"saludExpediente", "type"=>"select", "class"=>"form-control", "readonly"=>false, "label"=>"Salud expediente:", "datos"=>$arrSalud, "value"=>""),
);

$arrCampoTitular = array(
    array("nameid"=>"titularId2", "type"=>"select", "class"=>"form-control required", "readonly"=>true, "label"=>"Titular:", "datos"=>$arrTitulares, "value"=>"", "dataInput"=>$dataTitular),
);

//Cambiamos por un hidden JGP 18/11/22
/*$arrCampoVelocidad = array(
    array("nameid"=>"velocidad", "type"=>"select", "class"=>"form-control", "readonly"=>false, "label"=>"Velocidad:", "datos"=>$arrVelocidad, "value"=>""),
);*/
$arrCampoVelocidad = array(
    array("nameid"=>"velocidad", "type"=>"hidden", "value"=>"1"),
);

$arrCampoContrario = array(
    array("nameid"=>"contrario", "type"=>"text", "class"=>"form-control", "readonly"=>false, "label"=>"Contrario:", "datos"=>array(), "value"=>""),
);

$arrCampoEstatus = array(
    array("nameid"=>"estatusId", "type"=>"select", "class"=>"form-control", "readonly"=>false, "label"=>"Estatus:", "datos"=>$arrEstatus, "value"=>""),
);

//Cambiamos por un hidden JGP 18/11/22
/*$arrCampoProcesal = array(
    array("nameid"=>"procesalId", "type"=>"select", "class"=>"form-control", "readonly"=>false, "label"=>"Estado procesal:", "datos"=>$arrProcesal, "value"=>""),
);*/
$arrCampoProcesal = array(
    array("nameid"=>"procesalId", "type"=>"hidden", "value"=>"1"),
);

//Cambiamos por un hidden JGP 18/11/22
/*
$arrCampoContundencia = array(
    array("nameid"=>"contundencia", "type"=>"select", "class"=>"form-control", "readonly"=>false, "label"=>"Contundencia:", "datos"=>$arrContundencia, "value"=>""),
);*/
$arrCampoContundencia = array(
    array("nameid"=>"contundencia", "type"=>"hidden", "value"=>"1"),
);

$claseCobro = ($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2)?"":"oculto";
$arrCampoCobro = array(
    array("nameid"=>"cobro", "type"=>"select", "class"=>"form-control", "readonly"=>false, "label"=>"Tipo cobro:", "datos"=>$arrTipoCobro, "value"=>"", "claseRow"=>$claseCobro),
);


$arrCampoCorreoNot = array(
    array("nameid"=>"correonot", "type"=>"select", "class"=>"form-control", "readonly"=>false, "label"=>"Correo notificaciones:", "datos"=>$arrCorreoNot, "value"=>"", "dataInput"=>$dataCorreoNot),
);

$arrCampoOtro = array(
    array("nameid"=>"otro", "type"=>"text", "class"=>"form-control", "readonly"=>true, "label"=>"Otro:", "datos"=>array(), "value"=>""),
);


$arrCampoParte = array(
    array("nameid"=>"parteId", "type"=>"select", "class"=>"form-control selectpicker", "readonly"=>false, "label"=>"Parte:", "datos"=>$arrParte, "value"=>"", "dataInput"=>$dataInput),
  );
  
  $arrCampoMateria = array(
    array("nameid"=>"materiaId", "type"=>"select", "class"=>"form-control selectpicker", "readonly"=>false, "label"=>"Materia:", "datos"=>$arrMateria, "value"=>"", "dataInput"=>$dataMateria),
  );
  
  $arrCampoJuicio = array(
    array("nameid"=>"juicioId", "type"=>"select", "class"=>"form-control selectpicker", "readonly"=>false, "label"=>"Juicio:", "datos"=>$arrJuicio, "value"=>"", "dataInput"=>$dataInput),
  );

  $arrCampoAccion = array(
    array("nameid"=>"accionId", "type"=>"select", "class"=>"form-control selectpicker", "readonly"=>false, "label"=>"Accion:", "datos"=>$arrAccion, "value"=>"", "dataInput"=>$dataInput, "claseRow"=>"rowAccion oculto"),
  );

  $arrCampoDistrito = array(
    array("nameid"=>"distritoId", "type"=>"select", "class"=>"form-control selectpicker", "readonly"=>false, "label"=>"Distrito:", "datos"=>$arrDistrito, "value"=>"", "dataInput"=>$dataDistrito),
  );

  $arrCampoJuzgado = array(
    array("nameid"=>"juzgadoId", "type"=>"select", "class"=>"form-control selectpicker", "readonly"=>false, "label"=>"Juzgado:", "datos"=>$arrJuzgado, "value"=>"", "dataInput"=>$dataInput),
  );

  $arrCampoDomicilio = array(
    array("nameid"=>"domicilioEmplazar", "type"=>"text", "class"=>"form-control", "readonly"=>false, "label"=>"Domicilio para emplazar:", "datos"=>array(), "value"=>""),
  );

  $arrCampoRepresentado = array(
    array("nameid"=>"representado", "type"=>"text", "class"=>"form-control", "readonly"=>false, "label"=>"Representado:", "datos"=>array(), "value"=>""),
  );
  // LDAH IMP 17/08/2022 para nuevos campos de juez
    $arrCampoNombreJuez = array(
    array("nameid"=>"nombreJuez", "type"=>"text", "class"=>"form-control", "readonly"=>false, "label"=>"Nombre de Juez:", "datos"=>array(), "value"=>""),
  );
  // LDAH IMP 18/08/2022 para nuevos campos de juez
    $arrCampoNombreSecret = array(
    array("nameid"=>"nombreSecretaria", "type"=>"text", "class"=>"form-control", "readonly"=>false, "label"=>"Nombre de secretaria:", "datos"=>array(), "value"=>""),
  );


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Nuevo Expediente</title>
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

                    <div class="col-md-10">
                        <h1 class="titulo">Nuevo Expediente<span class="pull-right"><a id="btnAyudaweb" onclick="mostrarAyuda('web_expediente')" href="#fancyAyudaWeb"><img src="../images/icon_ayuda.png" width="20px"></a></span></h1>
                        <ol class="breadcrumb">
                            <li><a href="expedientes.php">Mis expedientes</a></li>
                            <li class="active">Nuevo Expediente</li>
                        </ol>

                        <!--Mostrar en caso de presionar el boton de guardar-->
                        <?php if ($msjResponse != "") { ?>
                            <div class="new_line alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?php echo $msjResponse; ?>
                            </div>
                        <?php } ?>

                        <div class="row button-fixed">
                            <div class="row">
                                <div class="col-xs-1">
                                    <a onclick="crearCaso()" class="btn btn-primary" role="button" id="btnCrearCaso">Aceptar</a>
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-xs-1">
                                    <a onclick="window.close()" class="btn btn-primary" role="button">Cerrar</a>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        </div>

                        <form role="form" id="formCaso" name="formCaso" method="post" action="">
                            <input type="hidden" name="form_caso">
                            <input type="hidden" name="c_id" id="c_id" value="<?php echo $id;?>">
                            <!--
                            <input type="hidden" name="usuarioIdCreador" id="usuarioIdCreador" value="<?php echo $_SESSION['idUsuario'];?>">
                            <input type="hidden" name="dp_ids_deptos" id="dp_ids_deptos" value="<?php echo $idsDeptos; ?>">
                            <input type="hidden" id="dp_borrarP" value="<?php echo $borrarP; ?>">
                            <input type="hidden" id="salvarSinCrearVer" name="salvarSinCrearVer" value="0">
                            <input type="hidden" id="check_soloLectura" value="<?php echo $soloLectura;?>"> -->


                            <div class="content_wrapper">
                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading"></div>
                                                <div class="panel-body">
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-xs-1"></div>
                                                        <div class="col-xs-10">
                                                        <div class="alert alert-info">
                                                            <strong>Recuerde:</strong> Si se deja el numero de expediente en blanco, se generar&aacute; de forma autom&aacute;tica tomando en cuenta el año en curso.
                                                        </div>
                                                        </div>
                                                        <div class="col-xs-1"></div>
                                                    </div>
                                                <?php
                                                $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                                                echo generaHtmlForm($arrCampoNumExpediente, $cols);
                                                 ?>
                                                <div class="row">
                                                    <div class="col-md-4 text-right">
                                                        <label>Tipo cliente:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <select id="c_tipo" name="c_tipo" class="form-control required" style="width:90%;display:inline-block;">
                                                            <option value="">---Seleccionar---</option>
                                                                <?php
                                                                    foreach ($colTipos as $elem) {
                                                                        $sel = ($idTipo==$elem->idTipo)?"selected":"";
                                                                        echo '<option '.$sel.' value="'.$elem->idTipo.'">'.$elem->nombre.'</option>';
                                                                    }
                                                                ?>
                                                        </select>
                                                        <span>&nbsp;<a href="#" data-toggle="modal" data-target="#popup_modalCrearTipo" class="agregarTipo" title="Agregar tipo"><img width="16px" src="../images/iconos/iconos_grid/agregar.png"></a></span>
                                                    </div>
                                                </div>
                                                <br>
                                                <?php
                                                $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                                                echo generaHtmlForm($arrCampoNumExpJuz, $cols);
                                                 ?>
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
                                                    <div class="col-md-8">
                                                        <input class="form-control inputfechaGral required" type="text" name="c_falta" id="c_falta" value="<?php echo $fechaAlta;?>" style="width:50%;display:inline-block;" readonly>
                                                        
                                                    </div>
                                                </div>
                                                <?php
                                                $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"8", "input_md"=>"8");
                                                echo generaHtmlForm($arrCampoUsuario, $cols);
                                                 ?>
                                                </div>
                                        </div>
                                    </div>
                                </div>
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
                                                        <?php if($idRol == 4){  ?>
                                                            
                                                            <input type="hidden" id="c_idtitular" name="c_idtitular" value="<?php echo $currentUsr->idUsuario; ?>"/>
                                                            <input type="text" id="c_titular" name="c_titular" value="<?php echo $currentUsr->nombre;?>" class="form-control required" readonly style="width:80%;display:inline-block;"/>
                                                        <?php }else{  ?>
                                                            <input type="hidden" id="c_idtitular" name="c_idtitular" value="0"/>
                                                            <input type="text" id="c_titular" name="c_titular" value="<?php echo $titular;?>" class="form-control required" readonly style="width:80%;display:inline-block;"/>
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
                                                echo generaHtmlForm($arrCampoSaludExp, $cols);
                                                echo generaHtmlForm($arrCampoVelocidad, $cols);
                                                echo generaHtmlForm($arrCampoEstatus, $cols);
                                                echo generaHtmlForm($arrCampoProcesal, $cols);
                                                echo generaHtmlForm($arrCampoContundencia, $cols);
                                                echo generaHtmlForm($arrCampoCobro, $cols);
                                                 ?>
                                                </div>
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
                                                        <input type="hidden" id="c_idcliente" name="c_idcliente" value="0"/>
                                                        <input type="text" id="c_cliente" name="c_cliente" value="<?php echo $cliente;?>" class="form-control required" readonly style="width:72%;display:inline-block;"/>
                                                        <button type="button" class="btn btn-primary" role="button" title="Buscar" id="busca_clientes" value="Buscar" onclick="obtListaClientes();">
                                                            <span class="glyphicon glyphicon-search"></span>
                                                        </button>
                                                        <!-- <span>&nbsp;<a href="#" data-toggle="modal" data-target="#popup_modalCrearCliente" class="agregarCliente" title="Agregar cliente" onclick="reseteaFormulario('formCrearCliente');asignaValorCampo('clienteEditId',0)"><img width="16px" src="../images/iconos/iconos_grid/agregar.png"></a></span> -->
                                                    </div>
                                                    <div class="col-md-2">
                                                        <?php if($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2){ ?>
                                                        <a class="cursorPointer" onclick="muestraEditarCliente()"><span class="material-icons">edit</span></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <?php
                                                    $cols = array("label_xs"=>"3", "label_md"=>"3", "input_xs"=>"7", "input_md"=>"7");
                                                    echo generaHtmlForm($arrCampoRepresentado, $cols);
                                                ?>
                                                <div class="row">
                                                    <!-- <div class="col-md-3 text-right"></div> -->
                                                    <div class="col-md-10 form-group" id="rowDivDatosCliente"></div>
                                                </div>
                                                </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-md-6">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading">Contactos</div>
                                                <div class="panel-body"><br>
                                                    Para agregar contactos, es necesario haber guardado el expediente
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-xs-12 col-md-12">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading">Descripci&oacute;n del asunto</div>
                                                <div class="panel-body"><br>
                                                <div class="row">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-md-10">
                                                        <textarea class="form-control" name="descripcion" id="descripcion" rows="5"></textarea>
                                                        <input type="hidden" name="descripcionHd" id="descripcionHd" value="">
                                                    </div>
                                                    <div class="col-md-1"></div>
                                                </div>
                                                <br/>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" hidden>

                                    <div class="col-xs-12 col-md-12">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading">Comentarios internos (Hechos, qu&eacute; quiere el cliente y c&oacute;mo vamos a ganar)</div>
                                                <div class="panel-body"><br>
                                                <div class="row">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-md-10">
                                                        <textarea class="form-control" name="internos" id="internos" rows="5"></textarea>
                                                        <input type="hidden" name="internosHd" id="internosHd" value="">
                                                    </div>
                                                    <div class="col-md-1"></div>
                                                </div>
                                                <br/>
                                                </div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                if($_SESSION["idRol"] == 1){
                                    ?>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-12">
                                            <div class="panel panel-warning">
                                                <div class="panel-heading">Comentarios titular</div>
                                                    <div class="panel-body"><br>
                                                    <div class="row">
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-10">
                                                            <textarea class="form-control" name="comentariosTitular" id="comentariosTitular" rows="5"></textarea>
                                                            <input type="hidden" name="comentariosTitularHd" id="comentariosTitularHd" value="">
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                    </div>
                                                    <br/>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>

                                <div class="row" hidden>

                                    <div class="col-xs-6 col-md-6">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading">Autorizaciones sistema</div>
                                                <div class="panel-body"><br>
                                                <div class="row">
                                                    <div class="col-md-3 text-right">
                                                        <label>Autorizados:</label>
                                                    </div>
                                                    <div class="col-md-9 form-group" style="margin-left:25%;">
                                                        <select id="c_autorizados" name="c_autorizados" multiple="multiple" class="duallb">
                                                            <?php
                                                            $colAutorizados =  array_merge($colAbogados, $colExternos);
                                                            foreach ($colAutorizados as $elem) {
                                                                if(in_array($elem->idUsuario, $arrIdsAutorizados)){
                                                                    $sel = 'selected';
                                                                }else{
                                                                    $sel = '';
                                                                }
                                                                echo '<option value="'.$elem->idUsuario.'">'.$elem->numAbogado.' - '.$elem->nombre.'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <input type="hidden" id="c_idsautorizados" name="c_idsautorizados" value="<?php echo $autorizadosIds;?>" />
                                                    </div>
                                                </div>
                                                </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-md-6">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading">Autorizados en juzgados</div>
                                                <div class="panel-body"><br>
                                                <div class="row">
                                                    <div class="col-md-3 text-right">
                                                        <label>Autorizados en juzgados:</label>
                                                    </div>
                                                    <div class="col-md-9 form-group" style="margin-left:25%;">
                                                        <select id="c_autorizadosj" name="c_autorizadosj" multiple="multiple" class="duallb">
                                                            <?php
                                                            foreach ($colAbogados as $elem) {
                                                                if(in_array($elem->idUsuario, $arrIdsAutorizadosJ)){
                                                                    $sel = 'selected';
                                                                }else{
                                                                    $sel = '';
                                                                }
                                                                echo '<option value="'.$elem->idUsuario.'">'.$elem->numAbogado.' - '.$elem->nombre.'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <input type="hidden" id="c_idsautorizadosj" name="c_idsautorizadosj" value="<?php echo $autorizadosIds;?>" />
                                                    </div>
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
                                                    Para agregar documentos, es necesario haber guardado el expediente
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">

                                    <!-- <div class="col-xs-12 col-md-6">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading"></div>
                                                <div class="panel-body">
                                                    Contenido del panel
                                                </div>
                                        </div>
                                    </div> -->
                                    <!-- columna 1 -->
                                    <div class="col-md-12">
                                        
                                        
                                        
                                        <?php

                                        
                                        ?>


                                        
                                        
                                        
                                        

                                        
                                    </div>

                                    <div class="cont_btnrequisicion">
                                        <div class="new_line">
                                        </div>
                                    </div>

                                    <!-- columna 2 -->
                                    <!-- <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-3 text-right">
                                                <label>Estatus:</label>
                                            </div>
                                            <div class="col-md-7 form-group  alert alert-info">
                                                ?php
                                                if($idP>0){
                                                    if($estatusDp==1){
                                                        echo '<span class="texto_estatus">En creaci&oacute;n</span>';
                                                    }
                                                    if($estatusDp==2){
                                                        echo '<span class="texto_estatus">Espera de aprobaci&oacute;n</span>';
                                                    }
                                                    if($estatusDp==3){
                                                        echo '<span class="texto_estatus">Aprobado</span>';
                                                    }
                                                    if($estatusDp==4){
                                                        echo '<span class="texto_estatus">Rechazado</span>';
                                                    }

                                                    echo '<input type="hidden" id="dp_estatus" name="dp_estatus" value="'.$estatusDp.'">';
                                                }else{
                                                    echo '<span class="texto_estatus">En creaci&oacute;n</span>';
                                                    echo '<input type="hidden" id="dp_estatus" name="dp_estatus" value="1">'; //Valor default en creacion
                                                }
                                                ?>
                                            </div>
                                            <div class="col-md-2 text-right">
                                                <button type="button" class="btn btn-primary" role="button" title="Imprimir Proceso" onclick="printProcess()">
                                                    <span class="glyphicon glyphicon-print"></span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                            <label>Revisiones:</label>
                                            </div>
                                            <div class="col-md-12">
                                            ?php
                                                echo $koolajax->Render();
                                                if($resultRevs != null){
                                                    echo $resultRevs->Render();
                                                }
                                            ?>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>

                        </form>


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
                        <div class="text-right">
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
                              <label for="pc_cliente">Nombre completo:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control required" name="pc_cliente" id="pc_cliente">
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
                              <label for="pc_empresa">Empresa:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="pc_empresa" id="pc_empresa">
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
                        <div class="row comentTituBg">
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

        <!-- Modal crear cliente -->
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
    <script>
        $(document).ready(function(){
            <?php if( isset($res) && $res==1){ ?>
                alertify.success("Informaci&oacute;n guardada correctamente.");
                setTimeout(function(){
                    window.location.href = "frmUsuario.php?id="+'<?php echo $idUsuario?>';
                }, 500);
            <?php } ?>
            var params = {selector:"#descripcion", height:"230", btnImg:true};
            opcionesTinymce(params);
            var params = {selector:"#internos", height:"230", btnImg:true};
            opcionesTinymce(params);

            if(idRol == 1){
                var params = {selector:"#comentariosTitular", height:"230", btnImg:true};
                opcionesTinymce(params);
            }
        });
    </script>
</body>

</html>
