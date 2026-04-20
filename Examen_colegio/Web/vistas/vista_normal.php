<?php
$url_notas = DIR_API_COLEGIO . '/notasAlumno/' . $datos_usu_log['cod_usu'];
$respuesta_notas = consumir_servicios_JWT_REST($url_notas, 'GET', $headers);
$json_notas = json_decode($respuesta_notas, true);

if (!$json_notas) {
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
}
if (isset($json_notas["error"])) {
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_notas["error"] . "</p>"));
}
if (isset($json_notas["mensaje"])) {
    $_SESSION['mensaje'] = $json_notas['mensaje'];
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
    <h1>Examen Final PHP</h1>
    <form action="index.php" method="post">
        <p>Bienvenido <strong><?php echo $datos_usu_log["usuario"]; ?></strong> - <button type="submit" class="enlace" name="btnCerrarSesion">Salir</button></p>
    </form>

    <h3>Notas del alumno: <?= $datos_usu_log['nombre'] ?></h3>

    <?php
    if (isset($_SESSION['mensaje'])) {
        echo $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);
    }
    ?>
    <table>
        <tr>
            <th>Asignatura</th>
            <th>Nota</th>
        </tr>

        <?php
        foreach ($json_notas['notas_alum'] as $nota) {
            echo "<tr>";
            echo "<td>" . $nota['denominacion'] . "</td>";
            echo "<td>" . $nota['nota'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

</body>

</html>