<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/comunicadosDB.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';

class comunicadosObj extends configuracionesGridObj{
    private $_idComunicado = 0;
    private $_titulo = '';
    private $_descripcionCorta = '';
    private $_contenido = '';
    private $_urlComunicado =  '';
    private $_urlVideo = '';
    private $_imgComunicado = '';
    private $_opcVisto = 0;
    private $_vistoPor = '';
    private $_opcTipo = 0;  //0=capsulas
    private $_compartir = 0;
    private $_activo = 0;
    private $_fechaPublicacion =  '0000-00-00 00:00:00';
    private $_fechaDespublicacion = '0000-00-00 00:00:00';
    private $_fechaCreacion = '0000-00-00 00:00:00';
    private $_idUsuarioCmb = 0;
    private $_fechaUltCambio = '0000-00-00 00:00:00';

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion de comunicadoss
    public function GetAllComunicados($activo=0, $tipoOpc=""){
        $array = array();
        $ds = new comunicadosDB();
        $datosBD = new datosBD();
        $result = $ds->GetallComunicados($activo, $tipoOpc);
        $array = $datosBD->arrDatosObj($result);
        return $array;
    }

    //Obtener comunicadoss por su id
    public function obtComunicadoPorId($id){
        $ds = new comunicadosDB();
        $obj = new comunicadosObj();
        $datosBD = new datosBD();
        $result = $ds->obtComunicadoPorIdDB($id);

        return $datosBD->setDatos($result, $obj);
    }

    // guardar comunicadoss
    public function GuardarComunicado(){
        $objDB = new comunicadosDB();
        $this->_idComunicado = $objDB->insertComunicadoDB($this->getParams());
    }

    // actualiza comunicado
    public function ActualizarComunicado(){
        $objDB = new comunicadosDB();
        $res = $objDB->updateComunicadoDB($this->getParams(true));
        return $res;
    }

    //Eliminar comunicado
    public function EliminarComunicado($idComunicado)
    {
        $objDB = new comunicadosDB();
        $param[0] = $idComunicado;
        return $objDB->deleteComunicadoDB($param);
    }

    //Actualiza campo del comunicado
    public function ActualizarCampoComunicado($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new comunicadosDB();
        $resAct = $objDB->updateComunicadoCampoDB($param);
        return $resAct;
    }

    private function getParams($ctr=false)
    {
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
        $this->_fechaCreacion = $dateTime;
        $this->_fechaUltCambio = $dateTime;

        $param[0] = $this->_titulo;
        $param[1] = $this->_descripcionCorta;
        $param[2] = $this->_contenido;
        $param[3] = $this->_opcTipo;
        $param[4] = $this->_activo;

        if(!$ctr){
          $param[5] = $this->_imgComunicado;
          $param[6] = $this->_fechaCreacion;
          $param[7] = $this->_urlComunicado;
          $param[8] = $this->_urlVideo;
        }else{
          $param[5] = $this->_idUsuarioCmb;
          $param[6] = $this->_fechaUltCambio;
          $param[7] = $this->_urlComunicado;
          $param[8] = $this->_urlVideo;
          $param[9] = $this->_idComunicado;
        }

        return $param;
    }

    public function GetComunicadosGrid(){
       $DataServices = new DataServices();
       $dbConn = $DataServices->getConnection();
       $ds = new MySQLiDataSource($dbConn);
       $uDB = new comunicadosDB();
       $ds = $uDB->comunicadosDataSet($ds);
       $grid = new KoolGrid("comunicados");
       $configGrid = new configuracionesGridObj();

       $configGrid->defineGrid($grid, $ds);
       $configGrid->defineColumn($grid, "idComunicado", "ID", false, true);
       $configGrid->defineColumn($grid, "titulo", "Titulo", true, false, 1,"","100px");
       $configGrid->defineColumn($grid, "descripcionCorta", "Descripcion", true, false, 1,"","200px");
       $configGrid->defineColumn($grid, "contenido", "Contenido", false, true, 1,"","0px");
       $configGrid->defineColumn($grid, "opcTipo", "Tipo", false, true, 1,"","100px");
       $configGrid->defineColumn($grid, "urlVideo", "Video", true, false, 0,"","100px");
       $configGrid->defineColumn($grid, "activo", "Activo", true, true, 1,"","10px");
       if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
           $configGrid->defineColumnEdit($grid);
       }
       //pocess grid
       $grid->Process();
       return $grid;
    }
}
