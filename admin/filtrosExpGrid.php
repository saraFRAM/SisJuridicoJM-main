<?php 
const BTN_RST = '<div class="col-md-1"><span class="glyphicon glyphicon-refresh" value="rs" id="idfilter" onClick="resetFilt(this);" /></div>';
    

function obtenerCampoResponsables($responsables = ''){
    $usuariosObj = new usuariosObj();
    $claseResponsable = ($_SESSION["idRol"] == 4)?"likeDisaled":"";
    $responsableId = ($_SESSION["idRol"] == 4)?$_SESSION["idUsuario"]:"";;

    //datos
    $colRes = $usuariosObj->obtTodosUsuarios(true, 4, "", " numAbogado ASC ", true);
    $arrResponsable = array();
    // $arrResponsable[] = array("valor"=>"", "texto"=>"Seleccione...");

    foreach ($colRes as $itemRes) {
        $arrResponsable[] = array("valor"=>$itemRes->idUsuario, "texto"=>$itemRes->numAbogado." - ".$itemRes->nombre);    
    }

    //parametros input
    $data = array(
        "data-live-search"=>"true",
        "multiple"=>"multiple"
      );

    //campo
    $arrCampoResponsable = array(
        array("nameid"=>"fil_responsableId", "type"=>"select", "class"=>"form-control selectpicker required ".$claseResponsable, "readonly"=>false, "label"=>"Responsable:", "datos"=>$arrResponsable, "value"=>$responsableId, "dataInput"=>$data, "multivalue"=>$responsables),
    );

    $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"6", "input_md"=>"6");

    $btnRst = str_replace("idfilter","rs_fil_responsableId",BTN_RST);

    return generaHtmlForm($arrCampoResponsable, $cols, $btnRst);
}

function obtenerCampoClientes($clientesIds = ''){
    $clientesObj = new clientesObj();
    $claseCliente = "";
    $clienteId = "";

    //datos
    $colRes = $clientesObj->ObtClientes(true);
    $arrCliente = array();
    // $arrResponsable[] = array("valor"=>"", "texto"=>"Seleccione...");

    foreach ($colRes as $itemRes) {
        $arrCliente[] = array("valor"=>$itemRes->idCliente, "texto"=>$itemRes->nombre);    
    }

    //parametros input
    $data = array(
        "data-live-search"=>"true",
        "multiple"=>"multiple"
      );

    //campo
    $arrCampoCliente = array(
        array("nameid"=>"fil_clienteId", "type"=>"select", "class"=>"form-control selectpicker required ".$claseCliente, "readonly"=>false, "label"=>"Cliente:", "datos"=>$arrCliente, "value"=>$clienteId, "dataInput"=>$data, "multivalue"=>$clientesIds),
    );

    $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"6", "input_md"=>"6");

    $btnRst = str_replace("idfilter","rs_fil_clienteId",BTN_RST);

    return generaHtmlForm($arrCampoCliente, $cols,$btnRst);
}

function obtenerCampoEstatus($estatusIds = '',$esHistorico = false){
   
    $claseEstatus = "";
    $estatusId = "";

    //datos
   
    $arrEstatus = array();
    // $arrResponsable[] = array("valor"=>"", "texto"=>"Seleccione...");
    if($esHistorico){
        $arrEstatus[] = array("valor"=>2, "texto"=>'Suspendido');
        $arrEstatus[] = array("valor"=>3, "texto"=>'Baja');
        $arrEstatus[] = array("valor"=>4, "texto"=>'Terminado'); 
    }else{
        $arrEstatus[] = array("valor"=>1, "texto"=>'Activo');
        $arrEstatus[] = array("valor"=>5, "texto"=>'Prospecto');
        $arrEstatus[] = array("valor"=>2, "texto"=>'Suspendido');
        $arrEstatus[] = array("valor"=>3, "texto"=>'Baja');
        $arrEstatus[] = array("valor"=>4, "texto"=>'Terminado');   
    }
       

    //parametros input
    $data = array(
        "data-live-search"=>"true",
        "multiple"=>"multiple"
      );

    //campo
    $arrCampoEstatus = array(
        array("nameid"=>"fil_estatusId", "type"=>"select", "class"=>"form-control selectpicker required ".$claseEstatus, "readonly"=>false, "label"=>"Estatus:", "datos"=>$arrEstatus, "value"=>$estatusId, "dataInput"=>$data, "multivalue"=>$estatusIds),
    );

    $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"6", "input_md"=>"6");

    $btnRst = str_replace("idfilter","rs_fil_estatusId",BTN_RST);

    return generaHtmlForm($arrCampoEstatus, $cols, $btnRst);
}

function obtenerCampoMaterias($materiaIds = ''){
    $materiasObj = new catMateriasObj();
    $claseMateria = "";
    $materiaId = "";

    //datos
    $colRes = $materiasObj->ObtMaterias("", "nombre");
    $arrMateria = array();
    // $arrResponsable[] = array("valor"=>"", "texto"=>"Seleccione...");

    foreach ($colRes as $itemRes) {
        $arrMateria[] = array("valor"=>$itemRes->idMateria, "texto"=>$itemRes->nombre);    
    }

    //parametros input
    $data = array(
        "data-live-search"=>"true",
        "multiple"=>"multiple",
        "onchange"=>"cargaSelector('fil_materiaId', 'fil_juicioId', 'juicios');",
      );

    //campo
    $arrCampoMateria = array(
        array("nameid"=>"fil_materiaId", "type"=>"select", "class"=>"form-control selectpicker required ".$claseMateria, "readonly"=>false, "label"=>"Materia:", "datos"=>$arrMateria, "value"=>$materiaId, "dataInput"=>$data, "multivalue"=>$materiaIds),
    );

    $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"6", "input_md"=>"6");

    $btnRst = str_replace("idfilter","rs_fil_materiaId",BTN_RST);

    return generaHtmlForm($arrCampoMateria, $cols, $btnRst);
}

function obtenerCampoJuicios($juicioIds = ''){
    $juiciosObj = new catJuiciosObj();
    $claseJuicio = "";
    $juicioId = "";

    //datos
    $colRes = $juiciosObj->ObtJuicios("", -1, "nombre");
    $arrJuicio = array();
    // $arrResponsable[] = array("valor"=>"", "texto"=>"Seleccione...");

    foreach ($colRes as $itemRes) {
        $arrJuicio[] = array("valor"=>$itemRes->idJuicio, "texto"=>$itemRes->nombre);    
    }

    //parametros input
    $data = array(
        "data-live-search"=>"true",
        "multiple"=>"multiple"
      );

    //campo
    $arrCampoJuicio = array(
        array("nameid"=>"fil_juicioId", "type"=>"select", "class"=>"form-control selectpicker required ".$claseJuicio, "readonly"=>false, "label"=>"Juicio:", "datos"=>$arrJuicio, "value"=>$juicioId, "dataInput"=>$data, "multivalue"=>$juicioIds),
    );

    $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"6", "input_md"=>"6");

    $btnRst = str_replace("idfilter","rs_fil_juicioId",BTN_RST);

    return generaHtmlForm($arrCampoJuicio, $cols, $btnRst);
}

function obtenerCampoJuzgados($juzgadoIds = ''){
    $juzgadosObj = new catJuzgadosObj();
    $claseJuzgado = "";
    $juzgadoId = "";

    //datos
    $colRes = $juzgadosObj->ObtJuzgados("", -1, "nombre");
    $arrJuicio = array();
    // $arrResponsable[] = array("valor"=>"", "texto"=>"Seleccione...");

    foreach ($colRes as $itemRes) {
        $arrJuicio[] = array("valor"=>$itemRes->idJuzgado, "texto"=>$itemRes->nombre);    
    }

    //parametros input
    $data = array(
        "data-live-search"=>"true",
        "multiple"=>"multiple"
      );

    //campo
    $arrCampoJuzgado = array(
        array("nameid"=>"fil_juzgadoId", "type"=>"select", "class"=>"form-control selectpicker required ".$claseJuzgado, "readonly"=>false, "label"=>"Juzgado:", "datos"=>$arrJuicio, "value"=>$juzgadoId, "dataInput"=>$data, "multivalue"=>$juzgadoIds),
    );

    $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"6", "input_md"=>"6");

    $btnRst = str_replace("idfilter","rs_fil_juzgadoId",BTN_RST);

    return generaHtmlForm($arrCampoJuzgado, $cols, $btnRst);
}

//JGP 20/05/23
function obtenerCampoDistritos($distritorIds = ''){
    $distritosObj = new catDistritosObj();
    $claseDistrito = "";
    $distritoId = "";

    //datos
    $colRes = $distritosObj->ObtDistritos("", -1, "nombre");
    $arrDistrito = array();
    
    foreach ($colRes as $itemRes) {
        $arrDistrito[] = array("valor"=>$itemRes->idDistrito, "texto"=>$itemRes->nombre);    
    }

    //parametros input
    $data = array(
        "data-live-search"=>"true",
        "multiple"=>"multiple"
      );

    //campo
    $arrCampoDistrito = array(
        array("nameid"=>"fil_distritoId", "type"=>"select", "class"=>"form-control selectpicker required ".$claseDistrito, "readonly"=>false, "label"=>"Distrito:", "datos"=>$arrDistrito, "value"=>$distritoId, "dataInput"=>$data, "multivalue"=>$distritorIds),
    );

    $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"6", "input_md"=>"6");

    $btnRst = str_replace("idfilter","rs_fil_distritoId",BTN_RST);

    return generaHtmlForm($arrCampoDistrito, $cols, $btnRst);
}

function obtenerCampoTitular($mostrarCamposTitular = false){
    $juzgadosObj = new catJuzgadosObj();
    $claseJuzgado = "";
    $juzgadoId = "";

    //datos
    // $colRes = $juzgadosObj->ObtJuzgados("", -1, "nombre");
    $arrJuicio = array();
    // $arrResponsable[] = array("valor"=>"", "texto"=>"Seleccione...");

    // foreach ($colRes as $itemRes) {
            
        $arrJuicio[] = array("valor"=>0, "texto"=>"No");
        $arrJuicio[] = array("valor"=>1, "texto"=>"Si");
    // }

    //parametros input
    $data = array();

    
    //CMPB 14/02/2023 se quito la restriccion para que se pueda ver el aka
    //$claseRow = ($_SESSION["idRol"] == 4)? "oculto" : "";
    $claseRow="";

    //campo
    $arrCampoJuzgado = array(
        array("nameid"=>"fil_mostrar", "type"=>"select", "class"=>"form-control ".$claseJuzgado, "readonly"=>false, "label"=>"Mostrar campos titular:", "datos"=>$arrJuicio, "value"=>$mostrarCamposTitular, "dataInput"=>$data, "multivalue"=>"", "claseRow"=>$claseRow),
    );

    $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"6", "input_md"=>"6");
    return generaHtmlForm($arrCampoJuzgado, $cols);
}

function obtenerCampoCampos($camposIds = ''){
    $juzgadosObj = new catJuzgadosObj();
    $claseJuzgado = "";
    $juzgadoId = "";

    //datos
    // $colRes = $juzgadosObj->ObtJuzgados("", -1, "nombre");
    $arrJuicio = array();
    // $arrResponsable[] = array("valor"=>"", "texto"=>"Seleccione...");

    // foreach ($colRes as $itemRes) {
      //CMPB 14/02/2023 Mostrar solo los aks a los abogados
        /*$arrJuicio[] = array("valor"=>1, "texto"=>"Tipo cobro");    
        $arrJuicio[] = array("valor"=>2, "texto"=>"Modo cobro");  
        $arrJuicio[] = array("valor"=>3, "texto"=>"Aka");
        $arrJuicio[] = array("valor"=>4, "texto"=>"Aka asunto");*/
        if($_SESSION["idRol"] == 4){
          $arrJuicio[] = array("valor"=>3, "texto"=>"Aka");
          $arrJuicio[] = array("valor"=>4, "texto"=>"Aka asunto");
        }else if($_SESSION["idRol"] == 1){
          $arrJuicio[] = array("valor"=>1, "texto"=>"Tipo cobro");    
          $arrJuicio[] = array("valor"=>2, "texto"=>"Modo cobro");  
          $arrJuicio[] = array("valor"=>3, "texto"=>"Aka");
          $arrJuicio[] = array("valor"=>4, "texto"=>"Aka asunto");
        }
        
    // }

    //parametros input
    $data = array(
        "data-live-search"=>"true",
        "multiple"=>"multiple"
      );
      //CMPB 14/02/2023 se quito la restriccion para que se pueda ver el aka
    //$claseRow = ($_SESSION["idRol"] == 4)? "oculto" : "";
    $claseRow="";

    //campo
    $arrCampoJuzgado = array(
        array("nameid"=>"fil_campos", "type"=>"select", "class"=>"form-control selectpicker ".$claseJuzgado, "readonly"=>false, "label"=>"Campos titular:", "datos"=>$arrJuicio, "value"=>"", "dataInput"=>$data, "multivalue"=>$camposIds, "claseRow"=>$claseRow),
        
    );
    //var_dump($camposIds);

    $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"6", "input_md"=>"6");

    $btnRst = str_replace("idfilter","rs_fil_campos",BTN_RST);

    return generaHtmlForm($arrCampoJuzgado, $cols, $btnRst);
}

function obtenerCamposGrid($camposIds = ''){
    $juzgadosObj = new catJuzgadosObj();
    $claseJuzgado = "";
    $juzgadoId = "";

    //datos
    // $colRes = $juzgadosObj->ObtJuzgados("", -1, "nombre");
    $arrJuicio = array();
    // $arrResponsable[] = array("valor"=>"", "texto"=>"Seleccione...");

    $arrJuicio = getArrayCamposGridExpediente();

    //parametros input
    $data = array(
        "data-live-search"=>"true",
        "multiple"=>"multiple"
      ); 
    // 13/02/2023 CMPB, mostrar filtro l rol 4
    //$claseRow = ($_SESSION["idRol"] == 4)?"oculto" : "";
    $claseRow = "";

    //campo
    $arrCampoJuzgado = array(
        array("nameid"=>"fil_camposgrid", "type"=>"select", "class"=>"form-control selectpicker ".$claseJuzgado, "readonly"=>false, "label"=>"Campos grid:", "datos"=>$arrJuicio, "value"=>"", "dataInput"=>$data, "multivalue"=>$camposIds, "claseRow"=>$claseRow),
    );

    $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"6", "input_md"=>"6");
    return generaHtmlForm($arrCampoJuzgado, $cols);
}

function obtenerCampoClientesExep($clientesIds = ''){
    $clientesObj = new clientesObj();
    $claseCliente = "";
    $exclienteId = "";
    //datos
    $colRes = $clientesObj->ObtClientes();
    $arrCliente = array();
    // $arrResponsable[] = array("valor"=>"", "texto"=>"Seleccione...");
    foreach ($colRes as $itemRes) {
        $arrCliente[] = array("valor"=>$itemRes->idCliente, "texto"=>$itemRes->nombre);    
    }
    //parametros input
    $data = array(
        "data-live-search"=>"true",
        "multiple"=>"multiple"
      );
    //campo
    $arrCampoCliente = array(
        array("nameid"=>"fil_clientenoId", "type"=>"select", "class"=>"form-control selectpicker required ".$claseCliente, "readonly"=>false, "label"=>"Excepto Cliente:", "datos"=>$arrCliente, "value"=>$exclienteId, "dataInput"=>$data, "multivalue"=>$clientesIds),
    );
    $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"6", "input_md"=>"6");
    $btnRst = str_replace("idfilter","rs_fil_clientenoId",BTN_RST);
    return generaHtmlForm($arrCampoCliente, $cols,$btnRst);
}
?>