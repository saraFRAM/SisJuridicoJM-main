<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class mensajesDB {

  //Obtener coleccion de historial por rango de fecha
  public function ObtTodosMensajesDB($fDel, $fAl, $idUsuario, $concepto, $mostrar, $leido, $tipoIds = '', $caducidad = ''){
      $ds = new DataServices();
      $param[0] = "";
      $query = array();

      if($fDel!=""){
        $query[] = " ( fechaCreacion >= '$fDel 00:00:00' AND  fechaCreacion <= '$fAl 23:59:59' ) ";
      }

      if($idUsuario !== ""){
          $query[] = " usuarioId=$idUsuario ";
      }

      if($concepto !== ""){
          $query[] = " idConcepto=$concepto ";
      }

      if($mostrar !== ""){
          $query[] = " mostrar=$mostrar ";
      }

      if($leido !== ""){
        $query[] = " leido=$leido ";
      }

      if($tipoIds != ""){
        $query[] = " tipo IN($tipoIds) ";
      }

      if($caducidad != ""){
        $query[] = " (fechaCaducidad>=NOW() OR fechaCaducidad IS NULL) ";
      }

      //En caso de llevar filtro
      if(count($query) > 0){
        $wordWhere = " WHERE ";
        $setWhere = implode(" AND ", $query);
        // echo $setWhere;

        $param[0] = $wordWhere.$setWhere;
      }
      $param[1] = " ORDER BY fechaCreacion DESC ";
//echo $param[0];
      $result = $ds->Execute("ObtTodosMensajesDB", $param);
      $ds->CloseConnection();
      return $result;
  }

  //Actualiza algun dato del mensaje por su id y su nombre de columna
  public function ActualizarMensajeDB($param){
    $ds = new DataServices();
    $result = $ds->Execute("ActualizarMensajeDB", $param, false, true);
    $ds->CloseConnection();
    return $result;
  }



  //obtiene todos los datosUsuario
  public function GetallMensajesObj($peticionId, $order, $concepto = "0"){
      $ds = new DataServices();
      $param[0] = "";
      $query = array();
      if($peticionId != ""){
          $query[] = " a.idReporte=$peticionId ";
      }

      if($concepto != ""){
        $query[] = " a.idConcepto='$concepto' ";
      }
//En caso de llevar filtro
      if(count($query) > 0){
        $wordWhere = " WHERE ";
        $setWhere = implode(" AND ", $query);
        // echo $setWhere;

        $param[0] = $wordWhere.$setWhere;
      }
      
      $param[1] = $order;
      $result = $ds->Execute("GetallMensajesObj", $param);
      return $result;
  }
  //obtiene todos los datosUsuario
  public function GetAllMensajesObjByRptDB($idTipoRpt,$idRpt){
      $ds = new DataServices();
      $query = "";
      $query="WHERE idConcepto=".$idTipoRpt." AND idReporte=".$idRpt;
      $param[0] = $query;
      $result = $ds->Execute("GetAllMensajesObjByRptDB", $param);
      return $result;
  }
  //Otener tarea por id
  public function obtenerMensajesObjById($id)
  {
      $ds = new DataServices();
      $param[0]= $id;
      $result = $ds->Execute("obtenerMensajesObjById", $param);
      $ds->CloseConnection();

      return $result;
  }
  //Inserta informacion
  public function insertMensajesDB($param){
      $ds = new DataServices();
      $result = $ds->Execute("insertMensajesDB", $param, true);
      $ds->CloseConnection();
      return $result;
  }


  //Delete informacion
  public function deleteMensajesDB($param){
      $ds = new DataServices();
      $result = $ds->Execute("deleteMensajesDB", $param, false, true);
      $ds->CloseConnection();
      return $result;
  }
}
