<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catDocumentosDB {

    //method declaration
    public function ObtCatDocumentosDB($activo, $expedienteId = ""){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();

        if($activo > 0){
            $query[] = " activo=$activo ";
        }

        if($expedienteId > 0){
            $query[] = " casoId=$expedienteId ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }

        $result = $ds->Execute("ObtCatDocumentosDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function DocumentoPorIdDB($idCanal){
        $ds = new DataServices();
        $param[0]= $idCanal;
        $result = $ds->Execute("DocumentoPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function CrearDocumentoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("CrearDocumentoDB", $param, true);
        return $result;
    }

    /* public function ActCampoEnfermedadDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoEnfermedadDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }*/

    public function DocumentosDataSet($ds){
        $dsO = new DataServices();
        $param[0] = "";
        $ds->SelectCommand = $dsO->ExecuteDS("ObtCatDocumentosDB", $param);
        $param = null;
        $ds->InsertCommand = $dsO->ExecuteDS("insDocumentoGrid", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("actDocumentoGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("delDocumentoGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }

}