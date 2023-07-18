<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catPartesDB {

    //method declaration
    public function ObtPartesDB($activo, $campoOrder = ""){
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

        $result = $ds->Execute("ObtPartesDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function PartePorIdDB($id){
        $ds = new DataServices();
        $param[0]= ($id > 0)?$id:0;
        $result = $ds->Execute("PartePorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function insertParteDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("insertParteDB", $param, true);
        return $result;
    }
    
    public function ActCampoParteDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoParteDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function PartesDataSet($ds){
        $dsO = new DataServices();
        $param[0] = "";
        $ds->SelectCommand = $dsO->ExecuteDS("ObtPartesDB", $param);        
        $param = null;
        $ds->InsertCommand = $dsO->ExecuteDS("insParteGrid", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("actParteGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("delParteGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }
       
}