<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/digitalesDB.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';

class digitalesObj extends configuracionesGridObj{
    private $_idDocumento = 0;
    private $_casoId = 0;
    private $_tipo = 0;
    private $_nombre = '';
    private $_url = '';
    private $_orden = '';
    private $_usuarioId = 0;
    private $_fechaCreacion = '';
    private $_fechaAct = '';
    private $_descripcion = ''; // LDAH 16/08/2022 IMP para nuevo campo de descripcion de archivo


    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion
    public function ObtDigitales($casoId = 0, $tipo = 0, $estatus = '', $fecha = '', $desde = '', $hasta = '', $orderBy = ''){
        $array = array();
        $ds = new digitalesDB();
        $datosBD = new datosBD();
        $result = $ds->ObtDigitalesDB($casoId, $tipo, $estatus, $fecha, $desde, $hasta, $orderBy);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    public function DigitalesPorId($id){
        $usrDS = new digitalesDB();
        $obj = new digitalesObj();
        $datosBD = new datosBD();
        $result = $usrDS->DigitalesPorIdDB($id);

        return $datosBD->setDatos($result, $obj);
    }

    public function CrearDigital(){
        $objDB = new digitalesDB();
        $this->_idDocumento = $objDB->CrearDigitalDB($this->getParams());
    }

    public function EditarDigital(){
        $objDB = new digitalesDB();
        return $objDB->EditarDigitalDB($this->getParams(true));
    }

    private function getParams($update = false){
        $dateByZone = obtDateTimeZone();
        $this->_fechaCreacion = $dateByZone->fechaHora;
        $this->_fechaAct = $dateByZone->fechaHora;

        // $param[0] = $this->_usuarioId;
        $param[0] = $this->_casoId;
        $param[1] = $this->_tipo;
        $param[2] = $this->_nombre;
        $param[3] = $this->_url;
        $param[5] = $this->_descripcion; // LDAH 16/08/2022 IMP para nuevo campo de descripcion de archivo
        
        if($update){ //Para actualizar
            $param[4] = $this->_idDocumento;
        }else{
            // $param[5] = $this->_fechaCreacion;
        }
        return $param;
    }

    public function ActCampoDigital($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new digitalesDB();
        $resAct = $objDB->ActCampoDigitalDB($param);
        return $resAct;
    }

    public function Eliminar($idDocumento=0){
      $objDB = new digitalesDB();
      return $objDB->EliminarDigitalDB($idDocumento);
    }

    //Grid
    public function ObtDigitalesGrid($casoId=0,$isCst=0,$idUsuario=0,$esHistorico=false){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new digitalesDB();
        $ds = $uDB->DigitalesDataSet($ds, $casoId, $idUsuario,$esHistorico);
        $grid = new KoolGrid("digitales");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idDocumento", "ID", true, true);
        $configGrid->defineColumn($grid, "fechaCreacion2", "F. Alta", true, false, 1);
        $configGrid->defineColumn($grid, "tipo", "Tipo", true, false, 1);
        $configGrid->defineColumn($grid, "nombre", "Nombre", true, false, 1);
        $configGrid->defineColumn($grid, "usuarioId", "Usuario", true, false, 1);
        $configGrid->defineColumn($grid, "descripcion", "Descripcion", true, false, 1); // LDAH 16/08/2022 IMP para nuevo campo de descripcion de archivo

        // if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumnEdit($grid);
        // }

        //pocess grid
        $grid->Process();

        return $grid;
    }

    

}
