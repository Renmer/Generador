<?php
$creacion = new CreaModelo($_GET['server'],$_GET['db'],$_GET['user'],$_GET['password'],$_POST['select']);

class CreaModelo{

    public function __construct($servidor, $db, $user, $password , $tablasNecesarias){
        $connectionInfo =  array("Database"=>$db,"UID"=>$user,"PWD"=>$password);
        $conexion = sqlsrv_connect($servidor,$connectionInfo);
        $this->conexion = $conexion;
        $this->directorioModelos();
        $this->descartaTablas($conexion,$tablasNecesarias);
        echo '<a href="Launcher.php">Volver al inicio</a>';

    }

    private function directorioModelos()
    {
        if(is_dir("Helpers")){
            array_map('unlink', glob("Helpers/*.*"));
            rmdir("Helpers");
        }
        mkdir("Helpers");
    }
    private function descartaTablas($conexion,$tablasNecesarias){
        $tablasAll = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES;";
        $resultadoTablasAll = sqlsrv_query($conexion, $tablasAll);
        echo '<ul>';
        while($row = sqlsrv_fetch_array($resultadoTablasAll, SQLSRV_FETCH_ASSOC)){
            if (in_array($row['TABLE_NAME'], $tablasNecesarias)) {

                echo "\n<li>Helper para la tabla: " . $row['TABLE_NAME'] . " ...</li>";
                $this->creaModelo($row['TABLE_NAME']);
            }
        }
        echo '</ul>';
    }
    private function creaModelo($nombreTabla){
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

namespace App\Helpers;

class '.$nombreModelo.'
{
    '.$codigoVariables.'
    /*-->[Metodos set para cada una de las variables definidas]<--*/
    '.$codigoMetodosGet.'
    /*-->[Metodos get para cada una de las variables definidas]<--*/
    '.$codigoMetodosSet.'
}
?>');
        $Archivo = "Helpers/" . ucfirst($nombreModelo) . ".php";
        $Escrito = file_put_contents($Archivo, $codigoModelo);
        if ($Escrito !== false) {
            return $nombreModelo;
        } else {
            throw new Exception("No se pudo generar el modelo: $nombreModelo");
        }
    }
    private function obtieneColumnas($nombreTabla){
        $queryColumnas = "SELECT COLUMN_NAME AS columna FROM information_schema.columns WHERE TABLE_NAME = '" . $nombreTabla . "'";
        $resultadoColumnas = sqlsrv_query($this->conexion, $queryColumnas);
        $columnas = array();
        while($row = sqlsrv_fetch_array($resultadoColumnas, SQLSRV_FETCH_ASSOC)){
            array_push($columnas,$row['columna']);
        }
        return $columnas;
    }

}
?>