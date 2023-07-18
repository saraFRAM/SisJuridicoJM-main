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
        
require_once '../brules/utilsObj.php';
require_once '../brules/PDFgenerarPdfObj.php';
require_once '../brules/PDFgetContentHtmlToStringObj.php';

$contHtmlToStrObj = new getContentHtmlToStringObj();
$genpdfObj = new generarPdfObj();

$id = (isset($_GET['id']))?$_GET['id']:0;
$abogadoId = (isset($_GET['abogado']))?$_GET['abogado']:0;
$internos = (isset($_GET['int']))?$_GET['int']:0;

$estatusId = (isset($_GET['estatus']))?$_GET['estatus']:0;
$fecha = (isset($_GET['fecha']))?$_GET['fecha']:0;
$desde = (isset($_GET['from']) && $_GET['from'] != '')?conversionFechas($_GET['from'])." 00:00:00":0;
$hasta = (isset($_GET['to']) && $_GET['to'] != '')?conversionFechas($_GET['to'])." 23:59:59":0;
$mostrar = (isset($_GET['mostrar']))?$_GET['mostrar']:'si';

$url = "sabana.php?abogado=".$abogadoId."&estatus=".$estatusId."&fecha=".$fecha."&from=".$_GET["from"]."&to=".$_GET["to"]."&mostrar=".$_GET['mostrar']."";
$html = $contHtmlToStrObj->getContentHtmlToString($url, array());

$genpdfObj->GenerarFormatoPDF("sabana", $html, 1, 0, "", "A5"); //imprimir pdf en portrait con apertura en el navegador