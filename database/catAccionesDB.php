<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catAccionesDB {

    //method declaration
    public function ObtAccionesDB($activo, $materiaId = "", $campoOrder = ""){
        $ds = new DataServices();
        $param[0] = "";
        $param[1] = "";
        $query = array();
        
        if($activo > 0){
            $query[] = " activo=$activo ";
        }
// echo "pi: ".$materiaId;
        if($materiaId > 0){
            $query[] = " materiaId=$materiaId ";
        }
        if($materiaId === ""){
            $query[] = " materiaId=0 ";
        }

    if($campoOrder != ""){
        $param[1] = " ORDER BY $campoOrder";
    }
               
        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }

        $result = $ds->Execute("ObtAccionesDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function AccionPorIdDB($id){
        $ds = new DataServices();
        $param[0]= $id;
        $result = $ds->Execute("AccionPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function insertAccionDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("insertAccionDB", $param, true);
        return $result;
    }
    
    public function ActCampoAccionDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoAccionDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function AccionesDataSet($ds){
        $dsO = new DataServices();
        $param[0] = "";
        $ds->SelectCommand = $dsO->ExecuteDS("ObtAccionesDB", $param);        
        $param = null;
        $ds->InsertCommand = $dsO->ExecuteDS("insAccionGrid", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("actAccionGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("delAccionGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }
       
}