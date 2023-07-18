<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/catComunicadosDB.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';

class catComunicadosObj extends configuracionesGridObj{    
    private $_idComunicado = 0;
    private $_titulo = '';
    private $_contenido = '';
    private $_activo = 0;
    private $_fechaCreacion = '0000-00-00 00:00:00';
    private $_fechaAct = '0000-00-00 00:00:00';


    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion
    public function ObtComunicados($activo=-1, $materiaId = "", $campoOrder = ""){
        $array = array();
        $ds = new catComunicadosDB();
        $datosBD = new datosBD();
        $result = $ds->ObtComunicadosDB($activo, $materiaId, $campoOrder);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    public function ComunicadoPorId($id){
        $usrDS = new catComunicadosDB();
        $obj = new catComunicadosObj();
        $datosBD = new datosBD();
        $result = $usrDS->ComunicadoPorIdDB($id);

        return $datosBD->setDatos($result, $obj);
    }

    public function GuardarComunicado(){
        $objDB = new catComunicadosDB();
        $this->_idComunicado = $objDB->insertComunicadoDB($this->getParams());
    }

    private function getParams($update = false){
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
        $this->_fechaCreacion= $dateTime;
        $this->_fechaAct= $dateTime;

        $param[0] = $this->_nombre;
        $param[1] = $this->_activo;

        if($update){//Para actualizar{
            $param[2] = $this->_fechaAct;
            $param[3] = $this->_idFunte;
        }
        else{
            $param[2] = $this->_fechaCreacion;
            /*$param[3] = $this->_instructorId;
            $param[5] = $this->_fechaEstudio;*/
        }
        return $param;
    }
    
    public function ActCampoComunicado($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new catComunicadosDB();
        $resAct = $objDB->ActCampoComunicadoDB($param);
        return $resAct;
    }

    //Grid
    public function ObtComunicadosGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new catComunicadosDB();
        $ds = $uDB->ComunicadosDataSet($ds);
        $grid = new KoolGrid("cat_comunicados");
        $configGrid = new configuracionesGridObj();

        $configGrid->defineGrid($grid, $ds);
        $configGrid->defineColumn($grid, "idComunicado", "ID", false, true);
        $configGrid->defineColumn($grid, "titulo", "Titulo", true, false, 1);
        $configGrid->defineColumn($grid, "contenido", "Contenido", false, false, 1);
        $configGrid->defineColumn($grid, "activo", "Activo", true, false, 0);
        // if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumnEdit($grid);
        // }

        // $grid->MasterTable->EditSettings->Mode = "Template";
	    // $grid->MasterTable->EditSettings->Template = new MyEditTemplate();

        // $grid->MasterTable->InsertSettings->Mode = "Template";
	    // $grid->MasterTable->InsertSettings->Template = new MyInsertTemplate();

        //pocess grid
        $grid->Process();

        return $grid;
    }

}

class MyEditTemplate implements GridTemplate
	{
		function Render($_row)
		{
			// $html  = "<textarea id='city_input'  name='city_input' type='text' ></textarea>"; //User input
			// $html .= "<input type='button' value='Confirm' onclick='grid_confirm_edit(this)'/>"; //Render confirm button.
			// $html .= "<input type='button' value='Cancel' onclick='grid_cancel_edit(this)'/>"; //Render cancel button.

            $html = '
            <div class="row">
                <div class="col-xs-4 col-sm-4">
                    <label>T&iacute;tulo: </label>
                </div>
                <div class="col-xs-8 col-sm-8">
                    <input class="kgrEnNoPo" name="titulo" id="titulo" type="text">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4 col-sm-4">
                    <label>Contenido: </label>
                </div>
                <div class="col-xs-8 col-sm-8">
                    <textarea class="kgrEnNoPo" name="contenido" id="contenido" rows="6"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4 col-sm-4">
                    <label>Activo: </label>
                </div>
                <div class="col-xs-8 col-sm-8">
                    <input class="kgrEnNoPo" name="activo" id="activo" type="checkbox">
                </div>
            </div>
            <div class="kgrFormFooter">
            
            <span class="kgrConfirm"><input type="button" onclick="grid_confirm_edit(this)" title="Confirmar los cambios" class="nodecor"><a href="javascript:void 0" onclick="grid_confirm_edit(this)" title="Confirmar los cambios">Confirmar</a></span> 

            <span class="kgrCancel"><input type="button" onclick="grid_cancel_edit(this)" title="Cancelar cambios" class="nodecor"><a href="javascript:void 0" onclick="grid_cancel_edit(this)" title="Cancelar cambios">Cancelar</a></span> 
            </div>
            </div>
            ';
			return $html;
		}
		function GetData($_row)
		{
			return array(
                "titulo"=>$_POST["titulo"],
                "contenido"=>$_POST["contenido"],
                "activo"=>$_POST["activo"],
                "idComunicado"=>"@idComunicado"
            );
		}		
	}

    class MyInsertTemplate implements GridTemplate
	{
		function Render($_row)
		{
			// $html  = "<textarea id='city_input'  name='city_input' type='text' ></textarea>"; //User input
			// $html .= "<input type='button' value='Confirm' onclick='grid_confirm_edit(this)'/>"; //Render confirm button.
			// $html .= "<input type='button' value='Cancel' onclick='grid_cancel_edit(this)'/>"; //Render cancel button.

            $html = '
            <div class="row">
                <div class="col-xs-4 col-sm-4">
                    <label>T&iacute;tulo: </label>
                </div>
                <div class="col-xs-8 col-sm-8">
                    <input class="kgrEnNoPo" name="titulo" id="titulo" type="text">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4 col-sm-4">
                    <label>Contenido: </label>
                </div>
                <div class="col-xs-8 col-sm-8">
                    <textarea class="kgrEnNoPo" name="contenido" id="contenido" rows="6"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4 col-sm-4">
                    <label>Activo: </label>
                </div>
                <div class="col-xs-8 col-sm-8">
                    <input class="kgrEnNoPo" name="activo" id="activo" type="checkbox">
                </div>
            </div>
            <div class="kgrFormFooter">
            
            <span class="kgrConfirm"><input type="button" onclick="grid_confirm_insert(this)" title="Confirmar los cambios" class="nodecor"><a href="javascript:void 0" onclick="grid_confirm_insert(this)" title="Confirmar los cambios">Confirmar</a></span> 

            <span class="kgrCancel"><input type="button" onclick="grid_cancel_insert(this)" title="Cancelar cambios" class="nodecor"><a href="javascript:void 0" onclick="grid_cancel_insert(this)" title="Cancelar cambios">Cancelar</a></span> 
            </div>
            </div>
            ';
			return $html;
		}
		function GetData($_row)
		{
			return array(
                "titulo"=>$_POST["titulo"],
                "contenido"=>$_POST["contenido"],
                "activo"=>$_POST["activo"]
            );
		}		
	}

