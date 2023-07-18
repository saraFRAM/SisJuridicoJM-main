<?php
$dirname = dirname(__DIR__);
include_once $dirname.'/brules/usuariosObj.php';
include_once $dirname.'/brules/productosObj.php';
include_once $dirname.'/brules/utilsObj.php';


$tabla = $_GET['tabla'];
switch ($tabla)
{
    case "tablaProductos":
        getTablaProductos($_GET['tabla']);
    break;
default:
      echo "Not valid call";
}
function getTablaProductos($idTabla)
{
	$productosObj = new productosObj();

	$productos = $productosObj->GetAllProductos();
	$recordsTotal = count($productos);
	$recordsFiltered = count($productos);
// echo "<pre>";print_r($registros);echo "</pre>";
	$arrFilas = array();
	$cont = 0;
	$mostrados = 0;
	$hasta = ($_POST["length"] == -1)?$recordsFiltered:($_POST["length"]+$_POST["start"]);
	$hasta = ($hasta > $recordsFiltered)?$recordsFiltered:$hasta;
	$hasta = ($hasta === 0)?1:$hasta;
	// echo "hasta: ".$hasta."<br>";
	if($recordsTotal > 0){
	for ($i=$_POST["start"]; $i < $hasta; $i++) {
		$producto = $productos[$i];
        $usuariosObj = new usuariosObj();
        $usuario = $usuariosObj->UserByID($producto->usuarioId);

        $valores = $producto->idProducto.','.$producto->numero.','.$producto->numeroMadre.', \''.convertirFechaVistaConHora($producto->fechaSacrificio).'\',\''.convertirFechaVistaConHora($producto->fechaNacimiento.'\' ,'.$producto->origenId);

        $acciones = '<a class="btn btn-primary" href="../conoce.php?num='.$producto->numero.'" target="_blank">Ir</a>';
        $acciones .= '<a href="#fancyProductos" class="btnFancy" onclick="muestraProducto('.$producto->idProducto.')"><img class="iconoDesactivar" src="../images/icon_editar.png"></a>';
        $acciones .= '<a href="#fancyDespiece" class="btnFancy" onclick="agregarDespiece('.$producto->idProducto.', \''.$producto->numero.'\')" title="Agregar despiece" id="btn_despiece_'.$producto->idProducto.'"><img class="iconoDesactivar" src="../images/icon_agregar.png"></a>';
        $acciones .= '<a href="#fancyVerDespieces" class="btnFancy" onclick="verDespieces('.$producto->idProducto.', \''.$producto->numero.'\')" title="Ver despieces" id="btn_verdesp_'.$producto->idProducto.'"><img class="iconoDesactivar" src="../images/icon_list.png"></a>';

        $fila = array();
        $fila[] = '<input type="checkbox" class="checkProd" name="check_'.$producto->idProducto.'" id="check_'.$producto->idProducto.'">';
        $fila[] = $producto->idProducto;
        $fila[] = $producto->numero;
        $fila[] = $producto->numeroMadre;
        $fila[] = convertirFechaVistaConHora($producto->fechaSacrificio);
        $fila[] = $usuario->nombre;
        $fila[] = $acciones;
        $arrFilas[] = $fila;
	}
	}//Fin si hay resultados


	echo json_encode(array("recordsTotal"=>$recordsTotal, "recordsFiltered"=>$recordsFiltered,"data"=>$arrFilas));
}
?>