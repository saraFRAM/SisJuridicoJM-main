<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class tareasDB {

    //method declaration
    public function ObtTareasDB($casoId = 0, $padreTareaId = 0, $estatus = '', $fecha = '', $desde = '', $hasta = '', $orderBy = ''){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();

        if($casoId > 0){
            $query[] = " casoId=$casoId ";
        }
        //padreTareaId
        if($padreTareaId >= 0){
            $query[] = " padreTareaId=$padreTareaId ";
        }else{
            $query[] = " padreTareaId=-1 ";
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

        $result = $ds->Execute("ObtTareasDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function TareasPorIdDB($id){
        $ds = new DataServices();
        $param[0]= $id;
        $result = $ds->Execute("TareasPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function CrearTareaDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("CrearTareaDB", $param, true);
        return $result;
    }

    public function EditarTareaDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("EditarTareaDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function ActCampoTareaDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoTareaDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function TareasDataSet($ds, $tipoId,$usuarioId,$esHistorico){
        $dsO = new DataServices();
        $param[0] = "";
        // $param[1] = "";
        $param[2] = "";

        // if($casoId > 0){
            // $query[] = " casoId=$casoId ";
            $query[] = " padreTareaId=0 ";

            // $query2[] = " casoId=$casoId ";
            $query2[] = " padreTareaId=0 ";
            $query2[] = " estatusId=4 ";
        // }
        if($tipoId == ""){
            $query[] = " tipo<100 ";
        }else{
            $query[] = " tipo IN($tipoId) ";
        }

        if($usuarioId != 0){
            $query[] = " A.responsableId=".$usuarioId;
        }

        if($esHistorico){
            $query[] = " A.estatusId=4 ";
        }else{
            $query[] = " A.estatusId<>4 ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }

        if(count($query2) > 0){
            $wordWhere = " WHERE ";
            $setWhere = implode(" AND ", $query);
            // echo $setWhere;
            // $param[1] = $wordWhere.$setWhere;
        }

        $param[2] = " ORDER BY idTarea ";


        $ds->SelectCommand = $dsO->ExecuteDS("ObtTareasGridDB", $param);
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
        
        
        $query[] = " A.padreTareaId=0 ";
        

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[1] = $wordWhere.$setWhere;
        }

        $ds->SelectCommand = $dsO->ExecuteDS("GridTareasActividadesDB", $param);
        $dsO->CloseConnection();

        return $ds;
    }

    public function EliminarTareaDB($idTarea){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();

        if($idTarea > 0){
            $query[] = " idTarea=$idTarea ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }

        $result = $ds->Execute("EliminarTareaDB", $param);
        $ds->CloseConnection();

        return $result;
    }

}