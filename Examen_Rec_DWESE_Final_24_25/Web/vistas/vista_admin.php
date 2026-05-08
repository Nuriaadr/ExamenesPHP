<?php
if (isset($_POST['btnOcupacion'])) {
    $_SESSION['aula'] = $_POST['aula'];

}

if (isset(($_POST['btnQuitar']))) {
    $url_quitar = DIR_SERV . "/quitarProfesor/" . $_POST['dia'] . "/" . $_POST['hora'] . "/" . $_SESSION['aula'] . "/" . $_POST['profesor'];
    $respuesta_quitar = consumir_servicios_JWT_REST($url_quitar, 'delete', $headers);
    $json_quitar = json_decode($respuesta_quitar, true);

    if (isset($json_quitar['mensaje']))
        $_SESSION['mensaje'] = $json_quitar['mensaje'];
}

if (isset(($_POST['btnAñadir']))) {
    $url_aniadir = DIR_SERV . "/aniadirProfesor/" . $_POST['dia'] . "/" . $_POST['hora'] . "/" . $_SESSION['aula'] . "/" . $_POST['profesor_aniadir'] . "/" . $_POST['grupo_aniadir'];
    $respuesta_aniadir = consumir_servicios_JWT_REST($url_aniadir, 'post', $headers);
    $json_aniadir = json_decode($respuesta_aniadir, true);

    if (isset($json_aniadir['mensaje']))
        $_SESSION['mensaje'] = $json_aniadir['mensaje'];
}

if (isset($_POST['btnEditar']) || isset($_POST['btnQuitar']) || isset($_POST['btnAñadir'])) {
    $url_ocupacion_editar = DIR_SERV . "/profesores/" . $_POST['dia'] . "/" . $_POST['hora'] . "/" . $_SESSION['aula'];
    $respuesta_ocupacion_editar = consumir_servicios_JWT_REST($url_ocupacion_editar, 'get', $headers);
    $json_ocupacion_editar = json_decode($respuesta_ocupacion_editar, true);


}


$url_aulas_disponibles = DIR_SERV . '/aulas';
$respuesta_aulas_disponibles = consumir_servicios_JWT_REST($url_aulas_disponibles, 'get', $headers);
$json_aulas_disponibles = json_decode($respuesta_aulas_disponibles, true);

$url_profesores = DIR_SERV . '/profesores';
$respuesta_profesores = consumir_servicios_JWT_REST($url_profesores, 'get', $headers);
$json_profesores = json_decode($respuesta_profesores, true);

$url_grupos = DIR_SERV . '/grupos';
$respuesta_grupos = consumir_servicios_JWT_REST($url_grupos, 'get', $headers);
$json_grupos = json_decode($respuesta_grupos, true);

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
                foreach ($json_aulas_disponibles['aulas'] as $aula) {
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
    if (isset($_POST['btnOcupacion']) || isset($_POST['btnEditar']) || isset($_POST['btnQuitar']) || isset($_POST['btnAñadir'])) {
        ?>
        <table border="1">
            <tr>
                <th>
                </th>
                <?php
                for ($dia = 1; $dia <= count(DIAS); $dia++) {
                    echo "<th>" . DIAS[$dia] . "</th>";
                }
                ?>
            </tr>


            <?php
            $ocupacion_aula = [];
            for ($hora = 1; $hora <= count(HORAS); $hora++) {
                echo "<tr>";
                echo "<th>" . HORAS[$hora] . "</th>";

                if ($hora == 4) {
                    echo "<th colspan='5'>RECREO</th>";
                    echo "</tr>";
                    continue;
                }

                for ($dia = 1; $dia <= count(DIAS); $dia++) {
                    $url_ocupacion = DIR_SERV . "/profesores/" . $dia . "/" . $hora . "/" . $_SESSION['aula'];
                    $respuesta_ocupacion = consumir_servicios_JWT_REST($url_ocupacion, 'get', $headers);
                    $json_ocupacion = json_decode($respuesta_ocupacion, true);

                    if (isset($json_ocupacion['profes'])) {
                        foreach ($json_ocupacion['profes'] as $ocupacion) {
                            if (isset($ocupacion_aula[$dia][$hora]['usuario'])) {
                                $ocupacion_aula[$dia][$hora]['usuario'] .= "<br>" . $ocupacion['usuario'] . "(" . $ocupacion['nombre'] . ")";
                            } else {
                                $ocupacion_aula[$dia][$hora]['usuario'] = $ocupacion['usuario'] . "(" . $ocupacion['nombre'] . ")";
                            }
                            $ocupacion_aula[$dia][$hora]['nombre'] = $ocupacion['nombre'];

                        }
                    }

                    if (isset($ocupacion_aula[$dia][$hora]['usuario'])) {
                        echo "<td>" . $ocupacion_aula[$dia][$hora]['usuario'];
                        echo "<form action='index.php' method='post'>";
                        echo "<input type='hidden' name='dia' value='" . $dia . "'>";
                        echo "<input type='hidden' name='hora' value='" . $hora . "'>";
                        echo "<input type='hidden' name='aula' value='" . $_SESSION['aula'] . "'>";
                        echo "<button class='enlace' type='submit' name='btnEditar'>Editar</button>";
                        echo "</form>";
                        echo "</td>";
                    } else {
                        echo "<td>";
                        echo "<form action='index.php' method='post'>";
                        echo "<input type='hidden' name='dia' value='" . $dia . "'>";
                        echo "<input type='hidden' name='hora' value='" . $hora . "'>";
                        echo "<input type='hidden' name='aula' value='" . $_SESSION['aula'] . "'>";
                        echo "<button class='enlace' type='submit' name='btnEditar'>Editar</button>";
                        echo "</form>";
                        echo "</td>";
                    }

                }

                echo "</tr>";
            }
            ?>
        </table>
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo "<p>" . $_SESSION['mensaje'] . "</p>";
            unset($_SESSION['mensaje']);
        }
    }


    if (isset(($_POST['btnEditar'])) || isset(($_POST['btnQuitar'])) || isset(($_POST['btnAñadir']))) {
        echo "<h3>Editar ocupación del día " . DIAS[$_POST['dia']] . " a la hora " . HORAS[$_POST['hora']] . " en el aula " . $_SESSION['aula'] . "</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Profesor y Grupo</th><th>Acción</th></tr>";
        if (isset($json_ocupacion_editar['profes'])) {
            foreach ($json_ocupacion_editar['profes'] as $profesor) {
                echo "<tr><td>" . $profesor['usuario'] . "(" . $profesor['nombre'] . ")</td>";
                echo "<td><form action='index.php' method='post'>";
                echo "<input type='hidden' name='dia' value='" . $_POST['dia'] . "'>";
                echo "<input type='hidden' name='hora' value='" . $_POST['hora'] . "'>";
                echo "<input type='hidden' name='aula' value='" . $_SESSION['aula'] . "'>";
                echo "<input type='hidden' name='profesor' value='" . $profesor['id_usuario'] . "'>";
                echo "<button class='enlace' type='submit' name='btnQuitar'>Quitar</button>";
                echo "</form></td></tr>";
            }
            echo "</table>";
        } else {
            echo "<tr><td colspan='2'>No hay profesores asignados a esta aula en este día y hora</td></tr>";
            echo "</table>";
        }
        echo "<p>Añadir profesor: ";
        echo "<form action='index.php' method='post'>";
        echo "<input type='hidden' name='dia' value='" . $_POST['dia'] . "'>";
        echo "<input type='hidden' name='hora' value='" . $_POST['hora'] . "'>";
        echo "<input type='hidden' name='aula' value='" . $_SESSION['aula'] . "'>";
        echo "<select name='profesor_aniadir'>";
        foreach ($json_profesores['profesores'] as $profe) {
            echo "<option value='" . $profe['id_usuario'] . "'>" . $profe['usuario'] . "(" . $profe['nombre'] . ")</option>";
        }
        echo "</select>";



        echo "Añadir grupo: ";
        echo "<select name='grupo_aniadir'>";
        foreach ($json_grupos['grupos'] as $grupo) {
            echo "<option value='" . $grupo['id_grupo'] . "'>" . $grupo['nombre'] . "</option>";
        }
        echo "</select>";

        echo "<button type='submit' name='btnAñadir'>Añadir</button>";
        echo "</form>";

        echo "</p>";
    }
    ?>
</body>

</html>