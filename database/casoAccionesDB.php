<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class casoAccionesDB {

    //method declaration
    public function ObtCasoAccionesDB($casoId = 0, $padreAccionId = 0, $estatus = '', $fecha = '', $desde = '', $hasta = '', $orderBy = '', $responsableId = 0, $FComproMayIgu = ''){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();

        if($casoId > 0){
            $query[] = " casoId=$casoId ";
        }
        //padreAccionId
        if($padreAccionId >= 0){
            $query[] = " padreAccionId=$padreAccionId ";
        }else{
            $query[] = " padreAccionId=-1 ";
        }

        if($estatus != '' && $FComproMayIgu != ''){
            $query[] = " (estatusId IN($estatus) OR fechaCompromiso >= '$FComproMayIgu') ";
        }

        if($estatus != '' && $FComproMayIgu == ''){
            $query[] = " estatusId IN($estatus) ";
        }

        if($fecha != '' && $desde != '' && $hasta != ''){
            $query[] = " $fecha BETWEEN '$desde' AND '$hasta' ";
        }

        if($responsableId > 0){
            $query[] = " responsableId=$responsableId ";
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

        $result = $ds->Execute("ObtCasoAccionesDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function CasoAccionesPorIdDB($id){
        $ds = new DataServices();
        $param[0]= $id;
        $result = $ds->Execute("CasoAccionesPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function obtenerIdPadreDB($id){
        $ds = new DataServices();
        $param[0]= $id;
        $result = $ds->Execute("CasoAccionesObtenerIdPadre", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function CrearCasoAccionDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("CrearCasoAccionDB", $param, true);
        return $result;
    }
    public function EliminarCitaDB($idAccion){
        $ds = new DataServices();
        $param[0] = $idAccion;
        $result = $ds->Execute("EliminarCitaDB", $param, true);
        return $result;
    }

    public function EliminarCitaSerieDB($id){
        $ds = new DataServices();
        $param[0] = $id;
        $result = $ds->Execute("EliminarCitaSerieDB", $param, true);
        return $result;
    }

    public function EditarCasoAccionDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("EditarCasoAccionDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function ActCampoAccionDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoAccionDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function AccionesDataSet($ds, $casoId, $idUsuario = 0, $fechaRealizado = ''){
        $dsO = new DataServices();
        $param[0] = "";
        $param[1] = "";
        // $param[2] = "";
        $param[3] = "";

        if($idUsuario > 0){
            // $param[0] = " AND usuarioId!=$idUsuario  ";
            $param[0] = "  ";//Jair 17/2/2022 Se quita condicion para contabilizar todos los comentarios
        }

        if($casoId > 0){
            $query[] = " casoId=$casoId ";
            $query[] = " padreAccionId=0 ";

            // $query2[] = " casoId=$casoId ";
            // $query2[] = " padreAccionId=0 ";
            // $query2[] = " estatusId=4 ";
        }

        if($fechaRealizado != ''){
            $query[] = " A.fechaCreacion>='$fechaRealizado' ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[1] = $wordWhere.$setWhere;
        }

        // if(count($query2) > 0){
        //     $wordWhere = " WHERE ";
        //     $setWhere = implode(" AND ", $query);
        //     // echo $setWhere;
        //     $param[2] = $wordWhere.$setWhere;
        // }

        $param[3] = " ORDER BY idAccion ";


        $ds->SelectCommand = $dsO->ExecuteDS("ObtCasoAccionesGridDB", $param);
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
            $query[] = " (A.usuarioId=$usuarioId OR C.responsableId=$usuarioId) ";
            $query[] = " A.estatusId<4 ";//Rol 1 debe ver tambien los terminados
        }
        
        
        $query[] = " A.padreAccionId=0 ";
        

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[1] = $wordWhere.$setWhere;
        }

        $ds->SelectCommand = $dsO->ExecuteDS("GridActividadesDB", $param);
        $dsO->CloseConnection();

        return $ds;
    }

    public function EliminarAccionDB($idAccion){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();

        if($idAccion > 0){
            $query[] = "WHERE idAccion=$idAccion ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }

        $result = $ds->Execute("EliminarAccionDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function ObtenerAgendaDB($tipoToSearch,$idUsuario = 0){
        $ds = new DataServices();
        
        $param[0] = " AND tipo IN ($tipoToSearch)";

        if($idUsuario > 0 && $idUsuario == 21){
            if(!isset($param[1])) $param[1] = "";//CMPB 06/04/2023 quietar error de agenda 
            $param[1] .= " AND (A.responsableId=".$idUsuario." OR B.titularId2 =".$idUsuario.")";    
        }
        else if($idUsuario > 0){
            if(!isset($param[1])) $param[1] = "";
            $param[1] .= " AND A.responsableId=".$idUsuario;
        }
        else{
           $param[1] = "";
        }
        
        $result = $ds->Execute("ObtenerAgendaDB", $param);
        $ds->CloseConnection();

        return $result;
    }
    

    public function ObtProxEventosCasoId($casoId){
        $ds = new DataServices();
        
        $param[0] = $casoId;
        
        $result = $ds->Execute("ObtProxEventosCasoId", $param);
        $ds->CloseConnection();

        return $result;
    }

}