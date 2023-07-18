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

include_once '../brules/usuariosObj.php';
include_once '../brules/conceptosObj.php';
include_once '../brules/catConceptosObj.php';
include_once '../brules/utilsObj.php';

  $idUsuario = (isset($_GET['idUsuario']) !="")?$_GET['idUsuario']:"";
  $vista = (isset($_GET['vista']) !="")?$_GET['vista']:"";// Jair 7/4/2022 Para saber si viene de reportes y no imprimir directamente
  $imprimir = (isset($_GET['imprimir']) !="")?$_GET['imprimir']:0;

  $desde = (isset($_POST['desde_'.$idUsuario]) !="")?$_POST['desde_'.$idUsuario]:"";
  $hasta = (isset($_POST['hasta_'.$idUsuario]) !="")?$_POST['hasta_'.$idUsuario]:"";

  $permiso = (isset($_POST['desde_'.$idUsuario]))?true:false;
  $totalAbonos = (isset($_POST['totalAbonos_'.$idUsuario]) !="")?$_POST['totalAbonos_'.$idUsuario]:0;
  $totalCargos = (isset($_POST['totalCargos_'.$idUsuario]) !="")?$_POST['totalCargos_'.$idUsuario]:0;
  $saldoPeriodo = (isset($_POST['saldoPeriodo_'.$idUsuario]) !="")?$_POST['saldoPeriodo_'.$idUsuario]:0;
  $saldo = (isset($_POST['saldo_'.$idUsuario]) !="")?$_POST['saldo_'.$idUsuario]:0;
  
  $conceptosObj = new conceptosObj();
  $usuariosObj = new usuariosObj();
  $catConceptosObj = new catConceptosObj();
  $conceptos = $conceptosObj->ObtTodosConceptos($idUsuario, "", $desde, $hasta, "ORDER BY fecha DESC");

  //array fechas dias
  $fechaItem = '';
  $arrFechas = array();
  $arrFechas[] = $hasta;
  $fechaAnt = $hasta;

    // echo "<pre>";print_r($_POST);echo "</pre>";die();
  while($fechaItem != $desde){
    $fechaItem = diasPrevPos(1, $fechaAnt, "prev", 3);
    $arrFechas[] = $fechaItem;
    $fechaAnt = $fechaItem;
  }

//   echo "<pre>";print_r($arrFechas);echo "</pre>";
  
  $usuario = $usuariosObj->UserByID($idUsuario);
  $html = '';
  
  $html.= '
  <h4>'.$usuario->numAbogado.' - '.$usuario->nombre.'</h4>
  <div class="row">
        <table class="table table-hover table-actividad">
            <tr>
                <td>Total Entradas</td>
                <td class=" text-right">$ '.number_format($totalAbonos, 2).'</td>
            </tr>
            <tr>
                <td>Total Salidas</td>
                <td class=" text-right">$ '.number_format($totalCargos, 2).'</td>
            </tr>
            <tr>
                <td>Saldo periodo</td>
                <td class=" text-right">$ '.number_format($saldoPeriodo, 2).'</td>
            </tr>
            <tr>
                <td>Saldo total actual al d&iacute;a</td>
                <td class=" text-right">$ '.number_format($saldo, 2).'</td>
            </tr>
        </table>

      
  </div><br>
  <table class="table table-hover table-actividad">
  <thead>
  <tr>
  <th>Fecha</th>
  <th>F. Captura</th>
  <th>Entrada</th>
  <th>Salida</th>
  <th>Saldo Dia</th>
  <th>Saldo Sem Acum</th>
  <th>Tipo</th>
  <th>Concepto</th>
  </tr>
  </thead>
  <tbody>
      ';
    $arrFechas = array_reverse($arrFechas);  
    $arrConceptos = array();
    foreach($arrFechas as $fItem){
      $conceptosDia = $conceptosObj->ObtTodosConceptos($idUsuario, "", $fItem, $fItem, "ORDER BY fecha DESC");
      $conceptosSem = $conceptosObj->ObtTodosConceptos($idUsuario, "", $desde, $fItem, "ORDER BY fecha DESC");
      $entradasSem = $salidasSem = 0;
      $entradaSem = $salidaSem = '';
      
      foreach($conceptosSem as $itemConcepSem){
          if($itemConcepSem->tipoId == 1){
              $salidasSem += $itemConcepSem->monto;
              $salidaSem = '$ -'.number_format($itemConcepSem->monto, 2);
            }else{
              $entradasSem += $itemConcepSem->monto;
              $entradaSem = '$ '.number_format($itemConcepSem->monto, 2);
            }
      }
      
      if(count($conceptosDia) > 0){
          $entradasDia = 0;
          $salidasDia = 0;
          $saldoDia = 0;
          $htmlDia = '';
          $htmlConceptosDia = '';

          foreach ($conceptosDia as $concepto) {
            $catConcepto = $catConceptosObj->ConceptoPorId($concepto->catConceptoId);
            $entrada = $salida = '';
            if($concepto->tipoId == 1){
              $salidasDia += $concepto->monto;
              $salida = '$ -'.number_format($concepto->monto, 2);
            }else{
              $entradasDia += $concepto->monto;
              $entrada = '$ '.number_format($concepto->monto, 2);
            }
            // echo "<pre>";print_r($concepto);echo "</pre>";
              // switch ($concepto->tipoId) {
              //     case 1: $tipo = 'Salida'; break;
              //     case 2: $tipo = 'Entrada'; break;
              //     default: break;
              // }
    
              // $fechaAbono = ($concepto->fecha != '' && $concepto->fecha > 0)?convertirFechaVista($concepto->fecha):'';
             
              // $monto = ($concepto->tipoId == 3)?-$concepto->monto:$concepto->monto;
      
              // $html .= '
              // <tr>
              // <td>'.$tipo.'</td>
              // <td>'.$catConcepto->nombre.'</td>
              // <td class="text-right">$ '.number_format($monto, 2).'</td>
              
              // <td>'.convertirFechaVista($concepto->fechaCreacion).'</td>
              // <td>'.$fechaAbono.'</td>
              // <td>'.$concepto->descripcion.'</td>
              // </tr>
              // ';

              $htmlConceptosDia .= '
              <tr>
                <td></td>
                <td class="text-right">'.convertirFechaVistaConHora($concepto->fechaCreacion).'</td>
                <td class="text-right">'.$entrada.'</td>
                <td class="text-right">'.$salida.'</td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td>'.$catConcepto->nombre.'</td>
                <td>'.$concepto->descripcion.'</td>
              </tr>
              ';
          }

          $saldoDia = $entradasDia - $salidasDia;
          $saldoDiaAcum = $entradasSem - $salidasSem;

          $htmlDia .= '
          <tr class="rowDia">
            <th>'.convertirAFechaCompleta3($fItem).'</th>
            <td></td>
            <th class="text-right">$ '.number_format($entradasDia,2).'</th>
            <th class="text-right">$ -'.number_format($salidasDia,2).'</th>
            <th class="text-right">$ '.number_format($saldoDia,2).'</th>
            <th class="text-right">$ '.number_format($saldoDiaAcum,2).'</th>
            <th></th>
            <th></th>
          </tr>
          ';

          $html .= $htmlDia.$htmlConceptosDia;
      }
    }



  $html.= '
  </tbody<
  </table>';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control gastos</title>
    <style>
      html, body{
        padding:0;
        margin:0;
        }
        .wrapper{
            width:90%;
            max-width:1200px;
            margin:0 auto;
        }
        body {
            font-family: 'Gotham-Bold', Sans-Serif;
            font-size: 14px;
            margin: 0 auto;
            color: #000;
            width: 90%;
            align-items: center;
        }
        a {
            color: inherit; /* blue colors for links too */
            text-decoration: inherit; /* no underline */
        }

        .contimg, .conForm {
            width: 100%;
            margin: 0 auto;
            /* height: 100vh; */
        }
        .conForm {
            /* background: #4a90e2; */    display: flex;
        }
        h1 {
            width: 90%;
            text-align:center;
            text-transform: uppercase;
            margin: 0 auto;
            font-family: 'AvenirLTStd-Heavy';
            font-size:19px;
            color:#000;
        }
        img {
            margin-bottom: 0;
            width: 100%;
        }
        img.logo {
            max-width: 200px;
            margin: 1.5% 4% 1.5%;
        }
        .back-logo {
            width: 100%;
            text-align: left;
            display: inline-flex;
        }

        section{
            position: relative;
        }
        .container {
            margin: auto;
            width: 70%;
            z-index: 1;
        }

        p {
            font-size: 1.2em;
            line-height: 1.5;
        }
        section#sec-servicios {
            color: #3a200b;
            height: 60vh;
            margin: 0 auto;
            background: linear-gradient(1deg, #ece1d3 42%, #d4c0a8 100%);
            z-index: 1000000;
            width: 100%;
            text-align: left;
        }
        .text-serv {
            background: #f9f6f2;
            box-shadow: 0px 1px 6px 0 rgba(0,0,0,0.50);
        }
        .servicios {
            width: 60%;
            margin: 0 auto;
            position: absolute;
            max-width: 1200px;
            left: 10%;
            right: 10%;
            top: -10%
        }
        .line-top, .line-bottom {
            display: flex;
            height: 300px;
        }
        ul.list li {
            line-height: 2;
        }

        .text-top {
            float: right;
            right: 0;
            text-align: right;
        }

        .img-logo {
            width: 70%;
        }
        .logo-img {
            top: 3%;
            left: 0;
            right: 0;
            margin: 0 auto;
            max-width: 200px;
        }
        .contimg h2 {
            /* position: absolute; */
            bottom: 0;
            left: 0;
            right: 0;
            width: 77%;
            margin: 7em auto 1em;
        }
        .columna.col4 {
            padding: 0;
        }

        .borde {
            border: 1px solid #000;
            width: 82%;
        }
            .center{
                text-align: center;
            }
        .OpenTable_secc h2 {
            opacity: 0.77;
            font-family:Avenir-Medium;
            font-size:16.5px;
            color:#ffffff;
            letter-spacing:10.83px;
            text-align:center;
            text-transform: uppercase;
            margin-top: 1em;
        }
        .col4 {
            width: 33.33333334%;
        }
        .col3 {
            width: 25%;
        }
        .col7 {
            width: 58.33333334%;
        }
        .columnas {
            max-width: 1200px;
            margin: 0 auto;
            display: inline-flex;
        }
            .columnas:after {
            content: '';
            clear: both;
            position: relative;
            display: block;
        }
        .columna {
            position: relative;
            float: left;
            padding: 30px;
            box-sizing: border-box;
            line-height: 1.2em; 
        }

        .columna img, img.fullwidth {
            display: block;
            height: auto;
            width: 100%;
        }
        .col12 {
            width: 100%;
        }
        .col6 {
            width: 50%;
        }
        .datos {
            border: 1px solid #000;
            margin-bottom: 20px;
        }
            section#contenido .columna.col12 {
            padding: 0;
        }
        .copyright img {
            width: 2%;
        }
        .copyright {
            padding: 15px;
            text-align: center;
        }
        .text-right{
        text-align: right;
        }
        table {
            border-collapse: collapse;
            border: 1px solid #000;
        }
        th, td{
            border: 1px solid #000;
        }
        .table-inicio{
            width: 50%;
        }
        .table-actividad{
            width: 100%;
        }
    </style>
</head>
<body 
<?php if($imprimir == 1){ ?>
onload="window.print()"
<?php } ?>
>
    <img class="logo-img" src="../images/logo.png" class="">
    <button onclick="window.print()">Imprimir</button>
    <?php if($permiso){ ?>
  <h4><strong>PERIODO: </strong><?php echo 'De '.convertirAFechaCompleta3($desde).' a '.convertirAFechaCompleta3($hasta); ?></h4>
    <?php echo $html; }
    else{
        echo "No fue posible cargar la informacion, favor de intentar dando clic en el enlace del grid";
    }
     ?>

</body>
</html>