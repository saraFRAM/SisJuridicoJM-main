<?php 

function obtTipoCobro($tipoCobro = ""){
    if($tipoCobro > 0){
        switch ($tipoCobro) {//31/3/2022
            case 1 : $estatus = "Probono"; break;
            case 2 : $estatus = "Sin pago temporal"; break;
            case 3 : $estatus = "Pago activo"; break;
            case 4 : $estatus = "Liquidado"; break;
          }

          return $estatus;
    }else{
        $arrTipoCobro = array();
        $arrTipoCobro[] = array("valor"=>"", "texto"=>"Seleccione...");
        $arrTipoCobro[] = array("valor"=>1, "texto"=>"Probono");
        $arrTipoCobro[] = array("valor"=>2, "texto"=>"Sin pago temporal");
        $arrTipoCobro[] = array("valor"=>3, "texto"=>"Pago activo");
        $arrTipoCobro[] = array("valor"=>4, "texto"=>"Liquidado");
        return $arrTipoCobro;
    }
}

    function obtEstatusCaso($estatusId  = 0){
        switch ($estatusId) {//31/3/2022
            case 1 : $estatus = "Activo"; break;
            case 2 : $estatus = "Suspendido"; break;
            case 3 : $estatus = "Baja"; break;
            case 4 : $estatus = "Terminado"; break;
            case 5 : $estatus = "Prospecto"; break;
            default:
              # code...
              break;
          }

          return $estatus;
    }

    function obtSaludCaso($saludExpediente = 0){
        switch ($saludExpediente) {//31/3/2022
            case 1 : $salud = "Verde"; break;
            case 2 : $salud = "Amarillo"; break;
            case 3 : $salud = "Rojo"; break;
           
            default:
              # code...
              break;
          }

          return $salud;
    }

    function obtVelocidadCaso($velocidad = 0){
        switch ($velocidad) {//31/3/2022
            case 1 : $velocidadTxt = "Normal"; break;
            case 2 : $velocidadTxt = "Media"; break;
            case 3 : $velocidadTxt = "Alta"; break;
           
            default:
              # code...
              break;
          }

          return $velocidadTxt;
    }

    function obtContundenciaCaso($contundencia = 0){
        switch ($contundencia) {//31/3/2022
            case 1 : $contundenciaTxt = "Fuerte"; break;
            case 2 : $contundenciaTxt = "Muy fuerte"; break;
            case 3 : $contundenciaTxt = "Implacable"; break;
           
            default:
              # code...
              break;
          }

          return $contundenciaTxt;
    }

    function obtFolderDigital($tipo = 0){
        switch ($tipo) {
            case 1: $folder = 'escritos'; break;
            case 2: $folder = 'expedientes'; break;
            case 3: $folder = 'audiencias'; break;
            case 4: $folder = 'otros'; break;
            case 5: $folder = 'audios'; break;
            default:
              # code...
              break;
        }

        return $folder;
    }

    function obtPlanPagos($planId = ""){
        if($planId > 0){
            switch ($planId) {//31/3/2022
                case 1 : $plan = "Monto fijo pago unico"; break;
                case 2 : $plan = "Pago inicial e igualas mensuales"; break;
                case 3 : $plan = "Pago inicial y pago sobre avances"; break;
                case 4 : $plan = "Monto fijo en pagos"; break;
              }
    
              return $plan;
        }else{
            $arrPlan = array();
            $arrPlan[] = array("valor"=>"", "texto"=>"Seleccione...");
            $arrPlan[] = array("valor"=>1, "texto"=>"Monto fijo pago unico");
            $arrPlan[] = array("valor"=>2, "texto"=>"Pago inicial e igualas mensuales");
            $arrPlan[] = array("valor"=>3, "texto"=>"Pago inicial y pago sobre avances");
            $arrPlan[] = array("valor"=>4, "texto"=>"Monto fijo en pagos");
            return $arrPlan;
        }
    }

    function getArrayCamposGridExpediente(){
        $arrCampos = array();
        $arrCampos[] = array("valor" => "numExpediente", "texto" => "Expediente Interno");
        $arrCampos[] = array("valor" => "numExpedienteJuzgado", "texto" => "Num Exp Juz");
        $arrCampos[] = array("valor" => "saludExpediente", "texto" => "Salud");
        $arrCampos[] = array("valor" => "estatusId", "texto" => "Estatus");
        $arrCampos[] = array("valor" => "titular", "texto" => "Responsable");
        $arrCampos[] = array("valor" => "cliente", "texto" => "Cliente");
        $arrCampos[] = array("valor" => "representado", "texto" => "Representado");
        $arrCampos[] = array("valor" => "contrario", "texto" => "Contrario");
        $arrCampos[] = array("valor" => "tipocaso", "texto" => "Tipo cliente");
        $arrCampos[] = array("valor" => "parteId", "texto" => "Parte");
        $arrCampos[] = array("valor" => "materiaId", "texto" => "Materia");
        $arrCampos[] = array("valor" => "juicioId", "texto" => "Juicio");
        $arrCampos[] = array("valor" => "juzgadoId", "texto" => "Juzgado");
        $arrCampos[] = array("valor" => "fechaAlta2", "texto" => "F. Alta");
        $arrCampos[] = array("valor" => "fechaAct2", "texto" => "Cambio Exp.");
        $arrCampos[] = array("valor" => "ultimaActividad2", "texto" => "F. Ult. Act.");
        $arrCampos[] = array("valor" => "diferenciaFecha", "texto" => "Dias S/Mov");
        $arrCampos[] = array("valor" => "noLeidos", "texto" => "Com. No Leidos");
        
        
        $arrCampos[] = array("valor" => "nombreJuez", "texto" => "Nombre juez");
        $arrCampos[] = array("valor" => "nombreSecretaria", "texto" => "Nombre secretaria");
        $arrCampos[] = array("valor" => "escritos", "texto" => "Num. de escritos");
        $arrCampos[] = array("valor" => "expediente", "texto" => "Num. de expedientes");
        $arrCampos[] = array("valor" => "audiencias", "texto" => "Num. de aud");
        $arrCampos[] = array("valor" => "otros", "texto" => "Num. de otros");
        $arrCampos[] = array("valor" => "total", "texto" => "Total de archivos");
        $arrCampos[] = array("valor" => "ProxAudCitEmp", "texto" => "Prox. Aud, Cit o Emp");
        $arrCampos[] = array("valor" => "ultActividad", "texto" => "Ult. Actividad");
        return $arrCampos;
    }


