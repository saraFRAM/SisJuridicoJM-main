<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catMateriasDB {

    //method declaration
    public function ObtMateriasDB($activo, $campoOrder = "", $tieneAcciones = ""){
        $ds = new DataServices();
        $param[0] = "";
        $param[1] = "";
        $query = array();
        
        if($activo > 0){
            $query[] = " activo=$activo ";
        }

        if($tieneAcciones != ""){
            $query[] = " tieneAcciones=$tieneAcciones ";
        }
               
        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }

        if($campoOrder != ""){
            $param[1] = " ORDER BY $campoOrder ";
        }

        $result = $ds->Execute("ObtMateriasDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function MateriaPorIdDB($id){
        $ds = new DataServices();
        $param[0]= ($id > 0)?$id:0;
        $result = $ds->Execute("MateriaPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function insertMateriaDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("insertMateriaDB", $param, true);
        return $result;
    }
    
    public function ActCampoMateriaDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoMateriaDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function MateriasDataSet($ds){
        $dsO = new DataServices();
        $param[0] = "";
        $ds->SelectCommand = $dsO->ExecuteDS("ObtMateriasDB", $param);        
        $param = null;
        $ds->InsertCommand = $dsO->ExecuteDS("insMateriaGrid", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("actMateriaGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("delMateriaGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }
       
}