<?php

namespace Helpers;

include_once "Sesion.php";


class Login {


public static function estaLogeado() {
    Sesion::abrirsesion(); 
    return isset($_SESSION['user']);
}



public static function logout() {
 header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: 0");
        unset($_SESSION['user']);
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        Sesion::cerrarsesion();       
}

public static function login($user) {
    Sesion::abrirsesion();
    $_SESSION['user'] = $user;
}



public static function getRol() {
        $user = $_SESSION['user'];

        return $user['idRol'] ?? null;
    }

   public static function getId() {
        return $_SESSION['user']['id'] ?? null;
    }


}



?>