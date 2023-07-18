<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/catConceptosDB.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';

class catConceptosObj extends configuracionesGridObj{
    private $_idConcepto = 0;
    private $_tipo = 0;
    private $_nombre = '';
    private $_activo = 0;

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion
    public function ObtCatConceptos($activo=-1, $tipo = ""){
        $array = array();
        $ds = new catConceptosDB();
        $datosBD = new datosBD();
        $result = $ds->ObtCatConceptosDB($activo, $tipo);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    public function ConceptoPorId($id){
        $usrDS = new catConceptosDB();
        $obj = new catConceptosObj();
        $datosBD = new datosBD();
        $result = $usrDS->ConceptoPorIdDB($id);

        return $datosBD->setDatos($result, $obj);
    }

    public function CrearConcepto(){
        $objDB = new catConceptosDB();
        $this->_idConcepto = $objDB->CrearConceptoDB($this->getParams());
    }

    public function EditarConcepto(){
        $objDB = new catConceptosDB();
        return $objDB->EditarConceptoDB($this->getParams(true));
    }

    private function getParams($update = false){
        $param[0] = $this->_nombre;
        $param[1] = $this->_activo;

        if($update){ //Para actualizar
            $param[2] = $this->_idConcepto;
        }
        return $param;
    }

    /* public function ActCampoEnfermedad($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new catConceptosDB();
        $resAct = $objDB->ActCampoEnfermedadDB($param);
        return $resAct;
    }*/

    //Grid
    public function ObtConceptosGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new catConceptosDB();
        $ds = $uDB->ConceptosDataSet($ds);
        $grid = new KoolGrid("cat_conceptos");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idConcepto", "ID", false, true);
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
        $usrDS = new catConceptosDB();
        $obj = new catConceptosObj();
        $datosBD = new datosBD();
        $result = $usrDS->obtNombreEnfermedadesPorIdsDB($id);

        return $datosBD->setDatos($result, $obj);
    } */

}
