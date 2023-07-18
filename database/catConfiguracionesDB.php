<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catConfiguracionesDB {

    //Obtener coleccion de historial por rango de fecha
    public function ObtTodasConfiguracionesDB($fDel, $fAl){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();        

        if($fDel!=""){
          $query[] = " ( fechaCreacion >= '$fDel 00:00:00' AND  fechaCreacion <= '$fAl 23:59:59' ) ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;

          $param[0] = $wordWhere.$setWhere;          
        }
        $param[1] = " ORDER BY fechaCreacion DESC ";
        
        $result = $ds->Execute("ObtTodasConfiguracionesDB", $param);
        $ds->CloseConnection();
        return $result;
    }
    
    public function ConfiguracionByID($idComunicado){
        $ds = new DataServices();
        $param[0]= $idComunicado;
        $result = $ds->Execute("ConfiguracionByID", $param);
        $ds->CloseConnection();

        return $result;
    }
    
    public function insConfiguracionDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("insConfiguracionDB", $param, true);

        return $result;
    }
    
    
  public function updateConfiguracionDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("updateConfiguracionDB", $param, false, true);

        return $result;
    }
    
    public function ConfigsSet($ds){
        $dsO = new DataServices();
        $param[0] = "";
        $query = array();        

        $query[] = " idConfiguracion IN (1,3,5) ";

//        if($fDel!=""){
//          $query[] = " ( fechaCreacion >= '$fDel 00:00:00' AND  fechaCreacion <= '$fAl 23:59:59' ) ";
//        }
//        if($activo != ""){
//          $query[] = " activo=$activo ";
//        }
       // if($empresaId != ""){
       //   $query[] = " empresaId=$empresaId ";
       // }
//        
//        //En caso de llevar filtro
       if(count($query) > 0){
         $wordWhere = " WHERE ";
         $setWhere = implode(" AND ", $query);
         // echo $setWhere;

         $param[0] = $wordWhere.$setWhere;          
       }
//        $param[1] = " ORDER BY fechaCreacion DESC ";
        
        $ds->SelectCommand = $dsO->ExecuteDS("getConfigsForGrid", $param);
        $param = null;
        $ds->UpdateCommand = $dsO->ExecuteDS("updateConfigGrid", $param);
        // $ds->DeleteCommand = $dsO->ExecuteDS("deleteConfigGrid", $param);
        // $ds->InsertCommand = $dsO->ExecuteDS("insertConfigGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }


}
