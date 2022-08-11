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
        <input type="text" name="server">
        <br>
        <label for="">Base de datos</label>
        <br>
        <input type="text" name="db">
        <br>
        <label for="">Usuario</label>
        <br>
        <input type="text" name="user">
        <br>
        <label for="">Contraseña</label>
        <br>
        <input type="password" name="password">
        <br>
        <br>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
<style>
    label{
        padding: 5px;
        margin: 10px;
    }
    input,button{
        margin: 5px;
    }
</style>

<?php
    class GenerarDatos{
        
        public function __construct($server,$db,$user,$password){
            $connectionInfo =  array("Database"=>$db,"UID"=>$user,"PWD"=>$password);
            if(sqlsrv_connect($server,$connectionInfo)){
                $conn = sqlsrv_connect($server,$connectionInfo);
            }
            else{
                echo 'Error en las credenciales';
                die();
            }
            echo '<script>document.getElementById("formulario").style.display = "none"</script>';
            $tsql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES;";
            $dato = sqlsrv_query($conn, $tsql);
            $this->generaTabla($dato,$server,$db,$user,$password);
        }
        function generaTabla($dato,$server,$db,$user,$password){
            echo '
                <form action="CreaModelo.php?server='.urlencode($server).'&db='.urlencode($db).
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
                    <input type="submit" value="Generar"/>
                </form>';
        }

    }

if ($_POST) {
    if(empty($_POST['server']) or empty($_POST['db'])){
        echo 'Debe añadir un servidor y una base de datos';
        die();
    }
    else{
        $datos = new GenerarDatos($_POST['server'],$_POST['db'],$_POST['user'],$_POST['password']);
    }
}
?>
        