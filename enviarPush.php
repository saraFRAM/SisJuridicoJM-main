<?php
include_once 'brules/mensajesObj.php';
include_once 'brules/registroDispositivosObj.php';
$mensajesObj = new mensajesObj();
$regDispObj = new registroDispositivo();


//Parametros get
$idUsuario = $_GET['idUsuario'];
if($idUsuario!="" && $idUsuario>0){
	//consultar el id del dispositivo	
	$regDispObj->usuarioId = $idUsuario;
	$colRegDisp = $regDispObj->obtTodosRegDispositivoPorIdUsr();                   
	$regid = "";	
	if(count($colRegDisp)>0){	    
	    foreach ($colRegDisp as $elemRegDisp){
	      $regid = $elemRegDisp->idRegDispositivo;
	    }
	}

	// $regid = "e-u3p0mlBmo:APA91bEQeMqByvRBUiWS2H2AVzdEkRJqCS8llbMSWspVsDkie_Zn6FesBx6DtYC1vSBSGSbU3T7lFY7Eq7KmwJAHEWnBupOkBDeTpx11OeGRDfQmUCR3FZ1uXwCA1WfvibkemZqVsw_H";	
	$titulo = "Mensaje de prueba";
	$contenido = "Esta es la descripción del mensaje de prueba";
	
	//Setar datos
	$mensajesObj->usuarioId = $idUsuario;
	$mensajesObj->leido = 0;
	$mensajesObj->mostrar = 1;
	$mensajesObj->titulo = $titulo;
	$mensajesObj->contenido = $contenido;
	$mensajesObj->idUsuarioCmb = 1;
	$mensajesObj->GuardarMensajesObj(); //Salvar mensaje en db

	//Comprobar si se salvo para despues enviar el mensaje push
	if($mensajesObj->idMensaje>0){
		echo "El mensaje fue salvado en db<br/>";
		if($regid!=""){
			$resPush = gcmSend($regid, $titulo);
			// echo $resPush;	
			$objPush = json_decode($resPush);
			if($objPush->success==1){
				echo "El mensaje push se envio al dispositivo.";
			}else{
				echo "El mensaje push no fue posible enviarlo.";
			}
		}		
	}
}else{
	echo "Agregue como parametro el id del usuario";	
}


//Metodo que envia notificaciones push al dispositivo "Para android"
function gcmSend($regid, $message){
	$apiKey = 'AIzaSyBSh7RqTjkttVm_TPvPlY0XGC_fE9s9agY';          

	//api key admin-secon-beta https://console.developers.google.com/iam-admin/iam/project?project=admin-secon-beta
	$gcmUrl = 'https://android.googleapis.com/gcm/send';

	// Send message:
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $gcmUrl);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER,
		array(
			'Authorization: key=' . $apiKey,
			'Content-Type: application/json'
		)
	);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(
			array(
				'registration_ids' => array($regid),
				'data' => array(
					'message' => $message
				)
			),
			JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
		)
	);

	$result = curl_exec($ch);
	//if ($result === false) {
	//    throw new \Exception(curl_error($ch));
	//}
	curl_close($ch);

	return $result;
}


// $resPush = '{"multicast_id":7919221784625800906,"success":1,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1536266923843775%7b97700bf9fd7ecd"}]}';
// $objPush = json_decode($resPush);
// if($objPush->success==1){
// 	echo "El mensaje push se envio al dispositivo.";
// }else{
// 	echo "El mensaje push no fue posible enviarlo.";
// }