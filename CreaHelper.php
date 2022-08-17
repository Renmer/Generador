<?php
class CreaHelper{

    public function __construct($tablasNecesarias){
        $conexion = $this-> creaConexion();
        $this->conexion = $conexion;
        $this->directorioHelpers();
        $this->descartaTablas($tablasNecesarias);

    }

    private function directorioHelpers()
    {
        if(is_dir("Helpers")){
            array_map('unlink', glob("Helpers/*.*"));
            rmdir("Helpers");
        }
        mkdir("Helpers");
    }
    private function descartaTablas($tablasNecesarias){
        $querytablas = "SELECT TABLE_NAME AS nombre FROM INFORMATION_SCHEMA.TABLES";
        $resultadoTablas = $this->creaConsulta($querytablas);
        echo '<ul>';
        foreach($resultadoTablas as $tabla){
            if(in_array($tabla,$tablasNecesarias)){
                echo "\n<li>Helper para la tabla: " .$tabla. " ...</li>";
                $this->creaHelper($tabla);
            }
        }
        echo '</ul>';
    }
    private function creaHelper($nombreTabla){
        $nombreModelo = $nombreTabla;
        $columnas = $this->obtieneColumnas($nombreTabla);
        $codigoVariables = "";
        foreach($columnas as $columna){
            $codigoVariables.= sprintf('private $_'.strtolower($columna).';
    ');
        }
        $codigoMetodosSet = "";
        foreach($columnas as $columna){
            $codigoMetodosSet.= sprintf('public function get_'.strtolower($columna).'()
    {
        return $this->_'.strtolower($columna).';
    }
    ');
        }
        $codigoMetodosGet = "";
        foreach($columnas as $columna){
            $codigoMetodosGet.= sprintf('public function set_'.strtolower($columna).'( $'.strtolower($columna).' )
    {
        $this->_'.strtolower($columna).' = $'.strtolower($columna).';
    }
    ');
        }
        $codigoModelo = sprintf('<?php

namespace App\Models\Helpers;

class '.$nombreModelo.'Helper'.'
{
    '.$codigoVariables.'
    /*-->[Metodos set para cada una de las variables definidas]<--*/
    '.$codigoMetodosGet.'
    /*-->[Metodos get para cada una de las variables definidas]<--*/
    '.$codigoMetodosSet.'
}
?>');
        $Archivo = "Helpers/" . ucfirst($nombreModelo) ."Helper". ".php";
        $Escrito = file_put_contents($Archivo, $codigoModelo);
        if ($Escrito !== false) {
            return $nombreModelo;
        } else {
            throw new Exception("No se pudo generar el helper: $nombreModelo");
        }
    }
    private function obtieneColumnas($nombreTabla){
        $queryColumnas = "SELECT COLUMN_NAME AS nombre FROM information_schema.columns WHERE TABLE_NAME = '" . $nombreTabla . "'";
        $resultadoColumnas = $this->creaConsulta($queryColumnas);
        return $resultadoColumnas;
    }
    /*
        ->Funcion que crea la conexion a la base de datos
    */
    private function creaConexion(){

        switch ($_SESSION['tipodb']) {
            case 'sqlserver':
                $connectionInfo =  array("Database"=>$_SESSION['db'],"UID"=>$_SESSION['user'],"PWD"=>$_SESSION['password'],"ConnectionPooling"=>true);
                if(sqlsrv_connect($_SESSION['server'],$connectionInfo)){
                    $conn= sqlsrv_connect($_SESSION['server'],$connectionInfo);
                }
                 else{
                    echo 'Error en las credenciales de sqlserver';
                    die();
                }
                return $conn;

            case 'mysql':
                if(new PDO("mysql:host=".$_SESSION['server'].";dbname=".$_SESSION['db']."", $_SESSION['user'],$_SESSION['password'])){
                    $conn =new PDO("mysql:host=".$_SESSION['server'].";dbname=".$_SESSION['db']."", $_SESSION['user'],$_SESSION['password']);
                }
                else{
                    echo 'Error en las credenciales de mysql';
                    die();
                }
                return $conn;

            default:
               echo "El tipo de base de datos no se encuentra";
               die();
        }
    }
    private function creaConsulta($query){
        switch ($_SESSION['tipodb']) 
        {
            case 'sqlserver':
                $datos = sqlsrv_query($this->conexion,$query);
                $dato_en_arreglo = array();
                while($row = sqlsrv_fetch_array($datos, SQLSRV_FETCH_ASSOC))
                {
                    array_push($dato_en_arreglo,$row['nombre']);
                }
                return $dato_en_arreglo;

            case 'mysql':
                $datos = $this->conexion->query($query)->fetchAll(PDO::FETCH_OBJ);
                $dato_en_arreglo = array();
                foreach($datos as $dato)
                {
                    array_push($dato_en_arreglo,$dato->nombre);
                }
                return $dato_en_arreglo;
            default:
                echo "El tipo de base de datos no es correcto";
                die();
        }
    }

}
?>