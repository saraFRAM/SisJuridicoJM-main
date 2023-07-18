<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/mensajesDB.php';
include_once  $dirname.'/database/datosBD.php';

class mensajesObj{
    private $_idMensaje = 0;
    private $_usuarioId = 0;
    private $_tipo =  0;//1 = expediente, 2 = actividad, 3 = comentario
    private $_idRegistro =  0;//id del registro de la tabla correspondiente
    private $_leido =  0;
    private $_mostrar = 1;
    private $_titulo = '';
    private $_contenido = '';
    private $_usuarioIdAlta = 0;
    private $_campo = '';
    private $_cambioId = 0;
    private $_fechaCreacion = '';
    private $_fechaAct = '';

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion de mensajes por rango de fecha
    public function ObtTodosMensajes($fDel="", $fAl="", $idUsuario = "", $concepto = 1, $mostrar = "", $leido = "", $tipoIds = '-1,1,2,3,4,5', $caducidad = ''){
        $array = array();
        $ds = new mensajesDB();
        $datosBD = new datosBD();

        $fDel = ($fDel!="") ?conversionFechas($fDel) :"";
        $fAl = ($fAl!="") ?conversionFechas($fAl) :"";
        $caducidad = ($caducidad!="") ?conversionFechas($caducidad)." 23:59:59" :"";

        $result = $ds->ObtTodosMensajesDB($fDel, $fAl, $idUsuario, $concepto, $mostrar, $leido, $tipoIds, $caducidad);
        $array = $datosBD->arrDatosObj($result);

        return $array;
    }

    //Actualiza algun dato del mensaje por su id y su nombre de columna
    public function ActualizarMensaje($campo, $valor, $id)
    {
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new mensajesDB();
        $resAct = $objDB->ActualizarMensajeDB($param);
        return $resAct;
    }


    //Obtener coleccion de MensajesObjs
    public function GetAllMensajesObj($peticionId = "", $order = "DESC", $concepto = "0"){
        $array = array();
        $ds = new mensajesDB();
        $datosBD = new datosBD();
        $result = $ds->GetallMensajesObj($peticionId, $order, $concepto);
        $array = $datosBD->arrDatosObj($result);
        return $array;
    }
    //Obtener coleccion de MensajesObjs
    public function GetAllMensajesObjByRpt($idTipoRpt,$idRpt){
        $ds = new mensajesDB();
        $obj = new mensajesObj();
        $datosBD = new datosBD();
        $result = $ds->GetAllMensajesObjByRptDB($idTipoRpt,$idRpt);
        return  $datosBD->setDatos($result, $obj);;
    }
    //Obtener MensajesObjs por su id
    public function obtenerMensajesObjById($id){
        $ds = new mensajesDB();
        $obj = new mensajesObj();
        $datosBD = new datosBD();
        $result = $ds->obtenerMensajesObjById($id);

        return $datosBD->setDatos($result, $obj);
    }
    // guardar MensajesObjs
    public function GuardarMensajesObj(){
        $objDB = new mensajesDB();
        $this->_idMensaje = $objDB->insertMensajesDB($this->getParams());
    }
    //Eliminar MensajesObjs
    public function EliminarMensajesObj($idMensaje){
        $objDB = new mensajesDB();
        $param[0] = $idMensaje;
        return $objDB->deleteMensajesDB($param);
    }


    private function getParams($ctr=false){
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
        // $this->_fechaCreacion = $dateTime;
        // $this->_fechaUltCambio = $dateTime;

        $param[0] = $this->_usuarioId;
        $param[1] = $this->_leido;
        $param[2] = $this->_mostrar;
        $param[3] = $this->_titulo;
        $param[4] = $this->_contenido;
        $param[5] = $this->_tipo;
        $param[6] = $this->_idRegistro;
        $param[7] = $this->_usuarioIdAlta;
        // $param[7] = $this->_imagen;
        // $param[8] = $this->_fechaCreacion;

        return $param;
    }
    public function reportesMensajes(){

            $result = $this->GetAllMensajesObj();
            $idTable = "rptMensajesGrid";
            $tituloColNombre = '';
            $contenColNombre = '';

            $html = '
            <div class="datable_bootstrap">
                <table id="'.$idTable.'" class="table table-striped table-bordered table-condensed dataTable no-footer dt-responsive" role="grid" cellspacing="0" width="100%" >
                    <thead>
                        <tr>
                            <th>Id<i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                            <th>Receptor<i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                            <th>Tipo<i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                            <th>Titulo<i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                            <th>Contenido<i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                            <th>Reporte<i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                            <th>Fecha Creacion <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                            <th>Acciones <i class="fa fa-fw fa-sort " aria-hidden="true"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                    ';
                    if(count($result)>0){
                        foreach($result as $item){
                                    $usuariosObj = new usuariosObj();
                                    $catConceptoNotificacionObj = new catConceptoNotificacionObj();
                                    $concep=$catConceptoNotificacionObj->ObtCatConceptoNotificacionById($item->idConcepto);
                                    $nomConcep=$concep->nombre;
                                    $usuario = $usuariosObj->UserByID($item->usuarioId);
                                    $nombreRep="General";
                                    if ($item->idConcepto==2) {
                                      $rptCiudadanoUsuarioObj = new rptCiudadanoUsuarioObj();
                                      $dataR=$rptCiudadanoUsuarioObj->obtRptCiudadanoUsuarioPorId($item->idReporte);
                                      $nombreRep=$dataR->comentario;
                                    }elseif($item->idConcepto==3){
                                      $rptMascotasObj= new rptMascotasObj();
                                      $dataR=$rptMascotasObj->obtRptMascotaPorId($item->idReporte);
                                      $nombreRep=$dataR->nombreMascota;
                                    }
                                    if ($concep->idConcepto==0) {
                                        $nomConcep="General";
                                    }
                                      $html .= '
                                      <tr>
                                          <td>'.$item->idMensaje.'</td>
                                          <td>'.$usuario->nombre.'</td>
                                          <td>'.$nomConcep.'</td>
                                          <td>'.$item->titulo.'</td>
                                          <td>'.$item->contenido.'</td>
                                          <td>'.$nombreRep.'</td>
                                          <td>'.convertirFechaVista($item->fechaCreacion).'</td>
                                          <td>';
                                          if ($item->idConcepto!=1) {
                                            $html .='<a class="kgrLinkDelete" onclick="detallesMensaje('.$item->idReporte.','.$item->idConcepto.')" href="javascript:void 0" title="Detalles"><img src="../images/eye.png" class="iconoDesactivar" > </a>';
                                          }else {
                                            $html .='Sin Detalles';
                                          }
                                    $html .= '</tr> ';
                            }
                        }

                $html .= '
                    </tbody>
                </table>
                </div>
            ';
            return $html;
    }
}
