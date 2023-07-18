<?php
session_start();
$checkRol = ($_SESSION['idRol']==1 || $_SESSION['idRol']==2 || $_SESSION['idRol']==5) ?true :false;
$idRol = $_SESSION['idRol'];

if($_SESSION['status']!= "ok" || $checkRol!=true)
        header("location: logout.php");

include_once '../common/screenPortions.php';
include_once '../brules/utilsObj.php';
require_once '../brules/KoolControls/KoolAjax/koolajax.php';
libreriasKool();
$localization = "../brules/KoolControls/KoolCalendar/localization/es.xml";
include_once '../brules/usuariosObj.php';
include_once '../brules/utilsObj.php';
include_once '../brules/rolesObj.php';
include_once '../brules/catComunicadosObj.php';
include_once '../brules/catConfiguracionesObj.php';

$selected = '';
$result = '';
$optCat = false;
$msjResponse = "";
$mostrarNoti = "oculto2";
$classAyudas = "oculto2";
$tz = obtDateTimeZone();
//Mensajes
$usuariosObj = new usuariosObj();
$catComunicadosObj = new catComunicadosObj();

// $user = $usuariosObj->obtTodosUsuarios();
// $user = $usuariosObj->obtUsuariosByIdRol("1,2,3", 1);
$user = $usuariosObj->obtTodosUsuarios(true, "1,2,4", "", " numAbogado ASC ");
$usuarios= [];
foreach ($user as $usr) {  
//   $regDispObj->usuarioId = $usr->idUsuario;
//   $colRegDisp = $regDispObj->obtTodosRegDispositivoPorIdUsr();
//   $regid = "";
//   if(count($colRegDisp)>0){    
   $usuarios[]=$usr;
//   }
}

$comunicados =  $catComunicadosObj->ObtComunicados(1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Comunicados</title>
    <?php echo estilosPagina(true); ?>
    <?php echo scriptsPagina(true); ?>
</head>
<body>
    <?php echo getHeaderMain($_SESSION['myusername'], true);?>
    <?php $menu = getAdminMenu(); ?>

    <input type="hidden" name="vista" id="vista" value="mensajes">

    <section class="section-internas">
        <div class="panel-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="colmenu col-md-2 menu_bg">
                        <?php echo getNav($menu); ?>
                    </div>
                    <div class="col-md-10">
                        <h1 class="titulo">Comunicados <span class="pull-right"><a id="btnAyudaweb" onclick="mostrarAyuda('web_mensajes')" href="#fancyAyudaWeb"><img src="../images/icon_ayuda.png" width="20px"></a></span></h1>  


                        <ol class="breadcrumb">
                          <li><a href="index.php">Inicio</a></li>
                          <li class="active">Comunicados</li>
                        </ol>

                        <input type="hidden" name="idUsuarioCbm" id="idUsuarioCbm" value="<?php echo $_SESSION['idUsuario'];?>">
                        <input type="hidden" name="tipoMensaje" id="tipoMensaje" value="1">
                        <div class="col-md-12">
                          <form role="form" id="formEnviarMensaje" name="formEnviarMensaje" method="get" action="">
                          
                          <div class="row">
                              <div class="col-md-10 col-sm-10 col-xs-10">
                                  <select id="listaUsuarios" name="listaUsuarios" multiple="multiple" class="required duallb">
                                      <?php
                                      foreach ($usuarios as $usuario) {
                                          
                                          echo '<option value="'.$usuario->idUsuario.'">'.$usuario->nombre.'</option>';
                                      }
                                       ?>
                                  </select>
                              </div>
                          </div>
                          <div class="row">
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                      <label for="">Comunicado general</label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                  <select id="listComunicados" name="listComunicados" class="form-control" onchange="cambiaComunicadoGeneral(this.value)">
                                      <option value="">Texto libre</option>
                                      <?php
                                      foreach ($comunicados as $comunicado) {
                                          
                                          echo '<option value="'.$comunicado->idComunicado.'" data-contenido="'.$comunicado->contenido.'">'.$comunicado->titulo.'</option>';
                                      }
                                       ?>
                                  </select>
                              </div>
                          </div>
                          <div class="new_line"></div>                          
                          <div class="new_line"></div>

                          <div class="row">
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                  <label>Titulo</label>
                              </div>
                               <div class="col-md-4 col-sm-4 col-xs-4">
                                  <input type="text" class="form-control required" id="tituloMensaje" name="tituloMensaje" value="" />
                              </div>
                          </div>

                          <div class="row">
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                  <label>Mensaje</label>
                              </div>
                               <div class="col-md-4 col-sm-4 col-xs-4">
                                  <textarea class="form-control required" id="contenidoMensaje" name="contenidoMensaje" value="" rows="6"></textarea>

                              </div>                              
                          </div>

                          <div class="row">
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <label>Fecha caducidad:</label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <input class="form-control inputfechaGral required" type="text" name="caducidad" id="caducidad" value="<?php //echo $tz->fechaF2?>" style="width:90%;display:inline-block;" readonly>
                                </div>
                            </div>
                          
                          <div class="row">
                              <div class="col-md-2 col-sm-2 col-xs-2">
                                  <a  class="btn btn-primary" role="button" id="sendMes" onclick="enviarMesanjeClientesMultiple()">Enviar </a>
                              </div>
                          </div>


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
    <link href="../css/jquery-ui.css" rel="stylesheet" type="text/css" />
    <!-- <link href="../css/jquery.signature.css" rel="stylesheet" type="text/css" /> -->
    <link href="../css/jquery.timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/jquery.timepicker.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../js/jquery.timepicker.min.js?upd='.$upd.'"></script>
    <script type="text/javascript" src="../js/jquery-ui-timepicker.js?upd='.$upd.'"></script>
    <script type="text/javascript" src="../js/spanish_datapicker.js?upd='.$upd.'"></script>
</body>
</html>
