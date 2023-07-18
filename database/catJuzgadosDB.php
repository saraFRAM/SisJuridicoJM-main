<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catJuzgadosDB {

    //method declaration
    public function ObtJuzgadosDB($activo, $distritoId = "", $campoOrder = ""){
        $ds = new DataServices();
        $param[0] = "";
        $param[1] = "";
        $query = array();
        
        if($activo > 0){
            $query[] = " activo=$activo ";
        }

        
        if($distritoId > 0){
            $query[] = " distritoId=$distritoId ";
        }

        if($distritoId === ""){
            $query[] = " distritoId=0 ";
        }
        
        //En caso de llevar filtro
        if(count($query) > 0){
            $wordWhere = " WHERE ";
            $setWhere = implode(" AND ", $query);
            // echo $setWhere;
            $param[0] = $wordWhere.$setWhere;
        }
        
        if($campoOrder != ""){
            $param[1] = " ORDER BY $campoOrder";
        }

        $result = $ds->Execute("ObtJuzgadosDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function JuzgadoPorIdDB($id){
        $ds = new DataServices();
        $param[0]= ($id > 0)?$id:0;
        $result = $ds->Execute("JuzgadoPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function insertJuzgadoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("insertJuzgadoDB", $param, true);
        return $result;
    }
    
    public function ActCampoJuzgadoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoJuzgadoDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function JuzgadosDataSet($ds){
        $dsO = new DataServices();
        $param[0] = "";
        $ds->SelectCommand = $dsO->ExecuteDS("ObtJuzgadosDB", $param);        
        $param = null;
        $ds->InsertCommand = $dsO->ExecuteDS("insJuzgadoGrid", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("actJuzgadoGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("delJuzgadoGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }
       
}