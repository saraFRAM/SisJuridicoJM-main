<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/catMateriasDB.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';

class catMateriasObj extends configuracionesGridObj{    
    private $_idMateria = 0;    
    private $_nombre = '';
    private $_activo = 0;
    private $_tieneAcciones = 0;
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
    public function ObtMaterias($activo=-1, $campoOrder = "", $tieneAcciones = ""){
        $array = array();
        $ds = new catMateriasDB();
        $datosBD = new datosBD();
        $result = $ds->ObtMateriasDB($activo, $campoOrder, $tieneAcciones);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    public function MateriaPorId($id){
        $usrDS = new catMateriasDB();
        $obj = new catMateriasObj();
        $datosBD = new datosBD();
        $result = $usrDS->MateriaPorIdDB($id);

        return $datosBD->setDatos($result, $obj);
    }

    public function GuardarMateria(){
        $objDB = new catMateriasDB();
        $this->_idMateria = $objDB->insertMateriaDB($this->getParams());
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
    
    public function ActCampoMateria($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new catMateriasDB();
        $resAct = $objDB->ActCampoMateriaDB($param);
        return $resAct;
    }

    //Grid
    public function ObtMateriasGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new catMateriasDB();
        $ds = $uDB->MateriasDataSet($ds);
        $grid = new KoolGrid("cat_materias");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idMateria", "ID", false, true);
        $configGrid->defineColumn($grid, "nombre", "Nombre", true, false, 1);
        $configGrid->defineColumn($grid, "activo", "Activo", true, false, 0);
        $configGrid->defineColumn($grid, "tieneAcciones", "Tiene acciones", true, false, 0);
        // if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumnEdit($grid);
        // }

        //pocess grid
        $grid->Process();

        return $grid;
    }

}
