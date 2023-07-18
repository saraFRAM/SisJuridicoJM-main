<?php
session_start();
$idRol = $_SESSION['idRol'];
$rol = true;
switch ($idRol) {
    case 1: case 2: case 3: case 4:$rol = true; break;
    default: $rol = false; break;
}

if ($_SESSION['status'] != "ok" || $rol != true)
    header("location: logout.php");

$dirname = dirname(__DIR__);
include_once '../common/screenPortions.php';

include_once $dirname . '/brules/utilsObj.php';
include_once $dirname . '/brules/casoAccionesObj.php';
include_once $dirname . '/brules/usuariosObj.php';



//establecer la zona horaria
$dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City'));
$dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
$tipoToSearch = (isset($_GET['tipo']))?$_GET['tipo']:'3,5,6,7,8,9,10';
$idAbogadoSel = (isset($_GET['idAbogado']))?$_GET['idAbogado']:$_SESSION['idUsuario'];
$idAbogadoSelec = (isset($_GET['idAbogado']))?$_GET['idAbogado']:$_SESSION['idUsuario'];

if(!isset($_GET['idAbogado']) && $_SESSION['idUsuario'] == 23){
    $idAbogadoSel = -1;    
}
$usuariosObj = new usuariosObj();
$colAbogados = $usuariosObj->obtTodosUsuarios(true, "1,2,4", "", " numAbogado ASC ", true);

$casoAccionObj = new casoAccionesObj();
$eventosArr = $casoAccionObj->ObtenerAgenda($tipoToSearch, $idAbogadoSel);

$eventosData = json_encode($eventosArr);
$usrId = $_SESSION['idUsuario'];
//echo $eventosData."\n";

$arrAbogados = array();
$arrAbogadosCita = array();

$arrAbogados[] = array("valor"=>"-1", "texto"=>"Todos");

//para busqueda
foreach ($colAbogados as $abogadoItem) {
    $arrAbogados[] = array("valor"=>$abogadoItem->idUsuario, "texto"=>$abogadoItem->nombre);
}
//para cita
foreach ($colAbogados as $abogadoItem) {
  $arrAbogadosCita[] = array("valor"=>$abogadoItem->idUsuario, "texto"=>$abogadoItem->nombre);
}

$arrCampoAbogados = array(
    array("nameid"=>"abogadosSelec", "type"=>"select", "class"=>"form-control ", "label"=>"Abogado:", "datos"=>$arrAbogados, "value"=>$idAbogadoSel),
);
//para cita
$arrCampoResponsable = array(
  array("nameid"=>"abogadosSel", "type"=>"select", "class"=>"form-control ", "label"=>"Responsable:", "datos"=>$arrAbogadosCita, "value"=>$idAbogadoSelec),
);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Agenda</title>
    <?php echo estilosPagina(true); ?>
    <link href='../libs/fullcalendar/main.css' rel='stylesheet' />
    <script src='../libs/fullcalendar/moment-with-locales.min.js'></script>
    <script src='../libs/fullcalendar/main.js'></script>
    <script src='../libs/fullcalendar/main.global.min.js'></script>
    <script src='../libs/fullcalendar/locales/es.js'></script>

</head>

<body>
    <?php echo getHeaderMain($_SESSION['myusername'], true); ?>
    <?php $menu = getAdminMenu(); ?>

    <input type="hidden" name="vista" id="vista" value="agenda">

    <section class="section-internas">
        <div class="panel-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="colmenu col-md-2 menu_bg">
                        <?php echo getNav($menu); ?>
                    </div>
                    <div class="col-md-10">
                        <h1 class="titulo">Agenda<span class="pull-right"><a id="btnAyudaweb" onclick="mostrarAyuda('web_agenda')" href="#fancyAyudaWeb"><img src="../images/icon_ayuda.png" width="20px"></a></span></h1>

                        <ol class="breadcrumb">
                            <li><a href="index.php">Inicio</a></li>
                            <li class="active">Agenda</li>
                        </ol>
                        <div clas="row" style="margin-bottom: 10px;">
                            <div class="col-md-1">
                                <label for="eventosCldSel">Visualizar:</label>
                            </div>
                            <div class="col-md-3">
                                
                                <select class="form-control" name="eventosCldSel" id="eventosCldSel" onchange="location.href='agenda.php?tipo='+this.value;">
                                    <option value="3,5" <?php echo ($tipoToSearch == "3,5" ?'selected':''); ?>>Audiencias, Citaciones y Emplazamientos</option>
                                    <option value="6" <?php echo ($tipoToSearch == "6" ? 'selected':''); ?>>Citas en el despacho</option>
                                    <option value="7" <?php echo ($tipoToSearch == "7" ? 'selected':''); ?>>Escritos de termino</option>
                                    <?php if($_SESSION['idRol'] == 1){ ?>
                                      <option value="10" <?php echo ($tipoToSearch == "10" ? 'selected':''); ?>>Citas Sociales</option>
                                      <option value="8,9" <?php echo ($tipoToSearch == "8,9" ? 'selected':''); ?>>Cobros y Pagos</option>
                                      <option value="8" <?php echo ($tipoToSearch == "8" ? 'selected':''); ?>>Pagos</option>
                                      <option value="9" <?php echo ($tipoToSearch == "9" ? 'selected':''); ?>>Cobros</option>
                                      <option value="3,5,6,7,8,9,10" <?php echo ($tipoToSearch == "3,5,6,7,8,9,10" ? 'selected':''); ?>>Todos</option>
                                    <?php } else{ ?>
                                      <option value="3,5,6,7" <?php echo ($tipoToSearch == "3,5,6,7" ? 'selected':''); ?>>Todos</option>
                                    <?php } ?>
                                    
                                </select>
                            </div>
                            <?php
                                if($_SESSION['idRol'] <= 3){
                                    $cols = array("label_xs"=>"2", "label_md"=>"2", "input_xs"=>"3", "input_md"=>"3");
                                    echo generaHtmlForm($arrCampoAbogados, $cols);
                                }
                                
                            ?>
                            <br>
                            <a class="btn btn-primary" href="#" data-toggle="modal" id="#nuevaCita" data-target="#popup_modalCrearCitaSimple" onclick="clearForm('formCrearCitaSimple');$('#citaSimpleEditId').val(0);">Crear Cita</a>
                            <?php if($_SESSION['idRol'] == 1){ ?>
                              <a class="btn btn-primary" href="#" data-toggle="modal" id="#nuevoPago" data-target="#popup_modalCrearPago" onclick="clearForm('formCrearPago');$('#pagoEditId').val(0);">Crear Pago</a>
                              <a class="btn btn-primary" href="#" data-toggle="modal" id="#nuevoCobro" data-target="#popup_modalCrearCobro" onclick="clearForm('formCrearCobro');$('#cobroEditId').val(0);">Crear Cobro</a>
                              <a class="btn btn-primary" href="#" data-toggle="modal" id="#nuevaCitaSocial" data-target="#popup_modalCrearCitaSocial" onclick="clearForm('formCrearCitaSocial');$('#citaSocialEditId').val(0);">Crear C. Social</a>
                              
                            <?php } ?>
                            <br>
                        </div>
                        <div id="guiaAgenda">
                            <span class="dot dotAud"></span> Audiencias&nbsp;&nbsp;
                            <span class="dot dotCit"></span> Citaciones y Emplazamientos&nbsp;&nbsp;
                            <span class="dot dotEsc"></span> Escritos de Termino&nbsp;&nbsp;
                            <span class="dot dotDes"></span> Citas en el despacho&nbsp;
                            <?php if($_SESSION['idRol'] == 1){ ?>
                              <span class="dot dotPag"></span> Pagos&nbsp;
                              <span class="dot dotCob"></span> Cobros&nbsp;
                              <span class="dot dotCS"></span> C. Sociales&nbsp;
                            <?php } ?>
                        </div>

                        <div class="col-md-12">
                            <div id='calendar'></div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- Modal crear Cita sencilla -->
        <div class="modal fade" id="popup_modalCrearCitaSimple" role="dialog" data-backdrop="static" data-keyboard="false" >
          <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Crear Cita</h4>
                </div>
                <div class="row">

                  <form role="form" id="formCrearCitaSimple" name="formCrearCitaSimple" method="post" action="" enctype="multipart/form-data">
                    <!-- <input type="hidden" name="id_sel_titular" id="id_sel_titular" value="0"> -->
                    <input type="hidden" name="citaSimpleEditId" id="citaSimpleEditId" value="0">
                    <input type="hidden" name="citaSimpleUsrId" id="citaSimpleUsrId" value="<?php echo $usrId; ?>">
                    <input type="hidden" name="citaSimpleRespUsrId" id="citaSimpleRespUsrId" value="0">
                    <br>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4 text-right">
                              <label for="pc_nombreCita">Nombre Cita:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control required" name="pc_nombreCita" id="pc_nombreCita">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4 text-right">
                              <label for="pc_fechaCompromiso">Fecha compromiso:</label>
                            </div>
                            <div class="col-md-8">
                              <input class="form-control inputfechaGralHora required" type="text" name="pc_fechaCompromiso" id="pc_fechaCompromiso" style="width:90%;display:inline-block;"  readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                        <?php  
                                 echo generaHtmlForm($arrCampoResponsable);
                            ?>
                    </div>    
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4 text-right">
                              <label for="pc_comentarios">Comentarios:</label>
                            </div>
                            <div class="col-md-8">
                             <textarea rows="4" name="pc_comentarios" id="pc_comentarios"></textarea>
                    
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                      <div class="row">
                        <div class="col-md-offset-6 col-md-3 text-right">
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="col-md-3 text-right">
                          <a class="btn btn-primary" id="btnCrearCliente" onclick="btnCrearCitaSimple();">Aceptar</a>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12"><br/></div>
                  </form>

                </div>
            </div>
          </div>
        </div>

        <!-- Modal crear Cita social -->
        <div class="modal fade" id="popup_modalCrearCitaSocial" role="dialog" data-backdrop="static" data-keyboard="false" >
          <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Crear Cita Social</h4>
                </div>
                <div class="row">

                  <form role="form" id="formCrearCitaSocial" name="formCrearCitaSocial" method="post" action="" enctype="multipart/form-data">
                    <!-- <input type="hidden" name="id_sel_titular" id="id_sel_titular" value="0"> -->
                    <input type="hidden" name="citaSocialEditId" id="citaSocialEditId" value="0">
                    <input type="hidden" name="citaSocialUsrId" id="citaSocialUsrId" value="<?php echo $usrId; ?>">
                    <input type="hidden" name="citaSocialRespUsrId" id="citaSocialRespUsrId" value="0">
                    <br>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4 text-right">
                              <label for="pc_nombreCitaSocial">Nombre Cita:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control required" name="pc_nombreCitaSocial" id="pc_nombreCitaSocial">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4 text-right">
                              <label for="pc_fechaCompromisoCS">Fecha compromiso:</label>
                            </div>
                            <div class="col-md-8">
                              <input class="form-control inputfechaGralHora required" type="text" name="pc_fechaCompromisoCS" id="pc_fechaCompromisoCS" style="width:90%;display:inline-block;"  readonly>
                            </div>
                        </div>
                    </div>
                    <?php
                    if (isset($_POST['pc_comentariosCS'])){
                      $input=htmlspecialchars($_POST['input']);
                      echo $input;
                    }
                    ?>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4 text-right">
                              <label for="pc_comentariosCS">Comentarios:</label>
                            </div>
                            <div class="col-md-8">
                              <textarea rows="4" name="pc_comentariosCS" id="pc_comentariosCS"  htmlentities($pc_comentariosCS)></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                      <div class="row">
                        <div class="col-md-offset-6 col-md-3 text-right">
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="col-md-3 text-right">
                          <a class="btn btn-primary" id="btnCrearCitaSocial" onclick="btnCrearCitaSocial();">Aceptar</a>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12"><br/></div>
                  </form>

                </div>
            </div>
          </div>
        </div>

        <!-- Modal crear pago -->
        <div class="modal fade" id="popup_modalCrearPago" role="dialog" data-backdrop="static" data-keyboard="false" >
          <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Crear Pago</h4>
                </div>
                <div class="row">

                  <form role="form" id="formCrearPago" name="formCrearPago" method="post" action="" enctype="multipart/form-data">
                    <!-- <input type="hidden" name="id_sel_titular" id="id_sel_titular" value="0"> -->
                    <input type="hidden" name="pagoEditId" id="pagoEditId" value="0">
                    <input type="hidden" name="pagoUsrId" id="pagoUsrId" value="<?php echo $usrId; ?>">
                    <input type="hidden" name="pagoRespUsrId" id="pagoRespUsrId" value="0">
                    <br>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4 text-right">
                              <label for="pc_nombrePago">Nombre del Pago:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control required" name="pc_nombrePago" id="pc_nombrePago">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4 text-right">
                              <label for="pc_fechaPago">Fecha del Pago:</label>
                            </div>
                            <div class="col-md-8">
                              <input class="form-control inputfechaGralHora required" type="text" name="pc_fechaPago" id="pc_fechaPago" style="width:90%;display:inline-block;"  readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4 text-right">
                              <label for="pc_repeti">Repetir:</label>
                            </div>
                            <div class="col-md-8">
                              <input class="form-control required" type="number" name="pc_repeti" id="pc_repeti" style="display:inline-block;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4 text-right">
                              <label for="pc_cadaTime">Periodo:</label>
                            </div>
                            <div class="col-md-8">
                            <select class="form-control" name="pc_cadaTime" id="pc_cadaTime">
                                    <option value="1">Dia</option>
                                    <option value="2">Semana</option>
                                    <option value="3">Mes</option>
                                    <option value="4">Año</option>
                              </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4 text-right">
                              <label for="pc_comentariosPago">Comentarios:</label>
                            </div>
                            <div class="col-md-8">
                              <textarea rows="5" name="pc_comentariosPago" id="pc_comentariosPago"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                      <div class="row">
                        <div class="col-md-offset-6 col-md-3 text-right">
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="col-md-3 text-right">
                          <a class="btn btn-primary" id="btnCrearCliente" onclick="btnCrearPago();">Aceptar</a>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12"><br/></div>
                  </form>

                </div>
            </div>
          </div>
        </div>
        <!-- Modal crear cobro -->
        <div class="modal fade" id="popup_modalCrearCobro" role="dialog" data-backdrop="static" data-keyboard="false" >
          <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Crear Cobro</h4>
                </div>
                <div class="row">

                  <form role="form" id="formCrearCobro" name="formCrearCobro" method="post" action="" enctype="multipart/form-data">
                    <!-- <input type="hidden" name="id_sel_titular" id="id_sel_titular" value="0"> -->
                    <input type="hidden" name="cobroEditId" id="cobroEditId" value="0">
                    <input type="hidden" name="cobroUsrId" id="cobroUsrId" value="<?php echo $usrId; ?>">
                    <input type="hidden" name="cobroRespUsrId" id="cobroRespUsrId" value="0">
                    <br>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4 text-right">
                              <label for="pc_nombreCobro">Nombre del Cobro:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control required" name="pc_nombreCobro" id="pc_nombreCobro">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4 text-right">
                              <label for="pc_fechaCobro">Fecha del Cobro:</label>
                            </div>
                            <div class="col-md-8">
                              <input class="form-control inputfechaGralHora required" type="text" name="pc_fechaCobro" id="pc_fechaCobro" style="width:90%;display:inline-block;"  readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4 text-right">
                              <label for="pc_repetiCobro">Repetir:</label>
                            </div>
                            <div class="col-md-8">
                              <input class="form-control required" type="number" name="pc_repetiCobro" id="pc_repetiCobro" style="display:inline-block;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4 text-right">
                              <label for="pc_cadaTimeCobro">Periodo:</label>
                            </div>
                            <div class="col-md-8">
                            <select class="form-control" name="pc_cadaTimeCobro" id="pc_cadaTimeCobro">
                                    <option value="1">Dia</option>
                                    <option value="2">Semana</option>
                                    <option value="3">Mes</option>
                                    <option value="4">Año</option>
                              </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4 text-right">
                              <label for="pc_comentariosCobro">Comentarios:</label>
                            </div>
                            <div class="col-md-8">
                              <textarea rows="5" name="pc_comentariosCobro" id="pc_comentariosCobro"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                      <div class="row">
                        <div class="col-md-offset-6 col-md-3 text-right">
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="col-md-3 text-right">
                          <a class="btn btn-primary" id="btnCrearCobro" onclick="btnCrearCobro();">Aceptar</a>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12"><br/></div>
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
    <!-- <link href="../css/jquery-ui.css" rel="stylesheet" type="text/css" /> -->
    <link rel="stylesheet" media="all" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
    <!-- <link href="../css/jquery.signature.css" rel="stylesheet" type="text/css" /> -->
    <link href="../css/jquery.timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/jquery.timepicker.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../js/jquery.timepicker.min.js?upd='.$upd.'"></script>
    <script type="text/javascript" src="../js/jquery-ui-timepicker.js?upd='.$upd.'"></script>
    <script type="text/javascript" src="../js/jquery-ui-sliderAccess.js?upd='.$upd.'"></script>
    <script type="text/javascript" src="../js/spanish_datapicker.js?upd='.$upd.'"></script>
    <script>
        $('#abogadosSelec').on('change', function() {
            //alert( this.value );
           var searchParams = new URLSearchParams(window.location.search);
          searchParams.set("idAbogado", this.value);
           window.location.search = searchParams.toString();
        });
        

        moment.locale('es');

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                //height: '100%',
                expandRows: true,
                slotMinTime: '07:00',
                slotMaxTime: '20:00',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                locale: 'es',
                //timeZone: 'America/Mexico_City',
                initialView: 'dayGridMonth',
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                selectable: true,
                nowIndicator: true,
                dayMaxEvents: true, // allow "more" link when too many events
                events: <?php echo $eventosData; ?>,
                eventClick: function(info) {
                    //console.log(info);
                    //console.log(info.event.start);
                    //console.log(info.event);
                    let fecha = moment(info.event.start).format('dddd, DD MMMM YYYY HH:mm');
                    let title = info.event.title;
                    let tipo = info.event.extendedProps.tipo;
                    let nombre = info.event.extendedProps.nombre;
                    let actId = info.event.extendedProps.actId;
                    let responsable = info.event.extendedProps.responsable;
                    let creador = info.event.extendedProps.creador;
                    let html = '';

                    if(tipo == 6){
                        let comentario = info.event.extendedProps.comentario;
                        let responsableId = info.event.extendedProps.responsableId;
                        let fechaMetd = moment(info.event.start).format('L HH:mm');
                                               
                        $("#citaSimpleEditId").val(actId);
                        $("#pc_nombreCita").val(title);
                        $("#pc_fechaCompromiso").val(fechaMetd);
                        $("#pc_comentarios").val(comentario);
                        $("#citaSimpleRespUsrId").val(responsableId);
                        
                        html += '<div><b>Actividad: </b><span>Cita</span></div>';
                        html += '<div><b>Titulo: </b> <span>' + title + '</span></div>';
                        html += '<div><b>Fecha: </b> <span>' + fecha + '</span></div>';
                        html += '<div><b>Creada por: </b> <span>' + creador + '</span></div>';
                        html += '<div><b>Responsable: </b> <span>' + responsable + '</span></div>';
                        html += '<div><b>Comentario: </b> <span>' + comentario + '</span></div>';
                        html += '<br/>';
                        html += '<div class="row">';
                        html += '<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-primary" role="button" id="btnEditarCita" onclick="editarCita();" >Editar</a></div>';
                        html += '<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-primary" role="button" id="btnEliminarCita" onclick="eliminarCita('+actId+');">Eliminar</a></div>';
                        html += '</div>';
                    }else if(tipo == 8){
                      let comentario = info.event.extendedProps.comentario;
                      let responsableId = info.event.extendedProps.responsableId;
                      let fechaMetd = moment(info.event.start).format('L HH:mm');
                      
                      html += '<div><b>Actividad: </b><span>Pago</span></div>';
                      html += '<div><b>Titulo: </b> <span>' + title + '</span></div>';
                      html += '<div><b>Fecha: </b> <span>' + fecha + '</span></div>';
                      html += '<div><b>Responsable: </b> <span>' + responsable + '</span></div>';
                      html += '<div><b>Comentario: </b> <span>' + comentario + '</span></div>';
                      html += '<br/>';
                      html += '<div class="row">';
                      html += '<div class="col-md-3"><a href="javascript:void(0);" class="btn btn-primary" role="button" id="elimnarEste" onclick="eliminarPago('+actId+');" >Eliminar este pago</a></div>';
                      html += '<div class="col-md-3"><a href="javascript:void(0);" class="btn btn-primary" role="button" id="elimnarCombo" onclick="eliminarPagoSerie('+actId+');" >Eliminar serie</a></div>';
                      html += '</div>';
                    }else if(tipo == 9){
                      let comentario = info.event.extendedProps.comentario;
                      let responsableId = info.event.extendedProps.responsableId;
                      let fechaMetd = moment(info.event.start).format('L HH:mm');
                      
                      html += '<div><b>Actividad: </b><span>Cobro</span></div>';
                      html += '<div><b>Titulo: </b> <span>' + nombre + '</span></div>';
                      html += '<div><b>Fecha: </b> <span>' + fecha + '</span></div>';
                      html += '<div><b>Responsable: </b> <span>' + responsable + '</span></div>';
                      html += '<div><b>Comentario: </b> <span>' + comentario + '</span></div>';
                      html += '<br/>';
                      html += '<div class="row">';
                      html += '<div class="col-md-3"><a href="javascript:void(0);" class="btn btn-primary" role="button" id="elimnarEste" onclick="eliminarPago('+actId+');" >Eliminar este pago</a></div>';
                      html += '<div class="col-md-3"><a href="javascript:void(0);" class="btn btn-primary" role="button" id="elimnarCombo" onclick="eliminarPagoSerie('+actId+');" >Eliminar serie</a></div>';
                      html += '</div>';
                    }else if(tipo == 10){
                        let comentario = info.event.extendedProps.comentario;
                        let responsableId = info.event.extendedProps.responsableId;
                        let fechaMetd = moment(info.event.start).format('L HH:mm');
                                               
                        $("#citaSocialEditId").val(actId);
                        $("#pc_nombreCitaSocial").val(title);
                        $("#pc_fechaCompromisoCS").val(fechaMetd);
                        $("#pc_comentariosCS").val(comentario);
                        $("#citaSocialRespUsrId").val(responsableId);
                        
                        html += '<div><b>Actividad: </b><span>Cita Social</span></div>';
                        html += '<div><b>Titulo: </b> <span>' + title + '</span></div>';
                        html += '<div><b>Fecha: </b> <span>' + fecha + '</span></div>';
                        html += '<div><b>Responsable: </b> <span>' + responsable + '</span></div>';
                        html += '<div><b>Comentario: </b> <span>' + comentario + '</span></div>';
                        html += '<br/>';
                        html += '<div class="row">';
                        html += '<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-primary" role="button" id="btnEditarCita" onclick="editarCitaSocial();" >Editar</a></div>';
                        html += '<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-primary" role="button" id="btnEliminarCita" onclick="eliminarCita('+actId+');">Eliminar</a></div>';
                        html += '</div>';
                    }else{
                        let cliente = info.event.extendedProps.cliente;
                        let expId = info.event.extendedProps.expId;
                        let numExpJuzg = info.event.extendedProps.numExpJuzg;
                        let materia = info.event.extendedProps.materia;
                        let distrito = info.event.extendedProps.distrito;
                        let juzgado = info.event.extendedProps.juzgado;
                        let akaAsunto = info.event.extendedProps.akaAsunto;
                        let aka = info.event.extendedProps.aka;
                        
                        
                        //html += '<div><span>'+title+'</span></div>';
                        html += '<div><b>Actividad: </b> <span>' + nombre + '</span></div>';
                        html += '<div><b>Fecha: </b> <span>' + fecha + '</span></div>';
                        html += '<div><b>Exp ID: </b> <span>' + expId + '</span></div>';
                        html += '<div><b>Num Exp Juzgado: </b> <span>' + numExpJuzg + '</span></div>';
                        html += '<div><b>Aka Asunto: </b> <span>' + akaAsunto + '</span></div>';
                        html += '<div><b>Responsable: </b> <span>' + responsable + '</span></div>';
                        html += '<div><b>Cliente: </b> <span>' + cliente + '</span></div>';
                        html += '<div><b>Aka: </b> <span>' + aka + '</span></div>';
                        html += '<div><b>Materia: </b> <span>' + materia + '</span></div>';
                        html += '<div><b>Distrito: </b> <span>' + distrito + '</span></div>';
                        html += '<div><b>Juzgado: </b> <span>' + juzgado + '</span></div>';
                        html += '<br/>';
                        html += '<di><a href="actividad.php?expId=' + expId + '&actId=' + actId + '" class="btn btn-primary" role="button" id="btnVerActividad" target="_blank">Ver Actividad</a></div>';
                    }

                    alertify.alert('Informaci&oacute;n del evento', '<div class="cont_modal_evento">' + html + '</div>', function() {});
                    html = '';
                }
            });

            calendar.render();
        });
    </script>
</body>

</html>