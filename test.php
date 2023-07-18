<?php
include_once 'brules/usuariosObj.php';
$userObj = new usuariosObj();

echo "Hola mundo<br>";

$usuarios = $userObj->obtTodosUsuarios();

foreach ($usuarios as $usuario) {
	echo $usuario->idUsuario." - ".$usuario->nombre."<br>";
}

echo "<pre>";print_r($usuarios);echo"</pre>";

$user = $userObj->UserByID(1);
echo "<pre>";print_r($user);echo"</pre>";
echo "nombre: ".$user->nombre."<br>";
$user = $userObj->UserByID(6);
echo "<pre>";print_r($user);echo"</pre>";
echo "nombre: ".$user->nombre."<br>";
echo "fin<br>";

$cadena = ",11,12,13,14,16,19,,";
$arrCadena = preg_split("/[,]+/", $cadena,NULL,PREG_SPLIT_NO_EMPTY);
echo 'tengo:';
echo "<pre>";
print_r($arrCadena);
echo "</pre>";