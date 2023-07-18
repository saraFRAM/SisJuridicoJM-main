<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/rolesBD.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';

class rolesObj extends configuracionesGridObj{
    private $_idRol = 0;
    private $_rol = '';
    private $_fechaCreacion = '0000-00-00 00:00:00';

    //get y set
    public function __get($name) {             
        return $this->{"_".$name};
    }
    public function __set($name, $value) {        
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion de roles
    public function GetAllRoles($rolIds = ""){
        $array = array();
        $ds = new rolesBD();
        $datosBD = new datosBD();
        $result = $ds->GetallRoles($rolIds);
        $array = $datosBD->arrDatosObj($result);     
        return $array;            
    }

    //Obtener roles por su id
    public function obtenerRolById($id){
        $ds = new rolesBD();
        $obj = new rolesObj();
        $datosBD = new datosBD();
        $result = $ds->obtenerRolByIdDB($id);
        
        return $datosBD->setDatos($result, $obj);
    }




    //Grid roles
    public function GetRolesGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new rolesBD();
        $ds = $uDB->rolesDataSet($ds);
        $grid = new KoolGrid("rolGrid");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idRol", "ID", false, true);
        $configGrid->defineColumn($grid, "rol", "Nombre", true, false, 1);
        if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumnEdit($grid);
        }

        //pocess grid
        $grid->Process();

        return $grid;
    }
    

}
