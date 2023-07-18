<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catBancosDB {

    //method declaration
    public function ObtBancosDB($activo, $campoOrder = ""){
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

        $result = $ds->Execute("ObtBancosDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function BancoPorIdDB($id){
        $ds = new DataServices();
        $param[0]= ($id > 0)?$id:0;
        $result = $ds->Execute("BancoPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function insertBancoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("insertBancoDB", $param, true);
        return $result;
    }
    
    public function ActCampoBancoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoBancoDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function BancosDataSet($ds){
        $dsO = new DataServices();
        $param[0] = "";
        $ds->SelectCommand = $dsO->ExecuteDS("ObtBancosDB", $param);        
        $param = null;
        $ds->InsertCommand = $dsO->ExecuteDS("insBancoGrid", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("actBancoGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("delBancoGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }
       
}