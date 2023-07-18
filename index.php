<?php
include_once 'common/screenPortions.php';
include_once 'brules/catConfiguracionesObj.php';

$catConfiguracionesObj = new catConfiguracionesObj();
$catpcha = $catConfiguracionesObj->ObtConfiguracionByID(1);
$local = false;//Jair 17/3/2022 Variable para saber si se esta trabajando local, localmente no hay https y el captcha lo requiere
?>
<!DOCTYPE html>
<html lang="es">
    <meta name="robots" content="noindex,nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Juridico Martinez - Sistema</title>
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
                    <form method="post" action="checklogin.php" role="login">

                        <div class="form-group col-md-12">
                            <label class="col-md-3" for="email"><img src="images/iconos/perfil_gris.png"></label>
                            <input class="col-md-9" type="email" class="form-control input-lg" name="usr_email" id="usr_email"  placeholder="Correo electrónico" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-3" for="pwd"><img src="images/iconos/contrasena_icon.png"></label>
                            <input class="col-md-9" type="password" class="form-control input-lg" name="usr_pass" id="usr_pass" placeholder="Contraseña" required>
                        </div>
                        
                        <!-- Comentar en local porque no funciona sin https -->
                        <?php 
                        if(!$local){
                            ?>
                            <br><br><br>
                            <div class="form-group col-md-12">
                                <div class="g-recaptcha" data-sitekey="<?php echo $catpcha->valor ?>"></div>
                            </div>
                            <?php
                        }
                        ?>

                        <div class="pwstrength_viewport_progress"></div>
                        <button type="submit" name="aceptar" class="btn btn-lg btn-primary btn-block">INICIAR SESIÓN </button>

                        <div class="cont_error">
                                <?php if (isset($_GET['login'])){ ?>
                                    <?php if ($_GET['login'] == "error"){ ?>
                                            <div class="alert alert-danger">Datos Incorrectos intenta nuevamente</div>
                                    <?php }
                                           if ($_GET['login'] == "cliente"){ ?>
                                            <div class="alert alert-danger">Estimado cliente para utilizar nuestro servicio descargue la aplicacion en su movil.</div>
                                    <?php }
                                        if ($_GET['login'] == "inactivo"){ ?>
                                            <div class="alert alert-danger">Su cuenta ha sido dada de baja.</div>
                                    <?php } if ($_GET['login'] == "captcha"){ ?>
                                            <div class="alert alert-danger">Captcha invalido.</div>
                                    <?php }
                                    ?>
                                <?php } ?>
                        </div>
                    </form>
                    <!--
                    <div class="row">
                        <div class="col-md-12">
                            <a href="retrievepass.php">Recuperar contrase&ntilde;a</a>
                        </div>
                    </div>
		    -->                        
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
  <!-- Comentar en local porque no funciona sin https -->
  <?php 
    if(!$local){
        ?>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script>
            window.onload = function() {
            var $recaptcha = document.querySelector('#g-recaptcha-response');

            if($recaptcha) {
                $recaptcha.setAttribute("required", "required");
            }
            };
        </script>
        <?php
    }
    ?>
  
  
</body>
</html>
