<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/casoAccionesDB.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';
include_once  $dirname.'/brules/utilsObj.php';

class casoAccionesObj extends configuracionesGridObj{
    private $_idAccion = 0;

    private $_usuarioId = 0;

    private $_casoId = 0;
    private $_tipo = 0;
    private $_tipo2 = 0;
    private $_importancia = 0;
    private $_fechaCompromiso = '';
    private $_recordatorio = 0;
    private $_fechaRealizado = '';
    private $_nombre = '';
    private $_comentarios = '';
    private $_internos = '';
    private $_reporte = '';
    private $_fechaAlta = '0000-00-00';
    private $_fechaAct = '0000-00-00 00:00:00';
    private $_fechaCreacion = '0000-00-00 00:00:00';


    private $_padreAccionId = 0;
    private $_estatusId = 0;
    private $_esperoInstrucciones = 0;
    private $_avanzo = 0;
    private $_leido = 0;

    private $_responsableId = '';

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion
    public function ObtCasoAcciones($casoId = 0, $padreAccionId = 0, $estatus = '', $fecha = '', $desde = '', $hasta = '', $orderBy = '', $responsableId = 0, $FComproMayIgu = ''){
        $array = array();
        $ds = new casoAccionesDB();
        $datosBD = new datosBD();
        $result = $ds->ObtCasoAccionesDB($casoId, $padreAccionId, $estatus, $fecha, $desde, $hasta, $orderBy, $responsableId,$FComproMayIgu);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    public function obtenerIdPadre($idAccion){
        $ds = new casoAccionesDB();
        $obj = new casoAccionesObj();
        $datosBD = new datosBD();

        $result = $ds->obtenerIdPadreDB($idAccion);
        
        return $datosBD->setDatos($result, $obj);
    }

    public function CasoAccionesPorId($id){
        $usrDS = new casoAccionesDB();
        $obj = new casoAccionesObj();
        $datosBD = new datosBD();
        $result = $usrDS->CasoAccionesPorIdDB($id);

        return $datosBD->setDatos($result, $obj);
    }

    public function CrearCasoAccion(){
        $objDB = new casoAccionesDB();
        $this->_idAccion = $objDB->CrearCasoAccionDB($this->getParams());
    }
    public function EliminarCitaSimpleObj($idAccion){
        $objDB = new casoAccionesDB();
        return $objDB->EliminarCitaDB($idAccion);
    }

    public function EliminarCitaSimpleSerieObj($id){
        $objDB = new casoAccionesDB();
        return $objDB->EliminarCitaSerieDB($id);
    }

    public function EditarCasoAccion(){
        $objDB = new casoAccionesDB();
        return $objDB->EditarCasoAccionDB($this->getParams(true));
    }

    private function getParams($update = false){
        $dateByZone = obtDateTimeZone();
        $this->_fechaCreacion = $dateByZone->fechaHora;
        $this->_fechaAct = $dateByZone->fechaHora;

        $param[0] = $this->_usuarioId;
        $param[1] = $this->_casoId;
        $param[2] = $this->_tipo;
        $param[3] = $this->_importancia;
        $param[4] = $this->_nombre;
        $param[5] = $this->_comentarios;
        $param[6] = $this->_internos;
        $param[7] = $this->_reporte;
        $param[8] = $this->_padreAccionId;
        $param[9] = $this->_estatusId;
        $param[10] = $this->_fechaCompromiso;
        $param[11] = $this->_fechaRealizado;
        $param[12] = $this->_fechaAlta;
        $param[13] = $this->_responsableId;
        $param[14] = $this->_avanzo;

        if($update){ //Para actualizar
            $param[15] = $this->_fechaAct;
            $param[16] = $this->_idAccion;
        }else{
            // $param[5] = $this->_fechaCreacion;
        }
        return $param;
    }

    public function ActCampoAccion($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new casoAccionesDB();
        $resAct = $objDB->ActCampoAccionDB($param);
        return $resAct;
    }

    public function Eliminar($idAccion){
      $objDB = new casoAccionesDB();
      return $objDB->EliminarAccionDB($idAccion);
    }

    //Grid
    public function ObtAccionesGrid($casoId=0,$idUsuario=0, $fechaRealizado = ''){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new casoAccionesDB();
        $ds = $uDB->AccionesDataSet($ds, $casoId, $idUsuario, $fechaRealizado);
        $grid = new KoolGrid("caso_acciones");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idAccion", "ID", true, true);
        //$configGrid->defineColumn($grid, "fechaAlta2", "F. Alta", true, false, 1);
        //$configGrid->defineColumn($grid, "fechaCompromiso2", "F. Compromiso", true, false, 1);
        //$configGrid->defineColumn($grid, "fechaRealizado2", "F. Realizado", true, false, 1);
        //$configGrid->defineColumn($grid, "fechaAct2", "F. Ult. Act.", true, false, 1);
        //$configGrid->defineColumn($grid, "estatusId", "Estatus", true, false, 1);
        $configGrid->defineColumn($grid, "numAbogado", "Num Abogado", true, false, 1);
        $configGrid->defineColumn($grid, "nombre", "Actividad", true, false, 1);
       
        $configGrid->defineColumn($grid, "comentarios", "Comentarios", true, false, 1);
        //$configGrid->defineColumn($grid, "avanzo2", "Avanzo", true, false, 1);
        //$configGrid->defineColumn($grid, "noLeidos", "Com. No Leidos", true, false, 1);
        // if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumnEdit($grid);
        // }

        //pocess grid
        $grid->Process();

        return $grid;
    }

    public function ObtActividadesGrid($usuarioId = 0, $rolid = 0){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new casoAccionesDB();
        $ds = $uDB->ActividadesDataSet($ds, $usuarioId,$rolid);
        $grid = new KoolGrid("grid_actividades");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        
        $configGrid->defineColumn($grid, "ajeno", "Ajena", true, true);
        $configGrid->defineColumn($grid, "idCaso", "ID Exp", true, true);
        $configGrid->defineColumn($grid, "idAccion", "ID Act", true, true);
        $configGrid->defineColumn($grid, "numExpediente", "Expediente Interno", true, false, 1);
        $configGrid->defineColumn($grid, "numExpedienteJuzgado", "Exp. Juzgado", true, false, 1);
        $configGrid->defineColumn($grid, "nombreCliente", "Cliente", true, false, 1);
        $configGrid->defineColumn($grid, "fechaCompromiso", "F. compromiso", true, false, 1);
        $configGrid->defineColumn($grid, "fechaCreacion2", "F. creacion", true, false, 1);
        $configGrid->defineColumn($grid, "fechaCompromiso2", "F. compromiso", true, false, 1);
        $configGrid->defineColumn($grid, "fechaAct2", "F. Ult. Act.", true, false, 1);
        if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumn($grid, "fechaRealizado2", "F. Terminado", true, false, 1);
            $configGrid->defineColumn($grid, "diferenciaTerminado", "Dif", true, false, 1);
        }
        $configGrid->defineColumn($grid, "nombreAbogado", "Responsable", true, false, 1);
        $configGrid->defineColumn($grid, "tipoTexto", "Tipo", true, false, 1);
        $configGrid->defineColumn($grid, "nombre", "Actividad", true, false, 1);
        $configGrid->defineColumn($grid, "estatusId", "Estatus", true, false, 1);
        $configGrid->defineColumn($grid, "importanciaTexto", "Importancia", true, false, 1);
        $configGrid->defineColumn($grid, "NombreTitular", "Titular", true, false, 1);
        
       

        // if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumnEdit($grid);
        // }
       
        $grid->ClientSettings->ClientEvents["OnInit"] = "Handle_OnInit";
            $grid->ClientSettings->ClientEvents["OnLoad"] = "Handle_OnLoad";

        //pocess grid
        $grid->Process();

        return $grid;
    }

    //Obtiene listado de proximo evento de un caso 
    public function ObtProxEventosCasoId($casoId){
        $usrDS = new casoAccionesDB();
        $datosBD = new datosBD();

        $result = $usrDS->ObtProxEventosCasoId($casoId);

        $colEventos =  $datosBD->arrDatosObj($result);
        
        return $colEventos;
    }

    // Obtiene la agenda de eventos programados
    // para un rango determinado
    public function ObtenerAgenda($tipoToSearch, $idAbogadoSel){
        $usrDS = new casoAccionesDB();
        $datosBD = new datosBD();
        if($_SESSION['idRol']==4){
            $result = $usrDS->ObtenerAgendaDB($tipoToSearch, $_SESSION['idUsuario']);
        }else{
            if($idAbogadoSel > 0){
                $result = $usrDS->ObtenerAgendaDB($tipoToSearch, $idAbogadoSel);
            }else{
                $result = $usrDS->ObtenerAgendaDB($tipoToSearch);
            }
                
        }
        
        $colEventos =  $datosBD->arrDatosObj($result);
        $arrEventos = array();

        if(count($colEventos)>0){
            foreach ($colEventos as $key=>$elem) {
                $title = $elem->TitularID . ' | ' . $elem->nombreCliente. ' | ' .'ID: '.$elem->casoId.' | '.$elem->numExpedienteJuzgado;
                $colorEvento = $this->ObtenerEventoColor($elem->tipo);

                if($elem->tipo == 6 || $elem->tipo == 8 || $elem->tipo == 9 || $elem->tipo == 10){
                    $arrEventos[] = array(
                        "title"=>$elem->nombre, 
                        "url"=>"", 
                        "id"=>$elem->idAccion,
                        "actId"=>$elem->idAccion,
                        "responsable"=>$elem->responsable,
                        "tipo"=>$elem->tipo,
                        "responsableId"=>$elem->responsableId,
                        "creador"=>$elem->creador,
                        "comentario"=>$elem->comentarios,
                        "start"=>fechaHoraCal($elem->fechaCompromiso), 
                        "end"=>fechaHoraCal($elem->fechaCompromiso),
                        "backgroundColor"=>$colorEvento
                      );
                }else{
                    $arrEventos[] = array(
                        "title"=>$title, 
                        "url"=>"", 
                        "id"=>$elem->idAccion,
                        "expId"=>$elem->casoId,
                        "actId"=>$elem->idAccion,
                        "numExpJuzg"=>$elem->numExpedienteJuzgado,
                        "akaAsunto"=>$elem->akaAsunto,
                        "materia"=>$elem->materia,
                        "distrito"=>$elem->distrito,
                        "responsable"=>$elem->responsable,
                        "creador"=>$elem->creador,
                        "juzgado"=>$elem->juzgado,
                        "nombre"=>$elem->nombre,
                        "cliente"=>$elem->nombreCliente,
                        "aka"=>$elem->aka,
                        "tipo"=>$elem->tipo,
                        "start"=>fechaHoraCal($elem->fechaCompromiso), 
                        "end"=>fechaHoraCal($elem->fechaCompromiso),
                        "backgroundColor"=>$colorEvento
                      );
                }
                
            }
        }

        return $arrEventos;
    }

    private function ObtenerEventoColor($tipo){
        $colorEvento ="";

        switch($tipo){
            case 3:
                $colorEvento = "#f50213";
                break;
            case 5:
                $colorEvento = "#02f70f";
                break;
            case 6:
                $colorEvento = "#2b6dfc";
                break;
            case 7:
                $colorEvento = "#9404d1";
                break;
            case 8:
                $colorEvento = "#43c4f7";
                break;
            case 9:
                $colorEvento = "#f7cd43";
                break;  
            case 10:
                $colorEvento = "#8a6d3b";
                break;      
        }

        return $colorEvento;
    }

}



	
