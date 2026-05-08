<?php

require __DIR__ . '/Slim/autoload.php';

require "src/funciones_CTES.php";

$app = new \Slim\App;

$app->get('/logueado', function () {

    $test = validateToken();
    if (is_array($test)) {
        echo json_encode($test);
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});


$app->post('/login', function ($request) {

    $datos_login[] = $request->getParam("usuario");
    $datos_login[] = $request->getParam("clave");


    echo json_encode(login($datos_login));
});


$app->get('/aulasLibres/{dia}/{hora}', function ($request) {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test["usuario"])) {
            $dia = $request->getAttribute('dia');
            $hora = $request->getAttribute('hora');
            echo json_encode(aulas_libres($dia, $hora));

        } else
            echo json_encode($test);
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->get('/aulas', function () {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test["usuario"])) {
            if ($test["usuario"]["tipo"] == "admin")
                echo json_encode(aulas());
            else
                echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));

        } else
            echo json_encode($test);
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});


$app->get('/grupos', function () {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test["usuario"])) {
            if ($test["usuario"]["tipo"] == "admin")
                echo json_encode(grupos());
            else
                echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));

        } else
            echo json_encode($test);
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->get('/profesores', function () {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test["usuario"])) {
            if ($test["usuario"]["tipo"] == "admin")
                echo json_encode(profesores());
            else
                echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));

        } else
            echo json_encode($test);
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->get('/profesores/{dia}/{hora}/{id_aula}', function ($request) {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test["usuario"])) {
            $dia = $request->getAttribute('dia');
            $hora = $request->getAttribute('hora');
            $aula = $request->getAttribute('id_aula');
            echo json_encode(ocupacion_aula($dia, $hora, $aula));

        } else
            echo json_encode($test);
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->delete('/quitarProfesor/{dia}/{hora}/{id_aula}/{id_profesor}', function ($request) {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test["usuario"])) {
            if ($test["usuario"]["tipo"] == "admin") {
                $dia = $request->getAttribute('dia');
                $hora = $request->getAttribute('hora');
                $aula = $request->getAttribute('id_aula');
                $profesor = $request->getAttribute('id_profesor');
                echo json_encode(quitarProfesor($dia, $hora, $aula, $profesor));
            } else
                echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));

        } else
            echo json_encode($test);
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->post('/aniadirProfesor/{dia}/{hora}/{aula}/{profesor}/{grupo}', function ($request) {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test["usuario"])) {
            if ($test["usuario"]["tipo"] == "admin") {
                $dia = $request->getAttribute('dia');
                $hora = $request->getAttribute('hora');
                $aula = $request->getAttribute('aula');
                $profesor = $request->getAttribute('profesor');
                $grupo = $request->getAttribute('grupo');
                echo json_encode(aniadirProfesor($dia, $hora, $aula, $profesor, $grupo));
            } else
                echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));

        } else
            echo json_encode($test);
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});
$app->run();

?>