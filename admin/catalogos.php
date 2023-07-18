<?php
session_start();
$idRol = $_SESSION['idRol'];
switch ($idRol) {
    case 1: case 2:  $rol = true; break;
    default: $rol = false; break;
}
if($_SESSION['status']!= "ok" || $rol!=true)
        header("location: logout.php");

include_once '../common/screenPortions.php';
include_once '../brules/utilsObj.php';
require_once '../brules/KoolControls/KoolAjax/koolajax.php';
libreriasKool();
include_once '../brules/usuariosObj.php';
include_once '../brules/rolesObj.php';
include_once '../brules/catConfiguracionesObj.php';
include_once '../brules/catAyudasObj.php';
include_once '../brules/comunicadosObj.php';
include_once '../brules/clientesObj.php';
include_once '../brules/catTipoCasosObj.php';
include_once '../brules/catConceptosObj.php';

include_once '../brules/catPartesObj.php';
include_once '../brules/catMateriasObj.php';
include_once '../brules/catJuiciosObj.php';
include_once '../brules/catDistritosObj.php';
include_once '../brules/catJuzgadosObj.php';
include_once '../brules/catAccionesObj.php';
include_once '../brules/catComunicadosObj.php';

include_once '../brules/catMetodosObj.php';
include_once '../brules/catBancosObj.php';

$selected = '';
$result = '';
$optCat = false;
$msjResponse = "";
$mostrarNoti = "oculto2";
$classAyudas = "oculto2";

$ayudasObj = new catAyudasObj();
// $ayudasApp = $ayudasObj->ObtTodosCatAyudas(0);
$ayudasWeb = $ayudasObj->ObtTodosCatAyudas(1);

//Continuar si esta seleccionado algun catalogo
if(isset($_GET['catalog'])){
    $optCat = true;
    $selected = $_GET['catalog'];

    if($selected == 'usuarios'){
        $accObj = new usuariosObj();
        $result = $accObj->GetUsersGrid();
        $mostrarNoti = '';
    }
    elseif($selected == 'roles'){
        $accObj = new rolesObj();
        $result = $accObj->GetRolesGrid();
    }
    elseif($selected == 'clientes'){
        $accObj = new clientesObj();
        $result = $accObj->ObtClientesGrid();
    }
    elseif($selected == 'tipoCasos'){
        $accObj = new catTipoCasosObj();
        $result = $accObj->ObtTiposCasoGrid();
    }
    elseif($selected == 'conceptos'){
        $accObj = new catConceptosObj();
        $result = $accObj->ObtConceptosGrid();
    }
    elseif($selected == 'comunicados'){
        // $accObj = new comunicadosObj();
        // $result = $accObj->GetComunicadosGrid();
        $accObj = new catComunicadosObj();
        $result = $accObj->ObtComunicadosGrid();
    }
    elseif ($selected == 'ayudas') {
        $classAyudas = '';
    }
    elseif($selected == 'partes'){
        $accObj = new catPartesObj();
        $result = $accObj->ObtPartesGrid();
    }
    elseif($selected == 'materias'){
        $accObj = new catMateriasObj();
        $result = $accObj->ObtMateriasGrid();
    }
    elseif($selected == 'juicios'){
        $accObj = new catJuiciosObj();
        $result = $accObj->ObtJuiciosGrid();
    }
    elseif($selected == 'distritos'){
        $accObj = new catDistritosObj();
        $result = $accObj->ObtDistritosGrid();
    }
    elseif($selected == 'juzgados'){
        $accObj = new catJuzgadosObj();
        $result = $accObj->ObtJuzgadosGrid();
    }
    elseif($selected == 'acciones'){
        $accObj = new catAccionesObj();
        $result = $accObj->ObtAccionesGrid();
    }
    elseif($selected == 'metodos'){
        $accObj = new catMetodosObj();
        $result = $accObj->ObtMetodosGrid();
    }
    elseif($selected == 'bancos'){
        $accObj = new catBancosObj();
        $result = $accObj->ObtBancosGrid();
    }
}
else{
  $optCat = true;
  $accObj = new usuariosObj();
  $result = $accObj->GetUsersGrid();
  $selected = 'usuarios';
  $mostrarNoti = '';
}

//Mensajes
if(isset($_GET['mensaje'])){
    switch ($_GET['mensaje']) {
        case 1:$mensaje = 'Error al tratar de recuperar la imagen';break;
        case 2:$mensaje = 'El archivo que intento subir, no es una imagen';break;
        case 3:$mensaje = 'La imagen se ha subido correctamente';break;
        case 4:$mensaje = 'No se pudo subir el archivo al servidor';break;
        default:$mensaje = 'Error';break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Cat&aacute;logos</title>
    <?php echo estilosPagina(true); ?>
</head>
<body>
    <?php echo getHeaderMain($_SESSION['myusername'], true);?>
    <?php $menu = getAdminMenu(); ?>

    <input type="hidden" name="vista" id="vista" value="catalogos">

    <section class="section-internas">
        <div class="panel-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="colmenu col-md-2 menu_bg">
                      <?php echo getNav($menu); ?>
                    </div>
                    <div class="col-md-10">
                        <h1 class="titulo">Cat&aacute;logos <span class="pull-right"><a id="btnAyudaweb" onclick="mostrarAyuda('catalogo_<?php echo $selected?>')" href="#fancyAyudaWeb"><img src="../images/icon_ayuda.png" width="20px"></a></span> </h1>
                        <!--Mostrar en caso de presionar el boton de guardar-->
                        <?php if ($msjResponse != "") { ?>
                            <div class="new_line alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?php echo $msjResponse; ?>
                            </div>
                        <?php } ?>
                         <?php if (isset($mensaje)) { ?>
                            <div class="new_line alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?php echo $mensaje; ?>
                            </div>
                        <?php } ?>
                        <div class="filtro_cat">
                            <div class="row">
                                <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                    <label for="catalogo">Catálogo:</label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-6">
                                    <select id="selectCatalog" onchange="obtenervalorfiltro(this);" class="form-control" required>
                                        <option value="">--Seleccionar--</option>
                                        <option value="ayudas" <?php echo ($selected == "ayudas") ? "selected" : ""; ?> >Ayudas</option>
                                        <option value="comunicados" <?php echo ($selected == "comunicados") ? "selected" : ""; ?> >Comunicados</option>
                                        <option value="usuarios" <?php echo ($selected == "usuarios") ? "selected" : ""; ?> >Usuarios</option>
                                        <option value="roles" <?php echo ($selected == "roles") ? "selected" : ""; ?> >Roles</option>
                                        <option value="clientes" <?php echo ($selected == "clientes") ? "selected" : ""; ?> >Clientes</option>
                                        <option value="tipoCasos" <?php echo ($selected == "tipoCasos") ? "selected" : ""; ?> >Tipo Casos</option>
                                        <option value="conceptos" <?php echo ($selected == "conceptos") ? "selected" : ""; ?> >Conceptos</option>

                                        <option value="partes" <?php echo ($selected == "partes") ? "selected" : ""; ?> >Partes</option>
                                        <option value="materias" <?php echo ($selected == "materias") ? "selected" : ""; ?> >Materias</option>
                                        <option value="juicios" <?php echo ($selected == "juicios") ? "selected" : ""; ?> >Juicios</option>
                                        <option value="distritos" <?php echo ($selected == "distritos") ? "selected" : ""; ?> >Distritos</option>
                                        <option value="juzgados" <?php echo ($selected == "juzgados") ? "selected" : ""; ?> >Juzgados</option>
                                        <option value="acciones" <?php echo ($selected == "acciones") ? "selected" : ""; ?> >Acciones</option>

                                        <option value="metodos" <?php echo ($selected == "metodos") ? "selected" : ""; ?> >Metodos</option>
                                        <option value="bancos" <?php echo ($selected == "bancos") ? "selected" : ""; ?> >Bancos</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <?php if($optCat==true){ ?>
                        <br/>
                        <div class="row">
                            <div class="col-md-7 col-sm-7 col-xs-7"></div>
                            <div class="col-md-5 col-sm-5 col-xs-5">
                                <a href="frmUsuario.php" class="<?php echo $mostrarNoti; ?>"><input type="button" value="Nuevo" class="btn btn-primary"></a>
                            </div>
                        </div>
                         <?php if ($selected == "comunicados"): ?>
                            <!-- <div class="row">
                              <div class="col-md-12">
                                <div class="col-md-9"></div>
                                <div class="col-md-2"><a href="frmcomunicado.php" class="btn btn-info">Nuevo</a></div>
                              </div>
                            </div> -->
                            <div class="col-md-12"><p></div>
                          <?php endif; ?>
                        <br/>
                        <div style="" class="row">
                            <form name="grids" method="post">
                                <?php
                                echo $koolajax->Render();
                                echo '<div id="">';
                                if($result != null){
                                    echo $result->Render();
                                }
                                echo '</div>';
                                ?>
                            </form>
                        </div>
                        <?php } ?>
                        <div class="<?php echo $classAyudas ?>">
                            <div class="row">
                                <div class="col-md-2 text-right">
                                    <label>Ayuda: </label>
                                </div>

                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <input type="hidden" id="question_html" name="question_html" />
                                    <select class="form-control" name="ayudas" id="ayudas" onchange="editarAyuda(this.value)">
                                        <option value="">Seleccione...</option>
                                        <!-- <optgroup label="AYUDAS APP"> -->
                                        <?php
                                        // foreach ($ayudasApp as $ayuda) {
                                        //     echo '<option value="'.$ayuda->alias.'">'.$ayuda->titulo.'</option>';
                                        // }
                                        // echo '</optgroup>';

                                        // echo ' <optgroup label="AYUDAS WEB">';
                                        foreach ($ayudasWeb as $ayuda) {
                                            echo '<option value="'.$ayuda->alias.'">'.$ayuda->titulo.'</option>';
                                        }
                                        // echo '</optgroup>';
                                         ?>

                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 text-right">
                                    <label>Contenido: </label>
                                </div>

                                <div class="col-md-10 col-sm-10 col-xs-10" id="divContenidoAyuda">
                                    <textarea name="contenidoAyuda" id="contenidoAyuda" rows="6" class="form-control contenidoAyuda" required=""></textarea>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <a class="btn btn-primary" onclick="guardarAyuda()" id="btnGuardarAyuda">Guardar</a>
                                </div>
                            </div>
                        </div>

                        <div id="fancyEditConf" style="display:none;width:600px;height:500px;">
                            <h3>Editar configuraci&oacute;n</h3>
                        </div>
                        <?php //echo getFancyElimGral(); ?>
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

    <div id="fancyEditarConfig" style="display:none;width:600px;height:320px;">
        <h3>Editar <span id="spanNombreConfig"></span></h3>

        <form role="form" id="formEditarConfig" name="formEditarConfig" method="post" action="" >
            <input type="hidden" id="idConfiguracion" name="idConfiguracion" value="">

            <div class="row">
                <!-- <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                    <label for="referenciaPagar">: </label>
                </div> -->
                <div class="col-md-12 col-sm-12 col-xs-12" id="divTextAreaConfig">
                    <textarea class="form-control required" id="valorConfig" name="valorConfig"></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-6">

                </div>
                <div class="col-md-3 col-sm-3 col-xs-3">
                    <input type="button" value="Cancelar" class="btn btn-primary btn-delete" role="button" onclick="parent.$.fancybox.close();" name="btnCancelarPagar">
                </div>
                <div class="col-md-3 col-sm-3 col-xs-3">
                    <input type="button" name="btnGuardarConfig" id="btnGuardarConfig" value="Guardar" class="btn btn-primary" role="button" onclick="guardarConfiguracion()"/>
                </div>
            </div>
        </form>


    </div>
    <?php echo scriptsPagina(true); ?>
    <script type="text/javascript">
        // tinymce.init({
        //     selector: ".contenidoAyuda",
        //     theme: "modern",
        //     plugins: [
        //         ["advlist autolink link image lists preview hr pagebreak"],
        //         ["searchreplace wordcount visualblocks visualchars code media"],
        //         ["save contextmenu directionality emoticons paste "]
        //     ]
        //  });
         var params = {selector:".contenidoAyuda", height:"230", btnImg:true};
        opcionesTinymce(params);
    </script>
    <!--Para crear el grid-->
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/accounting.min.js"></script>
</body>
</html>
