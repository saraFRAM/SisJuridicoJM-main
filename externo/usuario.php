<?php
session_start();
$idRol = $_SESSION['idRol'];
$rol = true;
switch ($idRol) {
    case 1: case 2: case 3: case 5:$rol = true; break;
    default: $rol = false; break;
}
// echo $_SESSION['status']."<br>".$rol;
// die();
if($_SESSION['status']!= "ok" || $rol!=true)
        header("location: logout.php");
// elseif ($_SESSION["permisos"]["Catalogo_Usuarios_Ver"] == 0) {
//         // header("location: restringido.php");
//     }

$dirname = dirname(__DIR__);
include_once '../common/screenPortions.php';
include_once '../brules/KoolControls/KoolGrid/koolgrid.php';
require_once '../brules/KoolControls/KoolAjax/koolajax.php';
require_once '../brules/KoolControls/KoolCalendar/koolcalendar.php';
include_once '../brules/KoolControls/KoolGrid/ext/datasources/MySQLiDataSource.php';
$localization = "../brules/KoolControls/KoolCalendar/localization/es.xml";
include_once $dirname.'/brules/usuariosObj.php';
include_once $dirname.'/brules/rolesObj.php';
include_once $dirname.'/brules/utilsObj.php';

//establecer la zona horaria
$dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
$dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual

$msjResponse = "";
$msjResponseE = "";
$idUsuario = (isset($_SESSION['idUsuario']))?$_SESSION['idUsuario']:0;
$usuariosObj = new usuariosObj();
$rolesObj = new rolesObj();
$idCondomino = 0;
$usuFraccs = array();

$roles = $rolesObj->GetAllRoles();


if(isset($_POST["idUsuario"])){
	$usuarioGObj = new usuariosObj();
    $idUsuario = $_POST["idUsuario"];
    $idRolG = $_POST["idRol"];

    $usuarioGObj->idUsuario = $idUsuario;
    $usuarioGObj->idRol = $idRolG;
    $usuarioGObj->nombre = $_POST["nombreU"];
    $usuarioGObj->email = $_POST["emailU"];
    $usuarioGObj->password = $_POST["passU"];
    $usuarioGObj->activo = $_POST["activoU"];
    $usuarioGObj->numAbogado = $_POST["numabogado"];

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
        header("location: usuario.php");
    }
    else{
        $msjResponse .= "No hay cambios que guardar";
    }

}


$idFraccUsuario = "";

$usuarioObj = $usuariosObj->UserByID($idUsuario);
$empresaUsr = (isset($usuarioObj->empresaId)) ? $usuarioObj->empresaId : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Mi perfil</title>
    <?php echo estilosPagina(true); ?>
</head>

<body>
	<?php echo getHeaderMain($_SESSION['myusername'], true, 'externo');?>
    <?php $menu = getAdminMenu(); ?>

    <input type="hidden" name="vista" id="vista" value="inicio">

    <section class="section-internas">
    	<div class="panel-body">
    		<div class="container-fluid">
    			<div class="row">
    				<div class="colmenu col-md-2 menu_bg">
                        <?php echo getNav($menu); ?>
                    </div>

                    <div class="col-md-10">
                    	<h1 class="titulo">
                            Mi perfil <span class="pull-right"><a id="btnAyudaweb" onclick="mostrarAyuda('web_usuario')" href="#fancyAyudaWeb"><img src="../images/icon_ayuda.png" width="20px"></a></span>
                        </h1>
                        <ol class="breadcrumb">
                          <li><a href="index.php">Inicio</a></li>
                          <li class="active">Mi perfil</li>
                        </ol>

                        <!--Mostrar en caso de presionar el boton de guardar-->
                        <?php if ($msjResponse != "") { ?>
                            <div class="new_line alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?php echo $msjResponse; ?>
                            </div>
                        <?php } ?>
                        <div class="new_line alert  alert-danger " id="msgCorreo" style="display:none">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <?php echo "El correo brindado ya existe, favor de escribir uno nuevo."; ?>
                        </div>

                        <form role="form" id="formUsuario" name="formUsuario" method="post" action="" enctype="multipart/form-data">
                        	<input type="hidden" name="idRolSesion" id="idRolSesion" value="<?php echo $idRol; ?>">
                            <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $idUsuario; ?>">
                            <input type="hidden" name="idEmpresaUrs" id="idEmpresaUrs" value="<?php echo $empresaUsr; ?>">

                            <?php
                            if($usuarioObj->idUsuario > 0){
                                //echo '<input type="hidden" name="idRol2" id="idRol2" value="'.$usuarioObj->idRol.'">';
                            }

                             ?>

                             <div class="row" style="display: none;">
                                <div class="text-right form-group col-md-2 col-sm-2 col-xs-2">
                                    <label for="idRol">Rol:</label>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <select class="form-control sololectura <?php echo ($usuarioObj->idUsuario > 0)?"":"required"; ?>" id="idRol" name="idRol" <?php echo ($usuarioObj->idUsuario > 0)?"":""; ?>>
                                        <option value="">--Seleccionar--</option>
                                        <?php
                                        foreach ($roles as $rol) {
                                            $selec = "";
                                            if($rol->idRol == $usuarioObj->idRol){
                                                $selec = "selected";
                                            }
                                            echo '<option value="'.$rol->idRol.'" '.$selec.'>'.$rol->rol.'</option>';

                                        }
                                         ?>
                                    </select>
                                </div>
                            </div>


                            <div class="row">
                                <div class="text-right form-group col-md-2 col-sm-2 col-xs-2">
                                    <label for="nombreU">Nombre:</label>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <input type="text" id="nombreU" name="nombreU" class="form-control required" value="<?php echo $usuarioObj->nombre ?>" maxlength="150" >
                                </div>
                            </div>

                            <div class="row">
                                <div class="text-right form-group col-md-2 col-sm-2 col-xs-2">
                                    <label for="emailU">E-mail:</label>
                                </div>
                                <div class="col-md-5 col-sm-5 col-xs-5">
                                    <input type="text" id="emailU" name="emailU" class="form-control required email" onchange="validateMail()" value="<?php echo $usuarioObj->email ?>" maxlength="100" >
                                    <span id="spanLabel"></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="text-right form-group col-md-2 col-sm-2 col-xs-2">
                                    <label for="passU">Contrase&ntilde;a:</label>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <div class="password">
                                        <input type="text" id="passU" name="passU" value="<?php echo $usuarioObj->password ?>" class="form-control password required" onkeyup="mostrarOjo(this.id)" onmouseover="mostrarOjo(this.id)" autocomplete="?completes">
                                        <span class="glyphicon glyphicon-eye-open" id="eye-passU" onmousedown="mostrarPassword('passU')" onmouseup="ocultarPassword('passU')" mouseout="ocultarPassword('passU')"></span>
                                    </div>
                                </div>
                                <div class="form-group col-md-2 col-sm-2 col-xs-2">
                                    <a onclick="generarPassword('passU')" class="btn btn-primary" role="button" >Generar</a>
                                </div>
                            </div>

                            <div class="row" style="display: none;">
                                <div class="text-right form-group col-md-2 col-sm-2 col-xs-2">
                                    <label for="activoU">Activo:</label>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <select class="form-control required" id="activoU" name="activoU">
                                        <option value="1" <?php if($usuarioObj->activo == 1 || $usuarioObj->idUsuario === 0)echo "selected"; ?>>Si</option>
                                        <option value="0" <?php if($usuarioObj->activo === 0 && $usuarioObj->idUsuario > 0)echo "selected"; ?>>No</option>

                                    </select>
                                </div>
                            </div>

                            <div class="row" style="display: none;">
                                <div class="text-right form-group col-md-2 col-sm-2 col-xs-2">
                                    <label for="numabogado">Num Abogado:</label>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                   <input class="form-control" type="text" name="numabogado" id="numabogado" value="<?php echo $usuarioObj->numAbogado ?>" readonly="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8"></div>
                                <div class="col-md-2">
                                     <a href="index.php" class="btn btn-danger" role="button">Cancelar</a>
                                </div>
                                <div class="col-md-2">
                                    <a onclick="guardarFrmUsuario()" class="btn btn-primary" role="button" id="btnGuardarUsuario">Guardar</a>
                                </div>
                            </div>

                            <div class="cont_btnrequisicion">
                                <div class="new_line">



                                </div>
                            </div>
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
    <?php echo scriptsPagina(true); ?>

    <script>
        $(document).ready(function(){
            <?php if( isset($res) && $res==1){ ?>
                alertify.success("Informaci&oacute;n guardada correctamente.");
                setTimeout(function(){
                    window.location.href = "frmUsuario.php?id="+'<?php echo $idUsuario?>';
                }, 500);
            <?php } ?>
        });
    </script>
</body>

</html>
