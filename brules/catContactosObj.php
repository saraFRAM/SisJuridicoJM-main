<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/catContactosDB.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';

class catContactosObj extends configuracionesGridObj{    
    private $_idContacto = 0;    
    private $_casoId = 0;
    private $_nombre = '';
    private $_telefono = '';
    private $_email = '';
    private $_domicilio = '';
    private $_notas = '';
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
    public function ObtContactos($activo=-1, $expedienteId = 0){
        $array = array();
        $ds = new catContactosDB();
        $datosBD = new datosBD();
        $result = $ds->ObtContactosDB($activo, $expedienteId);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    public function ContactoPorId($id){
        $usrDS = new catContactosDB();
        $obj = new catContactosObj();
        $datosBD = new datosBD();
        $result = $usrDS->ContactoPorIdDB($id);

        return $datosBD->setDatos($result, $obj);
    }

    public function EditarContacto(){
        $objDB = new catContactosDB();
        return $objDB->EditarContactoDB($this->getParams(true));
    }


    public function GuardarContacto(){
        $objDB = new catContactosDB();
        $this->_idContacto = $objDB->insertContactoDB($this->getParams());
    }

    private function getParams($update = false){
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        // $dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
        // $this->_fechaCreacion= $dateTime;
        // $this->_fechaAct= $dateTime;

        $param[0] = $this->_casoId;
        $param[1] = $this->_nombre;
        $param[2] = $this->_telefono;
        $param[3] = $this->_email;
        $param[4] = $this->_domicilio;
        $param[5] = $this->_notas;

        if($update){//Para actualizar{
            // $param[2] = $this->_fechaAct;
            $param[] = $this->_idContacto;
        }
        else{
            // $param[2] = $this->_fechaCreacion;
            /*$param[3] = $this->_instructorId;
            $param[5] = $this->_fechaEstudio;*/
        }
        return $param;
    }
    
    public function ActCampoContacto($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new catContactosDB();
        $resAct = $objDB->ActCampoContactoDB($param);
        return $resAct;
    }

    //Grid
    public function ObtContactosGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new catContactosDB();
        $ds = $uDB->ContactosDataSet($ds);
        $grid = new KoolGrid("cat_contactos");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idContacto", "ID", false, true);
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
