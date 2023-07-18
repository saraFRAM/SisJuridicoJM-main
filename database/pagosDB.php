<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class pagosDB {

    //method declaration
    public function ObtPagosDB($cuentaId = "", $tipo = 1){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();

        if($cuentaId > 0){
            $query[] = " cuentaId=$cuentaId ";
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

        $result = $ds->Execute("ObtPagosDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function PagoPorIdDB($id){
        $ds = new DataServices();
        $param[0]= $id;
        $result = $ds->Execute("PagoPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function CrearPagoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("CrearPagoDB", $param, true);
        return $result;
    }

    public function EditarPagoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("EditarPagoDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    /* public function ActCampoEnfermedadDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoEnfermedadDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }*/

    public function PagosDataSet($ds, $idCuenta = 0, $tipo = 1){
        $dsO = new DataServices();
        $param[0] = ($idCuenta !== "")?" WHERE cuentaId=$idCuenta AND tipo=$tipo ":"";
        $ds->SelectCommand = $dsO->ExecuteDS("ObtPagosDB", $param);
        $param = null;
        $ds->InsertCommand = $dsO->ExecuteDS("insPagoGrid", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("actPagoGrid", $param);
        // $ds->DeleteCommand = $dsO->ExecuteDS("delPagoGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }

}