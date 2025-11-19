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





    public static function verificarToken()
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
        $token = null;

        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }

        $valido = $token && RepoToken::findUserByToken($token);

        if (!$valido) {
            http_response_code(401);
            echo json_encode(['error' => 'Token inválido o expirado']);
            exit;
        }
    }
}
