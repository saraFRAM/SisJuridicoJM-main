<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catConceptosDB {

    //method declaration
    public function ObtCatConceptosDB($activo, $tipo = ""){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();

        if($activo > 0){
            $query[] = " activo=$activo ";
        }

        if($tipo > 0){
            $query[] = " tipo=$tipo ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }

        $result = $ds->Execute("ObtCatConceptosDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function ConceptoPorIdDB($idCanal){
        $ds = new DataServices();
        $param[0]= $idCanal;
        $result = $ds->Execute("ConceptoPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function CrearConceptoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("CrearConceptoDB", $param, true);
        return $result;
    }

    /* public function ActCampoEnfermedadDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoEnfermedadDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }*/

    public function ConceptosDataSet($ds){
        $dsO = new DataServices();
        $param[0] = "";
        $ds->SelectCommand = $dsO->ExecuteDS("ObtCatConceptosDB", $param);
        $param = null;
        $ds->InsertCommand = $dsO->ExecuteDS("insConceptoGrid", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("actConceptoGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("delConceptoGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }

}