<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require 'Firebase/autoload.php';

define("PASSWORD_API", "Una_clave_para_usar_para_encriptar");
define("MINUTOS_API", 60);
define("SERVIDOR_BD", "localhost");
define("USUARIO_BD", "root");
define("CLAVE_BD", "");
define("NOMBRE_BD", "bd_horario_exam1");


function validateToken()
{

    $headers = apache_request_headers();
    if (!isset($headers["Authorization"]))
        return false;//Sin autorizacion
    else {
        $authorization = $headers["Authorization"];
        $authorizationArray = explode(" ", $authorization);
        $token = $authorizationArray[1];
        try {
            $info = JWT::decode($token, new Key(PASSWORD_API, 'HS256'));
        } catch (\Throwable $th) {
            return false;//Expirado
        }

        try {
            $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        } catch (PDOException $e) {

            $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
            return $respuesta;
        }

        try {
            $consulta = "select * from usuarios where id_usuario=?";
            $sentencia = $conexion->prepare($consulta);
            $sentencia->execute([$info->data]);
        } catch (PDOException $e) {
            $respuesta["error"] = "Imposible realizar la consulta:" . $e->getMessage();
            $sentencia = null;
            $conexion = null;
            return $respuesta;
        }
        if ($sentencia->rowCount() > 0) {
            $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);

            $payload['exp'] = time() + (MINUTOS_API * 60);
            $payload['data'] = $respuesta["usuario"]["id_usuario"];
            $jwt = JWT::encode($payload, PASSWORD_API, 'HS256');
            $respuesta["token"] = $jwt;
        } else
            $respuesta["mensaje_baneo"] = "El usuario no se encuentra registrado en la BD";

        $sentencia = null;
        $conexion = null;
        return $respuesta;
    }

}


function login($datos_login)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "No he podido conectarse a la base de batos: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select * from usuarios where usuario=? and clave=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute($datos_login);

    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        $respuesta["error"] = "No he podido realizarse la consulta: " . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
        $payload['exp'] = time() + (MINUTOS_API * 60);
        $payload['data'] = $respuesta["usuario"]["id_usuario"];
        $jwt = JWT::encode($payload, PASSWORD_API, 'HS256');
        $respuesta["token"] = $jwt;

    } else
        $respuesta["mensaje"] = "El usuario no se encuentra en la BD";

    $sentencia = null;
    $conexion = null;
    return $respuesta;
}

function aulas_libres($dia, $hora)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "No he podido conectarse a la base de batos: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "SELECT * FROM aulas 
                     WHERE id_aula NOT IN (
                     SELECT aula FROM horario_lectivo 
                     WHERE dia = ? AND hora = ?
                     )";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$dia, $hora]);

    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        $respuesta["error"] = "No he podido realizarse la consulta: " . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["aulas_libres"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);


    } else
        $respuesta["mensaje"] = "El usuario no se encuentra en la BD";

    $sentencia = null;
    $conexion = null;
    return $respuesta;
}

function aulas()
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "No he podido conectarse a la base de batos: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "SELECT * FROM aulas";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute();

    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        $respuesta["error"] = "No he podido realizarse la consulta: " . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["aulas"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);


    } else
        $respuesta["mensaje"] = "El usuario no se encuentra en la BD";

    $sentencia = null;
    $conexion = null;
    return $respuesta;
}

function grupos()
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "No he podido conectarse a la base de batos: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "SELECT * FROM grupos";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute();

    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        $respuesta["error"] = "No he podido realizarse la consulta: " . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["grupos"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);


    } else
        $respuesta["mensaje"] = "El usuario no se encuentra en la BD";

    $sentencia = null;
    $conexion = null;
    return $respuesta;
}
function profesores()
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "No he podido conectarse a la base de batos: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "SELECT * FROM usuarios where tipo='normal'";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute();

    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        $respuesta["error"] = "No he podido realizarse la consulta: " . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["profesores"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $respuesta["profesores"] = array();
    }

    $sentencia = null;
    $conexion = null;
    return $respuesta;
}

function ocupacion_aula($dia, $hora, $aula)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "No he podido conectarse a la base de batos: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "SELECT usuarios.id_usuario, usuarios.usuario, grupos.id_grupo, grupos.nombre FROM usuarios join horario_lectivo on usuarios.id_usuario = horario_lectivo.usuario join grupos on horario_lectivo.grupo = grupos.id_grupo where dia=? and hora=? and aula=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$dia, $hora, $aula]);

    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        $respuesta["error"] = "No he podido realizarse la consulta: " . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["profes"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);


    } else
        $respuesta["mensaje"] = "No hay profesores asignados a esta aula ";

    $sentencia = null;
    $conexion = null;
    return $respuesta;
}

function quitarProfesor($dia, $hora, $aula, $profesor)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "No he podido conectarse a la base de batos: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "DELETE FROM horario_lectivo WHERE dia=? and hora=? and aula=? and usuario=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$dia, $hora, $aula, $profesor]);

    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        $respuesta["error"] = "No he podido realizarse la consulta: " . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["mensaje"] = "Profesor eliminado correctamente";
    } else
        $respuesta["mensaje"] = "No se ha podido eliminar al profesor";

    $sentencia = null;
    $conexion = null;
    return $respuesta;
}

function aniadirProfesor($dia, $hora, $aula, $profesor, $grupo)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "No he podido conectarse a la base de batos: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "INSERT INTO horario_lectivo (dia, hora, aula, usuario, grupo) VALUES (?, ?, ?, ?, ?)";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$dia, $hora, $aula, $profesor, $grupo]);

    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        $respuesta["error"] = "No he podido realizarse la consulta: " . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["mensaje"] = "Profesor añadido correctamente";
    } else
        $respuesta["mensaje"] = "No se ha podido añadir al profesor";

    $sentencia = null;
    $conexion = null;
    return $respuesta;
}
?>