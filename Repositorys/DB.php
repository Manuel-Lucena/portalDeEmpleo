<?php

namespace Repositorys;

class DB {

    private static $con = null;
    private static $host = 'mysql'; //localhost
    private static $user = 'root';
    private static $pass = 'root';
    private static $db   = 'portaldeempleo';


    public static function getConnection() {
        if (self::$con === null) {
            self::$con = new \PDO(
                'mysql:host=' . self::$host . ';dbname=' . self::$db . ';charset=utf8',
                self::$user,
                self::$pass
            );
        }
        return self::$con;
    }
}
?>
