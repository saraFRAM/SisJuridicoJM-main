<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catMetodosDB {

    //method declaration
    public function ObtMetodosDB($activo, $campoOrder = ""){
        $ds = new DataServices();
        $param[0] = "";
        $param[1] = "";
        $query = array();
        
        if($activo > 0){
            $query[] = " activo=$activo ";
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

        $result = $ds->Execute("ObtMetodosDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function MetodoPorIdDB($id){
        $ds = new DataServices();
        $param[0]= ($id > 0)?$id:0;
        $result = $ds->Execute("MetodoPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function insertMetodoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("insertMetodoDB", $param, true);
        return $result;
    }
    
    public function ActCampoMetodoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoMetodoDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function MetodosDataSet($ds){
        $dsO = new DataServices();
        $param[0] = "";
        $ds->SelectCommand = $dsO->ExecuteDS("ObtMetodosDB", $param);        
        $param = null;
        $ds->InsertCommand = $dsO->ExecuteDS("insMetodoGrid", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("actMetodoGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("delMetodoGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }
       
}