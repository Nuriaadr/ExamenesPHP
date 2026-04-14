<?php
if (isset($_POST['btnQuitar'])) {
    $url_quitar = DIR_API_HORARIO . '/borrarProfesor/' . $_POST['dia'] . '/' . $_POST['hora'] . '/' . $_POST['grupo'] . '/' . $_POST['btnQuitar'];
    $request_quitar = consumir_servicios_JWT_REST($url_quitar, 'DELETE', $headers);
    $json_borrar = json_decode($request_quitar, true);
    var_dump($json_borrar);
    if (!$json_borrar) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $url_quitar . "</strong></p>"));
    }

    if (isset($json_borrar["error"])) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $json_borrar["error"] . "</strong></p>"));
    }

    if (isset($json_borrar["no_auth"])) {
        session_unset();
        $_SESSION["mensaje_seguridad"] = "El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }
    if (isset($json_borrar["mensaje_baneo"])) {
        session_unset();
        $_SESSION["mensaje_seguridad"] = "Usted ya no se encuentra registrado en la BD";
        header("Location:index.php");
        exit;
    }

    $_SESSION["mensaje"] = "Profesor quitado con éxito";
    $_SESSION["dia"] = $_POST["dia"];
    $_SESSION["hora"] = $_POST["hora"];
    $_SESSION["grupo"] = $_POST["grupo"];
}


if (isset($_POST["btnAgregar"])) {
    $url = DIR_API_HORARIO . "/insertarProfesor/" . $_POST["dia"] . "/" . $_POST["hora"] . "/" . $_POST["grupo"] . "/" . $_POST["profesor"] . "/" . $_POST["aula"];
    $respuesta = consumir_servicios_JWT_REST($url, "POST", $headers);
    $json_insertar = json_decode($respuesta, true);
    if (!$json_insertar) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $url . "</strong></p>"));
    }

    if (isset($json_insertar["error"])) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $json_insertar["error"] . "</strong></p>"));
    }

    if (isset($json_insertar["no_auth"])) {
        session_unset();
        $_SESSION["mensaje_seguridad"] = "El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }
    if (isset($json_insertar["mensaje_baneo"])) {
        session_unset();
        $_SESSION["mensaje_seguridad"] = "Usted ya no se encuentra registrado en la BD";
        header("Location:index.php");
        exit;
    }

    $_SESSION["mensaje"] = "Profesor añadido con éxito";
    $_SESSION["dia"] = $_POST["dia"];
    $_SESSION["hora"] = $_POST["hora"];
    $_SESSION["grupo"] = $_POST["grupo"];
}


$url_grupos = DIR_API_HORARIO . '/grupos';
$request_grupos = consumir_servicios_JWT_REST($url_grupos, 'GET', $headers);
$json_grupos = json_decode($request_grupos, true);

if (!$json_grupos) {
    session_destroy();
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
}
if (isset($json_grupos["error"])) {
    session_destroy();
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_grupos["error"] . "</p>"));
}

$url_aulas = DIR_API_HORARIO . '/aulas';
$request_aulas = consumir_servicios_JWT_REST($url_aulas, 'GET', $headers);
$json_aulas = json_decode($request_aulas, true);

if (!$json_aulas) {
    session_destroy();
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
}
if (isset($json_aulas["error"])) {
    session_destroy();
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_aulas["error"] . "</p>"));
}

if (isset($_POST['grupo'])) {
    $url_horario_grupo = DIR_API_HORARIO . '/horarioGrupo/' . $_POST['grupo'];
    $request_horario_grupo = consumir_servicios_JWT_REST($url_horario_grupo, 'GET', $headers);
    $json_horario_grupo = json_decode($request_horario_grupo, true);
    if (!$json_horario_grupo) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
    }
    if (isset($json_horario_grupo["error"])) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_horario_grupo["error"] . "</p>"));
    }

    $horario_grupo = [];
    if (isset($json_horario_grupo["horario"])) {
        foreach ($json_horario_grupo["horario"] as $horario) {
            if (isset($horario_grupo[$horario["dia"]][$horario["hora"]])) {
                $horario_grupo[$horario["dia"]][$horario["hora"]] .= "<br>" . $horario["profe"] . '(' . $horario['aula'] . ')';
            } else {
                $horario_grupo[$horario["dia"]][$horario["hora"]] =  $horario["profe"] . '(' . $horario['aula'] . ')';
            }
        }
    }
}


if (isset($_POST['dia'])) {
    $url_profes_grupo = DIR_API_HORARIO . '/profesores/' . $_POST['dia'] . '/' . $_POST['hora'] . '/' . $_POST['grupo'];
    $request_profe_grupo = consumir_servicios_JWT_REST($url_profes_grupo, 'GET', $headers);
    $json_profes_grupo = json_decode($request_profe_grupo, true);
    if (!$json_profes_grupo) {

        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
    }
    if (isset($json_profes_grupo["error"])) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_profes_grupo["error"] . "</p>"));
    }


    $url_profes_libres = DIR_API_HORARIO . '/profesoresLibres/' . $_POST['dia'] . '/' . $_POST['hora'] . '/' . $_POST['grupo'];
    $request_profe_grupo = consumir_servicios_JWT_REST($url_profes_libres, 'GET', $headers);
    $json_profes_libres = json_decode($request_profe_grupo, true);
    if (!$json_profes_libres) {

        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
    }
    if (isset($json_profes_libres["error"])) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_profes_libres["error"] . "</p>"));
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
    <h2>Horario de los grupos</h2>

    <form method="post" action="index.php">
        <p>Elija el grupo:</p>
        <select name="grupo" id="grupo">
            <?php
            foreach ($json_grupos['grupos'] as $grupo) {
                if (isset($_POST['grupo']) && $_POST['grupo'] == $grupo['id_grupo']) {

                    echo "<option selected value='" . $grupo["id_grupo"] . "'>" . $grupo["nombre"] . "</option>";
                    $nombre_grupo = $grupo['nombre'];
                } else
                    echo "<option value='" . $grupo["id_grupo"] . "'>" . $grupo["nombre"] . "</option>";
            }
            ?>
        </select>
        <button type="submit" name="btnVerGrupo">Ver grupo</button>
    </form>

    <?php

    if (isset($_POST['grupo']) || isset($_POST['btnQuitar']) || isset($_POST['btnAgregar'])) {
        echo "<h2>Horario del grupo: " . $nombre_grupo . "</h2>";

    ?>
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
                } else {
                    echo "<th>" . HORAS[$hora] . "</th>";
                    for ($dia = 1; $dia <= count(DIAS); $dia++) {
                        if (isset($horario_grupo[$dia][$hora]))
                            echo "<td>" . $horario_grupo[$dia][$hora];
                        else
                            echo "<td>";

                        echo "<form method='post' action='index.php'>";
                        echo "<input type='hidden' value='" . $dia . "' name='dia'>";
                        echo "<input type='hidden' value='" . $hora . "' name='hora'>";
                        echo "<input type='hidden' value='" . $_POST['grupo'] . "' name='grupo'>";
                        echo "<button type='submit' class='enlace' name='btnEditar'>Editar</button>";
                        echo "</form>";
                        echo "</td>";
                    }
                }
                echo "</tr>";
            }
            ?>

        </table>

        <?php if (isset($_POST["dia"])) {
            if ($_POST["hora"] < 4)
                echo "<h2>Editando la " . $_POST["hora"] . "º Hora (" . HORAS[$_POST["hora"]] . ") del " . DIAS[$_POST["dia"]] . "</h2>";
            else
                echo "<h2>Editando la " . ($_POST["hora"] - 1) . "º Hora (" . HORAS[$_POST["hora"]] . ") del " . DIAS[$_POST["dia"]] . "</h2>";

            if (isset($_SESSION["mensaje"])) {
                echo "<p class='mensaje'>" . $_SESSION["mensaje"] . "</p>";
                unset($_SESSION["mensaje"]);
            }
        ?>

            <table>
                <tr>
                    <th>Profesor(Aula)</th>
                    <th>Acción</th>
                </tr>

                <?php
                foreach ($json_profes_grupo["profesores"] as $profesor) {
                    echo "<tr>";
                    echo "<td>" . $profesor['usuario'] . '(' . $profesor['aula'] . ')' . "</td>";
                    echo "<td>";
                    echo "<form action='index.php' method='post'>";
                    echo "<input type='hidden' name ='hora' value='" . $_POST['hora'] . "'></input>";
                    echo "<input type='hidden' name ='dia' value='" . $_POST['dia'] . "'></input>";
                    echo "<input type='hidden' name='grupo' value='" . $_POST["grupo"] . "'>";
                    echo "<button class='enlace' type='submit' name='btnQuitar' value='" . $profesor['id_usuario'] . "'>Quitar</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "<tr>";
                }
                ?>

            </table>

        <?php
            echo "Añadir un profesor:";
            echo "<form method='post' action='index.php'>";
            echo "<select name='profeLibre'>";
            foreach ($json_profes_libres["profesores_libres"] as $profe_libre) {
                echo "<option value='" . $profe_libre['id_usuario'] . "'> " . $profe_libre['usuario'] . "</option>";
            }
            echo "</select>";

            echo "Añadir un aula:";
            echo "<select name='aulas'>";
            foreach ($json_aulas['aulas'] as $aula) {
                echo "<option value='" . $aula['id_aula'] . "'> " . $aula['nombre'] . "</option>";
            }
            echo "</select>";
            echo "<button type='submit' name='btnAniadir'>Añadir</button>";
            echo "</form>";
        }

        ?>

    <?php
    }
    ?>
</body>

</html>