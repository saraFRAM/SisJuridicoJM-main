<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catDistritosDB {

    //method declaration
    public function ObtDistritosDB($activo){
        $ds = new DataServices();
        $param[0] = "";
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

        $result = $ds->Execute("ObtDistritosDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function DistritoPorIdDB($id){
        $ds = new DataServices();
        $param[0]= ($id > 0)?$id:0;
        $result = $ds->Execute("DistritoPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function insertDistritoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("insertDistritoDB", $param, true);
        return $result;
    }
    
    public function ActCampoDistritoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoDistritoDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function DistritosDataSet($ds){
        $dsO = new DataServices();
        $param[0] = "";
        $ds->SelectCommand = $dsO->ExecuteDS("ObtDistritosDB", $param);        
        $param = null;
        $ds->InsertCommand = $dsO->ExecuteDS("insDistritoGrid", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("actDistritoGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("delDistritoGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }
       
}