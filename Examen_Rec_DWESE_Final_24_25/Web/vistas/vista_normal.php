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
        $aula_libre = [];
        for ($hora = 1; $hora <= count(HORAS); $hora++) {
            echo "<tr>";
            echo "<th>" . HORAS[$hora] . "</th>";

            if ($hora == 4) {
                echo "<th colspan='5'>RECREO</th>";
                echo "</tr>";
                continue;
            }

            for ($dia = 1; $dia <= count(DIAS); $dia++) {
                $url_aulas = DIR_SERV . "/aulasLibres/" . $dia . "/" . $hora;
                $respuesta_aulas = consumir_servicios_JWT_REST($url_aulas, 'get', $headers);
                $json_aulas = json_decode($respuesta_aulas, true);

                if (isset($json_aulas['aulas_libres'])) {
                    foreach ($json_aulas['aulas_libres'] as $aula) {
                      
                        if (isset($aula_libre[$dia][$hora]['nombre'])) {
                            $aula_libre[$dia][$hora]['nombre'] .= "<br>" . $aula['nombre'];
                        } else {
                            $aula_libre[$dia][$hora]['nombre'] = $aula['nombre'];
                        }
                    }
                }

                echo "<td>" .  $aula_libre[$dia][$hora]['nombre'] . "</td>";


               
            }

            echo "</tr>";
        }
        ?>
    </table>
</body>

</html>