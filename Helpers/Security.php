<?php
namespace Helpers;

use Repositorys\RepoToken;

class Security
{
    public static function generarToken($longitud = 64)
    {
        return bin2hex(random_bytes($longitud / 2));
    }

    public static function guardarTokenEnDB($idUser, $token)
    {
        RepoToken::delete($idUser); 
        RepoToken::save($idUser, $token);
    }

    public static function verificarToken($token)
    {
        // return RepoToken::findByToken($token) !== null
    }
}
