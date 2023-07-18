<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/cuentasDB.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';

class cuentasObj extends configuracionesGridObj{
    private $_idCuenta = 0;
    private $_casoId = 0;
    private $_clienteId = 0;
    private $_tipoCobro = 0;
    private $_planPagos = 0;
    private $_avance = 0;
    private $_monto = 0;
    private $_montoAux = 0;
    private $_numMeses = 0;
    private $_diaCobro = 0;
    private $_saldo = 0;
    private $_comentarios = '';
    private $_cobrosJson = '';
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
    public function ObtCuentas(){
        $array = array();
        $ds = new cuentasDB();
        $datosBD = new datosBD();
        $result = $ds->ObtCuentasDB();
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    public function CuentaPorId($id){
        $usrDS = new cuentasDB();
        $obj = new cuentasObj();
        $datosBD = new datosBD();
        $result = $usrDS->CuentaPorIdDB($id);

        return $datosBD->setDatos($result, $obj);
    }

    public function CuentaPorCasoId($casoId){
        $usrDS = new cuentasDB();
        $obj = new cuentasObj();
        $datosBD = new datosBD();
        $result = $usrDS->CuentaPorCasoIdDB($casoId);

        return $datosBD->setDatos($result, $obj);
    }

    /***
     * Obtiene los casos asociado a una cuenta 
     */
    public function obtCasosDecuenta($cuentaId){
        $array = array();
        $ds = new cuentasDB();
        $datosBD = new datosBD();
        $result = $ds->obtCasosDecuentaDB($cuentaId);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    /**
     * Obtener casos por ID Cliente que aún no tiene una cuenta asociada
     */
    public function ObtCasosPorIdClienteSinCta($idCliente){
        $array = array();
        $ds = new cuentasDB();
        $datosBD = new datosBD();
        $result = $ds->ObtCasosPorIdClienteSinCta($idCliente);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    /**
     * Asociar un caso a una cuenta
     */
    public function AsociarCuentaCaso($idCuenta, $casoId){
        $objDB = new cuentasDB();
        return $objDB->AsociarCuentaCasoDB($idCuenta, $casoId);
    }

    /**
     * Borrar la asociación de un caso a una cuenta
     */
    public function EliminarCuentaCaso($idCuenta, $casoId){
        $objDB = new cuentasDB();
        return $objDB->EliminarCuentaCasoDB($idCuenta, $casoId);
    }

    public function CrearCuenta(){
        $objDB = new cuentasDB();
        $this->_idCuenta = $objDB->CrearCuentaDB($this->getParams());
    }

    public function EditarCuenta(){
        $objDB = new cuentasDB();
        return $objDB->EditarCuentaDB($this->getParams(true));
    }

    private function getParams($update = false){
        $dateByZone = obtDateTimeZone();
        $this->_fechaCreacion = $dateByZone->fechaHora;
        $this->_fechaAct = $dateByZone->fechaHora;

        $param[0] = $this->_casoId;
        $param[1] = $this->_clienteId;
        $param[2] = $this->_tipoCobro;
        $param[3] = $this->_planPagos;
        $param[4] = $this->_monto;
        $param[5] = $this->_saldo;
        $param[6] = $this->_comentarios;
        $param[7] = $this->_avance;
        $param[8] = $this->_montoAux;
        $param[9] = $this->_numMeses;
        $param[10] = $this->_diaCobro;

        if($update){ //Para actualizar
            $param[11] = $this->_idCuenta;
        }else{
            $param[11] = $this->_fechaCreacion;
        }
        return $param;
    }

     public function ActCampoCuenta($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new cuentasDB();
        $resAct = $objDB->ActCampoCuentaDB($param);
        return $resAct;
    }

    public function ActCampoCuentaJson($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new cuentasDB();
        $resAct = $objDB->ActCampoCuentaJsonDB($param);
        return $resAct;
    }

    //Grid
    public function ObtCuentasGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new cuentasDB();
        $ds = $uDB->CuentasDataSet($ds);
        $grid = new KoolGrid("cuentas");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idCuenta", "ID", false, true);
        $configGrid->defineColumn($grid, "casoId", "Expediente Id", true, false, 1);
        $configGrid->defineColumn($grid, "clienteId", "Cliente", true, false, 0);
        $configGrid->defineColumn($grid, "tipoCobro", "Tipo Cobro", true, false, 0);
        $configGrid->defineColumn($grid, "planPagos", "Modo cobro", true, false, 0);
        
        // if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumnEdit($grid);
        // }

        //pocess grid
        $grid->Process();

        return $grid;
    }

    /* // Imp. obt nombre de Enfermedades por ids
    public function obtNombreEnfermedadesPorIds($id){
        $usrDS = new cuentasDB();
        $obj = new cuentasObj();
        $datosBD = new datosBD();
        $result = $usrDS->obtNombreEnfermedadesPorIdsDB($id);

        return $datosBD->setDatos($result, $obj);
    } */
    
    public function EliminarCobros($id, $idCuenta){
        $objDB = new cuentasDB();
        return $objDB->EliminarCobroDB($id, $idCuenta);
    }

    public function EditarCuentaMeses($id, $numeroMeses){
        $param[0] = $numeroMeses;
        $param[1] = $id;
        $objDB = new cuentasDB();
        return $objDB->EditarCuentaMesesDB($param);
    }

}
