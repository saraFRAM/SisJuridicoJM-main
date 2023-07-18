<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class comentariosDB {

    //Obtener coleccion de historial por rango de fecha
    public function ObtTodasComentariosDB($usuarioId = "", $casoId = "", $desde = "", $hasta = "", $order = ""){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();        

        // if($fDel!=""){
        //   $query[] = " ( fechaCreacion >= '$fDel 00:00:00' AND  fechaCreacion <= '$fAl 23:59:59' ) ";
        // }
        if($usuarioId > 0){
            $query[] = " usuarioId=$usuarioId ";
        }
        
        if($casoId > 0){
            $query[] = " casoId=$casoId ";
        }

        if($desde != ""){
            $query[] = " fecha BETWEEN '$desde' AND '$hasta' ";
        }
        
        //En caso de llevar filtro
        if(count($query) > 0){
            $wordWhere = " WHERE ";
            $setWhere = implode(" AND ", $query);
            // echo $setWhere;
            
            $param[0] = $wordWhere.$setWhere;          
        }
        $param[1] = " $order ";
        
        $result = $ds->Execute("ObtTodasComentariosDB", $param);
       
        $ds->CloseConnection();
        return $result;
    }

    public function ComentarioPorIdDB($id){
        $ds = new DataServices();
        $param[0]= $id;
        $result = $ds->Execute("ComentarioPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function fechasGastosDB(){
        $ds = new DataServices();
        $param = null;
        $ds->Execute("IdiomaEspanol", $param);
        $result = $ds->Execute("fechasGastosDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function ObtComentarioByCondominoYMensualidadDB($condominoId = 0, $fechaMensualidad = ''){
        $ds = new DataServices();
        $param[0]= $condominoId;
        $param[1]= $fechaMensualidad;
        $result = $ds->Execute("ObtComentarioByCondominoYMensualidadDB", $param);
        $ds->CloseConnection();
        return $result;
    }


    public function obtenerFechaMensualidadDB($condominoId = 0, $viviendaId = 0){
        $ds = new DataServices();
        $param[0]= $condominoId;
        $param[1]= $viviendaId;
        $result = $ds->Execute("obtenerFechaMensualidadDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function obtenerSaldoAntesDeRecargoDB($condominoId = 0){
        $ds = new DataServices();
        $param[0]= $condominoId;
        $result = $ds->Execute("obtenerSaldoAntesDeRecargoDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function obtenerUlimaMensualidadDB($condominoId = 0, $tipoId = 0){
        $ds = new DataServices();
        $param[0] = $condominoId;
        $param[1] = $tipoId;
        $result = $ds->Execute("obtenerUlimaMensualidadDB", $param);
        $ds->CloseConnection();
        return $result;
    }
    
    // public function ComentarioByPostId($idComentario)
    // {
    //     $ds = new DataServices();
    //     $param[0]= $idComentario;
    //     $result = $ds->Execute("ComentarioByPostId", $param);
    //     $ds->CloseConnection();
    //     return $result;
    // }
    
    public function insertComentarioDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("insertComentarioDB", $param, true);
        $ds->CloseConnection();
        return $result;
    }
  
    public function updateComentarioDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("updateComentarioDB", $param, true);
        $ds->CloseConnection();
        return $result;
    }
    
    public function updateImagenComentarioDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("updateImagenComentarioDB", $param, true);
        $ds->CloseConnection();
        return $result;
    }
    
     public function ComentariosSet($ds, $idFraccionamiento, $tipoId = ""){
        $dsO = new DataServices();
        $param[0] = "";
        $query = array();        

        //  if($fDel!=""){
        //    $query[] = " ( A.fechaCreacion >= '$fDel 00:00:00' AND  A.fechaCreacion <= '$fAl 23:59:59' ) ";
        //  }
        if($idFraccionamiento > 0){
          $query[] = " B.idFraccionamiento=$idFraccionamiento ";
        }

        if($tipoId > 0){
            $query[] = " A.tipoId IN($tipoId) ";
          }
        
        
        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;

          $param[0] = $wordWhere.$setWhere;          
        }
        $param[1] = " ORDER BY A.fechaCreacion DESC ";
//        echo $param[0]."<br>";
        $ds->SelectCommand = $dsO->ExecuteDS("getComentariosForGrid", $param);
        //$ds->UpdateCommand = $dsO->ExecuteDS("updateSesInvGrid", $param);
        //$ds->DeleteCommand = $dsO->ExecuteDS("deleteSesInvGrid", $param);
        //$ds->InsertCommand = $dsO->ExecuteDS("insertSesInvGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }

    public function updateCampoComentarioDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("updateCampoComentarioDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

}
