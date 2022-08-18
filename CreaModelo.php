<?php
class CreaModelo{
    /*Metodo constructor de la clase CreaModelo */
    public function __construct($tablasNecesarias){
        $conexion = $this-> creaConexion();
        $this->conexion = $conexion;
        $this->directorioModelos();
        $this->descartaTablas($tablasNecesarias);
        echo '<a href="Launcher.php">Volver al inicio</a>';
    }
    /*Funcion que crea el directorio donde se guardaran los archivos que se generen
        Si existe el directorio y contiene archivos estos se eliminaran y se creara nuevamente el directorio*/
    private function directorioModelos()
    {
        if(is_dir("Modelos"))
        {
            array_map('unlink', glob("Modelos/*.*"));
            rmdir("Modelos");
        }
        mkdir("Modelos");
    }
    /*Metodo que obtiene todas las tablas de la base de datos y las filtra por las que escogimos en un inicio*/
    private function descartaTablas($tablasNecesarias){
        $querytablas = "SELECT TABLE_NAME AS nombre FROM INFORMATION_SCHEMA.TABLES";
        $resultadoTablas = $this->creaConsulta($querytablas);
        echo '<ul>';
        foreach($resultadoTablas as $tabla){
            if(in_array($tabla,$tablasNecesarias)){
                echo "\n<li>Modelo para la tabla: " .$tabla. " ...</li>";
                $this->creaModelo($tabla);
            }
        }
        echo '</ul>';
    }
    /*Este metodo se encarga de hacer consulta de las columnas de la tabla y las llaves primarias para asi poder concatenar los
        datos y asi formar el modelo en base a la tabla */
    private function creaModelo($nombreTabla){
        $nombreModelo = $nombreTabla;
        $columnas = $this->obtieneColumnas($nombreTabla);
        $llavesPrimarias = $this->obtieneLlavesPrimarias($nombreTabla);
        $codigo_llaves_metodo_for = "";
        $codigoLlavesPrimarias = "";
        if(count($llavesPrimarias)>=2){
            $codigoLlavesPrimarias .= sprintf('if(is_array($busqueda_value))
            {
                ');
            for($i = 0 ; $i<count($llavesPrimarias); $i++){
            $codigoLlavesPrimarias .=sprintf('$array_where[] = [\''.$llavesPrimarias[$i].'\', \'=\', $busqueda_value['.$i.']];
                ');
            $codigo_llaves_metodo_for .= sprintf('[\''.$llavesPrimarias[$i].'\', $this->_catalogo->get_'.strtolower($llavesPrimarias[$i]).'()],
                ');
            }
            $codigoLlavesPrimarias .= sprintf('}');
        }
        elseif(count($llavesPrimarias)==1){
            $codigoLlavesPrimarias .= sprintf('$array_where[] = [\''.$llavesPrimarias[0].'\', \'=\', $busqueda_value];
            ');
            $codigo_llaves_metodo_for .= sprintf('[\''.$llavesPrimarias[0].'\', $this->_catalogo->get_'.strtolower($llavesPrimarias[0]).'()],
            ');

        }
        else{
            $codigoLlavesPrimarias .= sprintf('$array_where[] = [\''.$columnas[0].'\', \'=\', $busqueda_value];');
            $codigo_llaves_metodo_for .= sprintf('[\''.$columnas[0].'\', $this->_catalogo->get_'.strtolower($columnas[0]).'()],
            ');
        }

        $codigo_for_busqueda = '';
        foreach($columnas as $columna){
            $codigo_for_busqueda.=sprintf('$objarrayresult[$_counter]->set_'.strtolower($columna).'($row->'.strtolower($columna).');
            ');
        }
        $columnas = \array_diff($columnas ,$llavesPrimarias);
        $codigo_for_update = '';
        foreach($columnas as $columna){
            $codigo_for_update .= sprintf('\''.$columna.'\' => $this->_catalogo->get_'.strtolower($columna).'(),
            ');
        }
        
        $codigoModelo = "";
        $codigoModelo .= sprintf('<?php
        
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Helpers\\'.$nombreModelo.'Helper;

class '.$nombreModelo."Model".' extends Model
{
    use HasFactory;
    use '.$nombreModelo.'Helper;
    protected $table = \''.$nombreModelo.'\';
    private $_catalogo;

    /* ********************************************************
                        Metodos del modelo
    ******************************************************** */

    /* ------------------------------------------------------------------------
                    Función para devolver todos los registros
    ------------------------------------------------------------------------ */

    function buscar_'.$nombreModelo.'() {
        $resultado = DB::connection($this->connection)
            ->table($this->table)
            ->where(\'borrado\',\'=\', 0)
            ->get();

        $_counter = 0;
        $objarrayresult = array();

        foreach ($resultado as $row) {
            $objarrayresult[$_counter] = new self();
            '.$codigo_for_busqueda.'$objarrayresult[$_counter]->set_fecha($row->fecha);
            $objarrayresult[$_counter]->set_usuario_idc($row->usuario_idc);
            $objarrayresult[$_counter]->set_borrado($row->borrado);

            $_counter++;
        }

        return $objarrayresult;
    }

    /* ------------------------------------------------------------------------
                Función para buscar un registro por la llave(s) primaria(s)
    ------------------------------------------------------------------------ */
    
    function buscar_'.$nombreModelo.'_id($busqueda_value) {
        $array_where = array(
            [\'borrado\', \'=\', 0]
        );
		'.$codigoLlavesPrimarias.'

        $resultado = DB::connection($this->connection)
            ->table($this->table)
            ->where($array_where)
            ->get();  
        
        $_counter = 0;
        $objarrayresult = array();

        foreach ($resultado as $row) {
            $objarrayresult[$_counter] = new self();
            
			'.$codigo_for_busqueda.'$objarrayresult[$_counter]->set_fecha($row->fecha);
            $objarrayresult[$_counter]->set_usuario_idc($row->usuario_idc);
            $objarrayresult[$_counter]->set_borrado($row->borrado);

            $_counter++;
        }

        return $objarrayresult;
    }

    /* ------------------------------------------------------------------------
            Función para buscar por un campo distinto a la llave primaria
    ------------------------------------------------------------------------ */
    
    function buscar_'.$nombreModelo.'_campo_especifico($busqueda_field, $busqueda_value) {
        $array_where = array(
            [\'borrado\', \'=\', 0]
        );

        if ($busqueda_field != \'\') {
            $array_where[] = [$busqueda_field, \'=\', $busqueda_value];
        }

        $resultado = DB::connection($this->connection)
            ->table($this->table)
            ->where($array_where)
            ->get();
        
        $_counter = 0;
        $objarrayresult = array();

        foreach ($resultado as $row) {
            $objarrayresult[$_counter] = new self();
            
			'.$codigo_for_busqueda.'$objarrayresult[$_counter]->set_fecha($row->fecha);
            $objarrayresult[$_counter]->set_usuario_idc($row->usuario_idc);
            $objarrayresult[$_counter]->set_borrado($row->borrado);

            $_counter++;
        }

        return $objarrayresult;
    }

    /* ------------------------------------------------------------------------
                Función para realizar una busqueda por semejanza
    ------------------------------------------------------------------------ */
    
    function buscar_'.$nombreModelo.'_like($busqueda_field, $busqueda_value) {
        $array_where = array(
            [\'borrado\', \'=\', 0]
        );

        if ($busqueda_field != \'\') {
            $array_where[] = [$busqueda_field, \'like\', $busqueda_value];             
        }
        
        $resultado = DB::connection($this->connection)
            ->table($this->table)
            ->where($array_where)
            ->get();

        $_counter = 0;
        $objarrayresult = array();

        foreach ($resultado as $row) {
            $objarrayresult[$_counter] = new self();
            
			'.$codigo_for_busqueda.'$objarrayresult[$_counter]->set_fecha($row->fecha);
            $objarrayresult[$_counter]->set_usuario_idc($row->usuario_idc);
            $objarrayresult[$_counter]->set_borrado($row->borrado);

            $_counter++;
        }

        return $objarrayresult;
    }
    /* ------------------------------------------------------------------------
            Función para buscar un grupo de valores en un campo especifico
    ------------------------------------------------------------------------ */
    
    function buscar_'.$nombreModelo.'_in($busqueda_field, $busqueda_value) {
        if ($busqueda_field == \'\') {
            $resultado = DB::connection($this->connection)
                ->table($this->table)
                ->where(\'borrado\', \'=\', 0)
                ->get();
        } elseif (!is_array($busqueda_value)) {
            $resultado = DB::connection($this->connection)
                ->table($this->table)
                ->where($busqueda_field, \'=\', $busqueda_value)
                ->where(\'borrado\', \'=\', 0)
                ->get();
        } else {
            $resultado = DB::connection($this->connection)
                ->table($this->table)
                ->whereIn($busqueda_field, $busqueda_value)
                ->where(\'borrado\', \'=\', 0)
                ->get();            
        }
        
        $_counter = 0;
        $objarrayresult = array();

        foreach ($resultado as $row) {
            $objarrayresult[$_counter] = new self();
            
			'.$codigo_for_busqueda.'$objarrayresult[$_counter]->set_fecha($row->fecha);
            $objarrayresult[$_counter]->set_usuario_idc($row->usuario_idc);
            $objarrayresult[$_counter]->set_borrado($row->borrado);

            $_counter++;
        }

        return $objarrayresult;
    }

    /* ------------------------------------------------------------------------
                        Función para guardar un registro
    ------------------------------------------------------------------------ */
    
    function guardar_'.$nombreModelo.'($objeto_'.$nombreModelo.') {
        $this->_catalogo = $objeto_'.$nombreModelo.';

        $resultado = DB::connection($this->connection)
            ->table($this->table)
            ->insertGetId([
            '.$codigo_for_update.'\'fecha\' => now(),
            \'usuario_idc\' => Auth::id(),
            \'borrado\' => 0
            ]);
        
        return $resultado;
    }

    /* ------------------------------------------------------------------------
                        Función para actualizar el registro
    ------------------------------------------------------------------------ */
    
    function actualizar_'.$nombreModelo.'($obj_'.$nombreModelo.') {
        $this->_catalogo = $obj_'.$nombreModelo.';

        $resultado = DB::connection($this->connection)
            ->table($this->table)
            ->where([
                '.$codigo_llaves_metodo_for.'])
            ->update([
            '.$codigo_for_update.'\'fecha\' => now(),
            \'usuario_idc\' => Auth::id(),
            \'borrado\' => 0
            ]);
        
        return $resultado;
    }

    /* ------------------------------------------------------------------------
                        Función para borrado lógico
    ------------------------------------------------------------------------ */
    
    function borrar_'.$nombreModelo.'($obj_'.$nombreModelo.') {
        $this->_catalogo = $obj_'.$nombreModelo.';

        $resultado = DB::connection($this->connection)
            ->table($this->table)
            ->where([
                '.$codigo_llaves_metodo_for.'
            ])
            ->update([
                \'fecha\' => now(),
                \'usuario_idc\' => Auth::id(),
                \'borrado\' => 1
            ]);

        return $resultado;
    }
}
?>',$nombreModelo,$codigo_for_busqueda,$codigoLlavesPrimarias,$codigo_for_update,$codigo_llaves_metodo_for);
        $Archivo = "Modelos/" . ucfirst($nombreModelo) . "Model.php";
        $Escrito = file_put_contents($Archivo, $codigoModelo);
        if ($Escrito !== false) {
            return $nombreModelo;
        } else {
            throw new Exception("No se pudo generar el modelo: $nombreModelo");
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
    /* Obtiene las llaves primarias de la tabla en turno */
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