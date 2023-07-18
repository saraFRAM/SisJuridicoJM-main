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
include_once $dirname.'/brules/casosObj.php';
include_once $dirname.'/brules/catTipoCasosObj.php';
include_once $dirname.'/brules/usuariosObj.php';
include_once $dirname.'/brules/clientesObj.php';
include_once $dirname.'/brules/casoAccionesObj.php';
// include_once $dirname.'/brules/accionGastosObj.php';
include_once $dirname.'/brules/catConceptosObj.php';

include_once $dirname.'/brules/catPartesObj.php';
include_once $dirname.'/brules/catMateriasObj.php';
include_once $dirname.'/brules/catJuiciosObj.php';
include_once $dirname.'/brules/catDistritosObj.php';
include_once $dirname.'/brules/catJuzgadosObj.php';

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


$id = (isset($_GET['id']))?$_GET['id']:0;
$imprimir = (isset($_GET['imprimir']))?$_GET['imprimir']:0;
$abogadoId = (isset($_GET['abogado']))?$_GET['abogado']:0;
$internos = (isset($_GET['int']))?$_GET['int']:0;

$estatusId = (isset($_GET['estatus']))?$_GET['estatus']:0;
$fecha = (isset($_GET['fecha']))?$_GET['fecha']:0;
$desde = (isset($_GET['from']) && $_GET['from'] != '')?conversionFechas($_GET['from'])." 00:00:00":0;
$hasta = (isset($_GET['to']) && $_GET['to'] != '')?conversionFechas($_GET['to'])." 23:59:59":0;
$mostrar = (isset($_GET['mostrar']))?$_GET['mostrar']:'si';

$casos = $casosObj->ObtCasos("", $abogadoId, array("juzgadoId"));
if($_SESSION["idRol"] == 4 && $_SESSION["idUsuario"] != $abogadoId){
    $casos = array();
}

// Obtener grid de acciones
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sabana</title>
    <link href="../css/style-expediente.css" rel="stylesheet" type="text/css" >
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Sharp" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Two+Tone" rel="stylesheet">
</head>
<body
<?php if($imprimir == 1){ ?>
onload="window.print()"
<?php } ?>
>
<!-- <body onload="window.print()"> -->
    <img class="logo-img" src="../images/logo.png" class="">
    <button onclick="window.print()">Imprimir</button>
<?php
foreach ($casos as $caso) {
    $id = $caso->idCaso;
    $datosCaso = $casosObj->CasoPorId($id);
    $idResponsable = (isset($datosCaso->responsableId))?$datosCaso->responsableId:0;
    $datosResponsable = $usuariosObj->UserByID($idResponsable);
    $datosTitular = $usuariosObj->UserByID($datosCaso->titularId2);

    $parte = $catPartesObj->PartePorId($datosCaso->parteId);
    $materia = $catMateriasObj->MateriaPorId($datosCaso->materiaId);
    $juicio = $catJuiciosObj->JuicioPorId($datosCaso->juicioId);
    $distrito = $catDistritosObj->DistritoPorId($datosCaso->distritoId);
    $juzgado = $catJuzgadosObj->JuzgadoPorId($datosCaso->juzgadoId);

    $clienteId = (isset($datosCaso->clienteId))?$datosCaso->clienteId:0;
    $datosCliente = $clientesObj->ClientePorId($clienteId);
    $idTipo = (isset($datosCaso->tipoId))?$datosCaso->tipoId:0;
    $tipo = $catTipoCasosObj->TipoCasoPorId($idTipo);
    $cliente = (isset($datosCliente->nombre))?$datosCliente->nombre:"";
    // $idtitular = (isset($datosTitular->idUsuario))?$datosTitular->idUsuario:0;
    $responsable = (isset($datosResponsable->nombre))?$datosResponsable->nombre:"";
    $fechaAlta = (isset($datosCaso->fechaAlta))?convertirAFechaCompleta3($datosCaso->fechaAlta):"";
    $fechaAct = (isset($datosCaso->fechaAct))?convertirAFechaCompleta3($datosCaso->fechaAct):"";
    // $fechaAlta2 = (isset($datosCaso->fechaAlta))?convertirAFechaCompleta3($datosCaso->fechaAlta):"";// conversionFechaF2 
    //Obtener total de gastos
    // $colGastos = $accionGastosObj->ObtAccionGastos(0, $id);
    $tGastos = 0;
    // foreach($colGastos as $elem){
    //     $tGastos += $elem->monto;
    // }
    $tGastos = formatoMoneda($tGastos);
    $autorizadosIds = (isset($datosCaso->autorizadosIds))?$datosCaso->autorizadosIds:"";
    $arrIdsAutorizados = explode(",", $autorizadosIds);


    switch ($datosCaso->saludExpediente) {
        case 1: $salud = 'verde'; break;
        case 2: $salud = 'amarillo'; break;
        case 3: $salud = 'rojo'; break;
        default: $salud = 'no definido';break;
    }

    switch ($datosCaso->velocidad) {
        case 1: $velocidad = 'Normal'; break;
        case 2: $velocidad = 'Media'; break;
        case 3: $velocidad = 'Alta'; break;
        default: $velocidad = 'no definido';break;
    }

    switch ($datosCaso->estatusId) {
        case 1: $estatus = 'Activo'; break;
        case 2: $estatus = 'Suspendido'; break;
        case 3: $estatus = 'Baja'; break;
        case 4: $estatus = 'Terminado'; break;
        default: $estatus = 'no definido';break;
    }


    $actividades = $casoAccionesObj->ObtCasoAcciones($id, 0, $estatusId, $fecha, $desde, $hasta, 'importancia DESC');
// echo "<pre>";print_r($actividades);echo "</pre>";

if(count($actividades) == 0){continue;}//JGP desde el inicio si no tiene actividades no se debe mostrar

?>
<div class="div-actividad" style="page-break-after:always">    
    <h1 >Expediente Interno <?php echo $datosCaso->numExpediente ?></h1>
    <?php 
    if($internos == 1){
        echo '
        <div class="row">
            <div class="alert alert-warning divInfoInterno">
            <span class="material-icons">new_releases</span> Los comentarios internos est&aacute;n activados
            </div>
        </div><br>
        ';
    }
    ?>

<table class="break">
        <tr>
            <td class="table-inicio">
                <table class="table-actividad">
                    <tr><td>Id Expediente: </td><td> <?php echo $datosCaso->idCaso ?> </td>
                    <!-- <tr><td>Num Expediente: </td><td> <?php echo $datosCaso->numExpediente ?> </td> </tr> quitado -->
                    <tr><td>Cliente: </td><td> <?php echo $cliente ?> </td> </tr>
                    <tr><td>Representado:</td><td><?php echo $datosCaso->representado ?></td></tr>
                    <tr><td>Tipo: </td><td> <?php  echo $tipo->nombre ?> </td> </tr><!-- quitado -->
                    <tr><td>Responsable: </td><td> <?php echo $responsable ?> </td> </tr>
                    <tr><td>Expediente juzgado:</td><td><?php echo $datosCaso->numExpedienteJuzgado ?></td></tr>
                    <tr><td>Titular:</td><td><?php echo $datosTitular->nombre ?></td></tr><!-- quitado -->
                    <tr><td>Contrario:</td><td><?php echo $datosCaso->contrario ?></td></tr>
                    <tr><td>Fecha Alta: </td><td> <?php echo $fechaAlta ?> </td> </tr><!-- quitado -->
                    <tr><td>Fecha Act: </td><td> <?php echo $fechaAct ?> </td> </tr><!-- quitado -->
                </table>
            </td>
            <td class="table-inicio">
                <table class="table-actividad">
                    <tr><td>Salud expediente: </td><td>  <?php echo $salud ?></td> </tr>
                    <tr><td>Velocidad: </td><td>  <?php echo $velocidad ?></td> </tr><!-- quitado -->
                    <tr><td>Estatus: </td><td> <?php echo $estatus ?></td> </tr><!-- quitado -->
                    <tr><td>Parte: </td><td><?php echo $parte->nombre; ?></td> </tr>
                    <tr><td>Materia</td><td><?php echo $materia->nombre; ?></td></tr><!-- quitado -->
                    <tr><td>Juicio</td><td><?php echo $juicio->nombre; ?></td></tr>
                    <tr><td>Domicilio para emplazar</td><td><?php echo $datosCaso->domicilioEmplazar ?></td></tr><!-- quitado -->
                    <tr><td>Distrito: </td><td>  <?php echo $distrito->nombre; ?></td> </tr>
                    <tr><td>Juzgado: </td><td> <?php echo $juzgado->nombre; ?></td> </tr>
                    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                </table>
            </td>
        </tr>

        <tr>
            <td  colspan="2"><?php echo str_replace("&#34;",'"', $datosCaso->descripcion) ?></td>
        </tr><!-- quitado -->
    
        <?php
        if($internos == 1){
            // echo '
            // <tr>
            //     <td  colspan="2">'.$datosCaso->internos.'</td>
            // </tr>
            // ';
        }
         ?>
    
</table>



<h4>Actividades</h4>
<?php 
    if($internos == 1){
        echo '
        <div class="row">
            <div class="alert alert-warning divInfoInterno">
            <span class="material-icons">new_releases</span> Los comentarios internos est&aacute;n activados
            </div>
        </div><br>
        ';
    }
    ?>
<?php
if(count($actividades) == 0){
    echo '<table class="table-actividad break">
    <tr>
    <td>Este expediente no tiene actividades</td>
    </table><br>';
}
foreach ($actividades as $actividad) {
    $tipoa = ($actividad->tipo == 1)?"De fondo":"Seguimiento";
    $abogado = $usuariosObj->UserByID($actividad->usuarioId);
    $comentarios = $casoAccionesObj->ObtCasoAcciones($id, $actividad->idAccion);
    $htmlInt = $htmlComentarios = '';
    $colspan = 8;//3;
    $colspanCom = $colspan-2;

    switch ($actividad->importancia) {
        case 1: $importancia = 'Media'; break;
        case 2: $importancia = 'Normal'; break;
        case 3: $importancia = 'Alta'; break;
    }

    switch ($actividad->estatusId) {
        case 1: $estatus = 'Por Realizar'; break;
        case 2: $estatus = 'En proceso'; break;
        case 3: $estatus = 'Espero instrucciones'; break;
        case 4: $estatus = 'Terminado'; break;
    }

    if($internos == 1){
        $htmlInt = '
        <tr>
            <td  colspan="'.$colspan.'">'.$actividad->internos.'</td>
        </tr>
        ';
    }

    if(count($comentarios) > 0){
        $htmlComentarios .= '<tr><th colspan="'.$colspan.'">Comentarios:</th></tr>';
        if($mostrar == 'si'){
            foreach ($comentarios as $comentario) {
                $abogadoCom = $usuariosObj->UserByID($comentario->usuarioId);
                $htmlComentarios .= '
                
                <tr>
                    <td style="width: 10%">'.convertirAFechaCompleta3($comentario->fechaAlta).'</td>
                    <td colspan="'.$colspanCom.'" style="width: 70%">'.str_replace("&#34;",'"', $comentario->comentarios).'</td>
                    <td style="width: 20%">'.$abogadoCom->nombre.'</td>
                </tr>
                ';
            }
        }
    }else{
        
        // $htmlComentarios = '
        //     <tr><th colspan="'.$colspan.'">Comentarios:</th></tr>
        //     <tr>
        //         <td colspan="'.$colspan.'">Esta actividad no tiene comentarios</td>
        //     </tr>
        // ';
    }

    

    echo '<table class="table-actividad actividad" style="page-break-inside:auto; page-break-after:avoid;">
    <tr>
        <th>Id Act</th> <!-- quitado -->
        <th>Tipo</th> <!-- quitado -->
        <th style="width: 10%">Importancia</th>
        <th>Estatus</th> <!-- quitado -->
        <th style="width: 70%">Actividad</th>
        <th>Abogado</th> <!-- quitado -->
        <th>Fecha Alta</th> <!-- quitado -->
        <th style="width: 20%">Fecha Compromiso</th>
    <tr>
        <td>'.$actividad->idAccion.'</td> <!-- quitado -->
        <td>'.$tipoa.'</td> <!-- quitado -->
        <td style="width: 10%">'.$importancia.'</td>
        <td>'.$estatus.'</td> <!-- quitado -->
        <td style="width: 70%">'.$actividad->nombre.'</td>
        <td>'.$abogado->nombre.'</td> <!-- quitado -->
        <td>'.convertirAFechaCompleta3($actividad->fechaAlta).'</td> <!-- quitado -->
        <td style="width: 20%">'.convertirAFechaCompleta3($actividad->fechaCompromiso).'</td>
    </tr>
    <tr class="break">
        <td colspan="'.$colspan.'"><b>Descripci&oacute;n:</b> '.str_replace("&#34;",'"', $actividad->comentarios).'</td>
    </tr>
        '.$htmlInt.'
    <tr>
        <!--<td colspan="'.$colspan.'"><b>Reporte: </b>'.$actividad->reporte.'</td>-->
    </tr>
    
    '.$htmlComentarios.'
    </table>
    ';
}
echo '</div>';
 ?>
<?php }//fin ciclo casos ?>
</body>
</html>