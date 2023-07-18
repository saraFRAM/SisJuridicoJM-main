<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/catAccionesDB.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';

class catAccionesObj extends configuracionesGridObj{    
    private $_idAccion = 0;
    private $_materiaId = 0;
    private $_nombre = '';
    private $_activo = 0;
    private $_fechaCreacion = '0000-00-00 00:00:00';
    private $_fechaAct = '0000-00-00 00:00:00';


    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion
    public function ObtAcciones($activo=-1, $materiaId = "", $campoOrder = ""){
        $array = array();
        $ds = new catAccionesDB();
        $datosBD = new datosBD();
        $result = $ds->ObtAccionesDB($activo, $materiaId, $campoOrder);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    public function AccionPorId($id){
        $usrDS = new catAccionesDB();
        $obj = new catAccionesObj();
        $datosBD = new datosBD();
        $result = $usrDS->AccionPorIdDB($id);

        return $datosBD->setDatos($result, $obj);
    }

    public function GuardarAccion(){
        $objDB = new catAccionesDB();
        $this->_idAccion = $objDB->insertAccionDB($this->getParams());
    }

    private function getParams($update = false){
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
        $this->_fechaCreacion= $dateTime;
        $this->_fechaAct= $dateTime;

        $param[0] = $this->_nombre;
        $param[1] = $this->_activo;

        if($update){//Para actualizar{
            $param[2] = $this->_fechaAct;
            $param[3] = $this->_idFunte;
        }
        else{
            $param[2] = $this->_fechaCreacion;
            /*$param[3] = $this->_instructorId;
            $param[5] = $this->_fechaEstudio;*/
        }
        return $param;
    }
    
    public function ActCampoAccion($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new catAccionesDB();
        $resAct = $objDB->ActCampoAccionDB($param);
        return $resAct;
    }

    //Grid
    public function ObtAccionesGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new catAccionesDB();
        $ds = $uDB->AccionesDataSet($ds);
        $grid = new KoolGrid("cat_acciones");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idAccion", "ID", false, true);
        $configGrid->defineColumn($grid, "materiaId", "Materia", true, false, 1);
        $configGrid->defineColumn($grid, "nombre", "Nombre", true, false, 1);
        $configGrid->defineColumn($grid, "activo", "Activo", true, false, 0);
        // if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumnEdit($grid);
        // }

        //pocess grid
        $grid->Process();

        return $grid;
    }

}
