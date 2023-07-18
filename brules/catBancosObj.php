<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/catBancosDB.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';

class catBancosObj extends configuracionesGridObj{    
    private $_idBanco = 0;    
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
    public function ObtBancos($activo=-1, $campoOrder = ""){
        $array = array();
        $ds = new catBancosDB();
        $datosBD = new datosBD();
        $result = $ds->ObtBancosDB($activo, $campoOrder);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    public function BancoPorId($id){
        $usrDS = new catBancosDB();
        $obj = new catBancosObj();
        $datosBD = new datosBD();
        $result = $usrDS->BancoPorIdDB($id);

        return $datosBD->setDatos($result, $obj);
    }

    public function GuardarBanco(){
        $objDB = new catBancosDB();
        $this->_idBanco = $objDB->insertBancoDB($this->getParams());
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
    
    public function ActCampoBanco($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new catBancosDB();
        $resAct = $objDB->ActCampoBancoDB($param);
        return $resAct;
    }

    //Grid
    public function ObtBancosGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new catBancosDB();
        $ds = $uDB->BancosDataSet($ds);
        $grid = new KoolGrid("cat_bancos");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idBanco", "ID", false, true);
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
