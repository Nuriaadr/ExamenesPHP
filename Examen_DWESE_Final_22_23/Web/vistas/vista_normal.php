<?php


if (isset($_POST['btnInfo'])) {
    $url_info = DIR_API_HORARIO . '/usuario/' . $_POST['id_usuario'];
    $respuesta_info = consumir_servicios_JWT_REST($url_info, 'GET', $headers);
    $json_info = json_decode($respuesta_info, true);
}

if (isset($_POST['btnEquipo'])) {
    $_SESSION['dia'] = $_POST['dia'];
    $_SESSION['hora'] = $_POST['hora'];
    $url_deGuardia = DIR_API_HORARIO . '/deGuardia/' . $_SESSION['dia'] . '/' . $_SESSION['hora'] . '/' . $datos_usu_log['id_usuario'];
    $respuesta_deGuardia = consumir_servicios_JWT_REST($url_deGuardia, 'GET', $headers);
    $json_respuesta = json_decode($respuesta_deGuardia, true);

    if (isset($json_respuesta['de_guardia']) && $json_respuesta['de_guardia'] == true) {
        $url_equipos = DIR_API_HORARIO . '/usuariosGuardia/' . $_SESSION['dia'] . '/' . $_SESSION['hora'];
        $respuesta_equipos = consumir_servicios_JWT_REST($url_equipos, 'GET', $headers);
        $json_guardias = json_decode($respuesta_equipos, true);

        $_SESSION['guardias'] = $json_guardias;
    } else {
        $_SESSION['mensaje'] = "Usted no está de guardia el " . DIAS[$_SESSION['dia']] . " a las " . HORAS[$_SESSION['hora']] . "";
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
    <h1>Gestión de guardias</h1>
    <form action="index.php" method="post">
        <p>Bienvenido <strong><?php echo $datos_usu_log["usuario"]; ?></strong> - <button type="submit" class="enlace" name="btnCerrarSesion">Salir</button></p>
    </form>

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
                echo "<th colspan='6'>RECREO</th>";
                continue;
            } else
                echo "<th>" . HORAS[$hora] . "</th>";
            for ($dia = 1; $dia <= count(DIAS); $dia++) {
                echo "<td>";
                echo "<form action='index.php' method='post'>";
                echo "<input type='hidden' value='" . $dia . "' name='dia'></input>";
                echo "<input type='hidden' value='" . $hora . "' name='hora'></input>";
                echo "<button class='enlace' type='submit' name='btnEquipo'>Equipo</button>";
                echo "</form>";
                echo "</td>";
            }

            echo "</tr>";
        }
        ?>
    </table>

    <?php
    if (isset($_POST['btnEquipo']) || isset($_POST['btnInfo'])) {
        echo "<h2>Equipo Guardias</h2>";
        if (isset($_SESSION['mensaje'])) {
            echo $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);
        } else {
            if ($_SESSION['dia'] < 4) {
                echo "<h3>" . DIAS[$_SESSION['dia']] . " a " . $_SESSION['hora'] . "ª hora</h3>";
            } else
                echo "<h3>" . DIAS[$_SESSION['dia'] - 1] . " a " . ($_SESSION['hora'] - 1) . "ª hora</h3>";

            echo "<table>";
            echo "<tr>";
            echo "<th>Profesores de guardia</th>";
            echo "<th>Información del profesor</th>";
            echo "</tr>";

            $total = count($_SESSION['guardias']['usuario']);
            $primera = true;
            foreach ($_SESSION['guardias']['usuario'] as $guardia) {
                echo "<tr>";
                echo "<td>";
                echo   "<form action='index.php' method='post'>";
                echo   "<input type='hidden' value='" . $guardia['id_usuario'] . "' name='id_usuario'> ";
                echo   "<input type='hidden' value='" . $_SESSION['dia'] . "' name='dia'> ";
                echo   "<input type='hidden' value='" . $_SESSION['hora'] . "' name='hora'> ";
                echo   "<button class='enlace' type='submit' name='btnInfo'>" . $guardia['nombre'] . "</button> ";
                echo   "</form>";
                echo   "</td>";

                if ($primera) {
                    echo "<td rowspan='$total'>";
                    if (isset($json_info)) {
                        echo "<p>";
                        echo "<strong>Nombre:</strong> " . $json_info['usuario']['nombre'] . "<br>";
                        echo "<strong>Email:</strong> " . ($json_info['usuario']['email'] ?? 'No disponible') . "<br>";
                        echo "<strong>Usuario:</strong> " . ($json_info['usuario']['usuario'] ?? 'No disponible') . "<br>";
                        echo "<strong>Clave:</strong>";
                        echo "</p>";
                    } else {
                        echo "Seleccione un profesor";
                    }

                    echo "</td>";
                    $primera = false;
                }

                echo "</tr>";
            }

            echo "</table>";
        }
    }

    ?>

</body>

</html>