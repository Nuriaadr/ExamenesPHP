<?php
if (isset($_POST['btnAgregar'])) {
    $url_agregar = DIR_API_HORARIO . '/agregarGrupo/' . $_POST['dia'] . '/' . $_POST['hora'] . '/' . $_SESSION['profesor'] . '/' . $_POST['grupo'];
    $respuesta_agregar = consumir_servicios_JWT_REST($url_agregar, 'POST', $headers);
    $json_agregar = json_decode($respuesta_agregar, true);
    if (!$json_agregar) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url_agregar . "</strong></p>"));
    }
    if (isset($json_agregar["error"])) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_agregar["error"] . "</p>"));
    }
    $_SESSION['mensaje'] = $json_agregar['mensaje'];
    $_SESSION['profesor'] = $_POST['profesor'];
    $_SESSION['dia'] = $_POST['dia'];
    $_SESSION['hora'] = $_POST['hora'];
}

if (isset($_POST['btnBorrar'])) {
    $url_borrar = DIR_API_HORARIO . '/borrarGrupo/' . $_POST['dia'] . '/' . $_POST['hora'] . '/' . $_SESSION['profesor'] . '/' . $_POST['btnBorrar'];
    $respuesta_borrar = consumir_servicios_JWT_REST($url_borrar, 'DELETE', $headers);
    $json_borrar = json_decode($respuesta_borrar, true);
    if (!$json_borrar) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url_borrar . "</strong></p>"));
    }
    if (isset($json_borrar["error"])) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_borrar["error"] . "</p>"));
    }
    $_SESSION['mensaje'] = $json_borrar['mensaje'];
    $_SESSION['profesor'] = $_POST['profesor'];
    $_SESSION['dia'] = $_POST['dia'];
    $_SESSION['hora'] = $_POST['hora'];
}




if (isset($_POST["btnEditar"])) {
    $_SESSION['hora'] = $_POST['hora'];
    $_SESSION['dia'] = $_POST['dia'];
    $_SESSION['profesor'] = $_POST['profesor'];
}


if (isset($_POST['btnVerHorario']) || isset($_POST['btnEditar']) || isset($_POST['btnBorrar']) || isset($_POST['btnAgregar'])) {
    $url_horario = DIR_API_HORARIO . '/horarioProfesor/' . $_POST['profesor'];
    $respuesta_horario = consumir_servicios_JWT_REST($url_horario, 'GET', $headers);
    $json_horario = json_decode($respuesta_horario, true);
    if (!$json_horario) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url_horario . "</strong></p>"));
    }
    if (isset($json_horario["error"])) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_horario["error"] . "</p>"));
    }

    $horario_profe = [];
    if (isset($json_horario['horario'])) {
        foreach ($json_horario['horario'] as $horario) {
            if (isset($horario_profe[$horario['dia']][$horario['hora']])) {
                $horario_profe[$horario['dia']][$horario['hora']]["grupo"] .= '/' . $horario['grupo'];
            } else {
                $horario_profe[$horario['dia']][$horario['hora']]["grupo"] = $horario['grupo'];
            }
        }
    }
}

$url_profesores = DIR_API_HORARIO . '/profesores';
$respuesta_profesores = consumir_servicios_JWT_REST($url_profesores, 'GET', $headers);
$json_profesores = json_decode($respuesta_profesores, true);
if (!$json_profesores) {
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url_profesores . "</strong></p>"));
}
if (isset($json_profesores["error"])) {
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_profesores["error"] . "</p>"));
}

$url_grupos = DIR_API_HORARIO . '/grupos';
$respuesta_grupos = consumir_servicios_JWT_REST($url_grupos, 'GET', $headers);
$json_grupos = json_decode($respuesta_grupos, true);
if (!$json_grupos) {
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url_grupos .     "</strong></p>"));
}
if (isset($json_grupos["error"])) {
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_grupos["error"] . "</p>"));
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
    <h2>Horario de los Profesores</h2>

    <form action="index.php" method="post">
        <span>Consulte el horario del profesor:</span>
        <select name='profesor'>
            <?php
            foreach ($json_profesores['profesores'] as $profesor) {
                if (isset($_POST['profesor']) && $_POST['profesor'] == $profesor['id_usuario']) {
                    echo "<option value='" . $profesor['id_usuario'] . "' selected>" . $profesor['nombre'] . "</option>";
                } else
                    echo "<option value='" . $profesor['id_usuario'] . "'>" . $profesor['nombre'] . "</option>";
            }
            ?>
        </select>
        <button type="submit" name="btnVerHorario">Ver Horario</button>
    </form>


    <?php
    if (isset($_POST['btnVerHorario'])  || isset($_POST['btnEditar']) || isset($_POST['btnBorrar']) || isset($_POST['btnAgregar'])) {
        foreach ($json_profesores['profesores'] as $profesor) {
            if (isset($_POST['profesor']) && $_POST['profesor'] == $profesor['id_usuario']) {
                $nombre_profesor =  $profesor['nombre'];
                $id = $profesor['id_usuario'];
            }
        }

        echo "<h2>Horario del profesor: " . $nombre_profesor . "</h2>";

        echo "<table>";
        echo "<tr>";
        echo "<th></th>";
        for ($dia = 1; $dia <= count(DIAS); $dia++) {
            echo "<th>" . DIAS[$dia] . "</th>";
        }
        echo "</tr>";

        for ($hora = 1; $hora <= count(HORAS); $hora++) {
            echo "<tr>";
            echo "<th>" . HORAS[$hora] . "</th>";
            if ($hora == 4) {
                echo "<td colspan='5'>RECREO</td>";
                continue;
            }
            for ($dia = 1; $dia <= count(DIAS); $dia++) {
                echo "<td>";
                if (isset($horario_profe[$dia][$hora])) {
                    echo  $horario_profe[$dia][$hora]['grupo'];
                }
                echo "<form action='index.php' method='post'>";
                echo "<input type='hidden' name='dia' value='" . $dia . "'></input>";
                echo "<input type='hidden' name='hora' value='" . $hora . "'></input>";
                echo "<input type='hidden' name='profesor' value='" . $id . "'></input>";
                echo "<button class='enlace' type='submit' name='btnEditar'>Editar</button>";
                echo "</form>";
                echo "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }

    if (isset($_POST["btnEditar"]) || isset($_POST['btnBorrar']) || isset($_POST['btnAgregar'])) {
        if ($_SESSION["hora"] > 4) {
            echo "<h3>Editando la " . $_SESSION["hora"] - 1 . "ª(" . HORAS[$_SESSION["hora"]] . ") del " . DIAS[$_SESSION['dia']] . "</h3>";
        } else
            echo "<h3>Editando la " . $_SESSION["hora"] . "ª(" . HORAS[$_SESSION["hora"]] . ") del " . DIAS[$_SESSION['dia']] . "</h3>";

        if (isset($_SESSION['mensaje'])) {
            echo "<p class='mensaje'>" . $_SESSION['mensaje'] . "</p>";
            unset($_SESSION['mensaje']);
        }

        echo "<table>";
        echo "<tr>";
        echo "<th>Grupo</th>";
        echo "<th>Acción</th>";
        echo "</tr>";

        foreach ($json_horario['horario'] as $horario) {
            if ($horario['dia'] == $_SESSION['dia'] && $horario['hora'] == $_SESSION['hora']) {
                echo "<tr>";
                echo "<td>" . $horario['grupo'] . "</td>";
                echo "<td>";
                echo "<form action='index.php' method='post'>";
                echo "<input type='hidden' name='dia' value='" . $_SESSION['dia'] . "'>";
                echo "<input type='hidden' name='hora' value='" . $_SESSION['hora'] . "'>";
                echo "<input type='hidden' name='profesor' value='" . $_SESSION['profesor'] . "'>";
                echo "<button type='submit' class='enlace' name='btnBorrar' value='" . $horario['id_grupo'] . "'>Borrar</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        }
        echo "</table>";

        echo "<h3>Agregar un nuevo grupo a esa hora</h3>";
        echo "<form action='index.php' method='post'>";
        echo "<input type='hidden' name='dia' value='" . $_SESSION['dia'] . "'>";
        echo "<input type='hidden' name='hora' value='" . $_SESSION['hora'] . "'>";
        echo "<input type='hidden' name='profesor' value='" . $_SESSION['profesor'] . "'>";
        echo "<select name='grupo'>";
        foreach ($json_grupos['grupos'] as $grupo) {
            echo "<option value='" . $grupo['id_grupo'] . "'>" . $grupo['nombre'] . "</option>";
        }
        echo "</select>";
        echo "<button type='submit' name='btnAgregar'>Agregar</button>";
        echo "</form>";
    }


    ?>
</body>

</html>