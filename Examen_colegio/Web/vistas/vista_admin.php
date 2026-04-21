<?php

if (isset($_POST['btnBorrar'])) {
    $url_borrar = DIR_API_COLEGIO . '/borrarNota/' . $_SESSION['alumno'] . '/' . $_POST['btnBorrar'];
    $respuesta_borrar = consumir_servicios_JWT_REST($url_borrar, 'DELETE', $headers);
    $json_borrar = json_decode($respuesta_borrar, true);

    if (!$json_borrar) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url_borrar . "</strong></p>"));
    }
    if (isset($json_borrar["error"])) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_borrar["error"] . "</p>"));
    }
    if (isset($json_borrar["mensaje"])) {
        $_SESSION['mensaje'] = $json_borrar['mensaje'];
    }
}

if (isset($_POST['btnCalificar'])) {
    $url_calificar = DIR_API_COLEGIO . '/calificarNota/' . $_SESSION['alumno'] . '/' . $_POST['select_asignatura'];
    $respuesta_calificar = consumir_servicios_JWT_REST($url_calificar, 'POST', $headers);
    $json_calificar = json_decode($respuesta_calificar, true);
    if (!$json_calificar) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url_calificar . "</strong></p>"));
    }
    if (isset($json_calificar["error"])) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_calificar["error"] . "</p>"));
    }
    if (isset($json_calificar["mensaje"])) {
        $_SESSION['mensaje'] = $json_calificar['mensaje'];
    }
}


$asig_editando = $_POST['btnEditar'] ?? null;

if (isset($_POST['btnCambiar'])) {
    // Faltaba esto,  $asig_editando no existia cuando le dabas a cambiar y siempre entraba en el if porque nunca coincidia
    // if ($asig_editando != $nota['cod_asig']) { siempre era true y no mostraba el error
    $asig_editando = $_POST['btnCambiar'];

    if (!is_numeric($_POST['nota']) || $_POST['nota'] < 0 || $_POST['nota'] > 10 || $_POST['nota'] == "") {
        $mensaje_error = "No has introducido una nota válida. Introduce un número entre 0 y 10";
    } else {
        $url_cambiar = DIR_API_COLEGIO . '/cambiarNota/' . $_SESSION['alumno'] . '/' . $_POST['btnCambiar'] . '/' . $_POST['nota'];
        $respuesta_cambiar = consumir_servicios_JWT_REST($url_cambiar, 'PUT', $headers);
        $json_cambiar = json_decode($respuesta_cambiar, true);
        if (!$json_cambiar) {
            die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url_cambiar . "</strong></p>"));
        }
        if (isset($json_cambiar["error"])) {
            die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_cambiar["error"] . "</p>"));
        }
        if (isset($json_cambiar["mensaje"])) {
            $_SESSION['mensaje'] = $json_cambiar['mensaje'];
        }
    }
}




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
    $_SESSION['mensaje'] = $json_alumnos['mensaje'];
}

if (isset($_POST['btnVerNotas']) || isset($_POST['btnCalificar']) || isset($_POST['btnEditar']) || isset($_POST['btnBorrar']) || isset($_POST['btnCambiar'])) {
    $url_notas_alum = DIR_API_COLEGIO . '/notasAlumno/' . $_POST['alumno'];
    $respuesta_notas_alumnos = consumir_servicios_JWT_REST($url_notas_alum, 'GET', $headers);
    $json_notas_alumnos = json_decode($respuesta_notas_alumnos, true);

    if (!$json_notas_alumnos) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url_notas_alum . "</strong></p>"));
    }
    if (isset($json_notas_alumnos["error"])) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_notas_alumnos["error"] . "</p>"));
    }
    if (isset($json_notas_alumnos["mensaje"])) {
        $_SESSION['mensaje'] = $json_notas_alumnos['mensaje'];
    }

    $url_no_eval = DIR_API_COLEGIO . '/notasNoEvalAlumno/' . $_POST['alumno'];
    $respuesta_no_eval = consumir_servicios_JWT_REST($url_no_eval, 'GET', $headers);
    $json_no_eval = json_decode($respuesta_no_eval, true);

    if (!$json_no_eval) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url_no_eval . "</strong></p>"));
    }
    if (isset($json_no_eval["error"])) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_no_eval["error"] . "</p>"));
    }

    foreach ($json_alumnos['alumnos'] as $alumno) {
        if ($alumno['cod_usu'] == $_POST['alumno']) {
            $_SESSION['alumno'] = $alumno['cod_usu'];
            $_SESSION['nombre_alumno'] = $alumno['nombre'];
        }
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
        <label>Seleccione un alumno:</label>
        <select name="alumno">
            <?php
            foreach ($json_alumnos['alumnos'] as $alumno) {
                if (isset($_SESSION["alumno"]) && $_SESSION['alumno'] == $alumno['cod_usu']) {
                    echo "<option selected value='" . $alumno['cod_usu'] . "'>" . $alumno['nombre'] . "</option>";
                } else
                    echo "<option value='" . $alumno['cod_usu'] . "'>" . $alumno['nombre'] . "</option>";
            }
            ?>
        </select>
        <button type='submit' name="btnVerNotas">Ver notas</button>
    </form>

    <?php
    if (isset($_SESSION['mensaje'])) {
        echo "<p class='mensaje'>" . $_SESSION['mensaje'] . "</p>";
        unset($_SESSION['mensaje']);
    }
    ?>

    <?php
    if (isset($_POST['btnVerNotas']) || isset($_POST['btnCalificar']) || isset($_POST['btnEditar']) || isset($_POST['btnBorrar']) || isset($_POST['btnCambiar'])) {

        echo "<h3>Notas del alumno: " . $_SESSION['nombre_alumno'] . "</h3>";
        echo "<table>";
        echo "<tr>";
        echo "<th>Asignatura</th>";
        echo "<th>Nota</th>";
        echo "<th>Acciones</th>";
        echo "</tr>";
        foreach ($json_notas_alumnos['notas_alum'] as $nota) {
            echo "<tr>";

            if ($asig_editando != $nota['cod_asig']) {
                echo "<td>" . $nota['denominacion'] . "</td>";
                echo "<td>" . $nota['nota'] . "</td>";
                echo "<td>";
                echo "<form action='index.php' method='post'>";
                echo "<button type='submit' name='btnEditar' value='" . $nota['cod_asig'] . "'>Editar</button>";
                echo "<input type='hidden' name='alumno' value='" . $_SESSION['alumno'] . "'>";
                echo "<button type='submit' name='btnBorrar' value='" . $nota['cod_asig'] . "'>Borrar</button>";
                echo "</form>";
                echo "</td>";
            } else {
                echo "<td>" . $nota['denominacion'] . "</td>";
                echo "<form action='index.php' method='post'>";
                echo "<td><input type='text' name='nota' value='" . $nota['nota'] . "'>";
                if (isset($mensaje_error) && $_POST['btnCambiar'] == $asig_editando) {
                    echo "<p class='mensaje'>" . $mensaje_error . "</p>";
                }
                echo "</td>";
                echo "<td>";
                echo "<button type='submit' name='btnCambiar' value='" . $nota['cod_asig'] . "'>Cambiar</button>";
                echo "<input type='hidden' name='alumno' value='" . $_SESSION['alumno'] . "'>";
                echo "<button type='submit' name='btnAtras'>Atrás</button>";
                echo "</td>";
                echo "</form>";
            }

            echo "</tr>";
        }


        echo "</table>";


        if (isset($json_no_eval['notas_no_eval']) && count($json_no_eval['notas_no_eval']) > 0) {
            echo "<h3>Notas por calificar del alumno: " . $_SESSION['nombre_alumno'] . "</h3>";
            echo "<form action='index.php' method='post'>";
            echo "<input type='hidden' name='alumno' value='" . $_SESSION['alumno'] . "'>";
            echo "<select name='select_asignatura'>";
            foreach ($json_no_eval['notas_no_eval'] as $nota) {
                echo "<option value='" . $nota['cod_asig'] . "'>" . $nota['denominacion'] . "</option>";
            }
            echo "</select>";
            echo "<button type='submit' name='btnCalificar'>Calificar</button>";
            echo "</form>";
        } else {
            echo "<p class='mensaje'>El alumno " . $_SESSION['nombre_alumno'] . " no tiene notas por calificar</p>";
        }
    }

    ?>




</body>

</html>