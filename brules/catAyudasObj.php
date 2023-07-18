<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/catAyudasDB.php';

class catAyudasObj {
    private $_idAyuda = 0;    
    private $_alias = '';
    private $_titulo = '';
    private $_descripcion = '';
    private $_fechaCreacion = '0000-00-00 00:00:00';    
    private $_tipo = 0;

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion del catalogo de ayudas
    public function ObtTodosCatAyudas($tipo = ""){
        $array = array();
        $ds = new catAyudasDB();
        $datosBD = new datosBD();
        $result = $ds->ObtTodosCatAyudasDB($tipo);
        $array =  $datosBD->arrDatosObj($result);

        return $array;
    }

    public function ObtAyudaPorAlias($alias = "")
    {
        $DS = new catAyudasDB();
        $datosBD = new datosBD();
        $obj = new catAyudasObj();
        $result = $DS->ObtAyudaPorAliasDB($alias);     
        return $datosBD->setDatos($result, $obj);
    }

    public function ActAyudaPorAlias($campo , $valor, $alias)
    {
        $DS = new catAyudasDB();
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $alias;

        $result = $DS->updateAyudaPorAliasDB($param);     
        
        return $result;
    }




}