<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/usuariosBD.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';
include_once  $dirname.'/brules/rolesObj.php';
include_once  $dirname.'/brules/generalConsultObj.php';

class usuariosObj extends configuracionesGridObj{
    private $_idUsuario = 0;
    private $_idRol = 0;
    private $_nombre = '';
    private $_email = '';
    private $_password = '';
    private $_activo = 0;
    private $_permisohistorico = 0;
    private $_fechaCreacion = '0000-00-00 00:00:00';
    private $_fechaAct = '';
    //extras
    private $_usuarioActivo = '';
    private $_nombreImg = '';
    // private $_editcol = '';
    private $_saldo = 0;
    private $_numAbogado = '';
    private $_titularTodos = 0;
    private $_coordinadorId = 0;
    private $_camposGridExp = '';

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    public function GetAllData($activo){
      $generalConsultObj = new generalConsultObj();
      $data=$generalConsultObj->GetAllData("usuarios",$activo);
      return $data;
    }

   public function permisoH($permisohistorico){
       $generalConsultObj = new generalConsultObj();
        $data=$generalConsultObj->GetAllData("usuarios",$permisohistorico);
       return $data;
    }

    public function actualizar($campo,$valor,$id){
      $generalConsultObj = new generalConsultObj();
      $data=$generalConsultObj->Actualizar("usuarios",$campo,$valor,"idUsuario",$id);
      return $data;
    }

    public function Eliminar($id){
      $generalConsultObj = new generalConsultObj();
      $data=$generalConsultObj->Eliminar("usuarios","idUsuario",$id);
      return $data;
    }


    //logueo de usuario
    public function LoginUser($email, $password){
        $usrDS = new usuariosBD();
        $datosBD = new datosBD();
        $obj =  new usuariosObj();
        $result = $usrDS->LoginUser($email, $password);

        return $datosBD->setDatos($result, $obj);
    }
    //usuario por id
    public function UserByID($id){
        $usrDS = new usuariosBD();
        $obj = new usuariosObj();
        $datosBD = new datosBD();
        $result = $usrDS->UserByID($id);

        return $datosBD->setDatos($result, $obj);

    }

    //Usuario por email (para recupera la contrasenia)
    public function UserByEmail($email){
        $usrDS = new usuariosBD();
        $obj = new usuariosObj();
        $datosBD = new datosBD();
        $result = $usrDS->UserByEmailDB($email);
        return $datosBD->setDatos($result, $obj);
    }

    public function GetGastosGrid($rolIds = "4", $idAbogado = "", $desde = "", $hasta = ""){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new usuariosBD();
        $ds = $uDB->UsersDataSet($ds, $rolIds, $idAbogado, $desde, $hasta,true);
        $grid = new KoolGrid("gastosGrid");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idUsuario", "ID", false, true);
        $configGrid->defineColumn($grid, "numAbogado", "Num abogado", true, false, 1, "");
        $configGrid->defineColumn($grid, "nombre", "Nombre", true, false, 1, "");
        $configGrid->defineColumn($grid, "saldo", "Saldo Historico", true, false, 1, "");
        $configGrid->defineColumn($grid, "ultimaEntrada", "Ult. Entrada", true, false, 1, "");
        $configGrid->defineColumn($grid, "ultimaSalida", "Ult. Salida", true, false, 1, "");
        $configGrid->defineColumn($grid, "totalAbonos", "Entradas", true, false, 1, "");
        $configGrid->defineColumn($grid, "totalCargos", "Salidas", true, false, 1, "");
        // $configGrid->defineColumn($grid, "saldoPeriodo", "Saldo Periodo", true, false, 1, "");
        // if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumnEdit($grid);
        // }

        //pocess grid
        $grid->Process();

        return $grid;
    }


    //region
    //Grid usuarios
     public function GetUsersGrid($rolIds = "", $idAbogado = "", $desde = "", $hasta = ""){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new usuariosBD();
        $ds = $uDB->UsersDataSet($ds, $rolIds, $idAbogado, $desde, $hasta);
        $grid = new KoolGrid("usuariosGrid");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idUsuario", "ID", false, true);
        $configGrid->defineColumn($grid, "numAbogado", "Num abogado", true, false, 1, "");
        $configGrid->defineColumn($grid, "idRol", "Rol", true, false, 1, "");
        $configGrid->defineColumn($grid, "nombre", "Nombre", true, false, 1, "");
        $configGrid->defineColumn($grid, "email", "Correo Electr&oacute;nico", true, false, 1, "EMAIL");
        $configGrid->defineColumn($grid, "password", "Contraseña", true, false, 1, "");
        //$configGrid->defineColumn($grid, "usuarioActivo", "Activo", true, true, 1, "", "60px");
        //$configGrid->defineColumn($grid, "editcol", "", true, true, 1, "", "40px");
        $configGrid->defineColumn($grid, "activo", "Activo", true, true, 1, "", "80px");
        $configGrid->defineColumn($grid, "permisohistorico", "permisohistorico", true, true, 1, "", "80px");
        // if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumnEdit($grid);
        // }

        //pocess grid
        $grid->Process();

        return $grid;
    }

    public function GuardarUsuario(){
        $objDB = new usuariosBD();
        $this->_idUsuario = $objDB->insertUsuarioDB($this->getParams());
    }

    public function EditarUsuario(){
        $objDB = new usuariosBD();
        return $objDB->actualizarUsuarioDB($this->getParams(true));
    }

    private function getParams($update = false){
        $dateByZone = obtDateTimeZone();
        $this->_fechaCreacion = $dateByZone->fechaHora;
        $this->_fechaAct = $dateByZone->fechaHora;
        //IMP 30/03/2023, se agrego intval() a las variables necesarias para que los valores vayan en el formato adecuado 
        $idRol = intval($this->_idRol);
        $activo = intval($this->_activo);
        $permisohistorico = intval($this->_permisohistorico);
        $numAbogado = intval($this->_numAbogado);
        $coordinadorId = intval($this->_coordinadorId);
        $idUsuario = intval($this->_idUsuario);
        $param[0] = $idRol;
        $param[1] = $this->_nombre;
        $param[2] = $this->_email;
        $param[3] = $this->_password;
        $param[4] = $activo;
        $param[5] = $permisohistorico;
        $param[6] = $numAbogado;
        $param[7] = $coordinadorId;
        

        
        if(!$update){
            $param[8] = $this->_fechaCreacion;
        }
        else{
            $param[8] = $this->_fechaAct;
            $param[9] = $idUsuario;
        }
        return $param;
    }
    public function ActualizarUsuario($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new usuariosBD();
        $resAct = $objDB->updateUsuarioDB($param);
        return $resAct;
    }

    public function EliminarUsuario($idUsuario){
        $objDB = new usuariosBD();
        $param[0] = $idUsuario;
        return $objDB->deleteUsuarioDB($param);
    }
    public function usuariosinactivosDB($numAbogado){
        $ds = new DataServices();
        $result = $ds->Execute("usuariosinactivosDB", $numAbogado, true);
        return $result;
    }

    //Obtener todos los usuarios
    public function obtTodosUsuarios($opcObj=true, $idRol = "", $idAbogado = "", $order = "", $activos=false){
        $array = array();
        $ds = new usuariosBD();
        $datosBD = new datosBD();
        $result = $ds->obtTodosUsuariosDB($idRol, $idAbogado, $order, $activos);

        if($opcObj==true){
           $array = (array)$datosBD->arrDatosObj($result);
            // $array = (array)arrDatosObj($result);
        }else{
           $array = $datosBD->arrDatosObj($result);
           // $array = arrDatosObj($result);
        }
        return $array;
    }

    public function obtUsuariosByIdRol($idRol= "", $activo=1, $empresaId = "", $esDirector = ""){
        $array = array();
        $ds = new usuariosBD();
        $datosBD = new datosBD();

        $result = $ds->obtUsuariosByIdRolDB($idRol, $activo, $empresaId, $esDirector);
        $array = $datosBD->arrDatosObj($result);
        return $array;
    }

    // $ds = $uDB->UsersDataSet($ds, $rolIds, $idAbogado, $desde, $hasta);
    public function obtGastosUsuario($rolIds = "4", $idAbogado = "", $desde = "", $hasta = ""){
        $array = array();
        $ds = new usuariosBD();
        $datosBD = new datosBD();

        $result = $ds->obtGastosUsuarioDB($rolIds, $idAbogado, $desde, $hasta);
        $array = $datosBD->arrDatosObj($result);
        return $array;
    }

}
