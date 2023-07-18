<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catComunicadosDB {

    //method declaration
    public function ObtComunicadosDB($activo, $materiaId = "", $campoOrder = ""){
        $ds = new DataServices();
        $param[0] = "";
        $param[1] = "";
        $query = array();
        
        if($activo > 0){
            $query[] = " activo=$activo ";
        }
// echo "pi: ".$materiaId;
        // if($materiaId > 0){
        //     $query[] = " materiaId IN($materiaId) ";
        // }
        // if($materiaId === ""){
        //     $query[] = " materiaId=0 ";
        // }

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

        $result = $ds->Execute("ObtComunicadosDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function ComunicadoPorIdDB($id){
        $ds = new DataServices();
        $param[0]= ($id > 0)?$id:0;
        $result = $ds->Execute("ComunicadoPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function insertComunicadoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("insertComunicadoDB", $param, true);
        return $result;
    }
    
    public function ActCampoComunicadoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoComunicadoDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function ComunicadosDataSet($ds){
        $dsO = new DataServices();
        $param[0] = "";
        $ds->SelectCommand = $dsO->ExecuteDS("ObtComunicadosDB", $param);        
        $param = null;
        $ds->InsertCommand = $dsO->ExecuteDS("insComunicadoGrid", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("actComunicadoGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("delComunicadoGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }
       
}