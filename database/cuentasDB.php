<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class cuentasDB {

    //method declaration
    public function ObtCuentasDB(){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();

        // if($activo > 0){
        //     $query[] = " activo=$activo ";
        // }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }

        $result = $ds->Execute("ObtCuentasDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function CuentaPorIdDB($id){
        $ds = new DataServices();
        $param[0]= $id;
        $result = $ds->Execute("CuentaPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function CuentaPorCasoIdDB($id){
        $ds = new DataServices();
        $param[0]= $id;
        $result = $ds->Execute("CuentaPorCasoIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function CrearCuentaDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("CrearCuentaDB", $param, true);
        return $result;
    }

    public function EditarCuentaDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("EditarCuentaDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

     public function ActCampoCuentaDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoCuentaDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    public function ActCampoCuentaJsonDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoCuentaJsonDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function CuentasDataSet($ds){
        $dsO = new DataServices();
        $param[0] = "";
        $ds->SelectCommand = $dsO->ExecuteDS("ObtCuentasDB", $param);
        $param = null;
        $ds->InsertCommand = $dsO->ExecuteDS("insCuentaGrid", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("actCuentaGrid", $param);
        // $ds->DeleteCommand = $dsO->ExecuteDS("delCuentaGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }

    public function EliminarCobroDB($id, $idCuenta){
        $ds = new DataServices();
        $param[0] = $id;
        $param[1] = $idCuenta;
        $result = $ds->Execute("EliminarCobro", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function EditarCuentaMesesDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("EditarCuentaDBnumMeses", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    /***
     * Obtiene todos los casos asociados a una cuenta 
     */
    public function obtCasosDecuentaDB($idCuenta){
        $ds = new DataServices();
        $param[0]= $idCuenta;
        $result = $ds->Execute("CasosDecuentaDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    /**
     * Casos sin cuenta de un cliente especifico de la BD
     */
    public function ObtCasosPorIdClienteSinCta($idCliente){
        $ds = new DataServices();
        $param[0]= $idCliente;
        $result = $ds->Execute("ObtCasosPorIdClienteSinCta", $param);
        $ds->CloseConnection();

        return $result;
    }

    /**
     * Asociar el caso a una cuenta especifica 
     */
    public function AsociarCuentaCasoDB($idCuenta, $casoId){
        $ds = new DataServices();

        $param[0]= $idCuenta;
        $param[1]= $casoId;

        $result = $ds->Execute("AsociarCuentaCasoDB", $param, true);
        $ds->CloseConnection();
        
        return $result;
    }

    /**
     * Eliminar la asociación de un caso a una cuenta especifica
     */
    public function EliminarCuentaCasoDB($idCuenta, $casoId){
        $ds = new DataServices();

        $param[0]= $idCuenta;
        $param[1]= $casoId;

        $result = $ds->Execute("EliminarCuentaCasoDB", $param, false, true);
        $ds->CloseConnection();
        
        return $result;
    }

}