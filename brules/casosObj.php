<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/casosDB.php';
include_once  $dirname.'/database/datosBD.php';
include_once  $dirname.'/brules/configuracionesGridObj.php';

class casosObj extends configuracionesGridObj{
    private $_idCaso = 0;
    private $_idPadre = 0;
    private $_idPadreMain = 0;
    private $_clienteId = 0;
    private $_tipoId = 0;
    private $_responsableId = 0;
    private $_autorizadosIds = "";
    private $_arrAutorizados = [];
    private $_representado = '';
    private $_descripcion = '';
    private $_internos = '';

    private $_usuarioAltaId = 0;
    private $_numExpediente = '';
    private $_numExpedienteJuzgado = '';
    private $_saludExpediente = 0;
    private $_titularId2 = 0;
    private $_velocidad = 0;
    private $_contrario = '';
    private $_akaAsunto = '';

    private $_estatusId = 0;
    private $_parteId = 0;
    private $_domicilioEmplazar = '';
    private $_materiaId = 0;
    private $_juicioId = 0;
    private $_distritoId = 0;
    private $_juzgadoId = 0;

    private $_procesalId = 0;
    private $_contundencia = 0;
    private $_comentariosTitular = '';
    private $_correonot = '';
    private $_accionId = 0;

    private $_fechaAlta = '0000-00-00';
    private $_fechaAct = '0000-00-00 00:00:00';
    private $_fechaCreacion = '0000-00-00 00:00:00';
    private $_ultimaActividad = '';
    private $_autorizadosJuzgados = '';
    private $_cobro= 0;

    private $_generado= 0;
    private $_nombreJuez = '';// LDAH IMP 17/08/2022 para nuevos campos de juez
    private $_nombreSecretaria = '';// LDAH IMP 17/08/2022 para nuevos campos de juez


    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion
    public function ObtCasos($year = "", $abogadoId = "", $orderby = array()){
        $array = array();
        $ds = new casosDB();
        $datosBD = new datosBD();
        $result = $ds->ObtCasosDB($year, $abogadoId, $orderby);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    public function CasoPorId($id){
        $usrDS = new casosDB();
        $obj = new casosObj();
        $datosBD = new datosBD();
        
        $result = $usrDS->CasoPorIdDB($id);
        
        return $datosBD->setDatos($result, $obj);
    }

    /**
     * JGP 26/06/23
     * Obtiene esatistica b�sica de expedientes
     */
    public function obtenerEstadisticaExpedientes(){
        $array = array();
        $ds = new casosDB();
        $datosBD = new datosBD();
        $result = $ds->obtenerEstadisticaExpedientesDB();
        $array =  $datosBD->arrDatosObj($result);

        return $array[0];
    }

    /**
     * JGP 26/06/23
     * Obtiene esatistica de expedientes por abogado
     */
    public function expedientesPorabogado(){
        $array = array();
        $ds = new casosDB();
        $datosBD = new datosBD();
        $result = $ds->expedientesPorabogadoDB();
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    /**
     * CSAT 18/07/23
     * Obtiene esatistica b�sica de expedientes (HISTORICO)
     */
    public function obtenerEstadisticaExpedientesHISTORICO(){
        $array = array();
        $ds = new casosDB();
        $datosBD = new datosBD();
        $result = $ds->obtenerEstadisticaExpedientesDBHISTORICO();
        $array =  $datosBD->arrDatosObj($result);

        return $array[0];
    }

    /**
     * CSAT 18/07/23
     * Obtiene esatistica de expedientes por abogado
     */
    public function expedientesPorabogadoHISTORICO(){
        $array = array();
        $ds = new casosDB();
        $datosBD = new datosBD();
        $result = $ds->expedientesPorabogadoDBHISTORICO();
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    
//>>>>CMPB, 03/02/2023, cambios para eliminar nodos con hijos o sin hijos
    public function CasosHijos($id){
        $usrDS = new casosDB();
        $obj = new casosObj();
        $datosBD = new datosBD();
        
        $result = $usrDS->CasosHijosDB($id);
        
        return $datosBD->arrDatosObj($result);
    }

    public function ObtCasoInfoPorId($id){
        $array = array();
        $ds = new casosDB();
        $datosBD = new datosBD();
        $result = $ds->ObtCasoInfoPorId($id);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }
    
    public function GetCasoArbolPorRaiz($recorrido){
        $ds = new casosDB();
        $array = array();
        $indice = 0;
        foreach ($recorrido as $key => $obj) {
          $id=$obj["idCaso"];
          $padre=$obj["idPadre"];
          $posicio=$obj["posicion"];
          $hijo=$obj["hijo"];
          if($posicio == "1"){//inicio raiz
              $node='node_'.$indice;
              $dataArray=array('id'=>$node,'data-id'=>$indice,'caso'=>$id,'hijo'=>$hijo,'data-first-child'=>$hijo);
              array_push($array,$dataArray);
          }elseif($posicio == "2"){//si aun tiene mas hijos
            foreach ($array as &$item) {
              if($item["caso"]==$padre){
                $parent=$item["data-id"];
              }
            }
            $node='node_'.$indice;
            $dataArray=array('id'=>$node,'data-id'=>$indice,'data-parent'=>$parent,'caso'=>$id,'padre'=>$padre, 'hijo'=>$hijo, 
                              'data-first-child'=>$hijo);
            array_push($array,$dataArray);
              foreach ($array as $key => $value) {
                if(count($array) > 1){
                  if(array_key_exists('hijo', $value)){
                    if($value["hijo"]==$id){
                      $parent=$dataArray["data-parent"];
                      if(array_key_exists('data-first-child', $array[$parent])){
                        $array[$parent]['data-first-child'] = $indice;
                      }
                    }
                  }
                }
              }
              if($indice > 1){
                if(count($array) > 1){
                  if(array_key_exists('data-parent', $array[$indice]) && array_key_exists('data-parent', $array[$indice-1])){
                    if($array[$indice]['data-parent'] == $array[$indice-1]['data-parent']){
                      $array[$indice-1]['data-next-sibling'] = $indice;
                    }
                  }
                }
              }
          }elseif($posicio == "3"){
            foreach ($array as &$item) {
              if($item["caso"]==$padre){
                $parent=$item["data-id"];
              }
                }
                $node='node_'.$indice;
                $dataArray=array('id'=>$node,'data-id'=>$indice,'caso'=>$id,'padre'=>$padre,'data-parent'=>$parent); 
                array_push($array,$dataArray);
                
                foreach ($array as $key => $value) {
                if(count($array) > 1){
                  if(array_key_exists('hijo', $value)){
                    if($value["hijo"]==$id){
                        $parent=$dataArray["data-parent"];
                        if(array_key_exists('data-first-child', $array[$parent])){
                        $array[$parent]['data-first-child'] = $indice;
                        }
                    }
                  }
                }
                }
                if($indice > 1){
                if(count($array) > 1){
                    if(array_key_exists('data-parent', $array[$indice]) && array_key_exists('data-parent', $array[$indice-1])){
                    if($array[$indice]['data-parent'] == $array[$indice-1]['data-parent']){
                        $array[$indice-1]['data-next-sibling'] = $indice;
                    }
                    }
                }
                } 
            
            }
          $indice ++;
        }
        $indice2=0;
        foreach ($recorrido as $key => $obj) {
            $casoId=$obj["idCaso"];
            $result = $ds->datosCasoDB($casoId);
          
            foreach ($result as $key => $row) {
              $datosCaso=array(
                'representado'=>$row['representado'],
                'parte'=>$row['parte'],
                'numExpedienteJuzgado'=>$row['numExpedienteJuzgado'],
                'juicio'=>$row['juicio'],
                'materia'=>$row['materia'],
                'juzgado'=>$row['juzgado'],
                'estatusId'=>$row['estatusId']
                );
              $array[$indice2]['datosCaso'] = $datosCaso;
            }
            $indice2 ++;
          }
          
        
        return $array;
    }
    
    function recorrer_arbol($id_padre){
        $ds = new casosDB();
        //generar consulta
        $result = $ds->obtenerHijosDB($id_padre);
        $queue = array();
        $queue[] = array('idCaso' => $id_padre, 'idPadre' => null); 
        while (!empty($queue)) {
            $nodo_actual = array_shift($queue);
            $id_padre = $nodo_actual['idCaso'];
        //generar consulta
            $result = $ds->obtenerHijosDB($id_padre);
            if(mysqli_num_rows($result) > 0){
                while ($row = mysqli_fetch_array($result)) {
                $id_caso = $row['idCaso'];
                $hijo = $row['hijo'];
                //generar consulta
                $query =  $ds->verSiEsPadreDB($id_caso);
                $idPadres= mysqli_fetch_array($query);
                    switch ($id_padre) {
                        case $idPadres!=null:
                            $recorrido[] = array('posicion'=>2,'idCaso'=> $id_caso, 'idPadre'=> $id_padre,'hijo'=>$hijo); 
                        break;
                        default:
                            $recorrido[] = array('posicion'=>3,'idCaso'=> $id_caso, 'idPadre'=> $id_padre,'hijo'=>$hijo); 
                        break;
                    }
                    $queue[] = array('idCaso' => $id_caso, 'idPadre' => $id_padre);
                }
            }
        }    
        return $recorrido;
    }
    function arbolPorIdCaso($idCaso){//controla todo y es la funcion que llamamos primero
        $ds = new casosDB();
        $idRaizQuery = $ds->obtenerIdPadreMainDB($idCaso);
        $objr= $idRaizQuery->fetch_object();
        $idRaiz = $objr->idPadreMain;
        //print_r( $idRaiz);
        $result = $ds->obtenerHijosDB($idRaiz);
        //var_dump($result);
        $obj= $result->fetch_object();
        $id_caso_raiz = $obj->idPadreMain;
        $hijoRaiz = $obj->hijo;
        $dataArray=array('posicion'=>1,'idCaso'=> $id_caso_raiz,'idPadre'=>$obj->idPadre,'hijo'=>$hijoRaiz);
        
        $recorrido = $this->recorrer_arbol($id_caso_raiz);
        array_unshift($recorrido,$dataArray);
        $arraAtbol=$this->GetCasoArbolPorRaiz($recorrido);
        return $arraAtbol;
      }

    public function GetPadreCasoArbol($idCasoClic){
        $array = array();
        $objDB = new casosDB();
        $datosBD = new datosBD();
        $result= $objDB->GetPadreCasoArbolDB($idCasoClic);
        $array =  $datosBD->arrDatosObj($result);
        return $array;
    }

    public function CrearCaso(){
        $objDB = new casosDB();
        $this->_idCaso = $objDB->CrearCasoDB($this->getParams());
    }

    public function EditarCaso(){
        $objDB = new casosDB();
        return $objDB->EditarCasoDB($this->getParams(true));
    }

    private function getParams($update = false){
        $dateByZone = obtDateTimeZone();
        $this->_fechaCreacion = $dateByZone->fechaHora;
        $this->_fechaAct = $dateByZone->fechaHora;

        $param[0] = $this->_clienteId;
        $param[1] = $this->_tipoId;
        $param[2] = $this->_responsableId;
        $param[3] = $this->_autorizadosIds;
        $param[4] = $this->_descripcion;
        $param[5] = $this->_internos;
        $param[6] = $this->_usuarioAltaId;
        $param[7] = $this->_numExpediente;
        $param[8] = $this->_numExpedienteJuzgado;
        $param[9] = $this->_saludExpediente;
        $param[10] = $this->_titularId2;
        $param[11] = $this->_velocidad;
        $param[12] = $this->_contrario;

        $param[13] = $this->_estatusId;
        $param[14] = $this->_parteId;
        $param[15] = $this->_materiaId;
        $param[16] = $this->_juicioId;
        $param[17] = $this->_distritoId;
        $param[18] = $this->_juzgadoId;
        $param[19] = $this->_domicilioEmplazar;

        $param[20] = $this->_procesalId;
        $param[21] = $this->_contundencia;

        $param[22] = $this->_correonot;
        $param[23] = $this->_accionId;

        $param[24] = $this->_fechaAlta;
        $param[25] = $this->_fechaAct;
        $param[26] = $this->_representado;
        $param[27] = $this->_autorizadosJuzgados;
        $param[28] = $this->_cobro;
        $param[29] = $this->_nombreJuez; // LDAH IMP 17/08/2022 para nuevos campos de juez
        $param[30] = $this->_nombreSecretaria; // LDAH IMP 17/08/2022 para nuevos campos de juez
        
        if($update){ //Para actualizar
            $param[31] = $this->_idCaso;
        }else{
            $param[31] = $this->_fechaCreacion;
        }
        
        return $param;
    }

    public function ActCampoCaso($campo, $valor, $id){
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new casosDB();
        $resAct = $objDB->ActCampoCasoDB($param);
        return $resAct;
    }

    public function ActCampoCasoNull($campo, $valor, $id){
      $param[0] = $campo;
      $param[1] = $valor;
      $param[2] = $id;

      $objDB = new casosDB();
      $resAct = $objDB->ActCampoCasoDBNull($param);
      return $resAct;
    }

    public function verificarCasos($idCliente){
        $ds = new casosDB();
        $result = $ds->VerificarCasos($idCliente);
        return $result;
    }



    //Grid
    public function ObtListadoCasosGrid($idCliente=-1 ,$idAbogado=-1, $mostrarCamposTitular = false, $filTexto="", $filEstatusAnt="", $titularId = "", $filtroTitular = "",$pantalla = 0, $activar = 0, $filResponsables = '', $filClientes = '', $filEstatus = '', $filJuicios = '', $filJuzgados = '', $filMaterias = '', $camposIds = '', $filCamposGrid = '', $filClientesno = '', $filCaso = '', $filDistritos = '',$filRepresentado = ''){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new casosDB();
        $ds = $uDB->CasosDataSet($ds, $idCliente ,$idAbogado, $titularId,$pantalla, $filResponsables, $filClientes, $filEstatus, $filJuicios, $filJuzgados, $filMaterias, $filClientesno, $filCaso, $filDistritos, $filRepresentado);
        $grid = new KoolGrid("casos");
        
        $configGrid = new configuracionesGridObj();
        $arrCampos = explode(",", $camposIds);
        $arrCamposGrid = explode(",", $filCamposGrid);

        $configGrid->defineGrid($grid, $ds, $activar);
        if($activar > 0){
            $configGrid->defineColumn($grid, "seleccionar", "", true, true);
        }
        $configGrid->defineColumn($grid, "idCaso", "ID", true, true);
        if($_SESSION["idRol"] != 5 && ($filCamposGrid == '' || in_array("numExpediente", $arrCamposGrid))){
            $configGrid->defineColumn($grid, "numExpediente", "Expediente Interno", true, true);
        }

        if($filCamposGrid == '' || in_array("numExpedienteJuzgado", $arrCamposGrid)){
            $configGrid->defineColumn($grid, "numExpedienteJuzgado", "Num Exp Juz", true, true);
        }
        if($_SESSION["idRol"] != 4){
            if($filCamposGrid == '' || in_array("saludExpediente", $arrCamposGrid)){
                $configGrid->defineColumn($grid, "saludExpediente", "Salud", true, false);
            }
            //$configGrid->defineColumn($grid, "tipocaso", "Tipo cliente", true, false, 1);
        }
        if($_SESSION["idRol"] == 4){//CMPB 03/03/2023 ya no se pueden desactivar los campos
            $configGrid->defineColumn($grid, "saludExpediente", "Salud", true, false);
            $configGrid->defineColumn($grid, "tipocaso", "Tipo cliente", true, false);
           
        }

        if($filCamposGrid == '' || in_array("estatusId", $arrCamposGrid)){
            $configGrid->defineColumn($grid, "estatusId", "Estatus", true, false,0,"","90px","",($pantalla != 0) ? 1:0);
        }

        //$configGrid->defineColumn($grid, "estatusId", "Estatus", true, false,0,"","90px","", 0);
        if($filCamposGrid == '' || in_array("titular", $arrCamposGrid)){
            $configGrid->defineColumn($grid, "titular", "Responsable", true, false, 1);//Nombre del responsable rol abogado
        }
        if($filCamposGrid == '' || in_array("representado", $arrCamposGrid)){
            $configGrid->defineColumn($grid, "representado", "Representado", true, false);
        } 

        if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumn($grid, "numAbogado", "Num Abogado", true, false, 1);
            

            if($filtroTitular != ""){
                $configGrid->defineColumn($grid, "titular2", "Titular", true, false, 1, "", "90px", $filtroTitular);//Nombre del titular Rol Superadmin
            }

            // Jair 13/4/2022 
            if($mostrarCamposTitular){
                if(in_array(1, $arrCampos)){
                    $configGrid->defineColumn($grid, "cobro", "Tipo cobro", true, false, 1);
                }
                if(in_array(2, $arrCampos)){
                    $configGrid->defineColumn($grid, "modoCobro", "Modo cobro", true, false, 1);
                }
                if(in_array(3, $arrCampos)){
                    $configGrid->defineColumn($grid, "aka", "Aka", true, false, 1);
                }
            }
            
        }
        if($_SESSION["idRol"] != 5 && ($filCamposGrid == '' || in_array("cliente", $arrCamposGrid))){
            $configGrid->defineColumn($grid, "cliente", "Cliente", true, false, 1);
        }

        //Jair 6/5/2022 Campo aka asunto entre representado y contrario
        if($_SESSION['idRol']==1 || $_SESSION['idRol']==2 ||  $_SESSION['idRol']==4 ){
            if($mostrarCamposTitular){
                if(in_array(4, $arrCampos)){
                    $configGrid->defineColumn($grid, "akaAsunto", "Aka Asunto", true, false, 1);
                }
            }
        }
        if($_SESSION["idRol"] != 5){
            if($filCamposGrid == '' || in_array("contrario", $arrCamposGrid)){
                $configGrid->defineColumn($grid, "contrario", "Contrario", true, false, 1); 
            }
            if($_SESSION["idRol"] != 4){
                if($filCamposGrid == '' || in_array("tipocaso", $arrCamposGrid)){
                    $configGrid->defineColumn($grid, "tipocaso", "Tipo cliente", true, false, 1);
                }
            }

            if($filCamposGrid == '' || in_array("parteId", $arrCamposGrid)){
                $configGrid->defineColumn($grid, "parteId", "Parte", true, false, 1);
            }

            if($filCamposGrid == '' || in_array("materiaId", $arrCamposGrid)){
                $configGrid->defineColumn($grid, "materiaId", "Materia", true, false, 1);
            }
        }

        if($filCamposGrid == '' || in_array("juicioId", $arrCamposGrid)){
            $configGrid->defineColumn($grid, "juicioId", "Juicio", true, false, 1);
        }

        if($filCamposGrid == '' || in_array("juzgadoId", $arrCamposGrid)){
           $configGrid->defineColumn($grid, "NombreJuzgado", "Juzgado", true, false, 1);
        }

        if($filCamposGrid == '' || in_array("fechaAlta2", $arrCamposGrid)){
            $configGrid->defineColumn($grid, "fechaAlta2", "F. Alta", true, false, 1);
        }
        if($_SESSION["idRol"] != 5){
            if($filCamposGrid == '' || in_array("fechaAct2", $arrCamposGrid)){
                $configGrid->defineColumn($grid, "fechaAct2", "Cambio Exp.", true, false);
            }

            if($filCamposGrid == '' || in_array("ultimaActividad2", $arrCamposGrid)){
                $configGrid->defineColumn($grid, "ultimaActividad2", "F. Ult. Act.", true, false);
            }

            if($filCamposGrid == '' || in_array("diferenciaFecha", $arrCamposGrid)){
                $configGrid->defineColumn($grid, "diferenciaFecha", "Dias S/Mov", true, false);
            }
        }

        if($filCamposGrid == '' || in_array("noLeidos", $arrCamposGrid)){
            $configGrid->defineColumn($grid, "noLeidos", "Com. No Leidos", true, false);
        }
        
        
        //>>>> IMP LDAH 24/08/2022 >>>>
        if($filCamposGrid == '' || in_array("nombreJuez", $arrCamposGrid)){
            $configGrid->defineColumn($grid, "nombreJuez", "Nombre juez", true, false);
        }
        
        if($filCamposGrid == '' || in_array("nombreSecretaria", $arrCamposGrid)){
            $configGrid->defineColumn($grid, "nombreSecretaria", "Nombre secretaria", true, false); 
        }
        
        if($filCamposGrid == '' || in_array("escritos", $arrCamposGrid)){
            $configGrid->defineColumn($grid, "escritos", "Num. de escritos", true, false); 
        }
        
        if($filCamposGrid == '' || in_array("expediente", $arrCamposGrid)){
            $configGrid->defineColumn($grid, "expediente", "Num. de expedientes", true, false); 
        }
        
        if($filCamposGrid == '' || in_array("audiencias", $arrCamposGrid)){
            $configGrid->defineColumn($grid, "audiencias", "Num. de aud", true, false);
        }
        
        
        if($filCamposGrid == '' || in_array("otros", $arrCamposGrid)){
            $configGrid->defineColumn($grid, "otros", "Num. de otros", true, false); 
        }
        
        if($filCamposGrid == '' || in_array("total", $arrCamposGrid)){
            $configGrid->defineColumn($grid, "total", "Total de archivos", true, false);
        }
        if($filCamposGrid == '' || in_array("ProxAudCitEmp", $arrCamposGrid)){
            $configGrid->defineColumn($grid, "ProxAudCitEmp", "Prox. Aud, Cit, Emp o Escr", true, false); 
        }
        //<<<< IMP LDAH 24/08/2022 <<<<
        if($filCamposGrid == '' || in_array("ultActividad", $arrCamposGrid)){
            $configGrid->defineColumn($grid, "ultActividad", "Ult. Actividad", true, false);
        }
              
        // $configGrid->defineColumn($grid, "opcionInfo", "T. Gastos", false, false);
        // if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $configGrid->defineColumnEdit($grid);
        // }

        $grid->MasterTable->DataKeyNames = "idCaso";
        //pocess grid
        $grid->Process();

        //Get selected keys after grid processing
	    $selected_keys = $grid->GetInstanceMasterTable()->SelectedKeys;
        //echo 'tengo';print_r($selected_keys);
        return array($grid, $selected_keys);
    }

    /* // Imp. obt nombre de Enfermedades por ids
    public function obtNombreEnfermedadesPorIds($id){
        $usrDS = new casosDB();
        $obj = new casosObj();
        $datosBD = new datosBD();
        $result = $usrDS->obtNombreEnfermedadesPorIdsDB($id);

        return $datosBD->setDatos($result, $obj);
    } */

    
    public function Obtrepresentado(){
        $array = array();
        $ds = new casosDB();
        $datosBD = new datosBD();
        $result = $ds->ObtrepresentadoDB();
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    public function ObtrepresentadoH(){
        $array = array();
        $ds = new casosDB();
        $datosBD = new datosBD();
        $result = $ds->ObtrepresentadoDBH();
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }


}


