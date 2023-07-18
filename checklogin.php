<?php
ob_start();

include_once 'brules/usuariosObj.php';
include_once 'brules/catConfiguracionesObj.php';

$catConfiguracionesObj = new catConfiguracionesObj();
$catpcha = $catConfiguracionesObj->ObtConfiguracionByID(2);
$local = false;//Jair 17/3/2022 Variable para saber si se esta trabajando local, localmente no hay https y el captcha lo requiere
$continuar = false;
if(!$local){
    //Comentar en local porque no funciona sin https
    $response = $_POST["g-recaptcha-response"];
    if(!empty($response)){
        // $secret = "6Lf1jvAbAAAAAFZoZvVrKJic_kUfT0kbwBBtRW-e";
        $secret = $catpcha->valor;
        $ip = $_SERVER['REMOTE_ADDR'];
        $respuestaValidacion = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$ip");
    
        //Si queremos visualizar la informaci�n obtenida de la petici�n a la api de validaci�n de Google para comprobar el estado de esta lo haremos con la funci�n de PHP var_dump
        //var_dump($respuestaValidaci�n);
    
        $jsonResponde = json_decode($respuestaValidacion);
        if($jsonResponde->success){
            //entrar� aqu� cuando todo sea correcto	
            $continuar = true;
        }
        else{
            //Google ha detectado que se trata de un proceso no humano
            //header("location:index.html?mensaje=humanCaptcha");
            // echo json_encode(array("valida"=>'-1'));
            // exit();
            header("location:index.php?login=captcha");
            $continuar = false;
        }
    }
    else{
        header("location:index.php?login=captcha");
        $continuar = false;
    }
}else{
    $continuar = true;
}


if($continuar){
    $userObj = new usuariosObj();

    $usrEmail = stripslashes($_POST['usr_email']);
    $usrPass = stripslashes($_POST['usr_pass']);

    //verificar si existe usuario

    $user = $userObj->LogInUser(trim($usrEmail), str_replace("'", "", trim($usrPass)));

    if($user->idUsuario>0){
        if($user->activo>0){
            session_start();
            $_SESSION['idUsuario'] = $user->idUsuario;
            $_SESSION['idRol'] = $user->idRol;
            $_SESSION['myusername'] = $user->nombre;
            $_SESSION['permisoHistorico'] = $user->permisohistorico;
            $_SESSION["status"] = "ok";        
            
            if($user->idRol == 1 || $user->idRol == 2){
                header("location:admin/agenda.php");
            }
            elseif ($user->idRol == 3) {
                header("location:admin/agenda.php");
            }
            elseif ($user->idRol == 4) {
                header("location:admin/agenda.php");
            }  
            elseif ($user->idRol == 5) {
                header("location:externo/expedientes.php");
            }        
            else{
                header("location:index.php?login=error");
            }
        }
        else{
            header("location:index.php?login=inactivo");
        }
    }
    else{
        header("location:index.php?login=error");
    }
    ob_flush();
}



?>
