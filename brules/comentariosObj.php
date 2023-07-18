<?php
$dirname = dirname(__DIR__);
include_once $dirname.'/database/comentariosDB.php';
// include_once $dirname.'/brules/usuariosObj.php';
include_once  $dirname.'/database/datosBD.php';

class comentariosObj {
    private $_idComentario = 0;
    private $_usuarioId = 0;  
    private $_leido = 0;  
    private $_casoId = 0;  
    private $_comentario = '';
    private $_fechaCreacion = '';

   
    //get y set
    public function __get($name) {             
        return $this->{"_".$name};
    }
    public function __set($name, $value) {        
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion de historial por rango de fecha
    public function ObtTodosComentarios($usuarioId = "", $casoId = "", $desde = "", $hasta = "", $order = ""){
        $array = array();
        $ds = new comentariosDB();
        $datosBD = new datosBD();
        $obj = new comentariosObj();

        $result = $ds->ObtTodasComentariosDB($usuarioId, $casoId, $desde, $hasta, $order);
      
        return $datosBD->arrDatosObj($result); 
    }

    

    public function ObtComentarioByID($id){
        $usrDS = new comentariosDB();
        $obj = new comentariosObj();
        $datosBD = new datosBD();
        $result = $usrDS->ComentarioPorIdDB($id);

        return $datosBD->setDatos($result, $obj);
    }
    
    public function fechasGastos(){
        $DS = new comentariosDB();
        $datosBD = new datosBD();
        $result = $DS->fechasGastosDB();
        return $datosBD->setDatos($result);
    }

    //Se agrego el parametro tipoId para obtener la ultima mensualidad si es 1 o el ultimo pago es 3
    public function obtenerUlimaMensualidad($condominoId, $tipoId){
        $DS = new comentariosDB();
        $obj = new comentariosObj();
        $datosBD = new datosBD();
        $result = $DS->obtenerUlimaMensualidadDB($condominoId, $tipoId);
        return $datosBD->setDatos($result, $obj);
    }

    public function ObtComentarioByCondominoYMensualidad($condominoId = 0, $fechaMensualidad = ''){
        $DS = new comentariosDB();
        $result = $DS->ObtComentarioByCondominoYMensualidadDB($condominoId, $fechaMensualidad);
        $this->setDatos($result);
    }
    
    // public function ObtComentarioByPostId($id)
    // {
    //     $DS = new comentariosDB();
    //     $result = $DS->ComentarioByPostId($id);
    //     $this->setDatos($result);
    // }
    
     //Salvar cuentas por cobrar
    public function GuardarComentario(){       
        $objDB = new comentariosDB();
        $this->_idComentario = $objDB->insertComentarioDB($this->getParams());
    }
    public function ActualizarComentario(){   
        $objDB = new comentariosDB();
        $objDB->updateComentarioDB($this->getParams(2));
    }
    
    public function ActualizarImagen(){   
         //establecer la zona horaria
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $dateTime = $dateByZone->format('Y-m-d'); //fecha Actual

        $param[0] = $this->_imagenVideo;
        $param[1] = $dateTime;
        $param[2] = $this->_idComentario;
        $objDB = new comentariosDB();
        $objDB->updateImagenComentarioDB($param);
    }

    private function getParams($opc = 1){
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
        $this->_fechaCreacion = $dateTime;

        $param[0] = $this->_casoId;
        $param[1] = $this->_usuarioId;
        $param[2] = $this->_comentario;
        
        if($opc == 1){
            $param[3] = $this->_fechaCreacion;
        }
        elseif($opc == 2){
            $param[3] = $this->_fechaUltAct;
            $param[4] = $this->_idComentario;
        }
        return $param;
    }

    

    //setear datos en las variables privadas    
    private function setDatos($result){ 
        if ($result)
        {
            $myRows = mysqli_fetch_array($result);
            if($myRows == false) return;            
            foreach ($myRows as $key => $rowData) {
              if(is_string($key)) {
                  $this->{"_".$key} = $rowData;
              }
            }
        }           
    }
    //obtener coleccion de datos
    private function arrDatos($result, $nombreID){ 
        $array = array();
        $classObj = get_class($this);
        if ($result){
            while($myRows = mysqli_fetch_array($result)){                               
                $objTmp = new $classObj();
                foreach ($myRows as $key => $rowData){                 
                   if(is_string($key)) {
                     $objTmp->{"_".$key} = $rowData;
                     $array[$objTmp->{$nombreID}] = $objTmp;
                   }                   
                }
            }
        }
        return $array;
    }


    //Convertir fechas dd/mm/yy - yy-mm-dd
    private function convertDate($date){
        list($dd, $mm, $yy) = explode("/", $date);
        $date = $yy.'-'.$mm.'-'.$dd;

        return $date;
    }

    //Grid comentarios
     public function ObtComentariosGrid($idFraccionamiento, $tipoId = ""){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $siDB = new comentariosDB();
        
        // $fDel = ($fDel!="") ?$this->convertDate($fDel) :"";
        // $fAl = ($fAl!="") ?$this->convertDate($fAl) :"";
//        $activo = ($activo != -1)? $activo:"";        
        $ds = $siDB->ComentariosSet($ds, $idFraccionamiento, $tipoId);
        $grid = new KoolGrid("comentariosGrid");

        $this->defineGridComentario($grid, $ds);
        $this->defineColumnComentario($grid, "idComentario", "idComentario", false, true);
        $this->defineColumnComentario($grid, "fechaCreacion2", "Fecha", true, false, 1);
        $this->defineColumnComentario($grid, "condomino", "Condomino", true, false, 1);
        $this->defineColumnComentario($grid, "tipoId", "Tipo", true, false, 1);
        $this->defineColumnComentario($grid, "monto", "Monto", true, false, 1); 
        $this->defineColumnComentario($grid, "saldo", "Saldo al momento", true, false, 1);
        $this->defineColumnComentario($grid, "saldo2", "Saldo actual", true, false, 1);
        
        $this->defineColumnEditComentario($grid);

        //pocess grid
        $grid->Process();

        return $grid;
    }

    //Private Functions
    private function defineGridComentario($grid, $ds)
    {
        //create and define grid
        $grid->scriptFolder = "../brules/KoolControls/KoolGrid";
        $grid->styleFolder="office2010blue";
        $grid->Width = "760px";

        $grid->RowAlternative = true;
        $grid->AjaxEnabled = true;
        $grid->AjaxLoadingImage =  "../brules/KoolControls/KoolAjax/loading/5.gif";
        $grid->Localization->Load("../brules/KoolControls/KoolGrid/localization/es.xml");

        $grid->AllowInserting = true;
        $grid->AllowEditing = true;
        $grid->AllowDeleting = true;
        $grid->AllowSorting = true;
        $grid->ColumnWrap = true;
        $grid->AllowScrolling = false;
      	//$grid->MasterTable->Height = "540px";
      	//$grid->MasterTable->ColumnWidth = "130px";
        $grid->AllowResizing = true;

        $grid->MasterTable->DataSource = $ds;
        $grid->MasterTable->AutoGenerateColumns = false;
        $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
        $grid->MasterTable->Pager->ShowPageSize = true;
        $grid->MasterTable->Pager->PageSizeOptions = "10,25,50,100,150";
        //Show Function Panel
        $grid->MasterTable->ShowFunctionPanel = false;
        //Insert Settings
	      $grid->MasterTable->InsertSettings->Mode = "Form";
        $grid->MasterTable->EditSettings->Mode = "Form";
	      $grid->MasterTable->InsertSettings->ColumnNumber = 1;
        $grid->ClientSettings->ClientEvents["OnRowConfirmEdit"] = "Handle_OnRowConfirmEdit";
    }

    //define the grid columns
    private function defineColumnComentario($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0){

        if ($name_field == "condomino") {
            $column = new gridboundcolumn();
            $column->AllowFiltering = true;
            $column->FilterOptions  = array("No_Filter","Equal","Contain");//Only show 3 chosen options.
        }
        elseif($name_field == "monto" || $name_field == "saldo" || $name_field == "saldo2"){
            $column = new gridboundcolumn();
            $column->Align = "Right";
        }
        elseif($name_field == "tipoId"){
            $column = new GridDropDownColumn();
            
            $column->AddItem('Mensualidad',1);
            $column->AddItem('Recargo',2);
            $column->AddItem('Abono',3);
                
            $column->AllowFiltering = true;
            $column->FilterOptions  = array("No_Filter","Contain");//Only show 3 chosen
        }
        else{
            $column = new gridboundcolumn();
        }

        if($validator > 0)
            $column->addvalidator($this->GetValidatorComentario ($validator));

        $column->Visible = $visible;
        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $grid->MasterTable->AddColumn($column);
    }

    private function GetValidatorComentario($type){
        switch ($type) {
            case 1: //required
                $validator = new RequiredFieldValidator();
                $validator->ErrorMessage = "Campo requerido";
                return $validator;
                break;
        }
    }

    private function defineColumnEditComentario($grid){
        $column = new GridCustomColumn();
        $column->ItemTemplate = '<a class="btn bt-primary" onclick="imprimirPDFRecibo2({idComentario})">Ver recibo</a>';
        $column->Align = "center";
        $column->HeaderText = "Acciones";
        $column->Width = "50px";
        $grid->MasterTable->AddColumn($column);
    }
    
    //Actualizar campo uno a uno
    public function ActualizarCampoComentario($campo, $valor, $id){   
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual

    	$param[0] = $campo;
    	$param[1] = $valor;
    	$param[2] = $id;
        
        $objDB = new comentariosDB();
        $resAct = $objDB->updateCampoComentarioDB($param);
        return $resAct;
    }



}
