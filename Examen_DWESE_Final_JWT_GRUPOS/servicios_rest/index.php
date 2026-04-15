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

$app->get('/profesores', function () {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test['usuario'])) {
            if ($test['usuario']['tipo'] == 'admin') {
                echo json_encode(profesores());
            } else echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
        } else {
            echo json_encode($test);
        }
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->get('/grupos', function () {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test['usuario'])) {
            if ($test['usuario']['tipo'] == 'admin') {
                echo json_encode(grupos());
            } else echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
        } else {
            echo json_encode($test);
        }
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->get('/horarioProfesor/{id_usuario}', function ($request) {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test['usuario'])) {
            if ($test['usuario']['tipo'] == 'admin') {
                $id_usuario = $request->getAttribute("id_usuario");
                echo json_encode(horarioProfesor($id_usuario));
            } else echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
        } else {
            echo json_encode($test);
        }
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->delete('/borrarGrupo/{dia}/{hora}/{id_profesor}/{id_grupo}', function ($request) {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test['usuario'])) {
            if ($test['usuario']['tipo'] == 'admin') {
                $dia = $request->getAttribute("dia");
                $hora = $request->getAttribute("hora");
                $id_profesor = $request->getAttribute("id_profesor");
                $id_grupo = $request->getAttribute("id_grupo");
                echo json_encode(borrarGrupo($dia, $hora, $id_profesor, $id_grupo));
            } else echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
        } else {
            echo json_encode($test);
        }
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->post('/agregarGrupo/{dia}/{hora}/{id_profesor}/{id_grupo}', function ($request) {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test['usuario'])) {
            if ($test['usuario']['tipo'] == 'admin') {
                $dia = $request->getAttribute("dia");
                $hora = $request->getAttribute("hora");
                $id_profesor = $request->getAttribute("id_profesor");
                $id_grupo = $request->getAttribute("id_grupo");
                echo json_encode(agregarGrupo($dia, $hora, $id_profesor, $id_grupo));
            } else echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
        } else {
            echo json_encode($test);
        }
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});
$app->run();
