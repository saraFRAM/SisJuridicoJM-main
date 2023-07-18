<?php
/*
 *  © 2017 Framelova. All rights reserved. Privacy Policy
 *  Creado: 01/12/2017
 *  Por: JCR
 *  Descripción: This is called via Ajax to return data type json to app
 */
include_once '../brules/utilsObj.php';
include_once '../brules/usuariosObj.php';
include_once '../brules/catConfiguracionesObj.php';
include_once '../brules/catAyudasObj.php';
include_once '../brules/EmailFunctionsObj.php';
include_once '../brules/registroDispositivosObj.php';
include_once '../brules/comunicadosObj.php';



//Fisrt check the function name
$function="";
if(isset($_GET['funct'])){
  $function= $_GET['funct'];
}
else{
  $function= $_POST['funct'];
}

switch ($function)
{  
    case "loginUser":
        loginUser($_GET['callback'], $_GET['email'], $_GET['password'], $_GET['idRegDispositivo'], $_GET['plataforma']);
      break;

    case "recuperarContrasenia":
        recuperarContrasenia($_GET['callback'], $_GET['email']);
    break;
    case "obtAyudaPorAlias":
      obtAyudaPorAlias();
    break;

    case "configuracionPorId":
        configuracionPorId($_GET['callback'], $_GET['idConf']);
      break;
        
    default:
      echo "Not valid call";
}

/*
 *  autenticacion de usuario
*/

function loginUser($callback, $email, $password, $idRegDispositivo, $plataforma){
    $userObj = new usuariosObj();
    // $dataGeneral = $userObj->GetAllData(1);
    $regDispObj = new registroDispositivo();
    $datosUsr=$userObj->LoginUser(stripslashes(trim($email)), stripslashes(trim($password)));
    //verifica que el usuario exista
    if($datosUsr->idUsuario>0){
        //solo pueden acceder los clientes
        // if($datosUsr->idRol==3){
        $perfilesObj = new perfilesObj();
        $dataPerfil = $perfilesObj->PerfilPorIdUsuario($datosUsr->idUsuario);
          $arr = array("success"=>true, "idUsr"=>$datosUsr->idUsuario, "userName"=>$datosUsr->nombre,
                     "password"=>$datosUsr->password, "active"=>$datosUsr->activo, "email"=>$email,
                     "idRol"=>$datosUsr->idRol,"director"=>$dataPerfil->esDirector,"empresaId"=>$dataPerfil->empresaId
              );

          //Registrar el codigo de dispositivo
          $regDispObj->idRegDispositivo = $idRegDispositivo;
          $regDispObj->usuarioId = $datosUsr->idUsuario;
          $regDispObj->plataforma = $plataforma;

          

          //Verificar si el usuario ya cuenta con un id de dispositivo para solo actualizarlo
          $colRegDisp = $regDispObj->obtTodosRegDispositivoPorIdUsr();                   
          if(count($colRegDisp)>0){
            //obtener el id de la fila
            $idReg = 0;
            foreach ($colRegDisp as $elemRegDisp){
              $idReg = $elemRegDisp->idReg;
            }
            //Actualizar registro              
            $regDispObj->idReg = $idReg;
            $regDispObj->ActRegActivo();              
          }else{
            //Crear registro              
            //Comprobar que el registro no este vacio para no registrar basura
            if($idRegDispositivo!=""){
              $regDispObj->Salvar();
            }
          }
        // }else{
        //     $arr = array("success"=>false);
        // }
    }else{
       $arr = array("success"=>false);
    }
    echo $callback . '(' . json_encode($arr) . ');';
}

//recupera la contrasela y le manda un correo al cliente
function recuperarContrasenia($callback, $email){
    $userObj = new usuariosObj();
    $emailObj = new EmailFunctions();
    $datosUsr = $userObj->UserByEmail($email);

    //verifica que el usuario exista
    if($datosUsr->idUsuario>0){
        //generar un correo con un enlace de resetear contrasenia
        // $emailObj->reseteoContrasenia($email, $datosUsr->nombre, $datosUsr->idUsuario); //HABILITAR
        //Enviar datos de acceso
        $emailObj->RecuperarDatosDeAcceso($email, $datosUsr->nombre, $datosUsr->password);

        $arr = array("success"=>true);
     }else{
         $arr = array("success"=>false);
     }

     echo $callback . '(' . json_encode($arr) . ');';
}

function configuracionPorId($callback, $idConf){
    $confObj = new catConfiguracionesObj();
    $colConfig = $confObj->ObtConfiguracionByID($idConf);
    $valor = str_replace("&#34;",'"', $colConfig->valor);
    //verifica
    if($colConfig->idConfiguracion>0){
         $arr = array("success"=>true, "idConf"=>$idConf, "nombre"=>$colConfig->nombre, "valor"=>$valor);
     }else{
         $arr = array("success"=>false);
     }
     echo $callback . '(' . json_encode($arr) . ');';
}

//Obtener la ayudas por su id
function obtAyudaPorAlias(){
  $callback = (isset($_GET['callback']) !="")?$_GET['callback']:"";
  $alias = (isset($_GET['alias']) !="")?$_GET['alias']:"";

  $dirname = dirname(__DIR__);
  $catAyudaObj = new catAyudasObj();
  $datosAyuda = $catAyudaObj->ObtAyudaPorAlias($alias);
  if($datosAyuda->idAyuda>0){
    if($datosAyuda->urlImg!=""){
          $urlImg = 'upload/ayudas/'.$datosAyuda->urlImg;
          $pathUrlImg = $dirname.'/upload/ayudas/'.$datosAyuda->urlImg;

          //verificar que exista en carpeta
          if(!file_exists($pathUrlImg)){
            $urlImg = "";
          }
      }else{
        $urlImg = "";
      }
      $datosAyuda->urlImg = $urlImg;

    $arr = array("success"=>true, "datosAyuda"=>$datosAyuda);
  }else{
    $arr = array("success"=>false);
  }

  echo $callback . '(' . json_encode($arr) . ');';
}
?>
