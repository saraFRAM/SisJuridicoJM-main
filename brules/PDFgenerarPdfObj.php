<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/brules/dompdf/autoload.inc.php';
include_once  $dirname.'/brules/dompdf/lib/html5lib/Parser.php';
include_once  $dirname.'/brules/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
include_once  $dirname.'/brules/dompdf/lib/php-svg-lib/src/autoload.php';
include_once  $dirname.'/brules/dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();

include $dirname.'/common/config.php';

class generarPdfObj {
    public function GenerarFormatoPDF($titulo, $cuerpoHtml, $orientation=0, $tipoDescarga=0, $rutaDescarga="", $paper = "letter"){
     //ob_start();
        // create new PDF document
        $html = '
        <html>
            <head>
            </head>

            <body>
                 ';

        $html .= $cuerpoHtml;

        $html .= '</body>
               </html>';

        set_time_limit(0);
        ini_set("memory_limit","-1");
        // $dompdf = new Dompdf();
        ;

        $options = new Dompdf\Options();
        // $options->set('defaultFont', 'Courier');
        $options->set('isRemoteEnabled', TRUE);
        $options->set('debugKeepTemp', TRUE);
        $options->set('isHtml5ParserEnabled', true);
        //$options->set('chroot', '');
        $dompdf = new Dompdf\Dompdf($options);
        // $domPdf->output(['isRemoteEnabled' => true]);
        if($orientation==0){
            // $dompdf->set_paper("letter", "portrait"); //selecciona la orientación de la hoja en este caso horizontal
            $dompdf->setPaper($paper, 'portrait');
        }else{
            // $dompdf->set_paper("letter", "landscape"); //selecciona la orientación de la hoja en este caso horizontal
            $dompdf->setPaper($paper, 'landscape');
        }
        
        // $dompdf->load_html($html);
        $dompdf->loadHtml($html);
        $dompdf->render();

        //Salvar automaticamente el pdf en la ruta especifica
        if($tipoDescarga==0){
            $dompdf->stream($titulo.".pdf", array('Attachment'=>0));   //abre en el navegador
        }
        //Salvar automaticamente el pdf en la ruta especifica
        if($tipoDescarga==1){            
            file_put_contents($rutaDescarga.$titulo.".pdf", $dompdf->output());
            // file_put_contents('../upload/pdfgarantias/'.$titulo.".pdf", $dompdf->output());
        }
        if($tipoDescarga==2){
            $dompdf->stream($titulo.".pdf", array('Attachment'=>1));   //abre el popop para guardarlo en disco
        }

    }

}

?>
