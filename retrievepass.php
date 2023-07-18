<?php
include_once 'common/screenPortions.php';
include_once 'brules/usuariosObj.php';
include_once 'brules/EmailFunctionsObj.php';
$usuariosObj = new usuariosObj();
$EmailFunctions= new EmailFunctions();
$msg="";
if (isset($_POST['usr_email'])) {
  $dataUser = $usuariosObj->UserByEmail($_POST['usr_email']);

  $idUsuario = $dataUser->idUsuario;
  if ($idUsuario>0) {
    if ($dataUser->activo==0) {
      $msg = "inactivo";
    }else {
      $dataSend=$EmailFunctions->RecuperarDatosDeAcceso($dataUser->email,$dataUser->nombre,$dataUser->password);
      if ($dataSend==0) {
        $msg="error_envio";
      }else {
        $msg="envio_correcto";
      }
    }
  }else {
    $msg="error";
  }
}
?>
<!DOCTYPE html>
<html lang="es">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Control de procesos</title>
    <?php echo estilosPagina(); ?>
<body>
<section id="inicio">
<div class="container">
        <div class="row">
            <div class="col-md-5"></div>
    <figure>
    </figure>
     <div class="col-md-5"></div>
        </div>
</div>
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
				<div class="logo"><a href="index.php"><img src="images/logo.png" /></a></div>
                <div class="login-form">
                    <form method="post" action="" role="login">

                        <div class="form-group col-md-12">
                            <label class="col-md-3" for="email"><img src="images/iconos/perfil_gris.png"></label>
                            <input class="col-md-9" type="email" class="form-control input-lg" name="usr_email" id="usr_email"  placeholder="Correo electrónico" required>
                        </div>

                        <div class="pwstrength_viewport_progress"></div>
                        <button type="submit" name="aceptar" class="btn btn-lg btn-primary btn-block">RECUPERAR CONTRASEÑA </button>

                        <div class="cont_error">


                          <?php if ($msg!=""): ?>
                            <?php if ($msg == "error"): ?>
                              <div class="alert alert-danger">El correo brindado es incorrecto o no ha sido registrado, intenta nuevamente.</div>
                            <?php endif; ?>
                            <?php if ($msg == "inactivo"): ?>
                              <div class="alert alert-danger">Su cuenta ha sido dada de baja.</div>
                            <?php endif; ?>
                            <?php if ($msg == "error_envio"): ?>
                              <div class="alert alert-danger">Ocurrió un error al enviar los datos de acceso, intente más tarde.</div>
                            <?php endif; ?>
                            <?php if ($msg == "envio_correcto"): ?>
                              <div class="alert alert-success">Se han enviado correctamente sus datos de acceso.</div>
                            <?php endif; ?>
                          <?php endif; ?>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-md-12">
                            <a href="index.php">Inicio</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </section>
   <footer>
    <div class="panel-footer">
        <?php echo getPiePag(); ?>
    </div>
   </footer>

  <?php echo scriptsPagina(); ?>
</body>
</html>
