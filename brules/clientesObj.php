<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/clientesDB.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';
header("Content-Type: text/html;charset=UTF-8");

class clientesObj extends configuracionesGridObj{
    private $_idCliente = 0;
    private $_nombre = '';
    private $_telefono = '';
    private $_email = '';
    private $_direccion = '';
    private $_empresa = '';
    private $_aka = '';
    private $_activo = 0;
    private $_fechaAct = '0000-00-00 00:00:00';
    private $_fechaCreacion = '0000-00-00 00:00:00';


    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion
    public function ObtClientes($soloExpActivos = false){
        $array = array();
        $ds = new clientesDB();
        $datosBD = new datosBD();
        $result = $ds->ObtClientesDB($soloExpActivos);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    public function ClientePorId($id){
        $usrDS = new clientesDB();
        $obj = new clientesObj();
        $datosBD = new datosBD();
        $result = $usrDS->ClientePorIdDB($id);

        return $datosBD->setDatos($result, $obj);
    }

    public function CrearCliente(){
        $objDB = new clientesDB();
        $this->_idCliente = $objDB->CrearClienteDB($this->getParams());
    }

    public function EditarCliente(){
        $objDB = new clientesDB();
        return $objDB->EditarClienteDB($this->getParams(true));
    }

    private function getParams($update = false){
        $dateByZone = obtDateTimeZone();
        $this->_fechaCreacion = $dateByZone->fechaHora;
        $this->_fechaAct = $dateByZone->fechaHora;

        $param[0] = $this->_nombre;
        $param[1] = $this->_telefono;
        $param[2] = $this->_email;
        $param[3] = $this->_direccion;
        $param[4] = $this->_empresa;
        $param[5] = $this->_aka;
        
        if($update){ //Para actualizar
            $param[6] = $this->_fechaAct;
            $param[7] = $this->_idCliente;
        }else{
            $param[6] = $this->_fechaCreacion;
        }
        return $param;
    }

    public function EliminarClienteObj($idCliente){
        $objDB = new clientesDB();
        return $objDB->EliminarClienteDB($idCliente);
    }

    /* public function ActCampoCliente($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new clientesDB();
        $resAct = $objDB->ActCampoClienteDB($param);
        return $resAct;
    }*/

    //Grid
    public function ObtClientesGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new clientesDB();
        $ds = $uDB->ClientesDataSet($ds);
        $grid = new KoolGrid("clientes");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idCliente", "ID", false, true);
        $configGrid->defineColumn($grid, "nombre", "Nombre", true, false, 1);
        $configGrid->defineColumn($grid, "email", "Correo", true, false, 0);
        $configGrid->defineColumn($grid, "telefono", "Tel&eacute;fono", true, false, 0);
        $configGrid->defineColumn($grid, "direccion", "Direcci&oacute;n", true, false, 0);
        $configGrid->defineColumn($grid, "empresa", "Empresa", true, false, 0);
        $configGrid->defineColumn($grid, "aka", "Aka", false, false, 0);
        // if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumnEdit($grid);
        // }

        //pocess grid
        $grid->Process();

        return $grid;
    }

    /* // Imp. obt nombre de Enfermedades por ids
    public function obtNombreEnfermedadesPorIds($id){
        $usrDS = new clientesDB();
        $obj = new clientesObj();
        $datosBD = new datosBD();
        $result = $usrDS->obtNombreEnfermedadesPorIdsDB($id);

        return $datosBD->setDatos($result, $obj);
    } */

}
