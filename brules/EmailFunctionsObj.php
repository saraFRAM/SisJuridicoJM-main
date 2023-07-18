<?php
/*
 *  © 2017 Framelova. All rights reserved. Privacy Policy
 *  Creado: 24/01/2017
 *  Por: J. Carlos Ramirez G - JCRG
 *  Descripción: Functions need for sent emails
 */

$dirname = dirname(__DIR__);
include_once  $dirname.'/common/class.phpmailer.php';
include_once  $dirname.'/common/class.smtp.php';

class EmailFunctions{

    //private $_emailBlocked = "carlos.ramirez@framelova.com";

	 //Metodo que ejecuta el envio el correo
    private function SendDataMail($subject, $email, $mailHtml){
         $sfrom = 'Aeet <soporte@Aeet.com.mx>';
        //$sfrom = 'Z Motors <noresponder@casasAeet.com.mx>';
        $sheader= $this->GetHeader($sfrom,'');
        $res = mail($email,$subject,$mailHtml,$sheader);

        if($res!=true) {
           $statusSend = '0';
        } else {
           $statusSend = '1';
        }

        return $statusSend;
    }
  	//Header del email
  	private function GetHeader($sfrom, $bcc){
        $sheader = "From:".$sfrom."\nReply-To:".$sfrom."\n";
        if($bcc != ''){
            $sheader=$sheader.'Bcc:'.$bcc."\n";
        }
    		$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n";
    		$sheader=$sheader."Mime-Version: 1.0\n";
        $sheader=$sheader."X-Priority: 3\n";
        $sheader=$sheader."X-MSMail-Priority: Normal\n";
		    $sheader=$sheader."Content-Type: text/html; charset=utf-8";        

        return $sheader;
    }

    //Metodo encargado para enviar correos usando phpmailer
    public function EmailSmptNoPass($email="",$nombreEmail="", $body="",$subject="",$attached=""){
        include '../common/config.php';

        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->Host = $smtp_host; // SMTP server
        $mail->SMTPAuth = true;                  // enable SMTP authentication
        $mail->SMTPKeepAlive = true;
        $mail->Port = $smtp_Port;                    // set the SMTP port for the GMAIL server
        $mail->Username = $smtp_Username; // SMTP account username
        $mail->Password = $smtp_Password;        // SMTP account password
        $mail->SMTPSecure = $smtp_SMTPSecure;
        $mail->SetFrom($email_from, $name_from);


        $mail->AddAddress($email, "");


        $mail->Subject = $subject;
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        $mail->MsgHTML($body);

        //adjuntar documento
        if($attached!=""){
          $mail->AddAttachment($attached); // ruta del archivo
        }

        if(!$mail->Send()) {
            $statusSend = '0';
            //$statusSend = "Mailer Error: " . $mail->ErrorInfo;
          } else {
            $statusSend = '1';
          }

        return $statusSend;
    }


     //>>>>>>>>METODOS ENCARGADOS PARA CONTRUIR Y ENVIAR EL CORREO

    public function EnviarTarea($subject, $email, $url, $mensaje)
     {
       //$email = "jair.castaneda@framelova.com";//DESHABILITAR
       $mailHtml = $this->enviarTareaBody($url, $mensaje);
       //$resMail =  $this->EmailSmptNoPass($email, "", $mailHtml, $subject, "");//HABILITAR
       $resMail = 1;//DESHABILITAR
       return $resMail;
     }
    
    public function EnviarDatosDeAcceso($email, $nombreUsuario, $passCliente)
    {
      $subject = 'Datos de acceso';
      $mailHtml = $this->datosAccesoMailBody($nombreUsuario, $email, $passCliente);
      
//       $resMail =  $this->SendDataMail($subject, $email, $mailHtml);
       $resMail =  $this->EmailSmptNoPass($email, $nombreUsuario, $mailHtml, $subject, "");
      // $this->SendDataMail($subject, "karlos.0@live.com.mx", $mailHtml);
      // $this->SendDataMail($subject, "carlos.ramirez@framelova.com", $mailHtml);

       // echo $mailHtml;
      return $resMail;    
      
    }
    
    public function RecuperarDatosDeAcceso($email, $nombreUsuario, $passCliente)
    {
      $subject = 'Recuperar contraseña';
      $mailHtml = $this->recuperarDatosAccesoMailBody($nombreUsuario, $email, $passCliente);
//       $resMail =  $this->SendDataMail($subject, $email, $mailHtml);
       $resMail =  $this->EmailSmptNoPass($email, $nombreUsuario, $mailHtml, $subject, "");
      // $this->SendDataMail($subject, "karlos.0@live.com.mx", $mailHtml);
      // $this->SendDataMail($subject, "carlos.ramirez@framelova.com", $mailHtml);

      // echo $mailHtml;
      return $resMail;      
    }

    //Nueva aclaracion
    public function NuevaAclaracion($email, $nombreUsuario, $asunto, $comentario)
    {
      $subject = 'Aclaración';      
      //$email = "karlos.0@live.com.mx";
      $mailHtml = $this->nuevaAclaracionMailBody($nombreUsuario, $asunto, $comentario);
      // $resMail =  $this->SendDataMail($subject, $email, $mailHtml);
      $resMail =  $this->EmailSmptNoPass($email, $nombreUsuario, $mailHtml, $subject, "");
      
      return $resMail;      
    }

    //Nueva garantia
    public function NuevaGarantia($email, $nombreUsuario, $asunto, $categoria, $descripcion, $fotos="")
    {
      $subject = 'Garantía';
      $mailHtml = $this->nuevaGarantiaMailBody($nombreUsuario, $asunto, $categoria, $descripcion, $fotos);      
//      $resMail =  $this->SendDataMail($subject, $emailResponsable, $mailHtml);
      $resMail =  $this->EmailSmptNoPass($email, $nombreUsuario, $mailHtml, $subject, "");
      return $resMail;
    }

     public function EnviarCaracVersion($subject, $email, $htmlImg, $caracteristicas, $versionNombre = "", $precioDesde = "")
    { 
      // $email = "jair.castaneda@framelova.com";
      $mailHtml = $this->enviarCaracVersionBody($htmlImg, $caracteristicas, $versionNombre, $precioDesde);
      // echo $mailHtml;      
      $resMail =  $this->SendDataMail($subject, $email, $mailHtml);//HABILITAR
      // $resMail = 1;//DESHABILITAR
      return $resMail;          
    }

    public function EnviarGenerico($subject, $email, $titulo, $mensaje, $nombreApp = "AEET", $aux = "", $showNoReplay = true, $attached = "")
    { 
      //$email = "jair.castaneda@framelova.com";//DESHABILITAR
      $mailHtml = $this->enviarGenericoBody($titulo, $mensaje, $nombreApp, $aux, $showNoReplay);      
      //$resMail =  $this->EmailSmptNoPass($email, "", $mailHtml, $subject, $attached);//HABILITAR
      $resMail = 1;//DESHABILITAR
      return $resMail;          
    }


    //>>>>>>>>CUERPOS HTML  

     private function enviarTareaBody( $url, $mensaje){
          $dirname = dirname(__DIR__);
          include  $dirname.'/common/config.php';
          // $segObj = new seguridadObj();
          // $param1 = $segObj->encriptarCadena('param1='.$idUsuario);
          $mensaje = str_replace("../upload", $siteURL."upload", $mensaje);

            $html = '<html><body>';
                $html .= '<table style="width:600px;" >';
                    $html .= "<tbody>";
                      // $html .= '<tr><td><img src="'.$siteURL.'images/banner_sisti.jpg" style="width: 100%;"></td></tr>';
                       $html .= "<tr>";
                           $html .= '<td style="padding: 10px;">Le informamos que ha recibido una notificacion de IT & SOLUTIONS.</td>';
                       $html .= "</tr>";
                       $html .= "<tbody>";
                       $html .= "<tr>";
                           $html .= '<td style="padding: 10px;">
                                       Recibi&oacute; el siguiente Pendiente :<br>'.$mensaje.'
                                       <br>Para Completar el Pendiente debe dar click en el siguiente enlace:<br>
                                       <a href="'.$siteURL."admin/".$url.'"> Atender </a>
                                    </td>';
                       $html .= "</tr>";
                    $html .= "</tbody>";
                    $html .= "<tfoot>";
                        $html .= "<tr>";
                            $html .= '<td style="font-size: 12px; margin-top: 30px;"><br/>Este mensaje es enviado de forma automática favor de no responder.</td>';
                        $html .= "</tr>";
                    $html .= "</tfoot>";
                $html .= '</table>';
            $html .= '</body></html>';
            return $html;
        }

         //Cuerpo generico para correos de notificaciones
   private function enviarGenericoBody($titulo, $mensaje, $nombreApp, $aux, $showNoReplay){
      $dirname = dirname(__DIR__);
      include  $dirname.'/common/config.php';
      // $segObj = new seguridadObj();
      // $param1 = $segObj->encriptarCadena('param1='.$idUsuario);
      $mensaje = str_replace("../upload", $siteURL."upload", $mensaje);

        $html = '<html><body>';
            $html .= '<table style="width:600px;" >';
                $html .= "<tbody>";
                $html .= '<tr><td><img src="'.$siteURL.'images/banner_correo.jpg" style="width: 100%;"></td></tr>';
                   if($showNoReplay){
                      $html .= "<tr>";
                       $html .= '<td style="padding: 10px;">Le informamos que ha recibido una notificaci&oacute;n de '.$nombreApp.' .</td>';
                      $html .= "</tr>";
                    }
                   $html .= "<tbody>";
                   $html .= "<tr>";
                       $html .= '<td style="padding: 10px;">
                                   '.$titulo.' <br> '.$mensaje.'
                                </td>';
                   $html .= "</tr>";
                $html .= "</tbody>";
                if($showNoReplay){
                  $html .= "<tfoot>";
                      $html .= "<tr>";
                          $html .= '<td style="font-size: 12px; margin-top: 30px;"><br/>Este mensaje es enviado de forma autom&aacute;tica favor de no responder.</td>';
                      $html .= "</tr>";
                  $html .= "</tfoot>";
                }
            $html .= '</table>';
        $html .= '</body></html>';
        return $html;
    }

     //Cuerpo generico para correos de notificaciones
   private function enviarCaracVersionBody($htmlImg, $caracteristicas, $versionNombre, $precioDesde){
      $dirname = dirname(__DIR__);
      include  $dirname.'/common/config.php';
      // $segObj = new seguridadObj();
      // $param1 = $segObj->encriptarCadena('param1='.$idUsuario);

        $html = '<html><body>';
            $html .= '<table style="width:600px;" >';               
                $html .= "<tbody>";
                $html .='<tr>'.$htmlImg.'</tr>';
                   $html .= "<tr>";  
                       $html .= '<td style="padding: 10px;"><h3>'.$versionNombre.' .</h3><p>Desde '.$precioDesde.'</p></td>';                       
                   $html .= "</tr>";      
                   $html .= "<tbody>";
                   $html .= "<tr>";  
                       $html .= '<td style="padding: 10px;">
                                   '.$caracteristicas.'
                                </td>';                       
                   $html .= "</tr>";      
                $html .= "</tbody>";
                $html .= "<tfoot>";
                    $html .= "<tr>";
                        $html .= '<td style="font-size: 12px; margin-top: 30px;"><br/>Este mensaje es enviado de forma automática favor de no responder.</td>';
                    $html .= "</tr>"; 
                $html .= "</tfoot>";
            $html .= '</table>';        
        $html .= '</body></html>';
        return $html;
    }
    
    //Html para correo de datos de acceso
    private function recuperarDatosAccesoMailBody($nombre, $email, $password){
        $html = '<html><body>';
            // $html .= '<table style="width:600px;" >';               
            //     $html .= "<tbody>";
            //        $html .= "<tr>";  
            //            $html .= '<td style="padding: 10px;">Estimado '.$nombre.' recibimos su solicitud de recuperacion de contraseña.</td>';                       
            //        $html .= "</tr>";      
            //        $html .= "<tbody>";
            //        $html .= "<tr>";  
            //            $html .= '<td style="padding: 10px;">Sus datos de acceso son:.</td>';
            //            $html .= '<td>E-mail: '.$email.'<br> Contraseña: '.$password.'</td>';
            //        $html .= "</tr>";      
            //     $html .= "</tbody>";
            //     $html .= "<tfoot>";
            //         $html .= "<tr>";
            //             $html .= '<td style="font-size: 12px; margin-top: 30px;"><br/>Este mensaje es enviado de forma automática favor de no responder.</td>';
            //         $html .= "</tr>"; 
            //     $html .= "</tfoot>";
            // $html .= '</table>';

          $html .= '<table style="width:600px;" >';            
              $html .= '<tbody>';
                $html .= '<tr>';
                  $html .= '<td colspan="2" style="padding: 10px;">Estimado <b>'.$nombre.'</b>, recibimos su solicitud de recuperación de contraseña. tus datos de acceso a la aplicación son:</td>';
                $html .= '</tr>';
              $html .= '</tbody>';
              $html .= '<tbody>';
                $html .= '<tr>';
                  $html .= '<td width="115"><b>E-mail:</b></td>';
                  $html .= '<td width="475">'.$email.'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td><b>Contraseña</b></td>';
                  $html .= '<td>'.$password.'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td colspan="2">&nbsp;</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td colspan="2"><p>Una vez iniciado sesión podrás cambiar tu contraseña <br/> en la sección de perfil.</p></td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td colspan="2">&nbsp;</td>';
                $html .= '</tr>';
              $html .= '</tbody>';
              $html .= '<tfoot>';
                $html .= '<tr>';
                  $html .= '<td colspan="2" style="font-size: 12px; margin-top: 30px;">Este mensaje es enviado de forma automática favor de no responder.</td>';
                $html .= '</tr>';
              $html .= '</tfoot>';
          $html .= '</table>';


        $html .= '</body></html>';
        return $html;
    }
    private function datosAccesoMailBody($nombre, $email, $password){
        $html = '<html><body>';

            // $html .= '<table style="width:600px;" >';               
            //     $html .= "<tbody>";
            //        $html .= "<tr>";
            //            $html .= '<td style="padding: 10px;">Estimado '.$nombre.' los datos de acceso a su cuenta Aeet son:</td>';
            //            $html .= '<td>E-mail: '.$email.'<br> Contraseña: '.$password.'</td>';
            //        $html .= "</tr>";      
            //     $html .= "</tbody>";

            //     $html .= "<tfoot>";
            //         $html .= "<tr>";
            //             $html .= '<td style="font-size: 12px; margin-top: 30px;"><br/>Este mensaje es enviado de forma automática favor de no responder.</td>';
            //         $html .= "</tr>"; 
            //     $html .= "</tfoot>";
            // $html .= '</table>';  

            $html .= '<table style="width:600px;" >';            
              $html .= '<tbody>';
                $html .= '<tr>';
                  $html .= '<td colspan="2" style="padding: 10px;">Estimado <b>'.$nombre.'</b>, tus datos de acceso a la aplicación son:</td>';
                $html .= '</tr>';
              $html .= '</tbody>';
              $html .= '<tbody>';
                $html .= '<tr>';
                  $html .= '<td width="115"><b>E-mail:</b></td>';
                  $html .= '<td width="475">'.$email.'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td><b>Contraseña</b></td>';
                  $html .= '<td>'.$password.'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td colspan="2">&nbsp;</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td colspan="2"><p>Una vez iniciado sesión podrás cambiar tu contraseña <br/> en la sección de perfil.</p></td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td colspan="2">&nbsp;</td>';
                $html .= '</tr>';
              $html .= '</tbody>';
              $html .= '<tfoot>';
                $html .= '<tr>';
                  $html .= '<td colspan="2" style="font-size: 12px; margin-top: 30px;">Este mensaje es enviado de forma automática favor de no responder.</td>';
                $html .= '</tr>';
              $html .= '</tfoot>';
            $html .= '</table>';


        $html .= '</body></html>';

        return $html;
    }

    //Nueva aclaracion
    private function nuevaAclaracionMailBody($nombreUsuario, $asunto, $comentario){
        $html = '<html><body>';

            $html .= '<table style="width:600px;" >';               
                $html .= "<tbody>";
                   $html .= "<tr>";  
                       $html .= '<td style="padding: 10px;">Estimado usuario tiene una nueva aclaración</td>';
                   $html .= "</tr>";
                   $html .= "<tr>";  
                       $html .= '<td>Asunto: '.$asunto .'</td>';
                   $html .= "</tr>";
                   $html .= "<tr>";
                       $html .= '<td>Comentario: '.$comentario .'</td>';                       
                   $html .= "</tr>";
                $html .= "</tbody>";

                $html .= "<tfoot>";
                    $html .= "<tr>";
                        $html .= '<td style="font-size: 12px; margin-top: 30px;"><br/>Este mensaje es enviado de forma automática favor de no responder.</td>';
                    $html .= "</tr>"; 
                $html .= "</tfoot>";

            $html .= '</table>';        

        $html .= '</body></html>';

        return $html;
    }

    //Nueva garantia
    private function nuevaGarantiaMailBody($nombreUsuario, $asunto, $categoria, $descripcion, $fotos){
        $dirname = dirname(__DIR__);
        include  $dirname.'/common/config.php';
        
        $adjFotos = "";
        if($fotos!=""){
          $arrFotos = explode(",", $fotos);
          foreach($arrFotos as $elemFoto){                        
            $adjFotos .= '<span style="margin: 0 5px;"><img src="'.$siteURL.'upload/imagenesGarantias/'.$elemFoto.'" style="width:80px"></span>';
          }
        }        
        

        $html = '<html><body>';

            $html .= '<table style="width:600px;" >';               
                $html .= "<tbody>";
                   $html .= "<tr>";  
                       $html .= '<td style="padding: 10px;">Estimado usuario tiene una nueva garantía para darle seguimiento</td>';
                   $html .= "</tr>";
                   $html .= "<tr>";  
                       $html .= '<td>Asunto: '.$asunto .'</td>';
                   $html .= "</tr>";
                   $html .= "<tr>";  
                       $html .= '<td>Categoría: '.$categoria .'</td>';
                   $html .= "</tr>";
                   $html .= "<tr>";
                       $html .= '<td>Descripción: '.$descripcion .'</td>';                       
                   $html .= "</tr>";
                   $html .= "<tr>";
                       $html .= '<td>'.$adjFotos.'</td>';
                   $html .= "</tr>";                   
                $html .= "</tbody>";

                $html .= "<tfoot>";
                    $html .= "<tr>";
                        $html .= '<td style="font-size: 12px; margin-top: 30px;"><br/>Este mensaje es enviado de forma automática favor de no responder.</td>';
                    $html .= "</tr>"; 
                $html .= "</tfoot>";

            $html .= '</table>';        

        $html .= '</body></html>';

        return $html;
    }

}
