<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/catConfiguracionesDB.php';
include_once  $dirname.'/database/datosBD.php';

class catConfiguracionesObj {
    private $_idConfiguracion = 0;
    private $_nombre = '';
    private $_valor = '';
    private $_fechaCreacion = '';
    private $_fechaAct = '';
   
    //get y set
    public function __get($name) {             
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion de historial por rango de fecha
    public function ObtTodConfiguraciones($fDel="", $fAl=""){
        $array = array();
        $ds = new catConfiguracionesDB();
        $datosBD = new datosBD();

        $fDel = ($fDel!="") ?$this->convertDate($fDel) :"";
        $fAl = ($fAl!="") ?$this->convertDate($fAl) :"";

        $result = $ds->ObtTodasConfiguracionesDB($fDel, $fAl);
        $array = $datosBD->arrDatos($result);     

        return $array;            
    }
    
    public function ObtConfiguracionByID($id){
        $DS = new catConfiguracionesDB;
        $obj = new catConfiguracionesObj();
        $datosBD = new datosBD();

        $result = $DS->ConfiguracionByID($id);        
        return $datosBD->setDatos($result, $obj);
    }
    
    
     //Salvar cuentas por cobrar
    public function GuardarConfiguracion()
    {   
        $objDB = new catConfiguracionesDB;
        $this->_idConfiguracion = $objDB->insConfiguracionDB($this->getParams());
    }
    
    public function ActualizarConfiguracion(){   
        $objDB = new catConfiguracionesDB();
        return $objDB->updateConfiguracionDB($this->getParams(true));
    }

    private function getParams($update = false){
        $param[0] = $this->_nombre;
        $param[1] = $this->_valor;        
                
        if($update){
            $param[3] = $this->_idConfiguracion;        
        }
        return $param;
    }

    //Convertir fechas dd/mm/yy - yy-mm-dd
    private function convertDate($date){
        list($dd, $mm, $yy) = explode("/", $date);
        $date = $yy.'-'.$mm.'-'.$dd;

        return $date;
    }

    public function ObtConfigGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $siDB = new catConfiguracionesDB();

        $ds = $siDB->ConfigsSet($ds);
        $grid = new KoolGrid("configsGrid");

        $this->defineGridConfig($grid, $ds);
        $this->defineColumnConfig($grid, "idConfiguracion", "id", false, true);
        $this->defineColumnConfig($grid, "nombre", "Configuracion", true, true, 1);
        $this->defineColumnConfig($grid, "valor2", "Texto", true, true, 0);
        // $this->defineColumnConfig($grid, "valor", "Valor", false, false, 1);

        $this->defineColumnEditConfig($grid);

        //pocess grid
        $grid->Process();

        return $grid;
    }

    //Private Functions
    private function defineGridConfig($grid, $ds){
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
        $grid->MasterTable->ShowFunctionPanel = true;
        $grid->MasterTable->FunctionPanel->ShowInsertButton = false;
        $grid->MasterTable->FunctionPanel->ShowRefreshButton = true;
        //Insert Settings
          $grid->MasterTable->InsertSettings->Mode = "Form";
        $grid->MasterTable->EditSettings->Mode = "Form";
          $grid->MasterTable->InsertSettings->ColumnNumber = 1;
        $grid->ClientSettings->ClientEvents["OnRowConfirmEdit"] = "Handle_OnRowConfirmEdit";
    }

    //define the grid columns
    private function defineColumnConfig($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0, $empresaId = ""){
        if($name_field == 'idRol') {
            $column = new GridDropDownColumn();
            $rolObj = new rolesObj();
            $rolArr = $rolObj->GetAllRoles();
            $column->AddItem('-- Seleccionar --',NULL);
            foreach($rolArr as $rolTmp)
            {
                $column->AddItem($rolTmp->rol,$rolTmp->idRol);
            }
        }
        elseif($name_field == 'valor2'){            
            $column = new GridTextAreaColumn();
            $column->AllowHtmlRender = true;
            $column->BoxHeight = "10px";
        }
        /*elseif($name_field == 'empresaId') {
            $column = new GridDropDownColumn();
            $empresasObj = new empresasObj($empresaId);
            $arr = $empresasObj->ObtTodosEmpresas();
            $column->AddItem('-- Seleccionar --',NULL);
            foreach($arr as $item){
                $column->AddItem($item->nombre,$item->idEmpresa);
            }
            $column->AllowFiltering = true;
            $column->FilterOptions  = array("No_Filter","Equal");//Only show 3 chosen options.

        }*/
        else{
            $column = new gridboundcolumn();
        }

        if($validator > 0)
            $column->addvalidator($this->GetValidatorConfig ($validator));

        $column->Visible = $visible;
        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $grid->MasterTable->AddColumn($column);
    }

    private function GetValidatorConfig($type){
        switch ($type) {
            case 1: //required
                $validator = new RequiredFieldValidator();
                $validator->ErrorMessage = "Campo requerido";
                return $validator;
                break;
        }
    }

    private function defineColumnEditConfig($grid){
        //<a class="kgrLinkEdit" onclick="grid_edit(this)" href="javascript:void 0"></a>
        //<a class="kgrLinkDelete" onclick="grid_delete(this)" href="javascript:void 0" ></a>
        $column = new GridCustomColumn();
        $column->ItemTemplate = '<a class="kgrLinkEdit btnFancy" onclick="abrirEditarConfig({idConfiguracion}, \'{nombre}\')" href="#fancyEditarConfig"></a>';
        // $column->ItemTemplate .= '<input type="hidden" id="valor_config_{idConfiguracion}" value="{valor}">';
        $column->Align = "center";
        $column->HeaderText = "Acciones";
        $column->Width = "90px";
        $grid->MasterTable->AddColumn($column);
    }


}
