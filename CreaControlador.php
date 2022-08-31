<?php
class CreaControlador{
    /*Metodo constructor de la clase CreaControlador que se encarga de inicializar el objeto con los parametros necesarios */
    public function __construct($tablasNecesarias){
        $conexion = $this-> creaConexion();
        $this->conexion = $conexion;
        $this->directorioControladores();
        $this->descartaTablas($tablasNecesarias);
        echo '<a href="Launcher.php">Volver al inicio</a>';
    }
    /*Funcion que crea el directorio donde se guardaran los archivos generados
        Si existe el directorio y contiene archivos estos se eliminaran y se creara nuevamente el directorio*/
        private function directorioControladores()
        {
            if(is_dir("Controladores"))
            {
                array_map('unlink', glob("Controladores/*.*"));
                rmdir("Controladores");
            }
            mkdir("Controladores");
        }
    /*Metodo que obtiene todas las tablas de la base de datos y las filtra por las que escogimos en un inicio*/
    private function descartaTablas($tablasNecesarias){
        $querytablas = "SELECT TABLE_NAME AS nombre FROM INFORMATION_SCHEMA.TABLES";
        $resultadoTablas = $this->creaConsulta($querytablas);
        echo '<ul>';
        foreach($resultadoTablas as $tabla){
            if(in_array($tabla,$tablasNecesarias)){
                echo "\n<li>Controlador para la tabla: " .$tabla. " ...</li>";
                $this->creaControlador($tabla);
            }
        }
        echo '</ul>';
    }
    /*Este metodo se encarga de hacer consulta de las columnas de la tabla y las llaves primarias para asi poder concatenar los
        datos y asi formar el controlador en base a la tabla */
    private function creaControlador($nombreTabla){
        $nombreControlador = $nombreTabla;
        $columnas = $this->obtieneColumnas($nombreTabla);
        $llavesPrimarias = $this->obtieneLlavesPrimarias($nombreTabla);
        
        $codigo_asignacion_guardado ="";
        foreach($columnas as $columna){
            $codigo_asignacion_guardado .= sprintf('$_'.$nombreTabla.'[$_counter][\''.$columna.'\'] = $obj->get_'.$columna.'();
            ');
        }
        
        $codigoControlador = "";
        $codigoControlador .= sprintf('<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\\'.$nombreTabla."Model".';

class '.$nombreControlador."Controller".' extends Controller
{
    public function index()
    {
        $values = array();
        
        $catalogo_'.strtolower($nombreControlador).' = new '.$nombreControlador."Model".'();
        $'.strtolower($nombreControlador).' = $catalogo_'.strtolower($nombreControlador).'->buscar_'.strtolower($nombreControlador).'();

        $_'.strtolower($nombreControlador).' = array();
        $_counter = 0;
        $values = array();
        
        if( count($llaveprimaria) > 0 )
        {
            foreach($llaveprimaria as $obj)
            {
                '.$codigo_asignacion_guardado.'
                $_counter++;
            }
        }
        else
        {
            $_'.$nombreTabla.' = \'\';
        }

        
        $values[\'tabla\'] = $_'.$nombreTabla.';
        $values[\'nuevo\'] = \'basico\';

        return view(\'pages.menu.table\', $values);
    }

    public function nuevo($menu_id = 0//llaves primarias)
    {
        $values = array();

        $values[\'menu_id\'] = \'\';//arreglo
        

        if( $menu_id != 0)
        {
            $catalogo_'.$nombreTabla.' = new '.$nombreTabla.'();
            $'.$nombreTabla.' = $catalogo_'.$nombreTabla.'->buscar_'.$nombreTabla.'_id($menu_id//llave primaria);

            foreach($'.$nombreTabla.' as $objeto)
            {
                $values[\'menu_id\'] = $obj->get_menu_id();//arreglo
            }
        }


        $catalogo_'.$nombreTabla.' = new '.$nombreTabla.'();
        $'.$nombreTabla.' = $catalogo_'.$nombreTabla.'->buscar_'.$nombreTabla.'_id();

        $_'.$nombreTabla.' = array();

        $_counter = 1;
        
        $values[\'menus\'] = $_menus;

        $cat_url = new Url();

        $url = $cat_url->buscar_url();
        $_url = array();
        $_counter = 0;

        if( count($url) > 0 )
        {
            $_url[$_counter][0] = \'\';
            $_url[$_counter][1] = \'Sin URL\';

            $_counter++;

            foreach($url as $obj)
            {
                $_url[$_counter][0] = $obj->get_url_id();
                $_url[$_counter][1] = $obj->get_url_nombre();

                $_counter++;
            }
        }

        $values[\'contenido\'] = $_contenido;

        return view(\'pages.menu.new\', $values);
    }
}
?>');
        $Archivo = "Controladores/" . ucfirst($nombreControlador) . "Controller.php";
        $Escrito = file_put_contents($Archivo, $codigoControlador);
        if ($Escrito !== false) {
            return $nombreControlador;
        } else {
            throw new Exception("No se pudo generar el controlador: $nombreControlador");
        }
    }
    
    /*
        ->Funcion para obtener las columnas
    */
    private function obtieneColumnas($nombreTabla){
        $queryColumnas = "SELECT COLUMN_NAME AS nombre FROM information_schema.columns WHERE TABLE_NAME = '" . $nombreTabla . "'";
        $resultadoColumnas = $this->creaConsulta($queryColumnas);
        return $resultadoColumnas;
    }

    private function obtieneLlavesPrimarias($nombreTabla){
        $queryLlaves = "SELECT COL_NAME(IC.OBJECT_ID,IC.COLUMN_ID) AS nombre
        FROM SYS.INDEXES  X 
        INNER JOIN SYS.INDEX_COLUMNS  IC 
        ON X.OBJECT_ID = IC.OBJECT_ID AND X.INDEX_ID = IC.INDEX_ID
        WHERE X.IS_PRIMARY_KEY = 1 AND OBJECT_NAME(IC.OBJECT_ID)='".$nombreTabla."'";
        $resultadoLlaves = $this->creaConsulta($queryLlaves);
        return $resultadoLlaves;
    }
    /*
        ->Funcion que crea la conexion a la base de datos
    */
    private function creaConexion()
    {
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

    /*
        ->Funcion que regresa el arreglo de datos en base a la consulta dada
    */
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