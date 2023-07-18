<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/catDocumentosDB.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';

class catDocumentosObj extends configuracionesGridObj{
    private $_idDocumento = 0;
    private $_casoId = 0;
    private $_nombre = '';
    private $_condiciones = '';
    private $_fechaRecepcion = '';
    private $_fechaRetorno = '';

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
    public function ObtCatDocumentos($activo=-1, $expedienteId = ""){
        $array = array();
        $ds = new catDocumentosDB();
        $datosBD = new datosBD();
        $result = $ds->ObtCatDocumentosDB($activo, $expedienteId);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    public function DocumentoPorId($id){
        $usrDS = new catDocumentosDB();
        $obj = new catDocumentosObj();
        $datosBD = new datosBD();
        $result = $usrDS->DocumentoPorIdDB($id);

        return $datosBD->setDatos($result, $obj);
    }

    public function CrearDocumento(){
        $objDB = new catDocumentosDB();
        $this->_idDocumento = $objDB->CrearDocumentoDB($this->getParams());
    }

    public function EditarDocumento(){
        $objDB = new catDocumentosDB();
        return $objDB->EditarDocumentoDB($this->getParams(true));
    }

    private function getParams($update = false){
        $param[0] = $this->_casoId;
        $param[1] = $this->_nombre;
        $param[2] = $this->_condiciones;
        $param[3] = $this->_fechaRecepcion;
        $param[4] = $this->_fechaRetorno;

        if($update){ //Para actualizar
            $param[5] = $this->_idDocumento;
        }
        return $param;
    }

    /* public function ActCampoEnfermedad($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new catDocumentosDB();
        $resAct = $objDB->ActCampoEnfermedadDB($param);
        return $resAct;
    }*/

    //Grid
    public function ObtDocumentosGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new catDocumentosDB();
        $ds = $uDB->DocumentosDataSet($ds);
        $grid = new KoolGrid("cat_documentos");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idDocumento", "ID", false, true);
        $configGrid->defineColumn($grid, "tipo", "Tipo", true, false, 1);
        $configGrid->defineColumn($grid, "nombre", "Nombre", true, false, 1);
        $configGrid->defineColumn($grid, "activo", "Activo", true, false, 0);
        // if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumnEdit($grid);
        // }

        //pocess grid
        $grid->Process();

        return $grid;
    }

    /* // Imp. obt nombre de Enfermedades por ids
    public function obtNombreEnfermedadesPorIds($id){
        $usrDS = new catDocumentosDB();
        $obj = new catDocumentosObj();
        $datosBD = new datosBD();
        $result = $usrDS->obtNombreEnfermedadesPorIdsDB($id);

        return $datosBD->setDatos($result, $obj);
    } */

}
