<?php
session_name("Examen_SW_25_26");
session_start();
require "src/funciones_ctes.php";


if(isset($_POST["btnSalir"]))
{
    session_destroy();
    header("Location:index.php");
    exit;
}

if(isset($_SESSION["logueado"]))
{
    require "src/seguridad.php";

    if($datos_usu_log["tipo"]=="tutor")
        require "vistas/vista_admin.php";
    else
        require "vistas/vista_normal.php";
}
else
    require "vistas/vista_login.php";


