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

$app->get('/notasAlumno/{id_alumno}', function ($request) {

    $test = validateToken();
    if (is_array($test)) {
        if (isset($test['usuario'])) {
            $id_alumno = $request->getAttribute('id_alumno');
            echo json_encode(notasAlumno($id_alumno));
        } else {
            echo json_encode($test);
        }
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->get('/notasNoEvalAlumno/{id_alumno}', function ($request) {

    $test = validateToken();
    if (is_array($test)) {
        if (isset($test['usuario'])) {
            $id_alumno = $request->getAttribute('id_alumno');
            echo json_encode(notasNoEvalAlumno($id_alumno));
        } else {
            echo json_encode($test);
        }
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->get('/alumnos', function () {
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test['usuario'])) {
            if (isset($test['usuario']) && $test['usuario']['tipo'] == 'tutor') {

                echo json_encode(alumnos());
            } else
                echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
        } else {
            echo json_encode($test);
        }
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->delete('/borrarNota/{id_alumno}/{id_asignatura}', function ($request) {

    $test = validateToken();
    if (is_array($test)) {
        if (isset($test['usuario'])) {
            if (isset($test['usuario']) && $test['usuario']['tipo'] == 'tutor') {
                $id_alumno = $request->getAttribute('id_alumno');
                $id_asignatura = $request->getAttribute('id_asignatura');
                echo json_encode(borrarNota($id_alumno, $id_asignatura));
            } else
                echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
        } else {
            echo json_encode($test);
        }
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->post('/calificarNota/{id_alumno}/{id_asignatura}', function ($request) {

    $test = validateToken();
    if (is_array($test)) {
        if (isset($test['usuario'])) {
            if (isset($test['usuario']) && $test['usuario']['tipo'] == 'tutor') {
                $id_alumno = $request->getAttribute('id_alumno');
                $id_asignatura = $request->getAttribute('id_asignatura');
                echo json_encode(calificar($id_alumno, $id_asignatura));
            } else
                echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
        } else {
            echo json_encode($test);
        }
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});

$app->put('/cambiarNota/{id_usuario}/{id_asignatura}/{nota}', function ($request) {

    $test = validateToken();
    if (is_array($test)) {
        if (isset($test['usuario'])) {
            if (isset($test['usuario']) && $test['usuario']['tipo'] == 'tutor') {
                $id_alumno = $request->getAttribute('id_usuario');
                $id_asignatura = $request->getAttribute('id_asignatura');
                $nota = $request->getAttribute('nota');
                echo json_encode(cambiarNota($id_alumno, $id_asignatura, $nota));
            } else
                echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
        } else {
            echo json_encode($test);
        }
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});
$app->run();
