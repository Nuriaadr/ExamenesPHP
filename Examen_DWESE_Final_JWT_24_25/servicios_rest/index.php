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


$app->get('/horarioProfesor/{id_usuario}', function ($request) {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test['usuario'])) {
            $id_usuario = $request->getAttribute('id_usuario');
            echo json_encode(horarioProfesor($id_usuario));
        } else
            echo json_encode($test);
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});


$app->get('/grupos', function () {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test['usuario'])) {
            echo json_encode(grupos());
        } else
            echo json_encode($test);
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->get('/aulas', function () {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test['usuario'])) {
            echo json_encode(aulas());
        } else
            echo json_encode($test);
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->get('/horarioGrupo/{id_grupo}', function ($request) {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test['usuario'])) {
            $id_grupo = $request->getAttribute('id_grupo');
            echo json_encode(horarioGrupo($id_grupo));
        } else
            echo json_encode($test);
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});


$app->get('/profesores/{dia}/{hora}/{id_grupo}', function ($request) {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test['usuario'])) {
            $dia = $request->getAttribute('dia');
            $hora = $request->getAttribute('hora');
            $id_grupo = $request->getAttribute('id_grupo');
            echo json_encode(profesores_grupo($dia, $hora, $id_grupo));
        } else
            echo json_encode($test);
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->get('/profesoresLibres/{dia}/{hora}/{id_grupo}', function ($request) {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test['usuario'])) {
            $dia = $request->getAttribute('dia');
            $hora = $request->getAttribute('hora');
            $id_grupo = $request->getAttribute('id_grupo');
            echo json_encode(profesores_libres($dia, $hora, $id_grupo));
        } else
            echo json_encode($test);
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});


$app->delete('/borrarProfesor/{dia}/{hora}/{id_grupo}/{id_usuario}', function ($request) {


    $test = validateToken();
    if (is_array($test)) {
        if (isset($test["usuario"])) {
            if ($test["usuario"]["tipo"] == "admin") {
                $dia = $request->getAttribute("dia");
                $hora = $request->getAttribute("hora");
                $id_grupo = $request->getAttribute("id_grupo");
                $id_usuario = $request->getAttribute("id_usuario");
                echo json_encode(quitar_profesor($dia, $hora, $id_grupo, $id_usuario));
            } else
                echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
        } else
            echo json_encode($test);
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->post('/insertarProfesor/{dia}/{hora}/{id_grupo}/{id_usuario}/{id_aula}', function ($request) {

    $test = validateToken();
    if (is_array($test)) {
        if (isset($test["usuario"])) {
            if ($test["usuario"]["tipo"] == "admin") {
                $dia = $request->getAttribute("dia");
                $hora = $request->getAttribute("hora");
                $id_grupo = $request->getAttribute("id_grupo");
                $id_usuario = $request->getAttribute("id_usuario");
                $id_aula = $request->getAttribute("id_aula");
                echo json_encode(insertar_profesor($dia, $hora, $id_grupo, $id_usuario, $id_aula));
            } else
                echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
        } else
            echo json_encode($test);
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->run();
