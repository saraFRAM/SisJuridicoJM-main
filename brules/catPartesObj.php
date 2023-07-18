<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/catPartesDB.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';

class catPartesObj extends configuracionesGridObj{    
    private $_idParte = 0;    
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
    public function ObtPartes($activo=-1, $campoOrder = ""){
        $array = array();
        $ds = new catPartesDB();
        $datosBD = new datosBD();
        $result = $ds->ObtPartesDB($activo, $campoOrder);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    public function PartePorId($id){
        $usrDS = new catPartesDB();
        $obj = new catPartesObj();
        $datosBD = new datosBD();
        $result = $usrDS->PartePorIdDB($id);

        return $datosBD->setDatos($result, $obj);
    }

    public function GuardarParte(){
        $objDB = new catPartesDB();
        $this->_idParte = $objDB->insertParteDB($this->getParams());
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
    
    public function ActCampoParte($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new catPartesDB();
        $resAct = $objDB->ActCampoParteDB($param);
        return $resAct;
    }

    //Grid
    public function ObtPartesGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new catPartesDB();
        $ds = $uDB->PartesDataSet($ds);
        $grid = new KoolGrid("cat_partes");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idParte", "ID", false, true);
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
