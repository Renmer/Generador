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
    class GenerarDatos{
        
        public function __construct($server,$db,$user,$password,$tipoDeBD){
            $conexion = $this->seleccionaTipoBD($server,$db,$user,$password,$tipoDeBD);
            echo '<script>document.getElementById("formulario").style.display = "none"</script>';
            $this->ejecutaConsulta($conexion);
            $tsql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES;";
            $dato = sqlsrv_query($conn, $tsql);
            $this->generaTabla($dato,$server,$db,$user,$password);
        }
        function generaTabla($dato,$server,$db,$user,$password){
            echo '
                <form action="CreaMVC.php?server='.urlencode($server).'&db='.urlencode($db).
                    '&user='.urlencode($user).'&password='.urlencode($password).'" method="post">

                    <table id="tabla" border=1 cellspacing=0 cellpadding=2 bordercolor="666633">
                        <tr>
                            <th>Seleccion</th>
                            <th>Tabla</th>
                        </tr>';
                        while($row = sqlsrv_fetch_array($dato, SQLSRV_FETCH_ASSOC))
                        {
                            echo "<tr><td>".'<input type="checkbox" name="select[]" value="'.$row['TABLE_NAME'].'">'."</td>";
                            echo "<td>".$row['TABLE_NAME']."</td></tr>";
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
        function seleccionaTipoBD($server,$db,$user,$password,$tipoDeBD){
            switch ($tipoDeBD) {
                case 'sqlserver':
                    $connectionInfo =  array("Database"=>$db,"UID"=>$user,"PWD"=>$password);
                    if(sqlsrv_connect($server,$connectionInfo)){
                        $conn = sqlsrv_connect($server,$connectionInfo);
                    }
                     else{
                        echo 'Error en las credenciales';
                        die();
                    }
                    return $conn;

                case 'mysql':
                    echo "i equals 1";

                default:
                   echo "El tipo de base de datos no se encuentra";
            }
        }
        function ejecutaConsulta(){

        }

    }

if ($_POST) {
    if(empty($_POST['server']) or empty($_POST['db'])){
        echo 'Debe añadir un servidor y una base de datos<br>';
        die();
    }
    else{
        $datos = new GenerarDatos($_POST['server'],$_POST['db'],$_POST['user'],$_POST['password'],$_POST['tipodb']);
    }
}
?>
        