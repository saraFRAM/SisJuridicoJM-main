<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class comunicadosDB {

  //obtiene todos los datosUsuario
  public function GetallComunicados($activo, $tipoOpc){
      $ds = new DataServices();
      $query = array();
      $param[0] = "";

      if($activo>0){
        $query[] = " activo=$activo ";
      }
      if($tipoOpc!=""){
        $query[] = " opcTipo=$tipoOpc ";
      }
      /*
      $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
      $dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual

      $query[] = " '$dateTime' BETWEEN CONCAT(fechaPublicacion, ' 00:00:00') AND CONCAT(fechaDespublicacion, ' 23:59:59') ";
      */

      //En caso de llevar filtro
      if(count($query) > 0){
        $wordWhere = " WHERE ";
        $setWhere = implode(" AND ", $query);
        // echo $setWhere;
        $param[0] = $wordWhere.$setWhere;
      }

      $result = $ds->Execute("GetallComunicados", $param);
      return $result;
  }

  //Otener tarea por id
  public function obtComunicadoPorIdDB($id)
  {
      $ds = new DataServices();
      $param[0]= $id;
      $result = $ds->Execute("obtComunicadoPorIdDB", $param);
      $ds->CloseConnection();

      return $result;
  }

  //Inserta informacion
  public function insertComunicadoDB($param){
      $ds = new DataServices();
      $result = $ds->Execute("insertComunicadoDB", $param, true);
      $ds->CloseConnection();
      return $result;
  }


  //Delete informacion
  public function deleteComunicadosDB($param){
      $ds = new DataServices();
      $result = $ds->Execute("deleteComunicadosDB", $param, false, true);
      $ds->CloseConnection();
      return $result;
  }

  public function updateComunicadoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("updateComunicadoDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
  }

  //actualiza campo
  public function updateComunicadoCampoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("updateComunicadoCampoDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
  }

  public function comunicadosDataSet($ds)
  {
      $dsO = new DataServices();
      // $param[0] = "";
      $param = null;
      $ds->SelectCommand = $dsO->ExecuteDS("getComunicadoForGrid", $param);
      // $param = null;
      // $ds->UpdateCommand = $dsO->ExecuteDS("updateComunicadoGrid", $param);
      // $ds->InsertCommand = $dsO->ExecuteDS("insertComunicadoGrid", $param);
      // $ds->DeleteCommand = $dsO->ExecuteDS("deleteComunicadoGrid", $param);
      $dsO->CloseConnection();

      return $ds;
  }
}