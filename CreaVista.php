<?php
class CreaVista{

    public function __construct($tablasNecesarias){
        $conexion = $this-> creaConexion();
        $this->conexion = $conexion;
        $this->directorioVistas();
        // $this->descartaTablas($tablasNecesarias);

    }

    private function directorioVistas()
    {
        if(is_dir("Vistas")){
            array_map('unlink', glob("Vistas/*.*"));
            rmdir("Vistas");
        }
        mkdir("Vistas");
    }

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