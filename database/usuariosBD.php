<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class usuariosBD {
    //method declaration
    public function LoginUser($email, $password){
        $ds = new DataServices();
        $param[0]= $email;
        $param[1]= $password;

        $result = $ds->Execute("LoginUser", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function UserByID($idUser){
        $ds = new DataServices();
        $param[0]= $idUser;
        $result = $ds->Execute("UserByID", $param);
        $ds->CloseConnection();

        return $result;
    }

    //Otener todos los usuarios
    public function obtGastosUsuarioDB($rolIds = "", $idAbogado = "", $desde = "", $hasta = ""){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();

        $query[] = " A.activo = 1 ";
        
        if($rolIds !== ""){
            $query[] = " A.idRol IN('$rolIds') ";
        }
        if($idAbogado > 0){
            $query[] = " A.idUsuario=$idAbogado ";
        }

        if($desde !== ""){
            // echo "desde;";
            $param[0]  = " AND B.fecha BETWEEN '$desde' AND '$hasta' ";
            $param[1]  = " AND B.fecha BETWEEN '$desde' AND '$hasta' ";
            $param[2]  = " AND B.fecha BETWEEN '$desde' AND '$hasta' ";
            $param[3]  = " AND B.fecha BETWEEN '$desde' AND '$hasta' ";
        }
        
        //En caso de llevar filtro
        if(count($query) > 0){
            $wordWhere = " WHERE ";
            $setWhere = implode(" AND ", $query);
            // echo $setWhere;
            $param[4] = $wordWhere.$setWhere;
        }
        
        $result = $ds->Execute("getGastosForGrid", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function UsersDataSet($ds, $rolIds = "", $idAbogado = "", $desde = "", $hasta = "", $isGastos=False){
        $dsO = new DataServices();
        $param[0] = "";
        // echo "aqui;";
        $query = array();
        if($isGastos){
            $query[] = " A.activo = 1 "; //LDAH IMP 23/08/2022 Grid gastos solo activos
        }
        
        if($rolIds !== ""){
            $query[] = " A.idRol IN('$rolIds') ";
        }
        if($idAbogado > 0){
            $query[] = " A.idUsuario=$idAbogado ";
        }

        if($desde !== ""){
            // echo "desde;";
            $param[0]  = " AND B.fecha BETWEEN '$desde' AND '$hasta' ";
            $param[1]  = " AND B.fecha BETWEEN '$desde' AND '$hasta' ";
            $param[2]  = " AND B.fecha BETWEEN '$desde' AND '$hasta' ";
            $param[3]  = " AND B.fecha BETWEEN '$desde' AND '$hasta' ";

            //En caso de llevar filtro
            if(count($query) > 0){
                $wordWhere = " WHERE ";
                $setWhere = implode(" AND ", $query);
                // echo $setWhere;
                $param[4] = $wordWhere.$setWhere;
            }
            
            // $dsO->ExecuteDS("IdiomaEspanol", null);
            $ds->SelectCommand = $dsO->ExecuteDS("getGastosForGrid", $param);
        }else{
            //En caso de llevar filtro
            if(count($query) > 0){
              $wordWhere = " WHERE ";
              $setWhere = implode(" AND ", $query);
              // echo $setWhere;
              $param[0] = $wordWhere.$setWhere;
            }
    
            $ds->SelectCommand = $dsO->ExecuteDS("getUsersForGrid", $param);
        }

        
        $param = null;
        // $ds->UpdateCommand = $dsO->ExecuteDS("updateUserGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("deleteUserGrid", $param);
        // $ds->InsertCommand = $dsO->ExecuteDS("insertUserGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }
    //Otener todos los usuarios
    public function obtTodosUsuariosDB($idRol, $idAbogado = "", $order = "", $activos = false){
        $ds = new DataServices();
        $param[0] = "";
        $param[1] = "";
        $query = array();

        
        if($idAbogado != "" && $idRol != ""){
            // echo "id abnogado ".$idAbogado;
            $query[] = " (A.idRol IN( $idRol ) OR A.idUsuario IN(". $idAbogado . ") )" ;
        }
        else if($idRol != ""){
            $query[] = " A.idRol IN( $idRol )";//JGP cambio por IN para permtir separado por comas
        }elseif($idAbogado != ""){
            $query[] = " A.idUsuario IN(". $idAbogado . ")";
        }

        

        if($activos){
            $query[] = " A.activo=1";
        }
        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }

        //Jair 13/4/2022 Ordenar
        if($order != ""){
            $param[1] = " ORDER BY $order ";
        }

        $result = $ds->Execute("obtTodosUsuariosDB", $param);
        $ds->CloseConnection();
        return $result;
    }
    public function UserByEmailDB($email){
        $ds = new DataServices();
        $param[0]= $email;
        $result = $ds->Execute("UserByEmailDB", $param);
        $ds->CloseConnection();
        return $result;
    }

  
    public function insertUsuarioDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("insertUsuarioDB", $param, true);
        return $result;
    }
    public function updateUsuarioDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("updateUsuarioDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function actualizarUsuarioDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("actualizarUsuarioDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    // //Recuperar contrasenia por su email


    // //Recuperar datos por numero de contrato y email
    // public function UserByContractEmailDB($contrat, $email)
    // {
    //     $ds = new DataServices();
    //     $param[0]= $contrat;
    //     $param[1]= $email;

    //     $result = $ds->Execute("UserByContractEmailDB", $param);
    //     $ds->CloseConnection();
    //     return $result;
    // }
    //Obtener usuarios por rol
    public function obtUsuariosByNomEmailDB($dato, $campo){
        $ds = new DataServices();
        $param[0] = $campo;
        $param[1] = $dato;
        $result = $ds->Execute("obtUsuariosByNomEmailDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function obtUsuariosByIdRolDB($idRol, $activo, $empresaId, $esDirector){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();

        if($idRol != ""){
            $query[] = " idRol IN($idRol) ";
        }

        if($activo !== ""){
            $query[] = " activo=$activo ";
        }

        if($empresaId != ""){
            $query[] = " B.empresaId IN ($empresaId)";
        }

        if($esDirector != ""){
            $query[] = " B.esDirector IN ($esDirector) ";
        }
        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }
        $result = $ds->Execute("obtUsuariosByIdRolDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function deleteUsuarioDB($param){
      $ds = new DataServices();
      $result = $ds->Execute("deleteUsuarioDB", $param, false, true);
      $ds->CloseConnection();
      return $result;
    }

    // //Obtener ids de usuarios X numero de contrato
    // public function ObtIdsUsuarioXNoContratoDB($noContratos)
    // {
    //   $ds = new DataServices();
    //   $param[0]= $noContratos;

    //   $result = $ds->Execute("ObtIdsUsuarioXNoContratoDB", $param);
    //   $ds->CloseConnection();
    //   return $result;
    // }

}