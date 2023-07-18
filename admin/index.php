<?php
ini_set("session.cookie_lifetime","36000");
ini_set("session.gc_maxlifetime","36000");
session_start();
$checkRol = ($_SESSION['idRol']==1 || $_SESSION['idRol']==2 || $_SESSION['idRol']==4) ?true :false;

if($_SESSION['status']!= "ok" || $checkRol!=true)
        header("location: logout.php");

include_once '../common/screenPortions.php';
include_once '../brules/casosObj.php';
include_once '../brules/casoAccionesObj.php';
include_once '../brules/clientesObj.php';
include '../brules/mensajesObj.php';
include_once '../brules/tareasObj.php';
include_once '../brules/usuariosObj.php';
include_once '../brules/utilsObj.php';
include_once '../brules/comentariosObj.php';
require_once '../brules/KoolControls/KoolAjax/koolajax.php';
libreriasKool();

$casosObj = new casosObj();
$clientesObj = new clientesObj();
$casoAccionesObj = new casoAccionesObj();
$tareasObj = new tareasObj();
$usuariosObj = new usuariosObj();
$comentariosObj = new comentariosObj();
$tz = obtDateTimeZone();
$gridAcciones = $casoAccionesObj->ObtActividadesGrid($_SESSION['idUsuario'],$_SESSION['idRol']);
$gridTareas = $tareasObj->ObtActividadesGrid($_SESSION['idUsuario'],$_SESSION['idRol']);

$mensajesObj = new mensajesObj();
$notificaciones = $mensajesObj->ObtTodosMensajes("", "", $_SESSION["idUsuario"], "", 1, 0, "-1,1,2,3,4,5");
$notificaciones2 = $mensajesObj->ObtTodosMensajes("", "", $_SESSION["idUsuario"], "", 1, 0, "7");
$notificaciones = array_merge($notificaciones, $notificaciones2);
$comunicados = $mensajesObj->ObtTodosMensajes("", "", $_SESSION["idUsuario"], "", 1, 0, "6", $tz->fechaF2);
foreach ($notificaciones as $notificacion) {
    // echo "<pre>";print_r($notificacion);echo "</pre>";
    # code...
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Inicio</title>
    <?php echo estilosPagina(true); ?>
</head>
<body>
    <?php echo getHeaderMain($_SESSION['myusername'], true);?>
    <?php $menu = getAdminMenu(); ?>

    <input type="hidden" name="vista" id="vista" value="inicio">

    <section class="section-internas">
        <div class="panel-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="colmenu col-md-2 menu_bg ">
                        <?php echo getNav($menu); ?>
                    </div>
                    <div class="col-md-10">
                        <h1 class="titulo">Bienvenido <span class="pull-right"><a id="btnAyudaweb" onclick="mostrarAyuda('web_inicio')" href="#fancyAyudaWeb"><img src="../images/icon_ayuda.png" width="20px"></a></span> </h1>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12"> <!-- //LDAH IMP 23/08/2022 Panel alertas que abarque la pantalla  -->
                                <div class="panel panel-warning">
                                    <div class="panel-heading">Actividades</div>
                                        <div class="panel-body"><br>
                                            <!-- <div class="col-xs-1"></div> -->
                                            <div class="col-xs-12">
                                                <form name="grids" method="post">
                                                    <?php
                                                    echo $koolajax->Render();
                                                    if($gridAcciones != null){
                                                        echo $gridAcciones->Render();
                                                    }
                                                    ?>
                                                </form>
                                            </div>
                                            <!-- <div class="col-xs-1"></div> -->
                                        </div>
                                </div>
                            </div>

                            <input type="hidden" name="idRol" id="idRol" value="<?php echo $_SESSION["idRol"] ?>">
                            <div class="col-xs-12 col-md-12"> <!-- //LDAH IMP 23/08/2022 Panel alertas que abarque la pantalla  -->
                                <div class="panel panel-warning">
                                    <div class="panel-heading">Alertas</div>
                                        <div class="panel-body"><br>
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <a class="cursorPointer"  onclick="multiLeido()" title="Marcar todo lo visible como leido">
                                                <span class="material-icons">done_all</span>
                                                </a>
                                                <div id="contLoading"><div id="contForLoading"></div></div>
                                            </div>
                                            <div class="col-xs-4"></div>
                                            <div class="col-xs-4">
                                                <span class="material-icons">notifications</span>
                                                <span class="badge" id="spanBadge"><?php //echo count($notificaciones); ?></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <input type="hidden" name="selected_ids" id="selected_ids" value="">
                                            <input type="hidden" name="class_selected" id="class_selected" value="">
                                            <div class="row ">
                                                <div class="col-xs-1"></div>
                                                <div class="col-xs-10 container-border">
                                                    <h5>Totales</h5>
                                                    <div class="col-xs-8">
                                                        <input type="radio" name="filtro" id="filtro_propia" value="propia" onchange="cambiaRadioNot(this)">
                                                        <label for="filtro_propia">Propias</label>
                                                        <span class="badge" id="spanBadge_propia">0</span>
                                                    </div>
                                                    <div class="col-xs-8">
                                                        <input type="radio" name="filtro" id="filtro_ajena" value="ajena" onchange="cambiaRadioNot(this)">
                                                        <label for="filtro_ajena">Ajenas</label>
                                                        <span class="badge" id="spanBadge_ajena">0</span>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-xs-1"></div> -->
                                            </div><div class="espacio"></div>
                                            <?php  if($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2){ ?>
                                            
                                                <div class="row">
                                                    <div class="col-xs-1"></div>
                                                    <div class="col-xs-10 container-border">
                                                        <h5>Subfiltros</h5>
                                                        <div class="col-xs-8">
                                                            <input type="radio" name="filtro" id="filtro_titular" value="titular" onchange="cambiaRadioNot(this)">
                                                            <small>
                                                                <label for="filtro_titular">Titular</label>
                                                                <span class="badge" id="spanBadge_titular">0</span>
                                                            </small>
                                                        </div>
                                                        <div class="col-xs-8">
                                                            <input type="radio" name="filtro" id="filtro_espero" value="espero" onchange="cambiaRadioNot(this)">
                                                            <small>
                                                                <label for="filtro_espero">Espero Instr.</label>
                                                                <span class="badge" id="spanBadge_espero" style="display: inline;">0</span>
                                                            </small>
                                                        </div>
                                                        <div class="col-xs-8">
                                                            <input type="radio" name="filtro" id="filtro_comentario" value="comentario" onchange="cambiaRadioNot(this)">
                                                            <small>
                                                                <label for="filtro_comentario">Comentario</label>
                                                                <span class="badge" id="spanBadge_comentario">0</span>
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-1"></div>
                                                </div><div class="espacio"></div>
                                                    
                                            
                                            <?php } ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-1"></div>
                                            <div class="col-xs-10">
                                                <div class="btn-group">
                                                    <input type="text" name="search_not" id="search_not" value="" class="form-control" onchange="buscarEnContenidoNot('search_not', 'itemFiltered')" placeholder="Buscar">
                                                    <span id="searchclear" class="material-icons" onclick="limpiaInput('search_not')">clear</span>
                                                    
                                                </div>
                                               
                                            </div>
                                            <div class="col-xs-1"></div>
                                        </div>
                                        <div class="recent-activities card alertas">
                                            <div class="card-body no-padding">
                                                <?php 

                                            foreach ($notificaciones as $notificacion) {

                                           
                                                $expedienteId = 0;
                                                $actividadId = 0;
                                                $cliente = '';
                                                $contenido = '';
                                                $claseItem = ($notificacion->usuarioIdAlta == $_SESSION["idUsuario"])?'propia':'ajena';
                                                $claseItem2 = ($notificacion->usuarioIdAlta == $_SESSION["idUsuario"])?'not_propia':'not_ajena';
                                                $iconTitulo = '';
                                                $usuarioAlta = ($notificacion->usuarioIdAlta > 0)?$usuariosObj->UserByID($notificacion->usuarioIdAlta):new usuariosObj();

                                                switch ($notificacion->tipo) {//tipo de notificacion
                                                    case -1: $icon = 'folder'; //cambio en expediente
                                                        $href = '';

                                                        $expedienteId = $notificacion->idRegistro;
                                                    break;
                                                    case 1: $icon = 'folder'; //cambio en expediente
                                                        $href = 'frmExpedienteEdit.php?id='.$notificacion->idRegistro;

                                                        $expedienteId = $notificacion->idRegistro;
                                                    break;
                                                    case 2: $icon = 'badge';//cambio en actividad
                                                        $actividad = $casoAccionesObj->CasoAccionesPorId($notificacion->idRegistro);
                                                        $href = 'actividad.php?expId='.$actividad->casoId.'&actId='.$notificacion->idRegistro;
                                                        $expedienteId = $actividad->casoId;
                                                        $actividadId = $notificacion->idRegistro;
                                                        if($notificacion->campo == 'estatusId' && $notificacion->cambioId == 3 && $claseItem == 'ajena'){//si se modifico el estatus a espero instrucciones
                                                            $claseItem .= ' titular espero';
                                                            $claseItem2 .= ' not_titular not_espero';
                                                        }
                                                        $contenido = $actividad->comentarios;
                                                    break;
                                                    case 3: $icon = 'forum';//comentario
                                                        $comentario = $casoAccionesObj->CasoAccionesPorId($notificacion->idRegistro);
                                                        $actividad = $casoAccionesObj->CasoAccionesPorId($comentario->padreAccionId);
                                                        $href = 'actividad.php?expId='.$comentario->casoId.'&actId='.$comentario->padreAccionId;
                                                        $expedienteId = $comentario->casoId;
                                                        $actividadId = $actividad->idAccion;
                                                        if($claseItem == 'ajena'){
                                                            $claseItem .= ' titular comentario';    
                                                            $claseItem2 .= ' not_titular not_comentario';    
                                                        }
                                                        $contenido = $comentario->comentarios;
                                                        //Jair 24/3/2022 Icono comentario por rol
                                                        $icono = obtenerIconoComentario($usuarioAlta->idRol);
                                                        
                                                    break;
                                                    case 4: $icon = 'task';//cambio en tarea
                                                        $tarea = $tareasObj->TareasPorId($notificacion->idRegistro);
                                                        $href = 'tarea.php?tareaId='.$notificacion->idRegistro;
                                                        // $expedienteId = $actividad->casoId;
                                                        $actividadId = $notificacion->idRegistro;
                                                        if($notificacion->campo == 'estatusId' && $notificacion->cambioId == 3 && $claseItem == 'ajena'){//si se modifico el estatus a espero instrucciones
                                                            $claseItem .= ' titular espero';
                                                            $claseItem2 .= ' not_titular not_espero';
                                                        }
                                                        $contenido = $tarea->comentarios;
                                                    break;
                                                    case 5: $icon = 'comment';//comentario tarea
                                                        $comentario = $tareasObj->TareasPorId($notificacion->idRegistro);
                                                        $tarea = $tareasObj->TareasPorId($comentario->padreTareaId);
                                                        $href = 'tarea.php?tareaId='.$tarea->idTarea;
                                                        $actividadId = $tarea->idTarea;
                                                        if($claseItem == 'ajena'){
                                                            $claseItem .= ' titular comentario';    
                                                            $claseItem2 .= ' not_titular not_comentario';    
                                                        }
                                                        $contenido = $comentario->comentarios;
                                                        //Jair 24/3/2022 Icono comentario por rol
                                                        $icono = obtenerIconoComentario($usuarioAlta->idRol);
                                                        
                                                    break;
                                                    case 7: $icon = 'comment';//comentario expediente
                                                    // echo "idcomentario: ".$notificacion->idRegistro;
                                                        $comentario = $comentariosObj->ObtComentarioByID($notificacion->idRegistro);
                                                        $href = 'frmExpedienteEdit.php?expId='.$comentario->casoId;
                                                        $expedienteId = $comentario->casoId;
                                                        if($claseItem == 'ajena'){
                                                            $claseItem .= ' titular comentario';    
                                                            $claseItem2 .= ' not_titular not_comentario';    
                                                        }
                                                        $contenido = $comentario->comentario;
                                                        //Jair 24/3/2022 Icono comentario por rol
                                                        $icono = obtenerIconoComentario($usuarioAlta->idRol);
                                                        
                                                    break;
                                                    default:
                                                        # code...
                                                        break;
                                                }

                                                $caso = $casosObj->CasoPorId($expedienteId);
                                                $cliente = $clientesObj->ClientePorId($caso->clienteId);
                                                $responsableId = ($expedienteId > 0)?$caso->responsableId:$tarea->responsableId;
                                                
                                                $responsable = ($responsableId > 0)?$usuariosObj->UserByID($responsableId):new usuariosObj();
                                                $representado = $caso->representado;
                                                ?>
                                            <div class="item <?php echo $claseItem ?>" id="item_<?php echo $notificacion->idMensaje ?>">
                                                <input type="hidden" name="not_<?php echo $notificacion->idMensaje ?>" id="not_<?php echo $notificacion->idMensaje ?>" value="<?php echo $notificacion->idMensaje ?>" class="<?php echo $claseItem2 ?>">
                                                <div class="row">
                                                <div class="col-xs-4 date-holder text-right">
                                                    <!-- <span class="material-icons">schedule</span> -->
                                                    <?php if( strpos('ajena', $claseItem)){
                                                        echo '<span class="material-icons">priority_high</span>';
                                                    }?>
                                                    <span class="material-icons"><?php echo $icon; ?></span>
                                                    <div class="date"> <span><?php echo convertirAFechaCompleta4($notificacion->fechaCreacion) ?></span><br>
                                                    
                                                </div>
                                                </div>
                                                <div class="col-xs-8 content">
                                                    <h5>
                                                        <?php 
                                                        echo $notificacion->titulo; 
                                                        if($iconTitulo != ''){
                                                            echo '<span class="material-icons">'.$iconTitulo.'</span>';
                                                        }
                                                        ?>
                                                    </h5>
                                                    <p><?php echo $notificacion->contenido ?></p>
                                                    <?php
                                                    //Expedientes, Actividades y Comentarios de actividades
                                                    if($expedienteId > 0){
                                                     ?>

                                                    <!-- <p><?php echo "Id Expediente:".$expedienteId ?></p> -->
                                                    <p>Expediente Juzgado: <?php echo $caso->numExpedienteJuzgado ?></p>
                                                    <p>Responsable: <?php echo $responsable->nombre; ?></p>
                                                    <p>Cliente:<?php echo $cliente->nombre ?></p>
                                                    <p>Representado: <?php echo $representado; ?></p>
                                                    <?php
                                                    if($actividadId > 0){
                                                        echo '<p>Id Actividad: '.$actividadId.'</p>';
                                                    }
                                                    if(isset($comentario)){
                                                        if(isset($comentario->idAccion)){
                                                            echo '<p>Id Comentario: '.$comentario->idAccion.'</p>';
                                                        }

                                                        if(isset($comentario->idComentario)){
                                                            echo '<p>Id Comentario: '.$comentario->idComentario.'</p>';
                                                        }

                                                    }
                                                    ?>
                                                     <?php }else{//Tareas
                                                        echo '
                                                        <p>Id Tarea: '.$tarea->idTarea.'</p>
                                                        <p>Responsable: '.$responsable->nombre.' </p>';

                                                        if(isset($comentario)){
                                                            echo '<p>Id Comentario Tarea: '.$comentario->idTarea.'</p>';
                                                        }
                                                     } ?>
                                                    <?php
                                                    if($contenido != ''){
                                                        echo '<p>
                                                        <div class="wrapper-col-contenido">
                                                            <div class="small-col-contenido">'.$contenido.'</div>
                                                            <a class="linktoogle-contenido" href="#">+</a>
                                                        </div>
                                                        </p>'
                                                        ;
                                                    }
                                                     ?>
                                                    <p class="text-right">
                                                        <a class="btn-notification" onclick="marcarComoLeido(<?php echo $notificacion->idMensaje ?>)" href="javascript:void(0)"><span class="material-icons">check_circle</span></a>
                                                        <?php if($href != ''){ ?>
                                                        <a class="btn-notification" onclick="ventanaNotificacion('<?php echo $href ?>', <?php echo $notificacion->idMensaje ?>, <?php echo $notificacion->idRegistro ?>, <?php echo $notificacion->tipo ?>)" target="_blank"><span class="material-icons">open_in_new</span></a>
                                                        <?php } ?>
                                                    </p>
                                                </div>
                                                </div>
                                            </div>
                                            <?php 
                                            if(isset($comentario)){
                                                unset($comentario);
                                            }
                                        
                                            }//fin for notificaciones ?>
                                            
                                            </div>
                                        </div>
                                        </div>
                                        
                                        
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <div class="panel panel-warning">
                                    <div class="panel-heading">Comunicados</div>
                                        <div class="panel-body"><br>
                                        <div class="recent-activities card comunicados">
                                            <div class="card-body no-padding">
                                            <?php foreach ($comunicados as $comunicado) {
                                            ?>
                                            <div class="item" id="item_<?php echo $comunicado->idMensaje ?>">
                                                <input type="hidden" name="not_<?php echo $comunicado->idMensaje ?>" id="not_<?php echo $comunicado->idMensaje ?>" value="<?php echo $comunicado->idMensaje ?>" class="">
                                                <div class="row">
                                                <div class="col-xs-4 date-holder text-right">
                                                    <span class="material-icons">campaign</span>
                                                    <div class="date"> <span><?php echo convertirAFechaCompleta3($comunicado->fechaCreacion) ?></span><br>
                                                    
                                                </div>
                                                </div>
                                                <div class="col-xs-8 content">
                                                    <h5><?php echo $comunicado->titulo; ?></h5>
                                                    <p><?php echo $comunicado->contenido ?></p>
                                                    
                                                    <p class="text-right">
                                                        <a class="btn-notification" onclick="marcarComoLeido(<?php echo $comunicado->idMensaje ?>)" href="javascript:void(0)"><span class="material-icons">check_circle</span></a>
                                                        
                                                    </p>
                                                </div>
                                                </div>
                                            </div>
                                            <?php
                                            }?>
                                            </div>
                                        </div>
                                        </div>
                                </div>
                            </div>
                        </div>
<?php // if($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2){ ?>
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <div class="panel panel-warning">
                                    <div class="panel-heading">Tareas</div>
                                        <div class="panel-body"><br>
                                            <!-- <div class="col-xs-1"></div> -->
                                            <div class="col-xs-12">
                                                <form name="grids2" method="post">
                                                    <?php
                                                    echo $koolajax->Render();
                                                    if($gridTareas != null){
                                                        echo $gridTareas->Render();
                                                    }
                                                    ?>
                                                </form>
                                            </div>
                                            <!-- <div class="col-xs-1"></div> -->
                                        </div>
                                </div>
                            </div>
                        </div>
<?php // } ?>
                        <!-- <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="panel panel-warning">
                                <div class="panel-heading"></div>
                                    <div class="panel-body"><br>
                                    
                                    </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <div class="panel panel-warning">
                                <div class="panel-heading"></div>
                                    <div class="panel-body"><br>
                                    </div>
                            </div>
                        </div>
                    </div> -->

                        <div class="cont_iconos">
                            <div class="row">
                                <div class="col-md-4">
                                    <a href="expedientes.php">
                                        <p>
                                            <div id="divicon_sesion"><i class="icon material-icons">folder</i></div>
                                            <!--<br class="inicio">-->
                                            Expedientes
                                        </p>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="gastos.php">
                                        <p>
                                            <div id="divicon_sesion"><i class="icon material-icons">account_balance_wallet</i></div>
                                            <!--<br class="inicio">-->
                                            Control de gastos
                                        </p>
                                    </a>
                                </div>
                                <?php if($_SESSION["idRol"] == 1 || $_SESSION["idRol"] == 2){ ?>
                                 <div class="col-md-4">
                                    <a href="catalogos.php">
                                        <p>
                                            <div id="divicon_sesion"><i class="icon material-icons">assignment</i></div>
                                            <!--<br class="inicio">-->
                                            Cat&aacute;logos
                                        </p>
                                    </a>
                                </div>
                                <?php } ?>
                            </div>
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

    <?php echo scriptsPagina(true); ?>
    <script>
        $( document ).ready(function() {
            // muestraNoficaciones('propia');
            // muestraNoficaciones('ajena');
            var idRol = $("#idRol").val()
            
            if(idRol == 1 || idRol == 2){//superadmin y admin
                $("#filtro_titular").attr("checked", true);
                $("#filtro_titular").trigger("change");
            }

            if(idRol > 2){//otros roles
                $("#filtro_ajena").attr("checked", true);
                $("#filtro_ajena").trigger("change");
            }
            recalcularContadores();
            
            // window.addEventListener('cors_event', function(event) {
            //     if(event.data.event_id === 'my_cors_message'){
            //         console.log(event.data.data);
            //     }
            // });
            searhColMaxLength();
            grid_actividades.refresh();
            grid_actividades.commit();

            grid_tareas.refresh();
            grid_tareas.commit();

            
        });


    </script>
</body>
</html>
