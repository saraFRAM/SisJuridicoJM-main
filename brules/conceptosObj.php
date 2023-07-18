<?php
$dirname = dirname(__DIR__);
include_once $dirname.'/database/conceptosDB.php';
// include_once $dirname.'/brules/usuariosObj.php';
include_once  $dirname.'/database/datosBD.php';

class conceptosObj {
    private $_idConcepto = 0;
    private $_tipoId = 0;
    private $_usuarioId = 0;
    private $_catConceptoId = 0;
    private $_descripcion = '';
    private $_monto = 0;
    private $_saldo = 0;
    private $_fecha = '';
    private $_fechaCreacion = '';
    private $_comprobante = '';
   
    //get y set
    public function __get($name) {             
        return $this->{"_".$name};
    }
    public function __set($name, $value) {        
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion de historial por rango de fecha
    public function ObtTodosConceptos($usuarioId = "", $tipoId = "", $desde = "", $hasta = "", $order = ""){
        $array = array();
        $ds = new conceptosDB();
        $datosBD = new datosBD();
        $obj = new conceptosObj();

        $result = $ds->ObtTodasConceptosDB($usuarioId, $tipoId, $desde, $hasta, $order);
      
        return $datosBD->arrDatosObj($result); 
    }

    //Obtener coleccion de historial por rango de fecha
    public function ObtConceptoByID($id){
        $DS = new conceptosDB();
        $result = $DS->ConceptoByID($id);
        $this->setDatos($result);
    }
    
    public function fechasGastos(){
        $DS = new conceptosDB();
        $datosBD = new datosBD();
        $result = $DS->fechasGastosDB();
        return $datosBD->setDatos($result);
    }

    //Se agrego el parametro tipoId para obtener la ultima mensualidad si es 1 o el ultimo pago es 3
    public function obtenerUlimaMensualidad($condominoId, $tipoId){
        $DS = new conceptosDB();
        $obj = new conceptosObj();
        $datosBD = new datosBD();
        $result = $DS->obtenerUlimaMensualidadDB($condominoId, $tipoId);
        return $datosBD->setDatos($result, $obj);
    }

    public function ObtConceptoByCondominoYMensualidad($condominoId = 0, $fechaMensualidad = ''){
        $DS = new conceptosDB();
        $result = $DS->ObtConceptoByCondominoYMensualidadDB($condominoId, $fechaMensualidad);
        $this->setDatos($result);
    }
    
    // public function ObtConceptoByPostId($id)
    // {
    //     $DS = new conceptosDB();
    //     $result = $DS->ConceptoByPostId($id);
    //     $this->setDatos($result);
    // }
    
     //Salvar cuentas por cobrar
    public function GuardarConcepto(){       
        $objDB = new conceptosDB();
        $this->_idConcepto = $objDB->insertConceptoDB($this->getParams());
    }
    public function ActualizarConcepto(){   
        $objDB = new conceptosDB();
        $objDB->updateConceptoDB($this->getParams(2));
    }
    
    public function ActualizarImagen(){   
         //establecer la zona horaria
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $dateTime = $dateByZone->format('Y-m-d'); //fecha Actual

        $param[0] = $this->_imagenVideo;
        $param[1] = $dateTime;
        $param[2] = $this->_idConcepto;
        $objDB = new conceptosDB();
        $objDB->updateImagenConceptoDB($param);
    }

    private function getParams($opc = 1){
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
        $this->_fechaCreacion = $dateTime;

        $param[0] = $this->_tipoId;
        $param[1] = $this->_usuarioId;
        $param[2] = $this->_catConceptoId;
        $param[3] = $this->_descripcion;
        $param[4] = $this->_monto;
        $param[5] = $this->_saldo;
        $param[6] = $this->_fecha;
        $param[7] = $this->_comprobante;
        
        if($opc == 1){
            // $param[6] = $this->_fechaCreacion;
        }
        elseif($opc == 2){
            $param[8] = $this->_fechaUltAct;
            $param[9] = $this->_idConcepto;
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

    //Grid conceptos
     public function ObtConceptosGrid($idFraccionamiento, $tipoId = ""){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $siDB = new conceptosDB();
        
        // $fDel = ($fDel!="") ?$this->convertDate($fDel) :"";
        // $fAl = ($fAl!="") ?$this->convertDate($fAl) :"";
//        $activo = ($activo != -1)? $activo:"";        
        $ds = $siDB->ConceptosSet($ds, $idFraccionamiento, $tipoId);
        $grid = new KoolGrid("conceptosGrid");

        $this->defineGridConcepto($grid, $ds);
        $this->defineColumnConcepto($grid, "idConcepto", "idConcepto", false, true);
        $this->defineColumnConcepto($grid, "fechaCreacion2", "Fecha", true, false, 1);
        $this->defineColumnConcepto($grid, "condomino", "Condomino", true, false, 1);
        $this->defineColumnConcepto($grid, "tipoId", "Tipo", true, false, 1);
        $this->defineColumnConcepto($grid, "monto", "Monto", true, false, 1); 
        $this->defineColumnConcepto($grid, "saldo", "Saldo al momento", true, false, 1);
        $this->defineColumnConcepto($grid, "saldo2", "Saldo actual", true, false, 1);
        
        $this->defineColumnEditConcepto($grid);

        //pocess grid
        $grid->Process();

        return $grid;
    }

    //Private Functions
    private function defineGridConcepto($grid, $ds)
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
    private function defineColumnConcepto($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0){

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
            $column->addvalidator($this->GetValidatorConcepto ($validator));

        $column->Visible = $visible;
        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $grid->MasterTable->AddColumn($column);
    }

    private function GetValidatorConcepto($type){
        switch ($type) {
            case 1: //required
                $validator = new RequiredFieldValidator();
                $validator->ErrorMessage = "Campo requerido";
                return $validator;
                break;
        }
    }

    private function defineColumnEditConcepto($grid){
        $column = new GridCustomColumn();
        $column->ItemTemplate = '<a class="btn bt-primary" onclick="imprimirPDFRecibo2({idConcepto})">Ver recibo</a>';
        $column->Align = "center";
        $column->HeaderText = "Acciones";
        $column->Width = "50px";
        $grid->MasterTable->AddColumn($column);
    }
    
    //Actualizar campo uno a uno
    public function ActualizarCampoConcepto($campo, $valor, $id){   
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual

    	$param[0] = $campo;
    	$param[1] = $valor;
    	$param[2] = $id;
        
        $objDB = new conceptosDB();
        $resAct = $objDB->updateCampoConceptoDB($param);
        return $resAct;
    }

    public function cambiaEstatusParkimovil($idFraccionamiento, $idRefPM, $estatusId){
        
        $fraccionamientosObj = new catFraccionamientosObj();

        $fraccionamientosObj->ObtFraccionamientoByID($idFraccionamiento);
        $parkimovilObj = new parkimovilObj($fraccionamientosObj->secret, $fraccionamientosObj->apikey);

        $arrRefs = array(
            array("id"=> "$idRefPM", "status_saldo"=>"$estatusId")
        );
        $res = $parkimovilObj->setEstatus($arrRefs);
    }

    public function crearCargo(){
        $conceptosObj = new conceptosObj();
       
        $usuariosObj = new usuariosObj();
        $usuario = $usuariosObj->UserByID($this->usuarioId);
       

        $arrRes = array("res"=>0, "msg"=>"", "msgError"=>"");
        
        //calcular saldo
        $saldo = floatval($usuario->saldo) - floatval($this->monto);
        // echo $saldo;die();
        $this->saldo = $saldo;
        
        //guardar concepto
        // echo "pi";die();
        $this->GuardarConcepto();
        // echo "cargo";die();
        
        //actualizar saldo condomino
        if($this->idConcepto > 0){
            $res = $usuariosObj->ActualizarUsuario("saldo", $saldo, $this->usuarioId);
            $arrRes["res"] = $res;
        }else{
            $arrRes["res"] = 0;
        }
        

        return $arrRes;

    }

    // public function crearMensualidad($condominoId = ""){
    //     $conceptosObj = new conceptosObj();
    //     $catViviendasObj = new catViviendasObj();
    //     $catCondominosObj = new catCondominosObj();
    //     $catCondominosObj->CondominoByID($condominoId);
    //     $catViviendasObj->ObtViviendaByID($catCondominosObj->idVivienda);

    //     $arrRes = array("res"=>0, "msg"=>"", "msgError"=>"");
    //     if($catCondominosObj->idVivienda > 0){
    //         //calcular saldo
    //         $saldo = $catCondominosObj->saldo + $catViviendasObj->importeCuota;
    
    //         //Obtener fechas mensualidad
    //         $usrDS = new conceptosDB();
    //         $obj = new conceptosObj();
    //         $datosBD = new datosBD();
    //         $result = $usrDS->obtenerFechaMensualidadDB($condominoId, $catCondominosObj->idVivienda);
    //         $fechasMensualidad = $datosBD->setDatos($result, $obj);
    
    //         //preparar objeto
    //         $conceptosObj->condominoId = $condominoId;
    //         $conceptosObj->tipoId = 1;
    //         $conceptosObj->descripcion = 'Mensualidad';
    //         $conceptosObj->monto = $catViviendasObj->importeCuota;
    //         $conceptosObj->saldo = $saldo;
    //         $conceptosObj->fechaLimite = $fechasMensualidad->fechaLimite;
    //         $conceptosObj->fechaMensualidad = ($fechasMensualidad->fechaProximaMensualidad != '')?$fechasMensualidad->fechaProximaMensualidad:$fechasMensualidad->fechaPrimeraMensualidad;
            
    //         //guardar concepto
    //         $conceptosObj->GuardarConcepto();
            
    //         //actualizar saldo condomino
    //         if($conceptosObj->idConcepto > 0){
    //             $res =$catCondominosObj->ActualizarCondomino("saldo", $saldo, $condominoId);
    //             $arrRes["res"] = $res;

    //             //5/11/2021 cambiar estatus parkimovil a 2
    //             // $resPM = $conceptosObj->cambiaEstatusParkimovil($catViviendasObj->idFraccionamiento, $catViviendasObj->idRefPM, 2);
    //         }else{
    //             $arrRes["res"] = 0;
    //         }
    //     }else{
    //         $arrRes["res"] = 0;
    //         $arrRes["msgError"] = "El condomino no tiene vivienda asignada";
    //     }

    //     return $arrRes;

    // }

    public function crearAbono(){
        $conceptosObj = new conceptosObj();
       
        $usuariosObj = new usuariosObj();
        $usuario = $usuariosObj->UserByID($this->usuarioId);
       

        $monto = floatval(removerCaracteres($this->usuarioId));
        $arrRes = array("res"=>0, "msg"=>"", "msgError"=>"");
        if($monto > 0){
            //calcular saldo
            $saldo = floatval($usuario->saldo) + floatval($this->monto);

            //preparar objeto
            $this->saldo = $saldo;
            
            //guardar concepto
            $this->GuardarConcepto();

            //actualizar saldo condomino
            if($this->idConcepto > 0){
                $res =$usuariosObj->ActualizarUsuario("saldo", $saldo, $this->usuarioId);
                $arrRes["res"] = $res;

                
            }else{
                $arrRes["res"] = 0;
            }
        }else{
            $arrRes["res"] = 0;
            $arrRes["msgError"] = "El condomino no tiene vivienda asignada, o el monto no tiene un valor correcto";
        }

        return $arrRes;
    }

    // public function crearRecargo($condominoId){
    //     $conceptosObj = new conceptosObj();
    //     $catViviendasObj = new catViviendasObj();
    //     $catCondominosObj = new catCondominosObj();
    //     $catFraccionamientosObj = new catFraccionamientosObj();

    //     $catCondominosObj->CondominoByID($condominoId);
    //     $catViviendasObj->ObtViviendaByID($catCondominosObj->idVivienda);
    //     $catFraccionamientosObj->ObtFraccionamientoByID($catCondominosObj->idFraccionamiento);

    //     $arrRes = array("res"=>0, "msg"=>"", "msgError"=>"");

    //     $recargos = $conceptosObj->ObtTodosConceptos($condominoId, 2);
    //     $concepto = $conceptosObj->obtenerUlimaMensualidad($condominoId, "1,3");//obtener ultimo concepto para aplicar recargo

    //     $montoRecargo = 0;
    //     // 1. Revisar si el condomino no tiene recargos
    //     if(count($recargos) == 0){
    //         // 2. Si no tiene, crear un recargo por el monto de primer recargo dependiendo su fraccionamiento (validar si esta configurado, si no mandar mensaje de error)
    //         if($catFraccionamientosObj->montoPrimerRecargo > 0){
    //             $montoRecargo = $catFraccionamientosObj->montoPrimerRecargo;
    //         }else{
    //             $arrRes["msgError"] = "El fraccionamiento no tiene configurado el monto para primer recargo";
    //         }
    //     }else{
    //         // 3. Si tiene recargos anteriores, obtener porcentaje de recargargos dependiendo su fraccionamiento (validar si esta configurado, si no mandar mensaje de error)
    //         if($catFraccionamientosObj->porcenRecargo > 0){
    //             // 4. Obtener segun las reglas indicadas, el ultimo concepto, sobre el que se calculara el recargo
    //             $usrDS = new conceptosDB();
    //             $obj = new conceptosObj();
    //             $datosBD = new datosBD();
    //             $result = $usrDS->obtenerSaldoAntesDeRecargoDB($condominoId);
    //             $saldoObj = $datosBD->setDatos($result, $obj);

    //             // 5. Calcular el recargo con el porcentaje y el saldo del concepto obtenido
    //             $porcenRecargo = $catFraccionamientosObj->porcenRecargo/100;
    //             $montoRecargo = $saldoObj->saldo * $porcenRecargo;

    //         }else{
    //             $arrRes["msgError"] = "El fraccionamiento no tiene configurado el porcentaje de recargos";
    //         }
    //     }

    //     if($montoRecargo > 0){
    //         // 6. Calcular nuevo saldo
    //         $saldo = $catCondominosObj->saldo + $montoRecargo;
    //         // 7. Crear concepto
    //         $conceptosObj->condominoId = $condominoId;
    //         $conceptosObj->tipoId = 2;
    //         $conceptosObj->descripcion = 'Recargo';
    //         $conceptosObj->monto = $montoRecargo;
    //         $conceptosObj->saldo = $saldo;
    //         $conceptosObj->fechaLimite = '';
    //         $conceptosObj->fechaMensualidad = '';
            
    //         //jair 5/11/2021 validar que el ultimo concepto no tengo recargo
    //         if($concepto->tieneRecargo == 0){
    //             //guardar concepto
    //             $conceptosObj->GuardarConcepto();
                
    //             //actualizar saldo condomino
    //             if($conceptosObj->idConcepto > 0){
    //                 // 8. Actualizar saldo condomino
    //                 $res =$catCondominosObj->ActualizarCondomino("saldo", $saldo, $condominoId);
    //                 $arrRes["res"] = $res;

    //                 //jair 5/11/2021 actualizar campo tiene recargo en concepto
    //                 $conceptosObj->ActualizarCampoConcepto("tieneRecargo", 1, $concepto->idConcepto);
    
    //                 //5/11/2021 Desactivar parkimovil al crear un recargo
    //                 // $resPM = $conceptosObj->cambiaEstatusParkimovil($catViviendasObj->idFraccionamiento, $catViviendasObj->idRefPM, 0);
    //             }else{
    //                 $arrRes["res"] = 0;
    //             }
    //         }else{
    //             $arrRes["res"] = 0;
    //         }
    //     }

    //     return $arrRes;
    // }

}
