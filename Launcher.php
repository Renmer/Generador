<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanzador</title>
</head>
<body>
    <form action="" id="formulario" method="POST">
        <label for="">Nombre del servidor</label>
        <br>
        <input class="inputs" type="text" name="server">
        <br>
        <label for="">Nombre de la base de datos</label>
        <br>
        <input class="inputs" type="text" name="db">
        <br>
        <label for="">Usuario</label>
        <br>
        <input class="inputs" type="text" name="user">
        <br>
        <label for="">Contraseña</label>
        <br>
        <input class="inputs" type="password" name="password">
        <br>
        <label for="">Tipo de base de datos</label>
        <br>
        <select name="tipodb" id="tipo">
            <option value="sqlserver">SQLServer</option>
            <option value="mysql">MySQL</option>
        </select>
        <br>
        <br>
        <button type="submit">Validar</button>
    </form>
</body>
</html>
<style>

    .inputs{
        margin: 5px;
        width: 200px;
    }
    
    label{
        padding: 5px;
        margin: 10px;
        width: 200px;
    }
    select{
        margin: 5px;
        width: 200px;
    }
    button{
        margin: 5px;
    }
    table{
        padding: 5px;
        margin: 10px;
        width: 200px; 
    }
</style>

<?php
    session_start();

    class GenerarDatos{
        
        public function __construct($server,$db,$user,$password){
            $conexion = $this->seleccionaTipoBD($server,$db,$user,$password);
            echo '<script>document.getElementById("formulario").style.display = "none"</script>';
            $dato = $this->ejecutaConsulta($conexion,$db);
            $this->generaTabla($dato);
        }
        function generaTabla($dato){
            echo '
                <form action="CreaMVC.php" method="POST">

                    <table id="tabla" border=1 cellspacing=0 cellpadding=2 bordercolor="666633">
                        <tr>
                            <th>Seleccion</th>
                            <th>Tabla</th>
                        </tr>';
                        foreach($dato as $tabla){
                            echo "<tr><td>".'<input type="checkbox" name="select[]" value="'.$tabla.'">'."</td>";
                            echo "<td>".$tabla."</td></tr>";
                        }
            echo '
                    </table>
                    <label>Plataforma</label>
                    <br>
                    <select name="plataforma">
                        <option value="laravel">Laravel</option>
                        <option value="dotnet">.Net</option>
                    </select>
                    <br>
                    <br>
                    <button type="submit"/>Generar</button>
                </form>';
        }
        function seleccionaTipoBD($server,$db,$user,$password){
            switch ($_SESSION['tipodb']) {
                case 'sqlserver':
                    $connectionInfo =  array("Database"=>$db,"UID"=>$user,"PWD"=>$password);
                    if(sqlsrv_connect($server,$connectionInfo)){
                        $conn= sqlsrv_connect($server,$connectionInfo);
                    }
                     else{
                        echo 'Error en las credenciales de sqlserver';
                        die();
                    }
                    return $conn;

                case 'mysql':
                    if(new PDO("mysql:host=$server;dbname=$db", $user,$password)){
                        $conn =new PDO("mysql:host=$server;dbname=$db", $user,$password);
                    }
                    else{
                        echo 'Error en las credenciales de mysql';
                        die();
                    }
                    return $conn;

                default:
                   echo "El tipo de base de datos no se encuentra";
            }
        }
        function ejecutaConsulta($conexion,$db){
            switch ($_SESSION['tipodb']) {
                case 'sqlserver':
                    $tsql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES;";
                    $dato = sqlsrv_query($conexion, $tsql);
                    $dato_en_arreglo = array();
                    while($row = sqlsrv_fetch_array($dato, SQLSRV_FETCH_ASSOC))
                    {
                        array_push($dato_en_arreglo,$row['TABLE_NAME']);
                    }
                    return $dato_en_arreglo;

                case 'mysql':
                    $dato = $conexion->query("SELECT TABLE_NAME AS nombre FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = '" . $db . "'")
                    ->fetchAll(PDO::FETCH_OBJ);
                    $dato_en_arreglo = array();
                    foreach($dato as $tabla){
                        array_push($dato_en_arreglo,$tabla->nombre);
                    }
                    return $dato_en_arreglo;
                default:
                   echo "El tipo de base de datos no es correcto";
            }

        }

    }
if ($_POST) {
    if(empty($_POST['server']) or empty($_POST['db'])){
        echo 'Debe añadir un servidor y una base de datos<br>';
        die();
    }
    else{
        $_SESSION['db'] = $_POST['db'];
        $_SESSION['tipodb'] = $_POST['tipodb'];
        $_SESSION['server'] = $_POST['server'];
        $_SESSION['user'] = $_POST['user'];
        $_SESSION['password'] = $_POST['password'];
        $datos = new GenerarDatos($_POST['server'],$_POST['db'],$_POST['user'],$_POST['password']);
    }
}
?>
        