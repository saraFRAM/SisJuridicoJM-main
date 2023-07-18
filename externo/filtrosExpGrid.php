<?php 
function obtenerCampoResponsables($responsables = ''){
    $usuariosObj = new usuariosObj();
    $claseResponsable = ($_SESSION["idRol"] == 4)?"likeDisaled":"";
    $responsableId = "";

    //datos
    $colRes = $usuariosObj->obtTodosUsuarios(true, 4, "", " numAbogado ASC ");
    $arrResponsable = array();
    // $arrResponsable[] = array("valor"=>"", "texto"=>"Seleccione...");

    foreach ($colRes as $itemRes) {
        $arrResponsable[] = array("valor"=>$itemRes->idUsuario, "texto"=>$itemRes->nombre);    
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
    return generaHtmlForm($arrCampoResponsable, $cols);
    
}

function obtenerCampoClientes($clientesIds = ''){
    $clientesObj = new clientesObj();
    $claseCliente = "";
    $clienteId = "";

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
        array("nameid"=>"fil_clienteId", "type"=>"select", "class"=>"form-control selectpicker required ".$claseCliente, "readonly"=>false, "label"=>"Cliente:", "datos"=>$arrCliente, "value"=>$clienteId, "dataInput"=>$data, "multivalue"=>$clientesIds),
    );

    $cols = array("label_xs"=>"4", "label_md"=>"4", "input_xs"=>"6", "input_md"=>"6");
    return generaHtmlForm($arrCampoCliente, $cols);
}

function obtenerCampoEstatus($estatusIds = ''){
   
    $claseEstatus = "";
    $estatusId = "";

    //datos
   
    $arrEstatus = array();
    // $arrResponsable[] = array("valor"=>"", "texto"=>"Seleccione...");

    $arrEstatus[] = array("valor"=>1, "texto"=>'Activo');
    $arrEstatus[] = array("valor"=>5, "texto"=>'Prospecto');    

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
    return generaHtmlForm($arrCampoEstatus, $cols);
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
    return generaHtmlForm($arrCampoJuicio, $cols);
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
    return generaHtmlForm($arrCampoJuzgado, $cols);
}

?>