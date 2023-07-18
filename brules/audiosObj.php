<?php
$dirname = dirname(__DIR__);
include_once  $dirname . '/database/audiosDB.php';
include_once  $dirname . '/brules/configuracionesGridObj.php';

class audiosObj extends configuracionesGridObj
{
    private $_idCaso = 0;
    private $_idNota = 0;
    private $_tipo = 0;
    private $_nombre = '';
    private $_url = '';
    private $_idUsuario = 0;
    private $_fechaCreacion = '';
    private $_fechaAct = '';
    private $_descripcion = ''; 
    private $_audienciaTipo = '';


    //get y set
    public function __get($name)
    {
        return $this->{"_" . $name};
    }
    public function __set($name, $value)
    {
        $this->{"_" . $name} = $value;
    }

    //Obtener coleccion
    public function ObtAudios($casoId = 0, $tipo = 0, $estatus = '', $fecha = '', $desde = '', $hasta = '', $orderBy = '')
    {
        $array = array();
        $ds = new digitalesDB();
        $datosBD = new datosBD();
        $result = $ds->ObtDigitalesDB($casoId, $tipo, $estatus, $fecha, $desde, $hasta, $orderBy);
        $array =  $datosBD->arrDatosObj($result);
        return $array;
    }

    public function AudiosPorId($id)
    {
        $usrDS = new digitalesDB();
        $obj = new digitalesObj();
        $datosBD = new datosBD();
        $result = $usrDS->DigitalesPorIdDB($id);

        return $datosBD->setDatos($result, $obj);
    }

    public function CrearAudio()
    {
        $objDB = new audioDB();
        $res=$objDB->CrearAudioDB($this->getParams());
        return $res;
    }


    private function getParams($update = false)
    {
        $dateByZone = obtDateTimeZone();
        $this->_fechaAct = $dateByZone->fechaHora;
        $param[0] = $this->_idUsuario;
        $param[1] = $this->_fechaAct;
        $param[2] = $this->_url;
        $param[3] = $this->_idCaso;
        $param[4] = $this->_descripcion;
        $param[5] = $this->_audienciaTipo;
        
        return $param;
    }



    public function ObtAudiosGrid($casoId = 0, $titular=false)
    {
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new audioDB();
        $ds = $uDB->AudiosDataSet($ds, $casoId, $titular);
        
        if($titular){
            $grid = new KoolGrid("audiosTitular");
        }else{
            $grid = new KoolGrid("audios");
            //echo 'test 2';
        }
                
        $configGrid = new configuracionesGridObj();
        
        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idNotaVoz", "ID", true, true);
        $configGrid->defineColumn($grid, "nombre", "Usuario", true, true, 1);
        $configGrid->defineColumn($grid, "fechaGrabacion", "Fecha", true, true, 1);
        $configGrid->defineColumn($grid, "descripcion", "Descripcion", true, false, 1);
        $configGrid->defineColumn($grid, "url", "Archivo", true, false, 1);
        $configGrid->defineColumnEdit($grid);
        
        $grid->Process();

        return $grid;
    }
    
    public function EliminarNotaObj($idNota){
        $objDB = new audioDB();
        return $objDB->EliminarNotaDB($idNota);
    }
}
