<?php
$gRecaptchaResponse = $_POST["response"];
$secretKey = "6LfslSUTAAAAALfWFpgQsBAiS3Rzw5h6pLVWwcFw"; //reemplazar por la llave secreta
$ip = $_SERVER['REMOTE_ADDR'];

$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$gRecaptchaResponse."&remoteip=".$ip);
$responseKeys = json_decode($response,true);

if(intval($responseKeys["success"]) !== 1) {
  $result = array("result"=>"error");
}else{
  $result = array("result"=>"ok");
}
echo json_encode($result);