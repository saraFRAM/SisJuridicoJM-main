<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class audioDB {

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

    public function CrearAudioDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("CrearAudioDB", $param, true);
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

    public function AudiosDataSet($ds, $casoId, $titular){
        $dsO = new DataServices();
        $param[0] = $casoId;
        
        if(!$titular){
            $query="AND audienciaTipo = 1";
            $param[1] = $query;          
        }else{
            $query="AND audienciaTipo = 0";
            $param[1] = $query;
        }

        $ds->SelectCommand = $dsO->ExecuteDS("ObtenerAudiosGridDB", $param);
        $param = null;
        $ds->UpdateCommand = $dsO->ExecuteDS("ActualizarAudiosGridDB", $param);
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

    public function EliminarNotaDB($idNota){
        
        $ds = new DataServices();
        $param[0] = $idNota;
        $result = $ds->Execute("EliminarNotaDB", $param, true);
        return $result;
    }

}