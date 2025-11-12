<?php
namespace Helpers;
class Sesion{

public static function abrirsesion() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}



public static function cerrarsesion(){
    session_destroy();
}

public static function leersesion($clave){
    if(existeclave($clave)){
        return $_SESSION[$clave];
    } else {
        return false;
    }
}

public static function existeclave($clave){
    return isset($_SESSION[$clave]);
}


public static function escribirsesion($clave,$valor){
    $_SESSION[$clave] = $valor;
}

}

?>