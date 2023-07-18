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
include_once '../brules/digitalesObj.php';
include_once '../brules/casosObj.php';
include_once '../libs/pdfmerge/vendor/autoload.php';
include_once '../libs/pdfmerge/vendor/composer/autoload_classmap.php';
include_once '../libs/pdfmerge/vendor/composer/autoload_namespaces.php';
// require_once '../libs/pdfmerge/vendor/itbz/fpdf/src/fpdf/FPDF.php';
// require_once '../libs/pdfmerge/vendor/itbz/fpdi/src/fpdi/fpdi/bridge.php';
// require_once '../libs/pdfmerge/vendor/itbz/fpdi/src/fpdi/FPDF/TPL.php';
// require_once '../libs/pdfmerge/vendor/itbz/fpdi/src/fpdi/FPDI.php';
// require_once '../libs/pdfmerge/src/PDFMerger/PDFMerger.php';

// require_once 'PDFMerger.php';
$digitalesObj = new digitalesObj();

$expedienteId = (isset($_GET['expedienteId']))?$_GET['expedienteId']:0;

$digitales = $digitalesObj->ObtDigitales($expedienteId, 2, '', '', '', '', 'orden ASC');

$fileArray = array();
foreach ($digitales as $digital) {
    $fileArray[] = "../upload/expedientes/".$digital->url;
}

$datadir = "../descargas/";
$outputName = $datadir."merged_".$expedienteId.".pdf";

$pdf = new \Clegginabox\PDFMerger\PDFMerger;
// $pdf = new PDFMerger;

foreach ($digitales as $digital) {
    // $fileArray[] = "../upload/expedientes/".$digital->url;
    $pdf->addPDF("../upload/expedientes/".$digital->url, 'all');
}

// $pdf->addPDF('samplepdfs/one.pdf', '1, 3, 4');
// $pdf->addPDF('samplepdfs/two.pdf', '1-2');
// $pdf->addPDF('samplepdfs/three.pdf', 'all');

//You can optionally specify a different orientation for each PDF
// $pdf->addPDF('samplepdfs/one.pdf', '1, 3, 4', 'L');
// $pdf->addPDF('samplepdfs/two.pdf', '1-2', 'P');

$pdf->merge('file', $outputName, 'P');

