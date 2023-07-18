<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/catTipoCasosDB.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';

class catTipoCasosObj extends configuracionesGridObj{
    private $_idTipo = 0;
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
    public function ObtCatTipoCasos($activo=-1){
        $array = array();
        $ds = new catTipoCasosDB();
        $datosBD = new datosBD();
        $result = $ds->ObtCatTipoCasosDB($activo);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    public function TipoCasoPorId($id){
        $usrDS = new catTipoCasosDB();
        $obj = new catTipoCasosObj();
        $datosBD = new datosBD();
        $result = $usrDS->TipoCasoPorIdDB($id);

        return $datosBD->setDatos($result, $obj);
    }

    /* public function ActCampoEnfermedad($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new catTipoCasosDB();
        $resAct = $objDB->ActCampoEnfermedadDB($param);
        return $resAct;
    }*/

    //Grid
    public function ObtTiposCasoGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new catTipoCasosDB();
        $ds = $uDB->TiposCasoDataSet($ds);
        $grid = new KoolGrid("cat_tipo_casos");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idTipo", "ID", false, true);
        $configGrid->defineColumn($grid, "nombre", "Nombre", true, false, 1);
        $configGrid->defineColumn($grid, "activo", "Activo", true, false, 0);
        // if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumnEdit($grid);
        // }

        //pocess grid
        $grid->Process();

        return $grid;
    }

    public function CrearTipoCaso(){
        $objDB = new catTipoCasosDB();
        $this->_idTipo = $objDB->crearTipoCasoDB($this->getParams());
    }

    public function EditarTipoCaso(){
        $objDB = new catTipoCasosDB();
        return $objDB->editarTipoCasoDB($this->getParams(true));
    }

    private function getParams($update = false){
        $param[0] = $this->_nombre;
        $param[1] = $this->_activo;

        if($update){ //Para actualizar
            $param[2] = $this->_idTipo;
        }
        return $param;
    }

    /* // Imp. obt nombre de Enfermedades por ids
    public function obtNombreEnfermedadesPorIds($id){
        $usrDS = new catTipoCasosDB();
        $obj = new catTipoCasosObj();
        $datosBD = new datosBD();
        $result = $usrDS->obtNombreEnfermedadesPorIdsDB($id);

        return $datosBD->setDatos($result, $obj);
    } */

}
