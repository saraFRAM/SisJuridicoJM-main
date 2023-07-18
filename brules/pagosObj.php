<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/pagosDB.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';

class pagosObj extends configuracionesGridObj{
    private $_idPago = 0;
    private $_tipo = 0;
    private $_cuentaId = 0;
    private $_metodoId = 0;
    private $_bancoId = 0;
    private $_monto = 0;
    private $_recibo = '';
    private $_comentarios = '';
    private $_fechaPago = '';
    private $_fechaCreacion = '';
    private $_fechaAct = '';


    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion
    public function ObtPagos($cuentaId = "", $tipo = 1){
        $array = array();
        $ds = new pagosDB();
        $datosBD = new datosBD();
        $result = $ds->ObtPagosDB($cuentaId, $tipo);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    public function PagoPorId($id){
        $usrDS = new pagosDB();
        $obj = new pagosObj();
        $datosBD = new datosBD();
        $result = $usrDS->PagoPorIdDB($id);

        return $datosBD->setDatos($result, $obj);
    }

    public function CrearPago(){
        $objDB = new pagosDB();
        $this->_idPago = $objDB->CrearPagoDB($this->getParams());
    }

    public function EditarPago(){
        $objDB = new pagosDB();
        return $objDB->EditarPagoDB($this->getParams(true));
    }

    private function getParams($update = false){
        $dateByZone = obtDateTimeZone();
        $this->_fechaCreacion = $dateByZone->fechaHora;
        $this->_fechaAct = $dateByZone->fechaHora;

        $param[0] = $this->_cuentaId;
        $param[1] = $this->_metodoId;
        $param[2] = $this->_bancoId;
        $param[3] = $this->_monto;
        $param[4] = $this->_comentarios;
        $param[5] = $this->_recibo;
        $param[6] = $this->_fechaPago;
        $param[7] = $this->_tipo;

        if($update){ //Para actualizar
            $param[8] = $this->_idPago;
        }else{
            $param[8] = $this->_fechaCreacion;
        }
        return $param;
    }

    /* public function ActCampoPago($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new pagosDB();
        $resAct = $objDB->ActCampoPagoDB($param);
        return $resAct;
    }*/

    //Grid
    public function ObtPagosGrid($idCuenta = 0, $tipo = 1){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new pagosDB();
        $ds = $uDB->PagosDataSet($ds, $idCuenta, $tipo);
        $nombreGrid = ($tipo == 1) ? 'pagos' : 'adicionales';
        $grid = new KoolGrid($nombreGrid);
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idPago", "ID", true, true);
        if($tipo == 2){
            $configGrid->defineColumn($grid, "comentarios", "Concepto", true, true);
        }
        $configGrid->defineColumn($grid, "cuentaId", "Id Cuenta", false, false, 1);
        $configGrid->defineColumn($grid, "metodoId", "Metodo", true, false, 0);
        $configGrid->defineColumn($grid, "bancoId", "Banco", true, false, 0);
        $configGrid->defineColumn($grid, "monto", "Monto", true, false, 0);
        $configGrid->defineColumn($grid, "fechaPago", "Fecha", true, false, 0);
       
        // if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumnEdit($grid);
        // }

        //pocess grid
        $grid->Process();

        return $grid;
    }

    /* // Imp. obt nombre de Enfermedades por ids
    public function obtNombreEnfermedadesPorIds($id){
        $usrDS = new pagosDB();
        $obj = new pagosObj();
        $datosBD = new datosBD();
        $result = $usrDS->obtNombreEnfermedadesPorIdsDB($id);

        return $datosBD->setDatos($result, $obj);
    } */

}
