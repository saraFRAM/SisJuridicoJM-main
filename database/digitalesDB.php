<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class digitalesDB {

    //method declaration
    public function ObtDigitalesDB($casoId = 0, $tipo = 0, $estatus = '', $fecha = '', $desde = '', $hasta = '', $orderBy = ''){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();

        if($casoId > 0){
            $query[] = " casoId=$casoId ";
        }

        if($tipo > 0){
            $query[] = " tipo=$tipo ";
        }
       

        if($estatus != ''){
            $query[] = " estatusId IN($estatus) ";
        }

        if($fecha != '' && $desde != '' && $hasta != ''){
            $query[] = " $fecha BETWEEN '$desde' AND '$hasta' ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }

        if($orderBy != ''){
            $param[1] = " ORDER BY $orderBy ";
        }

        $result = $ds->Execute("ObtDigitalesDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function DigitalesPorIdDB($id){
        $ds = new DataServices();
        $param[0]= $id;
        $result = $ds->Execute("DigitalesPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function CrearDigitalDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("CrearDigitalDB", $param, true);
        return $result;
    }

    public function EditarDigitalDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("EditarDigitalDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function ActCampoDigitalDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoDigitalDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function DigitalesDataSet($ds, $casoId,$usuarioId,$esHistorico){
        $dsO = new DataServices();
        $param[0] = "";
        // $param[1] = "";
        // $param[2] = "";

        if($casoId > 0){
            $query[] = " casoId=$casoId ";
            // $query[] = " padreDigitalId=0 ";

            // $query2[] = " casoId=$casoId ";
            // $query2[] = " padreDigitalId=0 ";
            // $query2[] = " estatusId=4 ";
        }

        // if($usuarioId != 0){
        //     $query[] = " A.responsableId=".$usuarioId;
        // }

        // if($esHistorico){
        //     $query[] = " A.estatusId=4 ";
        // }else{
        //     $query[] = " A.estatusId<>4 ";
        // }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }

        // if(count($query2) > 0){
        //     $wordWhere = " WHERE ";
        //     $setWhere = implode(" AND ", $query);
        //     // echo $setWhere;
        //     // $param[1] = $wordWhere.$setWhere;
        // }

        // $param[2] = " ORDER BY idDigital ";


        $ds->SelectCommand = $dsO->ExecuteDS("ObtDigitalesGridDB", $param);
        $dsO->CloseConnection();

        return $ds;
    }

    public function ActividadesDataSet($ds, $usuarioId,$rolid){
        $dsO = new DataServices();
        $param[0] = "";
        $param[1] = "";

        if($usuarioId > 0){
            $param[0] = ' , IF(A.usuarioId!='.$usuarioId.', "*", "") AS ajeno ';
        }
// echo $param[0];
        if($usuarioId > 0 and $rolid <> 1){
            $query[] = " (A.usuarioId=$usuarioId || A.responsableId=$usuarioId) ";
            $query[] = " A.estatusId<4 ";//Rol 1 debe ver también los terminados
        }
        
        
        $query[] = " A.padreDigitalId=0 ";
        

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[1] = $wordWhere.$setWhere;
        }

        $ds->SelectCommand = $dsO->ExecuteDS("GridDigitalesActividadesDB", $param);
        $dsO->CloseConnection();

        return $ds;
    }

    public function EliminarDigitalDB($idDigital){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();

        if($idDigital > 0){
            $query[] = " idDocumento=$idDigital ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }

        $result = $ds->Execute("EliminarDigitalDB", $param);
        $ds->CloseConnection();

        return $result;
    }

}