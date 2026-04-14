<?php
$url_alumnos = DIR_API_COLEGIO . '/alumnos';
$respuesta_alumnos = consumir_servicios_JWT_REST($url_alumnos, 'GET', $headers);
$json_alumnos = json_decode($respuesta_alumnos, true);

if (!$json_alumnos) {
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url_alumnos . "</strong></p>"));
}
if (isset($json_alumnos["error"])) {
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_alumnos["error"] . "</p>"));
}
if (isset($json_alumnos["mensaje"])) {
    $_SESSION["mensaje"] = $json_alumnos["mensaje"];
}

if (isset($_POST["alumno"])) {
    $_SESSION["alumno"] = $_POST["alumno"];
}

if (isset($_SESSION["alumno"])) {
    $alumno_id = $_SESSION["alumno"];

    $url_notas = DIR_API_COLEGIO . '/notasAlumno/' . $alumno_id;
    $respuesta_notas = consumir_servicios_JWT_REST($url_notas, 'GET', $headers);
    $json_notas = json_decode($respuesta_notas, true);

    if (!$json_notas) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url_notas . "</strong></p>"));
    }
    if (isset($json_notas["error"])) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_notas["error"] . "</p>"));
    }
    if (isset($json_notas["mensaje"])) {
        $_SESSION["mensaje"] = $json_notas["mensaje"];
    }

    $url_notas_no_eval = DIR_API_COLEGIO . '/notasNoEvalAlumno/' . $alumno_id;
    $respuesta_notas_no_eval = consumir_servicios_JWT_REST($url_notas_no_eval, 'GET', $headers);
    $json_notas_no_eval = json_decode($respuesta_notas_no_eval, true);

    if (!$json_notas_no_eval) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url_notas_no_eval . "</strong></p>"));
    }
    if (isset($json_notas_no_eval["error"])) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_notas_no_eval["error"] . "</p>"));
    }
    if (isset($json_notas_no_eval["mensaje"])) {
        $_SESSION["mensaje"] = $json_notas_no_eval["mensaje"];
    }

    if (isset($_POST["notaNoEval"])) {
        $url_evaluacion = DIR_API_COLEGIO . '/ponerNota/' . $alumno_id;
        $datos_env["cod_asig"] = $_POST["notaNoEval"];
        $respuesta_evaluacion = consumir_servicios_JWT_REST($url_evaluacion, "POST", $headers, $datos_env);
        $json_respuesta_evaluacion = json_decode($respuesta_evaluacion, true);


        if (!$json_respuesta_evaluacion) {
            die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url_evaluacion . "</strong></p>"));
        }
        if (isset($json_respuesta_evaluacion["error"])) {
            die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_respuesta_evaluacion["error"] . "</p>"));
        }
        if (isset($json_respuesta_evaluacion["mensaje"])) {
            $_SESSION["mensaje"] = $json_respuesta_evaluacion["mensaje"];
        }
        header("Location: index.php");
        exit;
    }

    if (isset($_POST["btnEditar"])) {
        $_SESSION["editar_asig"] = $_POST["btnEditar"];
    }

    if (isset($_POST["btnCambiar"])) {
        $url_cambiar_nota = DIR_API_COLEGIO . '/cambiarNota/' . $alumno_id;
        $datos_env["cod_asig"] = $_POST["btnCambiar"];
        $datos_env["nota"] = $_POST["nuevaNota"];
        $respuesta_cambiar_nota = consumir_servicios_JWT_REST($url_cambiar_nota, "PUT", $headers, $datos_env);
        $json_cambiar_nota = json_decode($respuesta_cambiar_nota, true);

        if (!$json_cambiar_nota) {
            die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url_cambiar_nota . "</strong></p>"));
        }
        if (isset($json_cambiar_nota["error"])) {
            die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_cambiar_nota["error"] . "</p>"));
        }
        if (isset($json_cambiar_nota["mensaje"])) {
            $_SESSION["mensaje"] = $json_cambiar_nota["mensaje"];
        }
        unset($_SESSION["editar_asig"]);
        header("Location: index.php");
        exit;
    }

    if (isset($_POST["btnAtras"])) {
        unset($_SESSION["editar_asig"]);
    }

    if (isset($_POST["btnQuitar"])) {
        $url_quitar_nota = DIR_API_COLEGIO . '/quitarNota/' . $alumno_id;
        $datos_env["cod_asig"] = $_POST["btnQuitar"];
        $respuesta_quitar_nota = consumir_servicios_JWT_REST($url_quitar_nota, "DELETE", $headers, $datos_env);
        $json_quitar_nota = json_decode($respuesta_quitar_nota, true);

        if (!$json_quitar_nota) {
            die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url_quitar_nota . "</strong></p>"));
        }
        if (isset($json_quitar_nota["error"])) {
            die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_quitar_nota["error"] . "</p>"));
        }
        if (isset($json_quitar_nota["mensaje"])) {
            $_SESSION["mensaje"] = $json_quitar_nota["mensaje"];
        }
        header("Location: index.php");
        exit;
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

        .text_centrado {
            text-align: center
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

        .mensaje {
            color: blue;
            font-size: 1.25em
        }
    </style>
</head>

<body>
    <h1>Examen Final PHP</h1>
    <form action="index.php" method="post">
        <p>Bienvenido <strong><?php echo $datos_usu_log["usuario"]; ?></strong> - <button type="submit" class="enlace" name="btnCerrarSesion">Salir</button></p>
    </form>

    <form action="index.php" method="post">
        <span>Seleccione un alumno:</span>
        <select name="alumno">
            <?php foreach ($json_alumnos['alumnos'] as $alumno) {
                if (isset($_SESSION["alumno"]) && $_SESSION["alumno"] == $alumno['cod_usu'])
                    echo "<option value='" . $alumno['cod_usu'] . "' selected>" . $alumno["nombre"] . "</option>";
                else
                    echo "<option value='" . $alumno['cod_usu'] . "'>" . $alumno["nombre"] . "</option>";
            } ?>
        </select>
        <button type='submit' name="btnConsulta">Consultar notas</button>
    </form>

    <?php
    if (isset($_SESSION["alumno"])) {
        foreach ($json_alumnos['alumnos'] as $alumno) {
            if ($alumno['cod_usu'] == $_SESSION['alumno']) {
                $nombre_alumno = $alumno["nombre"];
            }
        }
    ?>
        <h3>Notas del alumno: <?= $nombre_alumno ?></h3>

        <table>
            <tr>
                <th>Asignatura</th>
                <th>Nota</th>
                <th>Acción</th>

            </tr>

            <?php
            if (isset($json_notas["notas_alumno"])) {
                foreach ($json_notas['notas_alumno'] as $nota) {
                    $editando = (isset($_SESSION["editar_asig"]) && $_SESSION["editar_asig"] == $nota['cod_asig']);
                    echo "<tr>";
                    echo "<td>" . $nota['denominacion'] . "</td>";

                    if ($editando) {
                        echo "<td><input type='number' id='nuevaNota' value='" . $nota['nota'] . "' min='0' max='10' step='0.1' style='width:60px;'></td>";
                    } else {
                        echo "<td>" . $nota['nota'] . "</td>";
                    }

                    echo "<td>";
                    echo "<form action='index.php' method='post' style='display:inline;'>";
                    echo "<input type='hidden' name='alumno' value='" . $_SESSION['alumno'] . "'>";

                    if ($editando) {
                        echo "<input type='hidden' name='nuevaNota' id='inputNota' value=''>";
                        echo "<button class='enlace' type='submit' name='btnCambiar' value='" . $nota['cod_asig'] . "' onclick='document.getElementById(\"inputNota\").value=document.getElementById(\"nuevaNota\").value;'>Cambiar</button>";
                        echo "&nbsp;";
                        echo "<button class='enlace' type='submit' name='btnAtras' value='1'>Atrás</button>";
                    } else {
                        echo "<button class='enlace' type='submit' name='btnQuitar' value='" . $nota['cod_asig'] . "'>Quitar</button>";
                        echo "&nbsp;";
                        echo "<button class='enlace' type='submit' name='btnEditar' value='" . $nota['cod_asig'] . "'>Editar</button>";
                    }

                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>

    <?php
        if (isset($_SESSION["mensaje"])) {
            echo "<p class='mensaje'>" . $_SESSION["mensaje"] . "</p>";
            unset($_SESSION["mensaje"]);
        }

        if (isset($json_notas_no_eval["notas_alumno"]) && count($json_notas_no_eval["notas_alumno"]) > 0) {
            echo "<form action='index.php' method='post'>";
            echo "<input type='hidden' name='alumno' value='" . $_SESSION['alumno'] . "'>";
            echo "<span>Asignaturas que a " . $nombre_alumno . " le quedan por calificar: </span>";
            echo "<select name='notaNoEval'>";
            foreach ($json_notas_no_eval['notas_alumno'] as $nota_no_eval) {
                echo "<option value='" . $nota_no_eval["cod_asig"] . "'>" . $nota_no_eval["denominacion"] . "</option>";
            }
            echo "</select>";
            echo "<button name='btnCalificar'>Calificar</button>";
            echo "</form>";
        }
    }
    ?>
</body>

</html>