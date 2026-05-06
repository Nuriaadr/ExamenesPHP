<?php
$url_aulas = DIR_SERV . "/aulas";
$respuesta_aulas = consumir_servicios_JWT_REST($url_aulas, 'get', $headers);
$json_aulas = json_decode($respuesta_aulas, true);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen Rec Final PHP</title>
    <style>
        .enlinea {
            display: inline
        }

        .enlace {
            background: none;
            border: none;
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h1>Examen Rec Final PHP</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usu_log["usuario"]; ?></strong> - <form class="enlinea" action="index.php"
            method="post"><button class="enlace" type="submit" name="btnSalir">Salir</button></form>
    </div>

    <form method="post" action="index.php">
        <p>Elija un aula:
        <select name="aula">
            <?php
            foreach ($json_aulas['aulas'] as $aula) {
                if (isset($_POST['aula']) && $_POST['aula'] == $aula['id_aula']) {
                    echo "<option selected value='" . $aula['id_aula'] . "'>" . $aula['nombre'] . "</option>";

                } else
                    echo "<option value='" . $aula['id_aula'] . "'>" . $aula['nombre'] . "</option>";
            }
            ?>
        </select>
        <button type="submit" name="btnOcupacion">Consultar ocupación</button>
        </p>
    </form>

    <?php
        if(isset($_POST["btnOcupacion"])){
            echo "<h3>Ocupación del aula: </h3>";
        }
    ?>
</body>

</html>