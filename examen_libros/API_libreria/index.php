<?php

require __DIR__ . '/Slim/autoload.php';

require "src/funciones_CTES.php";

$app = new \Slim\App;

$app->get('/logueado/{cod_usu}', function ($request) {

    echo json_encode(logueado($request->getAttribute("cod_usu")));
});


$app->post('/login', function ($request) {

    $datos_login[] = $request->getParam("usuario");
    $datos_login[] = $request->getParam("clave");

    echo json_encode(login($datos_login));
});



$app->run();
