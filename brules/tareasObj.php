<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/tareasDB.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';

class tareasObj extends configuracionesGridObj{
    private $_idTarea = 0;
    private $_usuarioId = 0;
    // private $_casoId = 0;
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
    private $_fechaAlta = '';
    private $_fechaAct = '';
    private $_fechaCreacion = '';


    private $_padreTareaId = 0;
    private $_estatusId = 0;
    private $_esperoInstrucciones = 0;

    private $_responsableId = '';

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion
    public function ObtTareas($casoId = 0, $padreTareaId = 0, $estatus = '', $fecha = '', $desde = '', $hasta = '', $orderBy = ''){
        $array = array();
        $ds = new tareasDB();
        $datosBD = new datosBD();
        $result = $ds->ObtTareasDB($casoId, $padreTareaId, $estatus, $fecha, $desde, $hasta, $orderBy);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    public function TareasPorId($id){
        $usrDS = new tareasDB();
        $obj = new tareasObj();
        $datosBD = new datosBD();
        $result = $usrDS->TareasPorIdDB($id);

        return $datosBD->setDatos($result, $obj);
    }

    public function CrearTarea(){
        $objDB = new tareasDB();
        $this->_idTarea = $objDB->CrearTareaDB($this->getParams());
    }

    public function EditarTarea(){
        $objDB = new tareasDB();
        return $objDB->EditarTareaDB($this->getParams(true));
    }

    private function getParams($update = false){
        $dateByZone = obtDateTimeZone();
        $this->_fechaCreacion = $dateByZone->fechaHora;
        $this->_fechaAct = $dateByZone->fechaHora;

        $param[0] = $this->_usuarioId;
        // $param[1] = $this->_casoId;
        $param[2] = $this->_tipo;
        $param[3] = $this->_importancia;
        $param[4] = $this->_nombre;
        $param[5] = $this->_comentarios;
        $param[6] = $this->_internos;
        $param[7] = $this->_reporte;
        $param[8] = $this->_padreTareaId;
        $param[9] = $this->_estatusId;
        $param[10] = $this->_fechaCompromiso;
        $param[11] = $this->_fechaRealizado;
        $param[12] = $this->_fechaAlta;
        $param[13] = $this->_responsableId;

        if($update){ //Para actualizar
            $param[14] = $this->_fechaAct;
            $param[15] = $this->_idTarea;
        }else{
            // $param[5] = $this->_fechaCreacion;
        }
        return $param;
    }

    public function ActCampoTarea($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new tareasDB();
        $resAct = $objDB->ActCampoTareaDB($param);
        return $resAct;
    }

    public function Eliminar($idTarea=0){
      $objDB = new tareasDB();
      return $objDB->EliminarTareaDB($idTarea);
    }

    //Grid
    public function ObtTareasGrid($tipoId="",$isCst=0,$idUsuario=0,$esHistorico=false){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new tareasDB();
        $ds = $uDB->TareasDataSet($ds, $tipoId, $idUsuario,$esHistorico);
        $grid = new KoolGrid("tareas");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idTarea", "ID", true, true);
        $configGrid->defineColumn($grid, "fechaAlta2", "F. Alta", true, false, 1);
        $configGrid->defineColumn($grid, "fechaCompromiso2", "F. Compromiso", true, false, 1);
        $configGrid->defineColumn($grid, "estatusId", "Estatus", true, false, 1);

        if($idUsuario == 0){
            $configGrid->defineColumn($grid, "numAbogado", "Num Abogado", true, false, 1);
            $configGrid->defineColumn($grid, "nombreResponsable", "Responsable", true, false, 1);
        }
        
        $configGrid->defineColumn($grid, "nombre", "Actividad", true, false, 1);
      
        $configGrid->defineColumn($grid, "comentarios2", "Descripci&oacute;n", true, false, 1);
        $configGrid->defineColumn($grid, "reporte", "Reporte", true, false, 1);

        // if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumnEdit($grid, $tipoId);
        // }

        //pocess grid
        $grid->Process();

        return $grid;
    }

    public function ObtActividadesGrid($usuarioId = 0, $rolid = 0){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new tareasDB();
        $ds = $uDB->ActividadesDataSet($ds, $usuarioId,$rolid);
        $grid = new KoolGrid("grid_tareas");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        
        $configGrid->defineColumn($grid, "ajeno", "Ajena", true, true);
        // $configGrid->defineColumn($grid, "idCaso", "ID Exp", true, true);
        $configGrid->defineColumn($grid, "idTarea", "ID Tar", true, true);
        // $configGrid->defineColumn($grid, "numExpediente", "Expediente Interno", true, false, 1);
        // $configGrid->defineColumn($grid, "numExpedienteJuzgado", "Exp. Juzgado", true, false, 1);
        // $configGrid->defineColumn($grid, "nombreCliente", "Cliente", true, false, 1);
        $configGrid->defineColumn($grid, "fechaCompromiso", "F. compromiso", true, false, 1);
        $configGrid->defineColumn($grid, "fechaCompromiso2", "F. compromiso", true, false, 1);
        if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumn($grid, "fechaRealizado2", "F. Terminado", true, false, 1);
            $configGrid->defineColumn($grid, "diferenciaTerminado", "Dif", true, false, 1);
        }
        $configGrid->defineColumn($grid, "nombreCreador", "Creada Por", true, false, 1);
        $configGrid->defineColumn($grid, "nombreResponsable", "Responsable", true, false, 1);
        $configGrid->defineColumn($grid, "tipoTexto", "Tipo", true, false, 1);
        $configGrid->defineColumn($grid, "nombre", "Actividad", true, false, 1);
        $configGrid->defineColumn($grid, "estatusId", "Estatus", true, false, 1);
        $configGrid->defineColumn($grid, "importanciaTexto", "Importancia", true, false, 1);
        // $configGrid->defineColumn($grid, "NombreTitular", "Titular", true, false, 1);
        
       

        // if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumnEdit($grid);
        // }

        //pocess grid
        $grid->Process();

        return $grid;
    }

}
