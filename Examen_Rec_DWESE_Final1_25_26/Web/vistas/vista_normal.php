<?php

$url_guardias = DIR_SERV . "/deGuardia/" . $datos_usu_log["id_usuario"];
$respuesta = consumir_servicios_JWT_REST($url_guardias, 'GET', $headers);
$json_guardia = json_decode($respuesta, true);
if (!$json_guardia) {
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $url_guardias . "</strong></p>"));
}

if (isset($json_guardia["error"])) {
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $json_guardia["error"] . "</strong></p>"));
}

if (isset($json_guardia["no_auth"])) {
    session_unset();
    $_SESSION["mensaje_seguridad"] = "El tiempo de sesión de la API ha expirado";
    header("Location:index.php");
    exit;
}
if (isset($json_guardia["mensaje_baneo"])) {
    session_unset();
    $_SESSION["mensaje_seguridad"] = "Usted ya no se encuentra registrado en la BD";
    header("Location:index.php");
    exit;
}


if (isset($json_guardia["horario"])) {
    foreach ($json_guardia["horario"] as $horario) {
        $dia = (int) $horario['dia'];
        $hora = (int) $horario['hora'];
        $horario_profe[$dia][$hora] = true;
    }
}


if (isset($_POST["btnEquipo"])) {
    $url_usuarios_guardia = DIR_SERV . "/usuariosGuardia/" . $_POST['dia'] . "/" . $_POST['hora'];
    $respuesta_usuarios_guardia = consumir_servicios_JWT_REST($url_usuarios_guardia, 'GET', $headers);
    $json_usuarios_guardia = json_decode($respuesta_usuarios_guardia, true);
    if (!$json_usuarios_guardia) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $url_usuarios_guardia . "</strong></p>"));
    }

    if (isset($json_usuarios_guardia["error"])) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $json_usuarios_guardia["error"] . "</strong></p>"));
    }

    if (isset($json_usuarios_guardia["no_auth"])) {
        session_unset();
        $_SESSION["mensaje_seguridad"] = "El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }
    if (isset($json_usuarios_guardia["mensaje_baneo"])) {
        session_unset();
        $_SESSION["mensaje_seguridad"] = "Usted ya no se encuentra registrado en la BD";
        header("Location:index.php");
        exit;
    }

    $_SESSION['equipo_guardias'] = $json_usuarios_guardia['equipo'];
    $_SESSION['dia'] = $_POST["dia"];
    $_SESSION['hora'] = $_POST["hora"];
}

if (isset($_POST["btnProfesor"])) {
    $url_usuario = DIR_SERV . "/usuario/" . $_POST["btnProfesor"];
    $respuesta_usuario = consumir_servicios_JWT_REST($url_usuario, 'GET', $headers);
    $json_usuario = json_decode($respuesta_usuario, true);
    if (!$json_usuario) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $url_usuario . "</strong></p>"));
    }

    if (isset($json_usuario["error"])) {
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $json_usuario["error"] . "</strong></p>"));
    }

    if (isset($json_usuario["no_auth"])) {
        session_unset();
        $_SESSION["mensaje_seguridad"] = "El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }
    if (isset($json_usuario["mensaje_baneo"])) {
        session_unset();
        $_SESSION["mensaje_seguridad"] = "Usted ya no se encuentra registrado en la BD";
        header("Location:index.php");
        exit;
    }
    $_SESSION['profesor_detalle'] = $json_usuario['usuario'][0];
    var_dump($_SESSION['profesor_detalle']);
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Guardias</title>
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

        .centrado {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Gestión de Guardias</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usu_log["usuario"]; ?></strong> - <form class="enlinea" action="index.php" method="post"><button class="enlace" type="submit" name="btnSalir">Salir</button></form>
    </div>


    <table border="1">
        <tr>
            <th></th>
            <?php
            for ($i = 1; $i <= count(DIAS); $i++) {
                echo "<th>";
                echo DIAS[$i];
                echo "</th>";
            }
            ?>
        </tr>
        <?php
        for ($hora = 1; $hora <= count(HORAS); $hora++) {
            if ($hora == 4) {
                echo "<tr><td colspan='6' class='centrado'>RECREO</td></tr>";
            }
            echo "<tr>";
            echo "<th>" . HORAS[$hora] . "</th>";

            for ($dia = 1; $dia <= count(DIAS); $dia++) {
                if (isset($horario_profe[$dia][$hora])) {
                    echo "<td>";
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='hora' value='" . $hora . "'></input>";
                    echo "<input type='hidden' name='dia' value='" . $dia . "'></input>";
                    echo "<button type='submit' class='enlace' name='btnEquipo'>GUARDIA</button>";
                    echo "</form>";
                    echo "</td>";
                } else {
                    echo "<td></td>";
                }
            }
            echo "<tr>";
        }

        ?>

    </table>

    <?php
    if (isset($_SESSION['equipo_guardias'])) {
        echo "<h1>EQUIPO DE GUARDIA</h1>";
        echo  DIAS[$_SESSION["dia"]] . " a " . HORAS[$_SESSION["hora"]];

        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>Profesores de guardia</th>";
        echo "<th>Información del profesor</th>";
        echo "</tr>";

        foreach ($_SESSION["equipo_guardias"] as $guardia) {
            echo "<tr>";
            echo "<td>";
            echo "<form method='post'>";
            echo "<button class='enlace' name='btnProfesor' value='" . $guardia["id_usuario"] . "'>";
            echo $guardia["nombre"];
            echo "</button>";
            echo "</form>";
            echo "</td>";
            if (isset($_POST['btnProfesor']) && $_POST['btnProfesor'] == $guardia['id_usuario']) {
                echo "<td rowspan='" . count($_SESSION['equipo_guardias']) . "'>";
                $profe = $_SESSION['profesor_detalle'];
                echo "Nombre: " . $profe['nombre'] . "<br>";
                echo "Usuario: " . $profe['usuario'] . "<br>";
                echo "Email: " . $profe['email'] . "<br>";
                echo "</td>";
            }

            echo "</tr>";
        }


        echo "</table>";
    }
    ?>

</body>

</html>