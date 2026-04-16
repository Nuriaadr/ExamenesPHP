<?php
$id_seleccionado = null;
$hora_seleccionada = null;
if (isset($_POST['btnVerInfo'])) {
    $id_seleccionado = $_POST['btnVerInfo'];
    $hora_seleccionada = $_POST['hora'];
    $url_usuario = DIR_API_HORARIO . '/usuario/' . $id_seleccionado;
    $respuesta_usuario = consumir_servicios_JWT_REST($url_usuario, 'GET', $headers);
    $json_usuario = json_decode($respuesta_usuario, true);

    if (!$json_usuario) {
        die(error_page("Examen Final PHP", "<h1>Error consumiendo: $url_usuario</h1>"));
    }

    if (isset($json_usuario["error"])) {
        die(error_page("Examen Final PHP", $json_usuario["error"]));
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
    <h3> Hoy es <?php echo DIAS[date('w')]; ?></h3>
    <?php
    if (isset($_SESSION["mensaje_seguridad"])) {
        echo "<p class='mensaje'>" . $_SESSION["mensaje_seguridad"] . "</p>";
        unset($_SESSION["mensaje_seguridad"]);
    }
    ?>

    <table>
        <tr>
            <th>Hora</th>
            <th>Profesor de Guardia</th>
            <th>Información del Profesor con id: </th>
        </tr>

        <?php
        for ($hora = 1; $hora <= count(HORAS); $hora++) {
            $url_usuarios_horas = DIR_API_HORARIO . '/deGuardia/' . date('w') . '/' . $hora;
            $respuesta_horas = consumir_servicios_JWT_REST($url_usuarios_horas, 'GET', $headers);
            $json_horas = json_decode($respuesta_horas, true);
            if (!$json_horas) {
                die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
            }
            if (isset($json_horas["error"])) {

                die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_respuesta["error"] . "</p>"));
            }
            if (isset($json_horas["mensaje"])) {
                $_SESSION["mensaje"] = $json_horas["mensaje"];
            }

            $usuario_en_esta_hora = false;
            if (isset($json_horas["usuarios"]) && isset($id_seleccionado)) {
                foreach ($json_horas["usuarios"] as $usuario) {
                    if ($usuario["usuario"] == $id_seleccionado) {
                        $usuario_en_esta_hora = true;
                        break;
                    }
                }
            }

            echo "<tr>";
            echo "<td>" . HORAS[$hora] . "</td>";
            echo "<td>";
            echo "<ul>";

            if (isset($json_horas["usuarios"])) {
                foreach ($json_horas['usuarios'] as $usuario) {
                    echo "<li>";
                    echo "<form action='index.php' method='post'>";
                    echo "<input type='hidden' name='hora' value='$hora'>";
                    echo "<button class='enlace' name='btnVerInfo' type='submit' value='" . $usuario['usuario'] . "'>" . $usuario['nombre'] . "</button>";
                    echo "</form>";
                    echo "</li>";
                }
            } else {
                echo "<li>" . $json_horas['mensaje'] . "</li>";
            }

            echo "</ul>";
            echo "<td>";

            if (isset($id_seleccionado) &&  isset($hora_seleccionada) && $hora == $hora_seleccionada &&  isset($json_usuario["usuario"])) {
                foreach ($json_usuario["usuario"] as $dato) {

                    echo "<p><strong>Nombre:</strong> " . $dato["nombre"] . "</p>";
                    echo "<p><strong>Usuario:</strong> " . $dato["usuario"] . "</p>";
                    echo "<p><strong>Email:</strong> " . ($dato["email"] ?? "No disponible") . "</p>";
                }
            }

            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>



</body>

</html>