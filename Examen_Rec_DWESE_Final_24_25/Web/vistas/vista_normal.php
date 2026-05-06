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
        $nombre_aula = [];
        for ($hora = 1; $hora <= count(HORAS); $hora++) {
            echo "<tr>";
            echo "<th>" . HORAS[$hora] . "</th>";
            if ($hora == 4) {
                echo "<td colspan='" . count(DIAS) . "'>RECREO</td>";
            } else {
                for ($dia = 1; $dia <= count(DIAS); $dia++) {
                    $url_aulas = DIR_SERV . "/aulasLibres/" . $dia . "/" . $hora;
                    $respuesta_aulas = json_decode(consumir_servicios_JWT_REST($url_aulas, 'get', $headers), true);
                   

                    foreach ($respuesta_aulas['aulas_libres'] as $aula) {
                        if (isset($nombre_aula[$dia][$hora]['nombre']))
                            $nombre_aula[$dia][$hora]['nombre'] .= "<br>" . $aula['nombre'];
                        else
                            $nombre_aula[$dia][$hora]['nombre'] = $aula['nombre'];
                    }

                    echo "<td>" . $nombre_aula[$dia][$hora]['nombre'] . "</td>";
                }
            }

            echo "</tr>";
        }
        ?>
    </table>
</body>

</html>