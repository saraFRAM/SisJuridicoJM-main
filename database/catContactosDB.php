<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catContactosDB {

    //method declaration
    public function ObtContactosDB($activo, $expedienteId = 0){
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

        $result = $ds->Execute("ObtContactosDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function ContactoPorIdDB($id){
        $ds = new DataServices();
        $param[0]= $id;
        $result = $ds->Execute("ContactoPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function insertContactoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("insertContactoDB", $param, true);
        return $result;
    }

    public function EditarContactoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("EditarContactoDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    
    public function ActCampoContactoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoContactoDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function ContactosDataSet($ds){
        $dsO = new DataServices();
        $param[0] = "";
        $ds->SelectCommand = $dsO->ExecuteDS("ObtContactosDB", $param);        
        $param = null;
        $ds->InsertCommand = $dsO->ExecuteDS("insContactoGrid", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("actContactoGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("delContactoGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }
       
}