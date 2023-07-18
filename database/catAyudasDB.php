<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catAyudasDB {

    //Obtener coleccion del catalogo de ayudas
    public function ObtTodosCatAyudasDB($tipo = ""){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();
        if($tipo !== ""){
            $query[] = " tipo=$tipo ";
        }
//En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }        
        
        $result = $ds->Execute("ObtTodosCatAyudasDB", $param);
        $ds->CloseConnection();
        return $result;
    }
    

    public function ObtAyudaPorAliasDB($alias){
        $ds = new DataServices();
        $param[0]= $alias;
        $result = $ds->Execute("ObtAyudaPorAliasDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function updateAyudaPorAliasDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("updateAyudaPorAliasDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    // Obtener ayuda por el id
    public function ObtAyudaPorIdDB($id)
    {
        $ds = new DataServices();
        $param[0]= $id;
        $result = $ds->Execute("ObtAyudaPorIdDB", $param);
        $ds->CloseConnection();
        return $result;
    }
}