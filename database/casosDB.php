<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class casosDB {

    //method declaration
    public function ObtCasosDB($year = "", $abogadoId = "", $orderby = array()){
        $ds = new DataServices();
        $param[0] = "";
        $param[1] = "";
        $param[2] = "";
        $query = array();
        $queryH = array(); //query having

        // if($activo > 0){
        //     $query[] = " activo=$activo ";
        // }

        if($year != ""){
            $queryH[] = " (SELECT year)=$year ";
        }

        if($abogadoId > 0){
            $queryH[] = " a.responsableId=$abogadoId ";
        }

       

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }

        if(count($queryH) > 0){
            $wordWhere = " HAVING ";
            $setWhere = implode(" AND ", $queryH);
            // echo $setWhere;
            $param[1] = $wordWhere.$setWhere;
        }

        if(count($orderby) > 0){
            $wordOrder = " ORDER BY ";
            $setOrder = implode(", ", $orderby);
            // echo $setWhere;
            $param[2] = $wordOrder.$setOrder;
        }

        $result = $ds->Execute("ObtCasosDB", $param);
        $ds->CloseConnection();
        return $result;
    }

   
    public function ObtCasoInfoPorId($idCaso = ""){
        $ds = new DataServices();
        $param[0]= "WHERE a.idCaso = ".$idCaso;

        $result = $ds->Execute("ObtCasoInfoPorId", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function CasoPorIdDB($id){
        $ds = new DataServices();
        $param[0]= $id;
        $result = $ds->Execute("CasoPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    /**
     * JGP 26/06/23
     * Obtiene estadistica de expedientes
     */
    public function obtenerEstadisticaExpedientesDB(){
        $ds = new DataServices();
        $param= null;
        $result = $ds->Execute("obtenerEstadisticaExpedientesDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    /**
     * JGP 26/06/23
     * Obtiene estadistica de expedientes por abogado
     */
    public function expedientesPorabogadoDB(){
        $ds = new DataServices();
        $param= null;
        $result = $ds->Execute("expedientesPorabogadoDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    //>>>>CMPB, 03/02/2023, cambios para eliminar nodos con hijos o sin hijos
    public function CasosHijosDB($id){
        $ds = new DataServices();
        $param[0]= $id;
        $result = $ds->Execute("CasosHijosDB", $param);
        $ds->CloseConnection();

        return $result;
    }
    
    public function VerificarCasos($idCliente){
        $ds = new DataServices();
        $param[0]= $idCliente;
        $result = $ds->Execute("verNumCasosCliente", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function GetCasoArbolPorRaizDB($idCaso){
        $ds = new DataServices();
        $param[0]= $idCaso;

        $result = $ds->Execute("GetCasoArbolPorRaiz", $param);
        $ds->CloseConnection();

        return $result;
    }
   
    public function obtenerIdPadreMainDB($idCaso){
        $ds = new DataServices();
        $param[0]= $idCaso;

        $result = $ds->Execute("obtenerIdPadreMain", $param);
        $ds->CloseConnection();

        return $result;
    }
    public function GetPadreCasoArbolDB($idCasoClic){
        $ds = new DataServices();
        $param[0]= $idCasoClic;
        $result = $ds->Execute("GetPadreCasoArbol", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function datosCasoDB($casoId){
        $ds = new DataServices();
        $param[0]= $casoId;
        $result = $ds->Execute("datosCaso", $param);
        $ds->CloseConnection();
        return $result;
    }
   
    public function obtenerHijosDB($id_padre){
        $ds = new DataServices();
        $param[0]= $id_padre;
        $result = $ds->Execute("obtenerHijos", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function verSiEsPadreDB($id_padre){
        $ds = new DataServices();
        $param[0]= $id_padre;
        $result = $ds->Execute("verSiEsPadre", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function CrearCasoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("CrearCasoDB", $param, true);
        return $result;
    }

    public function EditarCasoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("EditarCasoDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function ActCampoCasoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("ActCampoCasoDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function ActCampoCasoDBNull($param){
      $ds = new DataServices();
      $result = $ds->Execute("ActCampoCasoDBNull", $param, false, true);
      $ds->CloseConnection();
      return $result;
  }

    public function CasosDataSet($ds, $idCliente, $idAbogado, $titularId,$pantalla=0, $filResponsables = '', $filClientes = '', $filEstatus = '', $filJuicios = '', $filJuzgados = '', $filMaterias = '', $filClientesno = '', $filCaso, $filDistritos = ''){
        $dsO = new DataServices();
        $param[0] = "";
        $param[1] = "";
        $query = array();

        if($idCliente > 0){
            $query[] = " a.clienteId IN ($idCliente) ";
        }      
        if($idAbogado > 0){
            //$idContact = $idAbogado.",";
            //$idAbogado = $idAbogado.",";
            $query[] =  "(a.responsableId =".$idAbogado." 
            OR  a.autorizadosIds LIKE '%,".$idAbogado.",%' )
            ";//Jair 4/2/2022 Validar con comas al inicio y al final del id, ya que si el autorizado id es 13, saldra en la consulta para id 3 o id 1 tambien 
        }

        if($titularId !== ""){
            $query[] = " a.titularId2=$titularId ";
        }

        if($filResponsables != ''){
            $query[] = " a.responsableId IN($filResponsables) ";
        }
        if($filCaso != ''){
            $query[] = " a.idCaso IN($filCaso) ";
        }
        if($filClientes != ''){
            $query[] = " a.clienteId IN($filClientes) ";
        }

        if($filEstatus != ''){
            $query[] = " a.estatusId IN($filEstatus) ";
        }

        if($filMaterias != ''){
            $query[] = " a.materiaId IN($filMaterias) ";
        }

        if($filJuicios != ''){
            $query[] = " a.juicioId IN($filJuicios) ";
        }

        if($filJuzgados != ''){
            $query[] = " a.juzgadoId IN($filJuzgados) ";
        }
        if($filClientesno != ''){
            $query [] = " a.clienteId NOT IN($filClientesno)";
        }
        if($filDistritos != ''){
            $query [] = " a.distritoId IN($filDistritos)";
        }
        
        //pantalla 0 = expedientes, 1=historico
        if($pantalla===0 && $filEstatus == ''){
            //$query[] = " a.estatusId IN (1,5) ";
            $query[] = " a.estatusId IN (1,5) ";
        }
        elseif($pantalla===1){
            $query[] = " a.estatusId IN (2,3,4) ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }
        // print_r($param);

        $ds->SelectCommand = $dsO->ExecuteDS("ObtCasosDB", $param);
        // $param = null;
        // $ds->InsertCommand = $dsO->ExecuteDS("insEnfermedadGrid", $param);
        // $ds->UpdateCommand = $dsO->ExecuteDS("actEnfermedadGrid", $param);
        // $ds->DeleteCommand = $dsO->ExecuteDS("delEnfermedadGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }

}