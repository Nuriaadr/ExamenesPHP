<?php
$url_horario_profe = DIR_API_HORARIO . '/horarioProfesor/' . $datos_usu_log['id_usuario'];
$respuesta_horario_profe = consumir_servicios_JWT_REST($url_horario_profe, 'GET', $headers);
$json_horario_profe = json_decode($respuesta_horario_profe, true);

if (!$json_horario_profe) {
    session_destroy();
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
}
if (isset($json_horario_profe["error"])) {
    session_destroy();
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_horario_profe["error"] . "</p>"));
}

$horario_profe = [];
foreach ($json_horario_profe['horario'] as $horario) {
    if (isset($horario_profe[$horario["dia"]][$horario["hora"]])) {
        $horario_profe[$horario["dia"]][$horario["hora"]]["grupo"] .= " / " . $horario["grupo"];
    } else {
        $horario_profe[$horario["dia"]][$horario["hora"]]["grupo"] = $horario["grupo"];
        $horario_profe[$horario["dia"]][$horario["hora"]]["aula"] = $horario["aula"];
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen Final PHP</title>
    <style>
        .enlace {
            background: none;
            border: none;
            color: blue;
            text-decoration: underline;
            cursor: pointer
        }

        table,
        th,
        td {
            border: 1px solid black
        }

        table {
            border-collapse: collapse;
            margin: 0 auto;
            width: 90%;
            text-align: center
        }

        th {
            background-color: #CCC
        }
    </style>
</head>

<body>
    <h1>Examen Final PHP</h1>
    <form action="index.php" method="post">
        <p>Bienvenido <strong><?php echo $datos_usu_log["usuario"]; ?></strong> - <button type="submit" class="enlace" name="btnCerrarSesion">Salir</button></p>
    </form>
    <h2>Su horario</h2>
    <h3>Horario del profesor: <?php echo $datos_usu_log["nombre"]; ?></h3>

    <table>
        <tr>
            <th></th>
            <?php
            for ($dia = 1; $dia <= count(DIAS); $dia++) {
                echo "<th>" . DIAS[$dia] . "</th>";
            }
            ?>
        </tr>

        <?php
        for ($hora = 1; $hora <= count(HORAS); $hora++) {
            echo "<tr>";
            if ($hora == 4) {
                echo "<th>" . HORAS[$hora] . "</th>";
                echo "<th colspan='6'>RECREO</th>";
                continue;
            }
            echo "<th>" . HORAS[$hora] . "</th>";
            for ($dia = 1; $dia <= count(DIAS); $dia++) {
                if (isset($horario_profe[$dia][$hora])) {
                    echo "<td>" . $horario_profe[$dia][$hora]['grupo'] . "(" . $horario_profe[$dia][$hora]['aula'] . ")" . "</td>";
                } else {
                    echo "<td></td>";
                }
            }
            echo "</tr>";
        }
        ?>

    </table>
    <?php
    if (isset($_SESSION["mensaje_seguridad"])) {
        echo "<p class='mensaje'>" . $_SESSION["mensaje_seguridad"] . "</p>";
        unset($_SESSION["mensaje_seguridad"]);
    }
    ?>
</body>

</html>