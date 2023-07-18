<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catJuiciosDB {

    //method declaration
    public function ObtJuiciosDB($activo, $materiaId = "", $campoOrder = ""){
        $ds = new DataServices();
        $param[0] = "";
        $param[1] = "";
        $query = array();
        
        if($activo > 0){
            $query[] = " activo=$activo ";
        }
// echo "pi: ".$materiaId;
        if($materiaId > 0){
            $query[] = " materiaId IN($materiaId) ";
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

        $result = $ds->Execute("ObtJuiciosDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function JuicioPorIdDB($id){
        $ds = new DataServices();
        $param[0]= ($id > 0)?$id:0;
        $result = $ds->Execute("JuicioPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function insertJuicioDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("insertJuicioDB", $param, true);
        return $result;
    }
    
    public function ActCampoJuicioDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoJuicioDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function JuiciosDataSet($ds){
        $dsO = new DataServices();
        $param[0] = "";
        $ds->SelectCommand = $dsO->ExecuteDS("ObtJuiciosDB", $param);        
        $param = null;
        $ds->InsertCommand = $dsO->ExecuteDS("insJuicioGrid", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("actJuicioGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("delJuicioGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }
       
}